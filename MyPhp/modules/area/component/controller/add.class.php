<?php
class Area_Component_Controller_Add extends Component
{
    private $aTmps = array();
    
    private function Menu_gui_bai($parentid,$menu,$res = '',$sep = ''){
        global $iId;
        foreach($menu as $v){
            if($v[2] == $parentid)
            {
                if($v[0] == $iId) continue;
                $this->aTmps[] = array(
                    $v[0],
                    $sep.$v[1],
                    'parent_id' => $parentid
                );
                $res .= $this->Menu_gui_bai($v[0],$menu,$res,$sep."&nbsp;&nbsp;&nbsp;");
            }
        }
    }
    
    public function process()
    {
        $type = 'create_edit_area';
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        $id = 0;
        if (isset($aVals['id'])) {
            $id = $aVals['id'];
        }
        
        $aPermission = $oSession->get('session-permission');
        $sPageType = $oSession->get('session-page_type');
        
        //$page['title'] = Core::getPhrase('language_khu-vuc-tao-sua');
        $page['title'] = 'Khu vực';
        
        if($aPermission['manage_extend'] != 1 && $sPageType == 'marketplace')
        {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }

        if(empty($errors))
        {
            if(!empty($_POST))
            {
                if(empty($errors))
                {
                    $data['name'] = $_POST['name'];
                    $v = $data['name'];
                    
                    $v = Core::getLib('input')->removeDuplicate(array('text' => $v));
                    $v = str_replace('#', '', $v);
                    $v = str_replace('.', '', $v);
                    
                    $v = Core::getLib('input')->removeXSS(stripslashes(trim($v)));
                    
                    $v = Core::getLib('input')->removeBreakLine(array('text' => $v));
                    $data['name'] = $v;
                    
                    $data['name_code'] = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($_POST['name_code'])));
                    
                    $data['status'] = $_POST['status']*1;
                    if($data['status'] != 1) $data['status'] = 0;
                    
                    $data['parent_id'] = $_POST['parent_id']*1;
                    if ($data['parent_id'] < 1) {
                        $data['parent_id'] = -1;
                    }
                }
                // global
                if($id < 1) $data['action'] = 'create';
                else $data['action'] = 'update';
                $data['id'] = $id;
                $data['type'] = $type;
                
                if(empty($errors))
                {
                    if(mb_strlen($data['name']) < 1 || mb_strlen($data['name']) > 225) $errors[] = sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225).'(1)';
                    if(mb_strlen($data['name_code']) < 1 || mb_strlen($data['name_code']) > 225) $errors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225).'(2)';
                }
                
                $bIsChangeCode = false;
                $bIsChangeParent = false;
                if(empty($errors))
                {
                    // kiểm tra id
                    if($id > 0)
                    {
                        // lấy đề tài stt và tên miền stt
                        $rows = $this->database->select('id, code, degree, path, detail_path, parent_id, child_list, parent_list')
                            ->from(Core::getT('area'))
                            ->where('id ='.$id.' AND status != 2')
                            ->execute('getRow');
                        
                        if(!isset($rows['id'])) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu')).'(5)';
                        else {
                            $iParentIdOld = $rows['parent_id'];
                            
                            if ($iParentIdOld != $data['parent_id']) {
                                $bIsChangeParent = true;
                            }
                            
                            if ($rows['code'] != $data['name_code']) {
                                $bIsChangeCode = true;
                            }
                            
                           
                            // dùng cho cập nhật khi chỉnh sửa đề tài và đường dẫn
                            $de_tai_con_list = $rows['child_list'];
                            $de_tai_cha_list = $rows['parent_list'];
                            
                            $de_tai_con_list = str_replace(',-1', '', $de_tai_con_list);
                            $de_tai_cha_list = str_replace($de_tai_con_list.',', '', $de_tai_cha_list);
                            $de_tai_cha_list = str_replace(',-1', '', $de_tai_cha_list);
                            
                            // end
                            
                            $duong_dan_cu = $rows['path'];
                            $duong_dan_chi_tiet_cu = $rows['detail_path'];
                            
                        }
                        
                    }
                    // end
                }
                
                if(empty($errors))
                {
                    if ($data['parent_id'] > 0) {
                        $aRow = $this->database->select('id, degree, path, detail_path, parent_id, child_list, parent_list')
                            ->from(Core::getT('area'))
                            ->where('id ='.$data['parent_id'].' AND status != 2')
                            ->execute('getRow');
                        
                        if(!isset($aRow['id'])) {
                            $errors[] = 'Khu vục cha không tồn tại';
                        }
                        else {
                           $data['degree'] = $aRow['degree'] + 1;
                        }
                    }
                    else {
                        $data['degree'] = 1;
                    }
                    
                    if (empty($errors)) {
                        $sql = '';
                        if($id > 0) $sql = ' AND id != '.$id;
                        
                        $sql .= ' AND degree ='.$data['degree'].' AND parent_id ='.$data['parent_id'];
                        
                        
                        // kiểm tra mã tên đã tồn tại chưa
                        $rows = $this->database->select('count(id)')
                            ->from(Core::getT('area'))
                            ->where('code =\''.addslashes($data['name_code']).'\' AND status != 2'.$sql)
                            ->execute('getField');
                        
                        if(!empty($rows)) $errors[] = Core::getPhrase('language_ten-da-ton-tai').'(6)';
                    }
                }
                
                if(empty($errors))
                {
                    $aInsert = array(
                        'degree' =>  $data['degree'],
                        'name' =>  $data['name'],
                        'code' => $data['name_code'],
                        'path' => $data['name_code'],
                        //'detail_path' => '/'.$data['name_code'].'/',
                        'status' => $data['status'],
                    );
                    
                    // thiết lập đường dẫn chi tiêt
                    if($id == 0)
                    {
                        if($data['parent_id'] > 0)
                        {
                            $rows = $this->database->select('parent_list, detail_path')
                                ->from(Core::getT('area'))
                                ->where('id ='.$data['parent_id'])
                                ->execute('getRow');
                        }
                        else
                        {
                            $rows['detail_path'] = '/';
                        }
                        
                        $data['detail_path'] = $rows['detail_path'].$data['name_code'];
                        if(strpos($data['name_code'], '.') === false) $data['detail_path'] .= '/';
                        
                        $aInsert['detail_path'] = $data['detail_path'];
                    }
                    
                    if($id > 0)
                    {
                        $this->database->update(
                            Core::getT('area'),
                            $aInsert,
                            'id ='.$id
                        );
                        $stt = $id;
                        
                    }
                    else
                    {
                        $iParentId = $data['parent_id'];
                        if($data['parent_id'] < 1) $iParentId = -1;
                        
                        $aInsert['parent_id'] = $iParentId;
                        //d($aInsert); die();
                        $stt =$this->database->insert(Core::getT('area'), $aInsert);
                        
                        if ($stt > 0) {
                            //cập nhật parent, child
                            if($iParentId < 1) $cha_list = $stt.',-1';
                            else
                            {
                                $rows = $this->database->select('parent_list, child_list')
                                    ->from(Core::getT('area'))
                                    ->where('id ='.$iParentId)
                                    ->execute('getRow');
                                
                                $cha_list=$rows["parent_list"];
                                $con_list=$rows["child_list"];
                                $con_list=substr($con_list,0,-3);
                                if (!$cha_list) {
                                    $cha_list ='-1';
                                }
                                else
                                {
                                    $aRows = $this->database->select('id, child_list')
                                        ->from(Core::getT('area'))
                                        ->where('id IN ('.$cha_list.')')
                                        ->execute('getRows');
                                    
                                    foreach ($aRows as $rows)
                                    {
                                        $con_list_id = str_replace($con_list.',', $con_list.','.$stt.',', $rows["child_list"]);
                                        $this->database->update(
                                            Core::getT('area'),
                                            array('child_list' => $con_list_id),
                                            'id ='.$rows['id']
                                        );
                                    }
                                }
                                $cha_list=$stt.','.$cha_list;
                            }
                            
                            $con_list = $stt.',-1';
                            
                            $this->database->update(
                                Core::getT('area'),
                                array (
                                    'parent_list' => $cha_list,
                                    'child_list' => $con_list,
                                ),
                                'id ='.$stt
                            );
                            
                        }
                        $data['id'] = $stt;
                        $id = $stt;
                    }
                    
                    //Trường hợp chỉnh sửa khu vực cha
                    $bIsChangeParent = true;
                    if($bIsChangeParent && $id > 0)
                    {
                        $bFlag = $this->database->update(
                            Core::getT('area'),
                            array('parent_id' => $data['parent_id']),
                            'id ='.$id
                        );
                        
                        $bFlag  =1;
                        if ($bFlag > 0 || 1==1)
                        {
                            // bước 1: xóa danh mục cha
                            if(!empty($de_tai_cha_list))
                            {
                                // tách riêng danh sách đề tài con dể thay thế chính xác
                                $aTmps = array();
                                $aTmps = explode(',', $de_tai_con_list);
                                foreach($aTmps as $key => $val)
                                {
                                    $val *= 1;
                                    if($val < 1)
                                    {
                                        unset($aTmps[$key]);
                                    }
                                    else $aTmps[$key] = $val;
                                }
                                
                                //
                                $aRows = $this->database->select('child_list, id')
                                    ->from(Core::getT('area'))
                                    ->where('id IN ('.$de_tai_cha_list.') AND id !='.$id)
                                    ->execute('getRows');
                                
                                foreach ($aRows as $rows)
                                {
                                    $rows["child_list"] = ','.$rows["child_list"].','; // thêm dấu , để tiến hành thay thế không bị trùng
                                    foreach($aTmps as $val)
                                    {
                                        $rows["child_list"] = str_replace(','.$val.',', ',', $rows["child_list"]);
                                    }
                                    
                                    // xóa 2 dấu ,
                                    $rows["child_list"] = substr($rows["child_list"], 1, -1);
                                    
                                    $this->database->update(
                                        Core::getT('area'),
                                        array('child_list' => $rows["child_list"]),
                                        'id ='.$rows['id']
                                    );
                                }
                            }
                            
                            // bước 2: chuyển đổi
                            if($data['parent_id'] > 0)
                            {
                                $rows = $this->database->select('parent_list, detail_path')
                                    ->from(Core::getT('area'))
                                    ->where('id ='.$data['parent_id'])
                                    ->execute('getRow');
                                
                                $cha_list = $rows["parent_list"];
                                $cha_list = $id.','.$cha_list;
                            }
                            else
                            {
                                $cha_list = $id.',-1';
                                $rows['detail_path'] = '/';
                            }
                            
                            $data['detail_path'] = $rows['detail_path'].$data['name_code'];
                            if(strpos($data['name_code'], '.') === false) $data['detail_path'] .= '/';
                            
                            // tách riêng danh sách đề tài con dể thay thế chính xác
                            $aTmps = array();
                            $aTmps = explode(',', $id.','.$de_tai_cha_list);
                            foreach($aTmps as $key => $val)
                            {
                                $val *= 1;
                                if($val < 1)
                                {
                                    unset($aTmps[$key]);
                                }
                                else $aTmps[$key] = $val;
                            }
                            // cập nhật đề tài cha cho stt và con list
                            $aRows = array();
                            if (!empty($de_tai_con_list)) {
                                $aRows = $this->database->select('parent_list, id')
                                    ->from(Core::getT('area'))
                                    ->where('id IN ('.$de_tai_con_list.')')
                                    ->execute('getRows');
                            }
                            foreach ($aRows as $rows)
                            {
                                $cha_list_id  = ','.$rows["parent_list"].','; // thêm dấu , để tiến hành thay thế không bị trùng
                                foreach($aTmps as $val)
                                {
                                    $cha_list_id = str_replace(','.$val.',', ',', $cha_list_id);
                                }
                                // xóa 2 dấu ,
                                $cha_list_id = substr($cha_list_id, 1, -1);
                                // cập nhật đề tài gốc
                                $cha_list_id = str_replace('-1', $cha_list, $cha_list_id);
                                
                                $this->database->update(
                                    Core::getT('area'),
                                    array('parent_list' => $cha_list_id),
                                    'id ='.$rows['id']
                                );
                            }
                            
                            // bước 3: cập nhật danh mục cha
                            $thay_the = ','.$data['parent_id'].','.$de_tai_con_list.',';
                            
                            $aRows = $this->database->select('child_list, id')
                                ->from(Core::getT('area'))
                                ->where('id IN ('.$cha_list.')')
                                ->execute('getRows');
                            
                            foreach ($aRows as $rows)
                            {
                                if($rows['id'] == $data['parent_id']) $rows["child_list"] = ','.$rows["child_list"];
                                $con_list_id = str_replace(','.$data['parent_id'].',', $thay_the, $rows["child_list"]);
                                if($rows['id'] == $data['parent_id']) $con_list_id = substr($con_list_id, 1);
                                
                                $this->database->update(
                                    Core::getT('area'),
                                    array('child_list' => $con_list_id),
                                    'id ='.$rows['id']
                                );
                            }
                            $bIsChangeCode = true;
                        }
                    }
                    
                    //Cập nhật đường dẫn chi tiết
                    if($bIsChangeCode && $id > 0)
                    {
                        // lấy đường dẫn chi tiết của đề tài trên
                        if(empty($data['detail_path']))
                        {
                            if($data['parent_id'] > 0)
                            {
                                $rows = $this->database->select('parent_list, detail_path')
                                    ->from(Core::getT('area'))
                                    ->where('id = '.$data['parent_id'])
                                    ->execute('getRow');
                            }
                            else
                            {
                                $rows['detail_path'] = '/';
                            }
                            $data['detail_path'] = $rows['detail_path'].$data['name_code'];
                            if(strpos($data['name_code'], '.') === false) $data['detail_path'] .= '/';
                        }
                        // end
                       
                        /*
                            thuật toán:
                             Lấy đường dẫn chi tiết cũ, cập nhật đoạn mở đầu
                        */
                        // cập nhật tất cả danh sách đường dẫn
                        $aRows = array();
                        if ($de_tai_con_list != '' && !empty($de_tai_con_list)) {
                            $aRows = $this->database->select('id, detail_path')
                                ->from(Core::getT('area'))
                                ->where('id IN ('.$de_tai_con_list.')')
                                ->execute('getRows');
                        }
                        
                        foreach ($aRows as $rows)
                        {
                            $duong_dan_chi_tiet_tam = Core::getService('core.tools')->str_mreplace($duong_dan_chi_tiet_cu, $data['detail_path'], $rows['detail_path'], 'left');
                            
                            $this->database->update(
                                Core::getT('area'),
                                array('detail_path' => $duong_dan_chi_tiet_tam),
                                'id ='.$rows['id']
                            );
                        }
                        
                    }
                    // ghi log hệ thống
                    
                    Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$id,'content' => 'phpinfo',));
                    // end
                    $status=3;
                    
                    //re-direct page
                    $sDir = $_SERVER['REQUEST_URI'];
                    $aTmps = explode('/', $sDir, 3);
                    $sDir = '/'.$aTmps[1].'/';
                    header('Location: '.$sDir);
                }
                
                if(!empty($errors)) $status=1;
            }
            else
            {
                $status = 1;
                if($id > 0)
                {
                    // lấy đề tài stt và tên miền stt
                    $rows = $this->database->select('id, name, code, status, parent_id')
                        ->from(Core::getT('area'))
                        ->where('id = '.$id.' AND status != 2')
                        ->execute('getRow');
                    
                    if (isset($rows['id'])) {
                        $data['name'] = $rows['name'];
                        $data['parent_id'] = $rows['parent_id'];
                        $data['name_code'] = $rows['code'];
                        $data['status'] = $rows['status'];
                    }
                    else {
                        $errors[] = 'Khu vực không tồn tại';
                        $status=2;
                    }
                }
                else
                {
                    $data['status'] = 1;
                }
            }
        }

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
        
        foreach($aRows as $aRow)
        {
            $menu[] = array($aRow["id"], $aRow["name"], $aRow["parent_id"]);
        }
        // end
        $this->Menu_gui_bai(-1, $menu, '', "");
        $aAreas = $this->aTmps;
        unset($this->aTmps);
        // end
        
        $return = array();
        $output = array(
            'data',
            'errors',
            'id',
            'status',
            'aAreas',
        );
        foreach($output as $key)
        {
            $return[$key] = $$key;
        }
        
        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'output' => $output,
            'return' => $return,
        ));
    }
}
?>
