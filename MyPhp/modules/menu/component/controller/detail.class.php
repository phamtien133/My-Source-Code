<?php
class Menu_Component_Controller_Detail extends Component
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

        $type = 'create_edit_menu_value';
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
            'page_type',
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
            'trang_thai',
        );
        $aPageType = array(
            'category' => "Danh mục",
            'feed' => 'New Feed',
            'wall' => "Tường cá nhân",
            'group' => 'Danh sách thành viên nhóm',
            'account' => 'Thông tin tài khoản'
        );
        $page['title']=Core::getPhrase('language_tao-menu');

        if($aPermission['create_menu']!=1)
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
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap').'(3)';
        }


        if($aPermission['create_menu']!=1)
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap').'(2)';
        if(empty($errors))
        {
            $targetWindows_list = array(
                'Parent',
                'New Window With Navigation',
                'New Without Navigation',
                'Parent + No-Follow',
                'New Window With Navigation + No-Follow',
                'New Without Navigation + No-Follow'
            );
            if(!empty($_POST))
            {
                // neu la tao de tai, them cac truong bat buoc phai co
                if($id == 0)
                {
                    $tonTaiMang['name'] = true;
                    $tonTaiMang['path'] = true;
                    $tonTaiMang['parent_id'] = true;
                    $tonTaiMang['column'] = true;
                    $tonTaiMang['status'] = true;
                }
                $tmps = explode(',', $_POST["mang"]);
                $tonTaiMang['page_type'] = true;
                foreach($tmps as $tmp)
                {
                    if($tmp == 'description')
                        $tonTaiMang['description'] = true;
                    if($tmp == 'note')
                        $tonTaiMang['note'] = true;
                    if($tmp == 'name')
                        $tonTaiMang['name'] = true;
                    if($tmp == 'parent_id')
                        $tonTaiMang['parent_id'] = true;
                    if($tmp == 'path')
                        $tonTaiMang['path'] = true;
                    if($tmp == 'image_path')
                        $tonTaiMang['image_path'] = true;
                    if($tmp == 'lien_ket_key')
                        $tonTaiMang['lien_ket_key'] = true;
                    if($tmp == 'target_windows')
                        $tonTaiMang['target_windows'] = true;
                    if($tmp == 'permission')
                        $tonTaiMang['permission'] = true;
                    if($tmp == 'column')
                        $tonTaiMang['column'] = true;
                    if($tmp == 'status')
                        $tonTaiMang['status'] = true;
                }
                $so_muc = 0;
                if($tonTaiMang['name'])
                    $ten = htmlspecialchars(stripslashes(trim($_POST["name"])));
                if($tonTaiMang['description'])
                    $mo_ta = htmlspecialchars(stripslashes($_POST["description"]));
                if($tonTaiMang['note'])
                    $ghi_chu = trim($_POST["note"]);;
                if($tonTaiMang['parent_id'])
                {
                    $cha_stt = $_POST["parent_id"]*1;
                    if($cha_stt == $id)
                    {
                        unset($tonTaiMang['parent_id']);
                    }
                    if($cha_stt < 1) $cha_stt = -1;
                }
                if($tonTaiMang['page_type'])
                    $page_type = Core::getLib('input')->removeXSS(trim($_POST["page_type"]));

                if($tonTaiMang['path'])
                    $duong_dan = Core::getLib('input')->removeXSS(trim($_POST["path"]));
                if($tonTaiMang['image_path'])
                    $duong_dan_hinh=Core::getLib('input')->removeXSS(trim($_POST["image_path"]));

                if($tonTaiMang['target_windows'])
                {
                    $targetWindows = (int)$_POST["target_windows"];
                    if($targetWindows < 0 || $targetWindows > count($targetWindows_list)) $targetWindows = 0;
                }
                else $targetWindows = 0;

                if($tonTaiMang['permission'])
                {
                    $quyen = $_POST["permission"]*1;
                    if($quyen < 0 || $quyen > 2) $quyen = 0;
                }
                else $quyen = 0;

                if($tonTaiMang['column'])
                {
                    $so_cot = $_POST["column"] * 1;
                    if($so_cot < 1 || $so_cot > 99) $so_cot = 1;
                }
                else $so_cot = 1;

                if($tonTaiMang['status'])
                {
                    if($_POST["status"] != 1) $trang_thai = 0;
                    else $trang_thai = 1;
                }
                else $trang_thai = 1;


                $iSessionCode = $oSession->get('session-ma_so_bai_viet');

                if($iSessionCode == $_POST["ma_so_bai_viet"]) $errors[] = Core::getPhrase('language_de-tai-da-duoc-dang-truoc');
                if(empty($errors))
                {
                    if($tonTaiMang['name'] && (mb_strlen($ten)<2 || mb_strlen($ten)>50)) $errors[] = sprintf(Core::getPhrase('language_x-phai-tu-x-den-x-ky-tu'), Core::getPhrase('language_ten'), 2, 50);
                    if($tonTaiMang['description'] && (mb_strlen($mo_ta)<2 || mb_strlen($mo_ta)>50)) $errors[] = sprintf(Core::getPhrase('language_mo-ta-phai-tu-x-den-x-ky-tu'), 2, 50);
                    if($tonTaiMang['note'] && mb_strlen($ghi_chu) > 1000) $errors[] = sprintf(Core::getPhrase('language_x-phai-it-hon-x-ky-tu'), Core::getPhrase('language_ghi-chu'), 1000);
                    //if($tonTaiMang['path'] && (mb_strlen($duong_dan) < 2 || mb_strlen($duong_dan)>225)) $errors[] = sprintf(Core::getPhrase('language_x-phai-tu-x-den-x-ky-tu'), Core::getPhrase('language_duong-dan'), 2, 225);
                    if($tonTaiMang['image_path'] && (mb_strlen($duong_dan_hinh)>225)) $errors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_duong-dan-hinh'), 1, 225);

                    if($tonTaiMang['parent_id'] && $cha_stt==0) $errors[] = Core::getPhrase('language_de-tai-chua-duoc-nhap');
                }
                if(empty($errors) && $tonTaiMang['lien_ket_key'])
                {
                    // update lien_ket_stt, và lien_ket_loai
                    $lien_ket_stt = $_POST['link_id']*1;

                    $lien_ket_loai = $_POST['link_type']*1;
                    if($lien_ket_loai != 1) $lien_ket_loai = 0;

                    $tu_cap_nhat = 0;

                    if($lien_ket_stt > 0)
                    {
                        if($lien_ket_loai == 1) {
                            $rows = $this->database->select('detail_path')
                                ->from(Core::getT('article'))
                                ->where('domain_id ='.Core::getDomainId().' AND id = '.$lien_ket_stt)
                                ->execute('getRow');
                        }
                        else {
                            $rows = $this->database->select('detail_path')
                                ->from(Core::getT('category'))
                                ->where('domain_id ='.Core::getDomainId().' AND id = '.$lien_ket_stt)
                                ->execute('getRow');
                        }

                        if(!isset($rows['detail_path']))
                        {
                            $lien_ket_stt = 0;
                            $lien_ket_loai = 0;
                        }
                        else
                        {
                            $tonTaiMang['path'] = true;
                            $duong_dan = $rows['detail_path'];
                        }
                    }
                }
                else
                {
                    $lien_ket_stt = 0;
                    $lien_ket_loai = 0;
                    $tu_cap_nhat = 0;
                }

                // kiểm tra xem stt menu tồn tại
                if(empty($errors) && $tonTaiMang['parent_id'] && $id > 0)
                {
                    $rows = $this->database->select('id, parent_id')
                        ->from(Core::getT('menu_value'))
                        ->where('menu_id ='.$mid.' AND id ='.$id)
                        ->execute('getRow');

                    if($rows['id'] < 1) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_menu'));
                    if($rows['parent_id'] == $cha_stt)
                    {
                        unset($tonTaiMang['parent_id']);
                    }
                }
                // kiểm tra xem cha stt tồn tại
                if(empty($errors) && $tonTaiMang['parent_id'] && $cha_stt > 0)
                {
                    $tong = $rows = $this->database->select('count(id)')
                        ->from(Core::getT('menu_value'))
                        ->where('menu_id ='.$mid.' AND id ='.$cha_stt)
                        ->execute('getField');

                    if(empty($tong)) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_menu-cha'));
                }

                if(empty($errors))
                {
                    $oSession->set('session-'.$type, 0);

                    $sql_noi_dung = '';

                    $aInsertContent = array();

                    if($tonTaiMang['name']) $aInsertContent['name'] = $ten;
                    if($tonTaiMang['path']) $aInsertContent['path'] = $duong_dan;
                    if($tonTaiMang['page_type']) $aInsertContent['page_type'] = $page_type;
                    if($tonTaiMang['image_path']) $aInsertContent['image_path'] = $duong_dan_hinh;
                    if($tonTaiMang['parent_id']) $aInsertContent['parent_id'] = $cha_stt;
                    if($tonTaiMang['description']) $aInsertContent['description'] = $mo_ta;
                    if($tonTaiMang['note']) $aInsertContent['note'] = $ghi_chu;
                    if($tonTaiMang['noi_dung']) $aInsertContent['noi_dung'] = $noi_dung;
                    if($tonTaiMang['target_windows']) $aInsertContent['target_windows'] = $targetWindows;
                    if($tonTaiMang['column']) $aInsertContent['`column`'] = $so_cot;
                    if($tonTaiMang['status']) $aInsertContent['status'] = $trang_thai;

                    if($tonTaiMang['lien_ket_key']) {
                        $aInsertContent['link_id'] = $lien_ket_stt;
                        $aInsertContent['link_type'] = $lien_ket_loai;
                        $aInsertContent['is_update'] = $tu_cap_nhat;
                    }

                    if($id > 0)
                    {
                        if(!empty($aInsertContent))
                        {
                            //$sql_noi_dung = mb_substr($sql_noi_dung, 0, -2);
                            $iFlag = $this->database->update(Core::getT('menu_value'), $aInsertContent, 'menu_id ='.$mid.' AND id = '.$id);

                            if($iFlag) Core::getService('core.tools')->updateMenu();
                        }
                        $stt = $id;
                    }
                    else
                    {
                        $aInsertContent['menu_id'] = $mid;
                        $aInsertContent['domain_id'] = Core::getDomainId();

                        $stt = $this->database->insert(Core::getT('menu_value'), $aInsertContent);
                        Core::getService('core.tools')->updateMenu();
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
                    //redirect page
                    $sDir = $_SERVER['REQUEST_URI'];
                    $aTmps = explode('/', $sDir, 3);
                    $sDir = '/'.$aTmps[1].'/list/id_'.$mid;
                    header('Location: '.$sDir);

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
                    if(!$tonTaiMang['page_type']) $query .= 'page_type,';
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
                    $rows = $this->database->select('id, name, description, note, page_type, path, image_path, `column`, status, link_id, link_type, is_update, target_windows, permission, position, parent_id')
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
                        $page_type = $rows["page_type"];
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
            'aPageType' => $aPageType
        ));

    }
}
?>
