<?php
class Discount_Component_Controller_List extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        
        //$page['title'] = Core::getPhrase('language_quan-ly-shop-custom');
        $page['title'] = 'Chi tiết mã giảm giá';
        
        $aPermission = $oSession->get('session-permission');
        $sPageType = $oSession->get('session-page_type');
        
        $errors = array();
        $iId = isset($aVals['id']) ? $aVals['id'] : -1;
        $aDiscount = array();
        if ($iId < 1) {
            $errors[] = 'Trang không tồn tại';
        }
        else {
            $aDiscount = $this->database->select('*')
                ->from(Core::getT('discount'))
                ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$iId)
                ->execute('getRow');
            if (!isset($aDiscount['id'])) {
                $errors[] = 'Loại giảm giá không tồn tại';
            }
            else {
                if ($aDiscount["type"] == 0) {
                    $aDiscount['value_txt'] = ($aDiscount["value"]*1).'%';
                }
                else {
                    $aDiscount['value_txt'] = Core::getService('core.currency')->formatMoney(array('money' => $aDiscount["value"]));
                }
                
                if ($aDiscount["start_time"] > 0) {
                    $aDiscount['start_time'] = date('d-m-Y', $aDiscount["start_time"]);
                }
                else {
                    $aDiscount['start_time'] = '';
                }
                if ($aDiscount["end_time"] > 0) {
                    $aDiscount['end_time'] = date('d-m-Y', $aDiscount["end_time"]);
                }
                else {
                    $aDiscount['end_time'] = '';
                }
            }
        }
        
        $sLinkFull = '/discount/list/?id='.$iId;
        
        if($aPermission['manage_extend'] != 1 && $sPageType == 'marketplace')
        {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }
        
        if(empty($errors))
        {
            if($iId > 0)
            {
                $query = ' AND discount_id ='.$iId;
                $duong_dan_phan_trang = '';
                $limit = $_GET['limit']*1;
                if($limit < 1) $limit = 15;
                
                if(!empty($limit))
                {
                    $sLinkFull .= '&limit='.$limit;
                    $duong_dan_phan_trang .= '&limit='.$limit;
                }
                
                //Trạng thái
                $status = $_GET['status']*1;
                
                $aStatus = array(
                    
                );
                
                // tìm kiếm
                $tu_khoa = '';
                if(isset($_GET["q"]))
                {
                    $tu_khoa = $_GET["q"];
                    $tu_khoa = urldecode($tu_khoa);
                    $tu_khoa = trim(Core::getLib('input')->removeXSS($tu_khoa));
                    if(mb_strlen($tu_khoa) > 100) $tu_khoa = '';
                }
                if(!empty($tu_khoa))
                {
                    $query .= ' AND code LIKE "%'.addslashes($tu_khoa).'%"';
                    //$query .= ' AND MATCH(tieu_de) AGAINST ("%'.addslashes($tu_khoa).'%" IN BOOLEAN MODE)';
                    $duong_dan_phan_trang .= '&q='.urlencode($tu_khoa);
                    $sLinkFull .= '&q='.urlencode($tu_khoa);
                }
                
                $order = '';
                $sap_xep = $_GET['sap_xep'];
                
                /*
                    Quy định sắp xếp:
                    0: id DESC (mặc định)
                    1: id ASC
                    2: name DESC
                    3: name ASC
                    4: total_used DESC
                    5: total_used ASC
                    6: vendor_id DESC
                    7: vendor_id ASC
                    
                */
                
                if($sap_xep == 1) $order = 'id ASC';
                elseif($sap_xep == 2) $order = ' code DESC';
                elseif ($sap_xep == 3) {
                    $order = ' code ASC';
                }
                elseif($sap_xep == 4) $order = ' total_used DESC';
                elseif ($sap_xep == 5) {
                    $order = ' total_used ASC';
                }
                elseif($sap_xep == 6) $order = ' last_time_used DESC';
                elseif ($sap_xep == 7) {
                    $order = ' last_time_used ASC';
                }
                else $order = ' id DESC';
                if ($sap_xep > 0) {
                    $duong_dan_phan_trang .= '&sap_xep='.$sap_xep;
                    $sLinkFull .= '&sap_xep='.$sap_xep;
                }
                
                $trang_hien_tai = addslashes($_GET["page"])*1;
                $tong_cong = $this->database->select('count(id)')
                        ->from(Core::getT('discount_item'))
                        ->where('status != 2 AND domain_id ='.Core::getDomainId().$query)
                        ->execute('getField');
                
                $tong_trang=ceil($tong_cong/$limit);
                if(!@$trang_hien_tai) $trang_hien_tai=1;
                $trang_bat_dau = ($trang_hien_tai-1)*$limit;
                
                $dir = $_SERVER['REQUEST_URI'];
                $tmps = explode('/', $dir, 3);
                $dir = '/'.$tmps[1].'/';
                
                $duong_dan_phan_trang = $dir.'list/?id='.$iId.$duong_dan_phan_trang;
                
                $aRows = $this->database->select('*')
                        ->from(Core::getT('discount_item'))
                        ->where('status != 2 AND domain_id ='.Core::getDomainId().$query)
                        ->order($order)
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');
                
                foreach ($aRows as $rows)
                {
                    $aData['id'][] = $rows["id"];
                    $aData['code'][] = $rows["code"];
                    $aData['total_used'][] = $rows["total_used"];
                    
                    if($rows["status"]==0) $tmp = 'status_no';
                    else $tmp = 'status_yes';
                    $aData['status_text'][] = $tmp;
                    $aData['status'][] = $rows["status"];
                    
                    if ($rows['last_time_used'] > 0) {
                        $aData['last_time_used'][] = date('H:i:s d/m/Y', $rows['last_time_used']);
                    }
                    else {
                        $aData['last_time_used'][] = 'Chưa S.D';
                    }
                    
                }
                $status=1;
                
            }
        }
        else $status=4;
        
        $output = array(
            'duong_dan_phan_trang',
            'errors',
            'aData',
            'status',
            'tong_trang',
            'tong_cong',
            'trang_hien_tai',
            'sap_xep',
            'tu_khoa',
            'sLinkFull',
            'sLinkSort',
            'aDiscount',
            'iId',
        );
        
        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sap_xep');
        
        foreach($output as $key)
        {
            $data[$key] = $$key;
        }
        
        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));
        
        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'output' => $output,
            'data' => $data,
        ));
    }
}
?>

