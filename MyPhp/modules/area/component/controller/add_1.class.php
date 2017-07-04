<?php
class Area_Component_Controller_Add extends Component
{
    private $_tmp = array();
    function Menu_gui_bai($parentid,$menu,$res = '',$sep = ''){
            
            foreach($menu as $v){
                if($v[2] == $parentid)
                {
                    $this->_tmp[] = array($v[0], $sep.$v[1]);
                    $res.= $this->Menu_gui_bai($v[0],$menu,$res,$sep."&nbsp;&nbsp;&nbsp;");
                }
            }
        }
    
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        
        $type = 'create_edit_area';
        $id = 0;
        $mid = 0;
        
        if (isset($aVals['id'])) {
            $id = $aVals['id'];
        }
        if (isset($aVals['mid'])) {
            $mid = $aVals['mid'];
        }
        
        $aPermission = $oSession->get('session-permission');
        
        $output = array(
            'status',
            'ten',
            'mo_ta',
            'ghi_chu',
            'duong_dan',
            'duong_dan_hinh',
            'so_cot',
            'trang_thai',
            'lien_ket_stt',
            'lien_ket_loai',
            'tu_cap_nhat',
            'targetWindows',
            'quyen',
            'vi_tri',
            'cha_stt',
            'errors',
            'danh_sach_menu',
            'mid',
            'id',
            'targetWindows_list',
            'sub',
            'tonTaiMang',
        );
        
        $page['title']=Core::getPhrase('language_tao-menu');

if($aPermission['manage_menu']!=1)
{
    // check xem có quyền chi tiết ko
    if(!empty($aPermission['edit_menu']))
    {
        $tmp = explode(',', $aPermission['edit_menu']);
        if(!in_array($id, $tmp))
        {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap').'(2)';
        }
    }
    else
    {
        $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap').'(1)';
    }
}

if(isset($aVals['sub']))
{
    $sub = (int)$aVals['sub'];
}

// kiểm tra có quyền tương tác với Menu gt ko
$iCnt = $this->database->select('count(*)')
            ->from(Core::getT('menu'))
            ->where('domain_id ='.Core::getDomainId().' AND id ='.$mid.' AND status != 2')
            ->execute('getField');

if ($iCnt < 1) {
    //$errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap').'(3)';
}


if($aPermission['manage_menu']!=1)
    $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap').'(2)';
if(empty($errors))
{
    if(!empty($_POST))
    {
        d($_POST); die();
        // neu la tao de tai, them cac truong bat buoc phai co
        if($id == 0)
        {
            $tonTaiMang['name'] = true;
            $tonTaiMang['name_code'] = true;
            //$tonTaiMang['path'] = true;
            $tonTaiMang['parent_id'] = true;
            $tonTaiMang['status'] = true;
        }
        $tmps = explode(',', $_POST["mang"]);
        foreach($tmps as $tmp)
        {
            if($tmp == 'name')
                $tonTaiMang['name'] = true;
            if($tmp == 'parent_id')
                $tonTaiMang['parent_id'] = true;
            if($tmp == 'path')
                $tonTaiMang['path'] = true;
            if($tmp == 'name_code')
                $tonTaiMang['name_code'] = true;
            if($tmp == 'status')
                $tonTaiMang['status'] = true;
        }
        $so_muc = 0;
        if($tonTaiMang['name'])
            $ten = htmlspecialchars(stripslashes(trim($_POST["name"])));
        
        if($tonTaiMang['parent_id'])
        {
            $cha_stt = $_POST["parent_id"]*1;
            if($cha_stt == $id)
            {
                unset($tonTaiMang['parent_id']);
            }
            if($cha_stt < 1) $cha_stt = -1;
        }
        if($tonTaiMang['path'])
            $duong_dan = Core::getLib('input')->removeXSS(trim($_POST["path"]));
        
        if($tonTaiMang['name_code']) {
            $ma_ten = Core::getLib('input')->removeXSS(trim($_POST["name_code"]));
            $duong_dan = $ma_ten;
            $tonTaiMang['path'] = true;
        }
            
        
        if($tonTaiMang['status'])
        {
            if($_POST["status"] != 1) $trang_thai = 0;
            else $trang_thai = 1;
        }
        else $trang_thai = 1;
        
        
        $iSessionCode = $oSession->get('session-ma_so_bai_viet');
        
        if($iSessionCode == $_POST["ma_so_bai_viet"]) $errors[] = 'Đã hết phiên làm viiệc trước đó';
        if(empty($errors))
        {
            if($tonTaiMang['name'] && (mb_strlen($ten)<2 || mb_strlen($ten)>50)) $errors[] = sprintf(Core::getPhrase('language_x-phai-tu-x-den-x-ky-tu'), Core::getPhrase('language_ten'), 2, 50);
            
            if($tonTaiMang['name_code'] && (mb_strlen($ma_ten)>225)) $errors[] = sprintf(Core::getPhrase('language_duong-dan-phai-it-hon-x-ky-tu'), 255);
        }
        
        // kiểm tra xem stt tồn tại
        if(empty($errors) && $tonTaiMang['parent_id'] && $id > 0)
        {
            $rows = $this->database->select('id, parent_id')
                ->from(Core::getT('area'))
                ->where('id ='.$id)
                ->execute('getRow');
            
            if($rows['id'] < 1) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), 'Khu vực');
            if($rows['parent_id'] == $cha_stt)
            {
                unset($tonTaiMang['parent_id']);
            }
        }
        // kiểm tra xem cha stt tồn tại
        if(empty($errors) && $tonTaiMang['parent_id'] && $cha_stt > 0)
        {
            $tong = $rows = $this->database->select('count(id)')
                ->from(Core::getT('area'))
                ->where('id ='.$cha_stt)
                ->execute('getField');
            
            if(empty($tong)) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), 'Khu vực cha');
        }
        
        //Kiểm tra mã tên có tồn tại chưa
        
        if(empty($errors))
        {
            $oSession->set('session-'.$type, 0);
            
            $sql_noi_dung = '';
            
            $aInsertContent = array();
            
            if($tonTaiMang['name']) $aInsertContent['name'] = $ten;
            if($tonTaiMang['path']) $aInsertContent['path'] = $duong_dan;
            if($tonTaiMang['name_code']) $aInsertContent['name_code'] = $ma_ten;
            
            if($tonTaiMang['parent_id']) $aInsertContent['parent_id'] = $cha_stt;
            
            if($tonTaiMang['status']) $aInsertContent['status'] = $trang_thai;
           
            
            if($id > 0)
            {
                if(!empty($aInsertContent))
                {
                    $iFlag = $this->database->update(Core::getT('area'), $aInsertContent, 'id = '.$id);
                }
                $stt = $id;
            }
            else
            {
                $stt = $this->database->insert(Core::getT('area'), $aInsertContent);
            }
            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array(
                'action' => $type.'-'.$id,
                'content' => 'phpinfo',
            ));
            
            if($stt > 0)
            {
                // xóa cache file
                // xóa cache cũ trang chủ
                //$sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.Core::getDomainId();
//                $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
//                xoa_cache_thu_muc(array(
//            'link' => Core::getDomainId(),
//            'type' => 'all',
//        ));
//                xoaGiaTriSession(0);
                // end;
                // end
            }
            $status=3;
        }
        else $status=2;
        // trả về các thành phần khác
        if(!empty($errors))
        {
            $oSession->set('session-'.$type, 1);
            $status=2;
            
            $danh_sach_menu = array();
            
            // lấy danh sách đề tài
            $menu = array();
            $aRows = $this->database->select('id, name, parent_id')
                        ->from(Core::getT('menu_value'))
                        ->where('menu_id ='.$mid.' AND status =1')
                        ->execute('getRows');
            
            foreach ($aRows as $rows)
            {
                $menu[] = array($rows["id"], $rows["name"], $rows["parent_id"]);
            }
            // end
            
            $this->Menu_gui_bai(-1, $menu, '', "--");
            $danh_sach_menu = $this->_tmp;
            $this->_tmp =  array();
            $query = '';
            if(!$tonTaiMang['name']) $query .= 'name,';
            if(!$tonTaiMang['path']) $query .= 'path,';
            if(!$tonTaiMang['image_path']) $query .= 'image_path,';
            if(!$tonTaiMang['description']) $query .= 'description,';
            if(!$tonTaiMang['note']) $query .= 'note,';
            if(!$tonTaiMang['parent_id']) $query .= 'parent_id,';
            if(!$tonTaiMang['target_windows']) $query .= 'target_windows,';
            if(!$tonTaiMang['column']) $query .= '`column`,';
            if(!$tonTaiMang['status']) $query .= 'status,';
            if(!$tonTaiMang['lien_ket_key']) $query .= 'link_id,link_type,is_update,';
            if(!$tonTaiMang['permission']) $query .= 'permission,';
            if(!$tonTaiMang['position']) $query .= 'position,';
            if(!empty($query))
            {
                $query = rtrim($query, ',');
                $rows = $this->database->select($query)
                        ->from(Core::getT('menu_value'))
                        ->where('menu_id ='.$mid.' AND id ='.$id)
                        ->execute('getRow');
                if(!$tonTaiMang['name'])
                    $ten = $rows["name"];
                if(!$tonTaiMang['path'])
                    $duong_dan = $rows["path"];
                if(!$tonTaiMang['image_path'])
                    $duong_dan_hinh = $rows["image_path"];
                if(!$tonTaiMang['description'])
                    $mo_ta = $rows["description"];
                if(!$tonTaiMang['note'])
                    $mo_ta = $rows["note"];

                if(!$tonTaiMang['parent_id'])
                    $cha_stt = $rows["parent_id"];
                if(!$tonTaiMang['target_windows'])
                    $targetWindows = $rows["target_windows"];
                if(!$tonTaiMang['column'])
                    $so_cot = $rows["column"];
                if(!$tonTaiMang['status'])
                    $trang_thai = $rows["status"];
                                // end
            }
        }
        else $status = 3;
    }
    else
    {
        
        $oSession->set('session-'.$type, 1);
        $status=2;
        $danh_sach_menu = array();
        
        // lấy danh sách Khu vực (cấp 1 và cấp 2)
        //TẠm thời chỉ hiển thị quốc gia Việt Nam
        $sConds = '';
        $aRow = $this->database->select('id, child_list')
            ->from(Core::getT('area'))
            ->where('code =\'vn\'')
            ->execute('getRow');
        if (isset($aRow['id'])) {
            $aTmps = explode(',', $aRow['child_list']);
            if (!empty($aTmps)) {
                $iPos = array_search(-1, $aTmps);
                if ($iPos !== false) {
                    unset($aTmps[$iPos]);
                }
            }
            if (!empty($aTmps)) {
                //$sConds = ' AND id IN ('.implode(',', $aTmps).')';
            }
        }
        $menu = array();
        $aRows = $this->database->select('id, name, parent_id')
            ->from(Core::getT('area'))
            ->where('status = 1 AND degree < 3'.$sConds)
            ->order('id DESC')
            ->execute('getRows');
        
        foreach ($aRows as $rows)
        {
            $menu[] = array($rows["id"], $rows["name"], $rows["parent_id"]);
        }
        // end
        $this->Menu_gui_bai(-1, $menu, '', "--");
        $danh_sach_menu = $this->_tmp;
        $this->_tmp =  array();
        
        $tonTaiMang['parent_id'] = true;
        if($id == 0)
        {
            $cha_stt = $sub;
            $trang_thai = 1;
            $so_cot = 1;
            $lien_ket_loai = -1;
            $tonTaiMang['column'] = true;
            $tonTaiMang['status'] = true;
        }
        else
        {
            $rows = $this->database->select('id, name, description, note, path, image_path, `column`, status, link_id, link_type, is_update, target_windows, permission, position, parent_id')
                ->from(Core::getT('menu_value'))
                ->where('menu_id ='.$mid.' AND id ='.$id)
                ->execute('getRow');
            
            $stt = $rows["id"];
            if($stt > 0)
            {
                $ten = $rows["name"];
                $mo_ta = $rows["description"];
                $ghi_chu = $rows["note"];
                $duong_dan = $rows["path"];
                $duong_dan_hinh = $rows["image_path"];
                $so_cot = $rows["column"];
                $trang_thai = $rows["status"];
                $lien_ket_stt = $rows["link_id"];
                $lien_ket_loai = $rows['link_type'];
                $tu_cap_nhat = $rows["is_update"];
                $targetWindows = $rows["target_windows"];
                $quyen = $rows["permission"];
                $vi_tri = $rows["position"];
                $cha_stt = $rows["parent_id"];
            }
        }
    }
}
else $status = 4;

        foreach($output as $key)
        {
            $data[$key] = $$key;
        }
        
        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'output' => $output,
            'data' => $data,
        ));
        
    }
}
?>
