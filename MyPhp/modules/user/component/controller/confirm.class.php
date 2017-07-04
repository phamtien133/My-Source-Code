<?php
class User_Component_Controller_Confirm extends Component
{
    public function process()
    {
        
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        
        //$page['title'] = Core::getPhrase('language_quan-ly-shop-custom');
        $page['title'] = 'Duyệt nạp tiền';
        
        $aPermission = $oSession->get('session-permission');
        $sPageType = $oSession->get('session-page_type');
        
        $sLinkFull = '/user/confirm/?';
        
        if($aPermission['manage_extend'] != 1 && $sPageType == 'marketplace')
        {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }
        $sSubDomain = Core::getParam('core.main_server');
        if ($sSubDomain != 'cms.') {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }
        
        if(empty($errors))
        {
            if($id == 0)
            {
                $query = ' AND status = 0';
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
                /*
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
                */
                $order = '';
                $sap_xep = $_GET['sap_xep'];
                
                /*
                    Quy định sắp xếp:
                    0: id DESC (mặc định)
                    1: id ASC
                    2: user_id DESC
                    3: user_id ASC
                    4: user_id_recharge DESC
                    5: user_id_recharge ASC
                    6: tiền DESC
                    7: tiền ASC
                    
                */
                
                if($sap_xep == 1) $order = 'id ASC';
                elseif($sap_xep == 2) $order = ' user_id DESC';
                elseif ($sap_xep == 3) {
                    $order = ' user_id ASC';
                }
                elseif($sap_xep == 4) $order = ' user_id_recharge DESC';
                elseif ($sap_xep == 5) {
                    $order = ' user_id_recharge ASC';
                }
                elseif($sap_xep == 6) $order = ' total_money DESC, total_coin DESC';
                elseif ($sap_xep == 7) {
                    $order = ' total_money ASC, total_coin ASC';
                }
                else $order = ' id DESC';
                if ($sap_xep > 0) {
                    $duong_dan_phan_trang .= '&sap_xep='.$sap_xep;
                    $sLinkFull .= '&sap_xep='.$sap_xep;
                }
                
                $trang_hien_tai = addslashes($_GET["page"])*1;
                $tong_cong = $this->database->select('count(id)')
                        ->from(Core::getT('recharge_temp'))
                        ->where('domain_id ='.Core::getDomainId().$query)
                        ->execute('getField');
                
                $tong_trang=ceil($tong_cong/$limit);
                if(!@$trang_hien_tai) $trang_hien_tai=1;
                $trang_bat_dau = ($trang_hien_tai-1)*$limit;
                
                $dir = $_SERVER['REQUEST_URI'];
                $tmps = explode('/', $dir, 3);
                $dir = '/'.$tmps[1].'/';
                
                $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;
                
                $aRows = $this->database->select('*')
                        ->from(Core::getT('recharge_temp'))
                        ->where('domain_id ='.Core::getDomainId().$query)
                        ->order($order)
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');
                
                $aUserId = array();
                $aUsers = array();
                foreach ($aRows as $rows)
                {
                    $aData['id'][] = $rows["id"];
                    $aData['total_money'][] = $rows["total_money"];
                    $aData['total_coin'][] = $rows["total_coin"];
                    $aData['note'][] = $rows["note"];
                    
                    if($rows["status"]==0) $tmp = 'status_no';
                    else $tmp = 'status_yes';
                    $aData['status_text'][] = $tmp;
                    $aData['status'][] = $rows["status"];
                    
                    if ($rows['create_time'] > 0) {
                        $rows['create_time'] = Core::getLib('date')->convertFromGmt($rows['create_time'], Core::getParam('core.default_time_zone_offset'));
                        $aData['create_time'][] = date('H:i:s d/m/Y', $rows['create_time']);
                    }
                    else {
                        $aData['create_time'][] = 'Không có T.T';
                    }
                    
                    $aData['user_id'][] = $rows['user_id'];
                    $aData['user_id_recharge'][] = $rows['user_id_recharge'];
                    if ($rows['user_id'] > 0 && !in_array($rows['user_id'], $aUserId)) {
                        $aUserId[] = $rows['user_id'];
                    }
                    if ($rows['user_id_recharge'] > 0 && !in_array($rows['user_id_recharge'], $aUserId)) {
                        $aUserId[] = $rows['user_id_recharge'];
                    }
                }
                
                if (!empty($aUserId)) {
                    $aRows = $this->database->select('id, fullname, username')
                        ->from(Core::getT('user'))
                        ->where('domain_id = '.Core::getDomainId().' AND id IN ('.implode(',',$aUserId).')')
                        ->execute('getRows');
                    foreach ($aRows as $aRow) {
                        if (empty($aRow['fullname'])) {
                            $aRow['fullname'] = 'Không có T.T';
                        }
                        $aUsers[$aRow['id']] = $aRow['fullname'];
                    }
                    
                }
                
                foreach ($aData['user_id'] as $sKey => $sVal) {
                    if (isset($aUsers[$sVal])) {
                        $aData['user_fullname'][$sKey] = $aUsers[$sVal];
                    }
                    else {
                        $aData['user_fullname'][$sKey] = 'Chưa có T.T';
                    }
                    if (isset($aUsers[$aData['user_id_recharge'][$sKey]])) {
                        $aData['user_recharge_name'][$sKey] = $aUsers[$aData['user_id_recharge'][$sKey]];
                    }
                    else {
                        $aData['user_recharge_name'][$sKey] = 'Chưa có T.T';
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
        );
        
        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sap_xep');
        
        foreach($output as $key)
        {
            $data[$key] = $$key;
        }
        
        $this->template()->setHeader(array(
            'advertise.css' => 'site_css',
            'confirm_recharge.js' => 'site_script',
        ));
        
        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'output' => $output,
            'data' => $data,
        ));
    }
}
?>
