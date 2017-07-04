<?php
class Core_Component_Controller_Index extends Component
{
    public function process()
    {
		$this->database = Core::getLib('database');
		$oSession = Core::getLib('session');
		
        $iNowTime = CORE_TIME;
        $iNowTimeGMT7 = Core::getLib('date')->convertFromGmt($iNowTime, Core::getParam('core.default_time_zone_offset'));
        $sDay = date('d-m-Y', $iNowTimeGMT7);
        $iNowDay = strtotime($sDay);
        $iFormTime = $iNowDay - 29*3600*24;
        
        
        
        //Thống kê trong vòng 1 tháng (30 ngày gần đây) trở lại
        $iStartTime = $iNowDay - 29*3600*24 - 7*3600;
        $iEndTime = $iNowTime;
        
        //convert to GMT
        //$iStartTime = Core::getLib('date')->convertToGmt($iStartTime);
        //$iEndTime = Core::getLib('date')->convertToGmt($iEndTime);
        
        $aOrderId = array();
        $iVendorId = -1;
        $sConds = '';
        if (Core::getParam('core.main_server') == 'sup.') {
            $iVendorId = $oSession->get('session-vendor');
            if ($iVendorId > 0) {
                $sConds = ' AND d.vendor_id = '.$iVendorId;
                $aRows = $this->database->select('d.shop_order_id as shop_order_id')
                    ->from(Core::getT('shop_order_dt'), 'd')
                    ->join(Core::getT('shop_order'), 's', 's.id = d.shop_order_id')
                    ->where('d.status != 5 AND s.status_deliver NOT IN ("da-huy", "khong-nhan-hang", "bi-tra-ve") AND d.vendor_id = '.$iVendorId.' AND s.time BETWEEN '.$iStartTime.' AND '.$iEndTime)
                    ->group('d.shop_order_id')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aOrderId[] = $aRow['shop_order_id'];
                }
            }
        }
        else {
            $aRows = $this->database->select('d.shop_order_id as shop_order_id')
                ->from(Core::getT('shop_order_dt'), 'd')
                ->join(Core::getT('shop_order'), 's', 's.id = d.shop_order_id')
                ->where('d.status NOT IN (3,5) AND s.status_deliver NOT IN ("da-huy", "khong-nhan-hang", "bi-tra-ve") AND s.time BETWEEN '.$iStartTime.' AND '.$iEndTime)
                ->group('d.shop_order_id')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aOrderId[] = $aRow['shop_order_id'];
            }
        }
        
        $data = array();
        if(!empty($aOrderId)) {
            // Thống kê truy cập
            // tính 14 ngày gần nhất
            $query = '';
            
            for ($i = $iFormTime; $i <= $iNowDay; $i += 24*3600)
            {
                //$n = $i*-1;
                //$tam = strtotime($n.' days');
                $aRow['visit'] = 0;
                $aRow['view'] = 0;
                $ngay_lay[] = date('Y-m-d', $i);
                $thong_ke_truy_cap[] = array(
                    'date' => date('d-m-Y', $i),
                    'date_dm' => date('d-m', $i),
                    'date_d' => date('d', $i),
                    'visit' => $aRow['visit'],
                    'view' => $aRow['view'],
                );
                $query .= '"'.addslashes(date('Y-m-d', $i)).'",';
            }
            
            $query = rtrim($query, ',');
            
            $aRows = $this->database->select('count(id) visit, sum(`count`) view, create_date')
                    ->from(Core::getT('log_access_sid'))
                    ->where('domain_id ='. Core::getDomainId() .' AND create_date IN ('.$query.')')
                    ->group('create_date')
                    ->execute('getRows');
            $tong_truy_cap = 0;
            $tong_xem_trang = 0;
            foreach ($aRows as $aRow) {
                
                // xác định $n
                foreach($ngay_lay as $n => $v)
                {
                    if($v == $aRow['create_date'])
                    {
                        break;
                    }
                }
                $tong_truy_cap += $aRow['visit'];
                $tong_xem_trang += $aRow['view'];
                $tam = strtotime($aRow['create_date']);
                $thong_ke_truy_cap[$n] = array(
                    'date' => date('d-m-Y', $tam),
                    'date_dm' => date('d-m', $tam),
                    'visit' => $aRow['visit'],
                    'view' => $aRow['view'],
                );
            }
            $data['thong_ke_truy_cap'] = $thong_ke_truy_cap;
            $data['tong_truy_cap'] = $tong_truy_cap;
            $data['tong_xem_trang'] = $tong_xem_trang;
            
            
            // Thống kê doanh số / đơn hàng
            for($i = $iFormTime; $i <= $iNowDay; $i += 24*3600)
            {
                //$n = $i*-1;
                //$tam = strtotime($n.' days');
                $aRow['don_hang'] = 0;
                $aRow['tong_tien'] = 0;
                $doanh_so[] = array(
                    'date' => date('d-m-Y', $i),
                    'date_dm' => date('d-m', $i),
                    'don_hang' => $aRow['don_hang'],
                    'tong_tien' => ceil($aRow['tong_tien']),
                );
            }
            
            $doanh_so_tong_tien = 0;
            
            
            $sSql = 'SELECT count(a.id) don_hang, sum(b.tong_tien) as tong_tien, DATE_FORMAT(FROM_UNIXTIME(a.time), "%Y-%m-%d") create_date FROM '.Core::getT('shop_order').' as a INNER JOIN (SELECT d.shop_order_id as shop_order_id, sum(d.quantity*d.unit_price) as tong_tien FROM '.Core::getT('shop_order_dt').' as d WHERE  d.shop_order_id IN ('.implode(',',$aOrderId).') '.$sConds.' GROUP BY d.shop_order_id) as b ON b.shop_order_id = a.id WHERE a.status != 3 GROUP BY create_date';
            
            $aRows = $this->database->getRows($sSql);
            
            $tong_giao_dich = 0; 
            $iCnt = 0;
            foreach ($aRows as $aRow) {
                $tong_giao_dich += $aRow['don_hang']*1;
                // xác định $n
                foreach($ngay_lay as $n => $v)
                {
                    if($v == $aRow['create_date'])
                    {
                        break;
                    }
                }
                $iCnt++;
                $tam = strtotime($aRow['create_date']);
                $doanh_so[$n] = array(
                    'date' => date('d-m-Y', $tam),
                    'date_dm' => date('d-m', $tam),
                    'don_hang' => $aRow['don_hang'],
                    'tong_tien' => ceil($aRow['tong_tien']),
                );
                $doanh_so_tong_tien += $aRow['tong_tien'];
            }
            
            $data['doanh_so'] = $doanh_so;
            $data['doanh_so_tong_tien'] = $doanh_so_tong_tien;
            $data['tong_giao_dich'] = $tong_giao_dich;
            
            // thời gian đăng nhập gần nhất
            $aRow = $this->database->select('last_visit')
                    ->from(Core::getT('user'))
                    ->where('domain_id ='. Core::getDomainId() .' AND id = '.$oSession->getArray('session-user', 'id'))
                    ->execute('getRow');
            if($aRow['last_visit'] > 0)
            {
                $thoi_gian_dang_nhap = date('d-m-Y H:i:s', $aRow['last_visit']);
            }
            else
                $thoi_gian_dang_nhap = $aRow['last_visit'];
            $data['thoi_gian_dang_nhap'] = $thoi_gian_dang_nhap;
            // end
            // cập nhật thành viên ol
            //thanhVienOnline();
            //$dang_truc_tuyen = $_SESSION['session-dang_truc_tuyen'];
            //$tong_so_view = $_SESSION['session-view'];
            
            // tổng thành vien
            $tong_thanh_vien = $this->database->select('count(id)')
                    ->from(Core::getT('user'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2')
                    ->execute('getField');
            $data['tong_thanh_vien'] = $tong_thanh_vien;
            
            // tổng đề tài
            $tong_de_tai = $this->database->select('count(id)')
                    ->from(Core::getT('category'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2')
                    ->execute('getField');
            $data['tong_de_tai'] = $tong_de_tai;
            
            // tổng bài viết
            $tong_bai_viet = $this->database->select('count(id)')
                    ->from(Core::getT('article'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2')
                    ->execute('getField');
            $data['tong_bai_viet'] = $tong_bai_viet;
            
            // tổng lời bình
            $tong_loi_binh = $this->database->select('count(id)')
                    ->from(Core::getT('comment'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2')
                    ->execute('getField');
            $data['tong_loi_binh'] = $tong_loi_binh;
            
            // tổng nhận mail
            $tong_nhan_mail = $this->database->select('count(id)')
                    ->from(Core::getT('receive_news'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2')
                    ->execute('getField');
            $data['tong_nhan_mail'] = $tong_nhan_mail;
            
            // tổng đơn hàng hôm nay
            $thoi_gian_tu = strtotime('0 day 00:00:00');
            $thoi_gian_den = strtotime('+1 day 00:00:00');
            
            $aRow = $this->database->select('count(id)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND time BETWEEN '.$thoi_gian_tu.' AND '.$thoi_gian_den)
                    ->execute('getField');
            $tong['don_hang']['hom_nay'] =array(
                'tu' => $thoi_gian_tu,
                'den' => $thoi_gian_den,
                'gia_tri' => $aRow
            );
            
            // tổng đơn hàng hôm qua
            $thoi_gian_tu = strtotime('-1 day 00:00:00');
            $thoi_gian_den = strtotime("0 day 00:00:00");
            $aRow = $this->database->select('count(id)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND time BETWEEN '.$thoi_gian_tu.' AND '.$thoi_gian_den)
                    ->execute('getField');
            $tong['don_hang']['hom_qua'] = array(
                'tu' => $thoi_gian_tu,
                'den' => $thoi_gian_den,
                'gia_tri' => $aRow
            );
            
            // tổng đơn hàng tuần này, lấy ngày đầu tuần
            if (date('N') == 1) $thoi_gian_tu = strtotime('0 day 00:00:00');
            else $thoi_gian_tu = strtotime('last Monday 00:00:00');
            $thoi_gian_den = strtotime('+1 day 00:00:00');
            $aRow = $this->database->select('count(id)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND time BETWEEN '.$thoi_gian_tu.' AND '.$thoi_gian_den)
                    ->execute('getField');
            $tong['don_hang']['tuan_nay'] = array(
                'tu' => $thoi_gian_tu,
                'den' => $thoi_gian_den,
                'gia_tri' => $aRow
            );
            
            // tổng đơn hàng tháng này
            $thoi_gian_tu = strtotime('first day of this month 00:00:00');
            $thoi_gian_den = strtotime('+1 day 00:00:00');
            $aRow = $this->database->select('count(id)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND time BETWEEN '.$thoi_gian_tu.' AND '.$thoi_gian_den)
                    ->execute('getField');
            $tong['don_hang']['thang_nay'] = array(
                'tu' => $thoi_gian_tu,
                'den' => $thoi_gian_den,
                'gia_tri' => $aRow
            );

            // doanh số
            
            // tổng doanh số hôm nay
            $aRow = $this->database->select('sum(total_amount)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND status_deliver NOT IN ("da-huy", "khong-nhan-hang", "bi-tra-ve") AND time > '.strtotime("0 day 00:00:00"))
                    ->execute('getField');
            $tong['doanh_so']['hom_nay'] = $aRow*1;
            
            // tổng doanh số hôm qua
            $aRow = $this->database->select('sum(total_amount)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND status_deliver NOT IN ("da-huy", "khong-nhan-hang", "bi-tra-ve") AND time BETWEEN '.strtotime("-1 day 00:00:00").' AND '.strtotime("0 day 00:00:00"))
                    ->execute('getField');
            $tong['doanh_so']['hom_qua'] = $aRow*1;
            
            // tổng doanh số tuần này, lấy ngày đầu tuần
            if (date('N') == 1) $thoi_gian_tu = strtotime('0 day 00:00:00');
            else $thoi_gian_tu = strtotime('last Monday 00:00:00');
            $aRow = $this->database->select('sum(total_amount)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND status_deliver NOT IN ("da-huy", "khong-nhan-hang", "bi-tra-ve") AND time > '.$thoi_gian_tu)
                    ->execute('getField');
            $tong['doanh_so']['tuan_nay'] = $aRow*1;
            
            // tổng doanh số tháng này
            $thoi_gian_tu = strtotime('first day of this month 00:00:00');
            $aRow = $this->database->select('sum(total_amount)')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND status_deliver NOT IN ("da-huy", "khong-nhan-hang", "bi-tra-ve") AND time > '.$thoi_gian_tu)
                    ->execute('getField');
            $tong['doanh_so']['thang_nay'] = $aRow*1;
            
            
            $status_deliver_danh_sach = array(
                '' => 'Đang xử lý',
                'da-xac-nhan' => 'Có hàng',
                'dang-giao-hang' => 'Đang giao hàng',
                'da-nhan-hang' => 'Đã hoàn thành',
                'da-huy' => 'Đã hủy',
                'khong-nhan-hang' => 'Không thể giao hàng',
                'bi-tra-ve' => 'Hàng bị trả về',
            );
            
            $status['don_hang'] = array();
            $status['don_hang']['tong'] = 0;
            // Trạng thái đơn hàng trong 30 ngày
            $aRows = $this->database->select('count(*) so_luong, status_deliver')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND time BETWEEN '.strtotime('-30 days 00:00:00').' AND '.time())
                    ->group('status_deliver')
                    ->execute('getRows');
            foreach ($aRows as $aRow) {
                $tmps[$aRow['status_deliver']] = $aRow['so_luong'];
                $status['don_hang']['tong'] += $aRow['so_luong'];
            }
            
            $k = 0;
            foreach($status_deliver_danh_sach as $aRow)
            {
                $status['don_hang'][$k] = array(
                    'ten' => $aRow,
                    'tong' => $tmps[$k]*1,
                );
                $k++;
            }
            $data['tong'] = $tong;
            $data['status'] = $status;
            
            //Lấy giờ bán chạy hàng (theo số lượng sản phẩm)
            $data['gio_vang'] = array();
            
            //$sSql = 'SELECT count(a.id) don_hang, sum(b.tong_tien) as tong_tien, sum(b.so_luong) as so_luong, DATE_FORMAT(FROM_UNIXTIME(a.time), "%H") create_time FROM '.Core::getT('shop_order').' as a INNER JOIN (SELECT d.shop_order_id as shop_order_id, sum(d.quantity) as so_luong, sum(d.quantity*d.unit_price) as tong_tien FROM '.Core::getT('shop_order_dt').' as d WHERE d.shop_order_id IN ('.implode(',',$aOrderId).') '.$sConds.' GROUP BY d.shop_order_id) as b ON b.shop_order_id = a.id GROUP BY create_time';
            
            $sSql = 'SELECT count(w.id) don_hang, sum(w.tong_tien) as tong_tien, sum(w.so_luong) as so_luong, w.create_time FROM (SELECT a.id, b.tong_tien, b.so_luong, DATE_FORMAT(FROM_UNIXTIME(a.time), "%d/%m/%Y %H:00") as create_time FROM '.Core::getT('shop_order').' as a INNER JOIN (SELECT d.shop_order_id as shop_order_id, sum(d.quantity) as so_luong, sum(d.quantity*d.unit_price) as tong_tien FROM '.Core::getT('shop_order_dt').' as d WHERE d.shop_order_id IN ('.implode(',',$aOrderId).') '.$sConds.' GROUP BY d.shop_order_id) as b ON b.shop_order_id = a.id)as w GROUP BY w.create_time ORDER BY so_luong DESC LIMIT 5';
            
            $aRows = $this->database->getRows($sSql);
            
            foreach ($aRows as $aRow) {
                $data['gio_vang'][] = array(
                    'total_order' => $aRow['don_hang'],
                    'total_product' => $aRow['so_luong'],
                    'total_amount' => $aRow['tong_tien'],
                    'hour' => $aRow['create_time'], 
                );
            }
            
            //khách hàng thân thiết: tạm thời dựa trên tiêu chí top thành viên
            $data['khach_hang_than_thiet'] = array();
            
            $sSql = 'SELECT a.id, a.user_id, sum(b.quantity) as cnt FROM '.Core::getT('shop_order').' as a INNER JOIN (SELECT d.shop_order_id as shop_order_id, sum(d.quantity) as quantity FROM '.Core::getT('shop_order_dt').' as d WHERE shop_order_id IN ('.implode(',', $aOrderId).')'.$sConds.' GROUP BY d.shop_order_id) as b ON b.shop_order_id = a.id GROUP BY a.user_id ORDER BY cnt DESC LIMIT 10';
            
            
            $aRows = $this->database->getRows($sSql);
            
            $aUserId = array();
            foreach ($aRows as $aRow) {
                $aUserId[] = $aRow['user_id'];
            }
            $aMappingUser = array();
            if (!empty($aUserId)) {
                $aMappingUser = Core::getService('user.community')->getUserInfo(array(
                    'user_list' =>$aUserId,
                ));
            }
            
            foreach ($aRows as $aRow) {
                if (isset($aMappingUser[$aRow['user_id']])) {
                    $data['khach_hang_than_thiet'][] = $aMappingUser[$aRow['user_id']];
                }
            }
            
            //danh sách sản phẩm bán chạy
            $data['san_pham_ban_chay'] = array();
            $aRows = $this->database->select('d.id as id, d.article_id as article_id, d.sku, sum(d.quantity) as quantity, d.unit_price as unit_price, d.vendor_id as vendor_id')
                ->from(Core::getT('shop_order_dt'), 'd')
                ->where('shop_order_id IN ('.implode(',', $aOrderId).')'.$sConds)
                ->group('d.sku')
                ->order('quantity DESC')
                ->limit(10)
                ->execute('getRows');
            
            $aArticleId = array();
            foreach ($aRows as $aRow) {
                if (!in_array($aRow['article_id'], $aArticleId)) {
                    $aArticleId[] = $aRow['article_id'];
                }
            }
            $aMappingArticle = array();
            if(!empty($aArticleId)) {
                $aTmps = $this->database->select('id, title, detail_path, image_path')
                    ->from(Core::getT('article'))
                    ->where('domain_id ='.Core::getDomainId().' AND id IN ('.implode(',', $aArticleId).')')
                    ->execute('getRows');
                
                foreach ($aTmps as $aTmp) {
                    $aMappingArticle[$aTmp['id']] = $aTmp;
                }
            }
            
            foreach ($aRows as $aRow) {
                if (isset($aMappingArticle[$aRow['article_id']]) && !empty($aMappingArticle[$aRow['article_id']])) {
                    $data['san_pham_ban_chay'][] = array(
                        'article_id' => $aRow['article_id'],
                        'sku' => $aRow['sku'],
                        'name' => $aMappingArticle[$aRow['article_id']]['title'],
                        'image_path' => $aMappingArticle[$aRow['article_id']]['image_path'],
                        'detail_path' => $aMappingArticle[$aRow['article_id']]['detail_path'],
                        'vendor_id' => $aRow['vendor_id'],
                        'price_sell' => $aRow['unit_price'],
                    );
                }
            }
        }
        
        
		$this->template()->assign(array('data' => $data));
    }
}
?>