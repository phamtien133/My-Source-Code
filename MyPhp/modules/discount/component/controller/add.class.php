<?php
class Discount_Component_Controller_Add extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        
        
        $iId = 0;
        if (isset($aVals['id'])) {
            $iId = $aVals['id'];
        }
        
        if ($iId < 0) $iId = 0;

        $cong_thuc_danh_sach = array('-', '+', '*', '/', '=');
        $aValueType = array(
            0 => Core::getPhrase('language_phan-tram'),
            1 => Core::getPhrase('language_co-dinh')
        );
        $gia_ap_dung_danh_sach = array(Core::getPhrase('language_gia-ban'), Core::getPhrase('language_thanh-tien'));
        $aData['apply'] = 0;
        $aData['status'] = 1;
        
        $aProgramType = array(
            0 => 'Đi siêu thị',
            1 => 'Nhà cung cấp',
        );
        
        $aVendorValue = array();
        $aRows = $this->database->select('id, name')
            ->from(Core::getT('vendor'))
            ->where('domain_id ='.Core::getDomainId().' AND status = 1')
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aVendorValue[$aRow['id']] = array(
                'id' => $aRow['id'],
                'name' => $aRow['name'],
            );
        }
        
        $aApply = array(
            0 => 'Đơn hàng',
            1 => 'Sản phẩm',
        );
        $aErrors = array();
        $aCondsSelect= array();
        if(!empty($_POST))
        {
            print_r($aVals);die;
            $aVals = $_POST['val'];
            if(empty($aErrors) && $iId < 1)
            {
                //Chỉ cho tạo mới
                $aData['name'] = $aVals['name'];
                //$aData['name_code'] = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($aVals['name_code'])));
                $aData['name_code'] = isset($aVals['name_code']) ? $aVals['name_code'] : '';
                $aData['program_type'] = isset($aVals['program_type']) ? $aVals['program_type'] : -1;
                $aData['vendor_id'] = isset($aVals['vendor_id']) ? $aVals['vendor_id'] : -1;
                $aData['total'] = isset($aVals['total']) ? $aVals['total'] : -1;
                if ($aData['program_type'] != 1) {
                    $aData['vendor_id'] = 0;
                }
                 
                $aData['status'] = $aVals['status']*1;
                if($aData['status'] != 1) $aData['status'] = 0;
                
                $phan_tram = $aVals['phan_tram']*1;
                if($phan_tram < 0) $phan_tram = 0;
                
                $aData['value'] = $aVals['value']*1;
                if($aData['value'] < 0) $aData['value'] = 0;
                
                $aData['type'] = $aVals['type']*1;
                if(!isset($aValueType[$aData['type']])) $aData['type'] = 0;
                
                $cong_thuc = $aVals['cong_thuc']*1;
                if(!isset($cong_thuc_danh_sach[$cong_thuc])) $cong_thuc = 0;
                
                // thiết lập mặc định cho $cong_thuc
                $cong_thuc = 0;
                /*
                $gia_ap_dung = $aVals['gia_ap_dung']*1;
                if(!isset($gia_ap_dung_danh_sach[$gia_ap_dung])) $gia_ap_dung = 0;
                
                $tong_tien_ap_dung = $aVals['tong_tien_ap_dung']*1;
                if($tong_tien_ap_dung < 0) $tong_tien_ap_dung = 0;
                */
                $aData['start_time'] = $aVals['start_time'];
                $aData['end_time'] = $aVals['end_time'];
                
                $thoi_gian_bat_dau = $aVals['start_time'];
                $thoi_gian_ket_thuc = $aVals['end_time'];
                
                if(is_numeric($aVals['times_to_use']))
                {
                    $aData['times_to_use'] = $aVals['times_to_use']*1;
                    if($aData['times_to_use'] > 9999) $aData['times_to_use'] = 1;
                }
                else
                    $aData['times_to_use'] = 0;
                
                $aData['apply'] = $aVals['apply']*1;
                if ($aData['apply'] < 0)
                    $aData['apply'] = 0;
                
                foreach($aVals['gt_ma_so'] as $i => $v)
                {
                    if($v < 1) continue;
                    $gt_ma_so[] = $v*1;
                    
                    $gt_loai[] = $aVals["gt_loai"][$i];
                    
                    if($aVals["gt_trang_thai"][$i] != 1) $gt_trang_thai[] = 0;
                    else $gt_trang_thai[] = 1;
                    
                    $tmp = $aVals["gt_stt"][$i]*1;
                    if($tmp < 1) $tmp = 0;
                    
                    $gt_stt[] = $tmp;
                }
                
                $aData['total_item'] = isset($aVals['total_item']) ? $aVals['total_item'] : -1;
                $aConds = array();
                $aData['conds'] = isset($aVals['conds']) ? $aVals['conds'] : array();
                $aCondsSelect = isset($aVals['conds_select']) ? $aVals['conds_select'] : array();
                
            }
            if(empty($aErrors))
            {
                if (!in_array($aData['program_type'], array_keys($aProgramType))) {
                    $aErrors[] = 'Chưa chọn loại chương trình giảm giá';
                }
                else {
                    if ($aData['program_type'] == 1) {
                        if ($aData['vendor_id'] < 1) {
                            $aErrors[] = 'Chưa chọn nhà cung cấp';
                        }
                    }
                }
                
                if(mb_strlen($aData['name']) < 1 || mb_strlen($aData['name']) > 225) $aErrors[] = sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225);
                
                if($aData['total_item'] < 1) $aErrors[] = 'Số lượng mã giảm giá phải lớn 0';
                
                if(mb_strlen($aData['name_code']) < 1 || mb_strlen($aData['name_code']) > 10) $aErrors[] = sprintf(Core::getPhrase('language_x-phai-tu-x-den-x-ky-tu'), 'Mã giảm giá', 1, 10);
                
                if (!in_array($aData['type'], array_keys($aValueType))) {
                    $aErrors[] = 'Chưa chọn loại giảm giá';
                }
                else {
                    if ($aData['type'] == 0) {
                        if ($aData['value'] <= 0 || $aData['value'] > 100) {
                            $aErrors[] = 'Phần trăm giảm giá phải lớn hơn 0 và nhỏ hơn 100';
                        }
                    }
                }
                
                if ($aData['times_to_use'] < 1) {
                    $aErrors[] = 'Số lẩn sử dung phải lớn hơn 0';
                }
            }
            
            if(!$aErrors)
            {
                // check thoi gian bat dau
                if(@!$thoi_gian_bat_dau) $aErrors[] = Core::getPhrase('language_thoi-gian-bat-dau-chua-duoc-nhap');
                else {
                    $thoi_gian_bat_dau = strtotime($thoi_gian_bat_dau);
                    if ($thoi_gian_bat_dau === false) {
                        $aErrors[] = Core::getPhrase('language_thoi-gian-bat-dau-khong-ton-tai');
                    }
                    /*
                    // tách năm và giờ phút
                    $tmp=explode(" ", $thoi_gian_bat_dau);
                    // tách năm
                    $ngay_thang = explode("-", $tmp[0]);
                    $ngay_thang[0] *= 1;
                    $ngay_thang[1] *= 1;
                    $ngay_thang[2] *= 1;
                    
                    if(!@checkdate($ngay_thang[1], $ngay_thang[0], $ngay_thang[2])) $aErrors[] = Core::getPhrase('language_thoi-gian-bat-dau-khong-ton-tai');
                    else
                    {
                        // tách giờ phút
                        $thoi_gian = explode(":", $tmp[1]);
                        $thoi_gian[0] *= 1;
                        $thoi_gian[1] *= 1;
                        $thoi_gian[2] *= 1;
                        if($thoi_gian[0] < 0 || $thoi_gian[0] > 23) $thoi_gian[0] = 0;
                        if($thoi_gian[1] < 0 || $thoi_gian[1] > 59) $thoi_gian[1] = 0;
                        if($thoi_gian[2] < 0 || $thoi_gian[2] > 59) $thoi_gian[2] = 0;
                        $thoi_gian_bat_dau_int = mktime($thoi_gian[0], $thoi_gian[1], $thoi_gian[2], $ngay_thang[1], $ngay_thang[0], $ngay_thang[2]);
                        unset($thoi_gian);
                    }
                    unset($ngay_thang);
                    */
                }
                // end
                // check thoi gian bat dau
                if(@!$thoi_gian_ket_thuc) $aErrors[] = Core::getPhrase('language_thoi-gian-ket-thuc-chua-duoc-nhap');
                else {
                    
                    $thoi_gian_ket_thuc = strtotime($thoi_gian_ket_thuc);
                    if ($thoi_gian_ket_thuc === false) {
                        $aErrors[] = Core::getPhrase('language_thoi-gian-ket-thuc-khong-ton-tai');
                    }
                    /*
                    // tách năm và giờ phút
                    $tmp=explode(" ", $thoi_gian_ket_thuc);
                    // tách năm
                    $ngay_thang = explode("-", $tmp[0]);
                    $ngay_thang[0] *= 1;
                    $ngay_thang[1] *= 1;
                    $ngay_thang[2] *= 1;
                    if(!@checkdate($ngay_thang[1], $ngay_thang[0], $ngay_thang[2])) $aErrors[] = Core::getPhrase('language_thoi-gian-ket-thuc-khong-ton-tai');
                    else
                    {
                        // tách giờ phút
                        $thoi_gian = explode(":", $tmp[1]);
                        $thoi_gian[0] *= 1;
                        $thoi_gian[1] *= 1;
                        $thoi_gian[2] *= 1;
                        if($thoi_gian[0] < 0 || $thoi_gian[0] > 23) $thoi_gian[0] = 0;
                        if($thoi_gian[1] < 0 || $thoi_gian[1] > 59) $thoi_gian[1] = 0;
                        if($thoi_gian[2] < 0 || $thoi_gian[2] > 59) $thoi_gian[2] = 0;
                        $thoi_gian_ket_thuc_int = mktime($thoi_gian[0], $thoi_gian[1], $thoi_gian[2], $ngay_thang[1], $ngay_thang[0], $ngay_thang[2]);
                        unset($thoi_gian);
                    }
                    unset($ngay_thang);
                    */
                }
                
                if (empty($aErrors)) {
                    if ($thoi_gian_bat_dau > $thoi_gian_ket_thuc) {
                        $aErrors[] = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu';
                    }
                }
                // end
            }
            
            if (empty($aErrors)) {
                //Hóa đơn
                if ($aData['apply'] == 0) {
                    $aConds['type'] = 'order';
                    //check select
                    if (!isset($aCondsSelect['order']) || empty($aCondsSelect['order'])) {
                        $aErrors[] = 'Chưa chọn điều kiện giảm giá';
                    }
                    else {
                        foreach ($aCondsSelect['order'] as $sKey => $sVal) {
                            if ($sKey == 'price' && $sVal == 'on') {
                                $aConds[$sKey] = $aData['conds']['order'][$sKey];
                                if (!isset($aData['conds']['order']['price']['from']) || $aData['conds']['order']['price']['from'] <= 0) {
                                    $aErrors[] = 'Điều kiện giá trị hóa đơn phải lớn hơn 0';
                                }
                            }
                            if ($sKey == 'all' && $sVal == 'on') {
                                $aConds[$sKey] = 1;
                            }
                        }
                    }
                    
                }
                else if ($aData['apply'] == 1) {
                    //san phâm
                    $aConds['type'] = 'product';
                    if (!isset($aCondsSelect['product']) || empty($aCondsSelect['product'])) {
                        $aErrors[] = 'Chưa chọn điều kiện giảm giá';
                    }
                    else {
                        foreach ($aCondsSelect['product'] as $sKey => $sVal) {
                            if ($sKey == 'price' && $sVal == 'on') {
                                $aConds[$sKey] = $aData['conds']['product'][$sKey];
                                if (!isset($aData['conds']['product']['price']['from']) || $aData['conds']['product']['price']['from'] <= 0
                                    || !isset($aData['conds']['product']['price']['to']) || $aData['conds']['product']['price']['to'] <= 0
                                    || $aData['conds']['product']['price']['from'] > $aData['conds']['product']['price']['to']
                                ) {
                                    $aErrors[] = 'Điều kiện giá giá tiền sản phẩm không hợp lệ';
                                }
                            }
                            
                            if ($sKey == 'list' && $sVal == 'on') {
                                $aConds[$sKey] = $aData['conds']['product'][$sKey];
                                if (!isset($aData['conds']['product']['list']['id']) || empty($aData['conds']['product']['list']['id'])) {
                                    $aErrors[] = 'Chưa có sản phẩm áp dụng giảm giá';
                                }
                                else {
                                    //Xử lý danh sách sản phẩm
                                    
                                }
                            }
                        }
                    }
                }
            }
            
            //Kiểm tra tồn tại mã
            if(empty($aErrors)) {
                $sCond = '';
                if ($iId > 0) {
                    $sCond = ' AND id != '.$iId;
                }
                $iCnt = $this->database->select('count(*)')
                    ->from(Core::getT('discount'))
                    ->where('domain_id ='.Core::getDomainId().' AND name_code = \''.$aData['name_code'].'\''.$sCond)
                    ->execute('getField');
                if ($iCnt > 0) {
                    $aErrors[] = 'Mã giảm giá đã tồn tại';
                }
            }
            //end
            
            // kiểm tra trích lọc dữ liệu
            if(empty($aErrors))
            {
                // lọc các phần tử có mã số trùng nhau
                for($i = 0; $i < count($gt_ma_so); $i++)
                {
                    for($j = $i + 1; $j < count($gt_ma_so); $j++)
                    {
                        if($gt_ma_so[$i] == $gt_ma_so[$j]) unset($gt_ma_so[$j]);
                    }
                }
                
                // tính vị trí = $i;
                $tong = count($gt_ma_so);
                for($i = 0; $i < $tong; $i++)
                {
                    $vi_tri[$i] = $i;
                }
                
            }
            // end
            
            // kiểm tra id của bài viết
            if(empty($aErrors))
            {
                $danh_sach = array();
                foreach($gt_ma_so as $k => $v)
                {
                    if($gt_loai[$k] == 'article')
                    {
                        if ($v > 0) {
                            $danh_sach[] = $v;
                        }
                    }
                }
                if(!empty($danh_sach))
                {
                    $rows = $this->database->select('group_concat("", id) id')
                        ->from(Core::getT('article'))
                        ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id =('.implode(',', $danh_sach).')')
                        ->execute('getRow');
                    $stt_list = explode(',', $rows['id']);
                    for($i = 0; $i < count($gt_ma_so); $i++)
                    {
                        if($gt_loai[$i] == 'article' && $gt_ma_so[$i] > 0)
                        {
                            $tiep = false;
                            for($j = 0; $j < count($stt_list); $j++)
                            {
                                if($gt_ma_so[$i] == $stt_list[$j])
                                {
                                    $tiep = true;
                                    break;
                                }
                            }
                            if(!$tiep)
                            {
                                $aErrors[] = Core::getPhrase('language_bai-viet').Core::getPhrase('language_khong-ton-tai-du-lieu').$gt_stt[$i];
                            }
                        }
                    }
                }
            }
            
            // kiểm tra id của đề tài
            if(empty($aErrors))
            {
                $danh_sach = array();
                foreach($gt_ma_so as $k => $v)
                {
                    if($gt_loai[$k] == 'category')
                    {
                        $danh_sach[] = $v;
                    }
                }
                if(!empty($danh_sach))
                {
                    $rows = $this->database->select('group_concat("", id) id')
                        ->from(Core::getT('category'))
                        ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id =('.implode(',', $danh_sach).')')
                        ->execute('getRow');
                   
                    $stt_list = explode(',', $rows['id']);
                    for($i = 0; $i < count($gt_ma_so); $i++)
                    {
                        if($gt_loai[$i] == 'category' && $gt_ma_so[$i] > 0)
                        {
                            $tiep = false;
                            for($j = 0; $j < count($stt_list); $j++)
                            {
                                if($gt_ma_so[$i] == $stt_list[$j])
                                {
                                    $tiep = true;
                                    break;
                                }
                            }
                            if(!$tiep)
                            {
                                $aErrors[] = Core::getPhrase('language_de-tai').Core::getPhrase('language_khong-ton-tai-du-lieu').$gt_stt[$i];
                            }
                        }
                    }
                }
            }
            
            
            if(empty($aErrors))
            {
                $aInsert = array(
                    'program_type' => $aData['program_type'],
                    'vendor_id' => $aData['vendor_id'],
                    'name' => $aData['name'],
                    //'total_item' => $aData['total_item'],
                    //'name_code' => $aData['name_code'],
                    'value' => $aData['value'],
                    'type' => $aData['type'],
                    'formula' => 0,
                    'price_apply' => 0,
                    'total_amount' => 0,
                    'times_to_use' => $aData['times_to_use'],
                    'apply' => $aData['apply'],
                    //'is_default' => 'mac_dinh',
                    'status' => $aData['status'],
                );
                
                foreach($tmps as $key => $v)
                {
                    $aInsert[$key] = $$v;
                }
                $aInsert['start_time'] = $thoi_gian_bat_dau;
                $aInsert['end_time'] = $thoi_gian_ket_thuc;
                $aInsert['is_default'] = 1;
                $aInsert['conds'] = serialize($aConds);
                // cập nhật dữ liệu
                if($iId > 0)
                {
                    $this->database->update(Core::getT('discount'), $aInsert, 'id ='.$iId);
                    $stt = $iId;
                }
                else
                {
                    $aInsert['total_item'] = $aData['total_item'];
                    $aInsert['name_code'] = $aData['name_code'];
                    $aInsert['user_id'] = Core::getUserId();
                    $aInsert['domain_id'] = Core::getDomainId();
                    $aInsert['time'] = CORE_TIME;
                    
                    $stt = $this->database->insert(Core::getT('discount'), $aInsert);
                    
                    if ($stt > 0) {
                        $iCount = 0;
                        $aArray = array();
                        $iTotalCharacter = 10;
                        while($iCount < $aData['total_item']) {
                            $iNumber = Core::getService('core.tools')->getRandomCode($iTotalCharacter);
                            
                            if(!in_array($iNumber, $aArray)) {
                                $aArray[] = $iNumber;
                                
                                $aInsertItem = array(
                                    'discount_id' => $stt,
                                    'code' => $aData['name_code'].$iNumber,
                                    'total_used' => 0,
                                    'last_time_used' => 0,
                                    'status' => 1,
                                    'domain_id' => Core::getDomainId(), 
                                );
                                $this->database->insert(Core::getT('discount_item'), $aInsertItem);
                                $iCount++;
                            }
                        }
                    }
                }
                /*
                if($iId > 0 && $aData['apply'] == 0)
                {
                    //Xóa chi tiết giảm giá (nếu có) khi áp dụng = 0
                    $this->database->delete(Core::getT('discount_detail'), 'discount_id = '.$iId);
                }
                else if($aData['apply'] == 1)
                {
                    if($iId > 0)
                    {
                        $this->database->delete(Core::getT('discount_detail'), 'discount_id = '.$iId);
                    }
                    
                    // thêm các dữ liệu không tồn tại
                    foreach($gt_ma_so as $i => $v)
                    {
                        if($gt_loai[$i] == 'article') $tmp = 0;
                        else $tmp = 1;
                        
                        $aInsertDetail = array(
                            'discount_id' => $stt,
                            'product_id' => $gt_ma_so[$i],
                            'type' => $tmp,
                            'status' => $gt_trang_thai[$i],
                            'position' => $vi_tri[$i],
                        );
                        $this->database->insert(Core::getT('discount_detail'), $aInsertDetail);
                    }
                }
                */
                // ghi log hệ thống
                Core::getService('core.tools')->saveLogSystem(array('action' => 'create_edit_discount'.'-'.$iId,'content' => 'phpinfo',));
                // end

                $status_global=3;
                //re-direct page
                $sDir = $_SERVER['REQUEST_URI'];
                $aTmps = explode('/', $sDir, 3);
                $sDir = '/'.$aTmps[1].'/';
                header('Location: '.$sDir);
            }
            if(!empty($aErrors) && 1 == 2)
            {
                $status_global=1;
                $aRows = $this->database->select('id, title article_name, detail_path')
                    ->from(Core::getT('article'))
                    ->where('id IN ('.implode(',', $gt_ma_so).')')
                    ->execute('getRows');
                
                foreach ($aRows as $rows)
                {
                    foreach($gt_ma_so as $k => $v)
                    {
                        if($v == $rows['id'])
                        {
                            $gt_ten[$k] = $rows['article_name'];
                            $gt_duong_dan[$k] = $rows['detail_path'];
                        }
                    }
                }
            }
        }
        elseif(empty($aErrors))
        {
            if (isset($aVals['id'])) {
                $aParam['id'] = $aVals['id'];
            }
            $aData = Core::getService('discount')->initCreate($aParam); 
        }
        else
            $status_global = 2;
            
        
        
        $output = array(
            'apply',
            'formula',
            'cong_thuc_danh_sach',
            'aErrors',
            'price_apply',
            'gia_ap_dung_danh_sach',
            'value',
            'gt_duong_dan',
            'gt_loai',
            'gt_ma_so',
            'gt_stt',
            'gt_ten',
            'gt_trang_thai',
            'id',
            'type',
            'aValueType',
            'name_code',
            'times_to_use',
            'status_global',
            'name',
            'start_time',
            'end_time',
            'tong_tien_ap_dung',
            'status',
            'aCondsSelect',
            'aPage',
            'aProgramType',
            'aVendorValue',
            'aApply',
            'aData',
            'iId',
        );
        
        
        $this->template()->setHeader(array(
            'marketing.css' => 'site_css',
            'discount.js' => 'site_script',
        ));
        
        $this->template()->assign(array(
            'aData' => $aData,
        ));
        
        $this->template()->setTitle('Mã giảm giá');
    }
}
?>