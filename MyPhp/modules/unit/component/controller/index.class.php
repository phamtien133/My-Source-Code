<?php
class Unit_Component_Controller_Index extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        
        //Điều hướng page
        if (isset($aVals['req2'])) {
            if ($aVals['req2'] == 'edit' || $aVals['req2'] == 'add') {
                return Core::getLib('module')->setController('unit.add');
            }
        }
        
        //$page['title'] = Core::getPhrase('language_quan-ly-shop-custom');
        $page['title'] = 'Quản lý đơn vị tính';
        
        $aPermission = $oSession->get('session-permission');
        $sPageType = $oSession->get('session-page_type');
        
        $sLinkFull = '/unit/?';
        
        if($aPermission['manage_extend'] != 1 && $sPageType == 'marketplace')
        {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }
        
        if(empty($errors))
        {
            if($id == 0)
            {
                $query = '';
                $duong_dan_phan_trang = '';
                $limit = $_GET['limit']*1;
                if($limit < 1) $limit = 15;
                
                if(!empty($limit))
                {
                    $sLinkFull .= '&limit='.$limit;
                    $duong_dan_phan_trang .= '&limit='.$limit;
                }
                
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
                    $query .= ' AND name LIKE "%'.addslashes($tu_khoa).'%"';
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
                    
                */
                
                if($sap_xep == 1) $order = 'id ASC';
                elseif($sap_xep == 2) $order = ' name DESC';
                elseif ($sap_xep == 3) {
                    $order = ' name ASC';
                }
                else $order = ' id DESC';
                if ($sap_xep > 0) {
                    $duong_dan_phan_trang .= '&sap_xep='.$sap_xep;
                    $sLinkFull .= '&sap_xep='.$sap_xep;
                }
                
                
                
                $trang_hien_tai = addslashes($_GET["page"])*1;
                $tong_cong = $this->database->select('count(id)')
                        ->from(Core::getT('unit'))
                        ->where('status != 2 AND domain_id ='.Core::getDomainId().$query)
                        ->execute('getField');
                
                $tong_trang=ceil($tong_cong/$limit);
                if(!@$trang_hien_tai) $trang_hien_tai=1;
                $trang_bat_dau = ($trang_hien_tai-1)*$limit;
                
                $dir = $_SERVER['REQUEST_URI'];
                $tmps = explode('/', $dir, 3);
                $dir = '/'.$tmps[1].'/';
                
                $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;
                
                $aRows = $this->database->select('*')
                        ->from(Core::getT('unit'))
                        ->where('status != 2 AND domain_id ='.Core::getDomainId().$query)
                        ->order($order)
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');
                
                foreach ($aRows as $rows)
                {
                    $shop_custom['id'][] = $rows["id"];
                    $shop_custom['name'][] = $rows["name"];
                    if($rows["status"]==0) $tmp = 'status_no';
                    else $tmp = 'status_yes';
                    $shop_custom['status_text'][] = $tmp;
                    $shop_custom['status'][] = $rows["status"];
                    if(!in_array($rows["custom_label_id"], $loai_ds)) $loai_ds[] = $rows["custom_label_sid"];
                }
                $status=1;
            }
        }
        else $status=4;
        
        $output = array(
            'duong_dan_phan_trang',
            'errors',
            'shop_custom',
            'status',
            'tong_trang',
            'tong_cong',
            'trang_hien_tai',
            'sap_xep',
            'tu_khoa',
            'sLinkFull',
            'sLinkSort',
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
