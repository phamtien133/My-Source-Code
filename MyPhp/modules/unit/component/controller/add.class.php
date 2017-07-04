<?php
class Unit_Component_Controller_Add extends Component
{
    public function process()
    {
        $type = 'create_edit_unit';
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        $id = 0;
        if (isset($aVals['id'])) {
            $id = $aVals['id'];
        }
        
        $aPermission = $oSession->get('session-permission');
        $sPageType = $oSession->get('session-page_type');
        
        $page['title'] = Core::getPhrase('language_nha_cung_cap-tao-sua');
        $page['title'] = 'Đơn vị tính';
        
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
                    
                    $data['description'] = isset($_POST['description']) ? $_POST['description'] : '';
                    
                    $data['status'] = $_POST['status']*1;
                    if($data['status'] != 1) $data['status'] = 0;
                    
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
                    if(!empty($data['image_path']) && (mb_strlen($data['image_path'])>225)) $errors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_duong-dan-hinh'), 1, 225);
                }
                
                if(empty($errors))
                {
                    // kiểm tra id
                    if($id > 0)
                    {
                        // lấy đề tài stt và tên miền stt
                        $rows = $this->database->select('count(id)')
                            ->from(Core::getT('unit'))
                            ->where('id ='.$id.' AND domain_id ='.Core::getDomainId().' AND status != 2')
                            ->execute('getField');
                        
                        if($rows == 0) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu')).'(5)';
                        
                    }
                    // end
                }
                if(empty($errors))
                {
                    $sql = '';
                    if($id > 0) $sql = ' AND id != '.$id;
                    // kiểm tra mã tên đã tồn tại chưa
                    $rows = $this->database->select('count(id)')
                        ->from(Core::getT('unit'))
                        ->where('name_code =\''.addslashes($data['name_code']).'\' AND domain_id ='.Core::getDomainId().' AND status != 2'.$sql)
                        ->execute('getField');
                    
                    if(!empty($rows)) $errors[] = Core::getPhrase('language_ten-da-ton-tai').'(6)';
                }
                
                if(empty($errors))
                {
                    $aInsert = array(
                        'name' =>  $data['name'],
                        'name_code' => $data['name_code'],
                        'description' => $data['description'],
                        'status' => $data['status'],
                    );
                    
                    if($id > 0)
                    {
                        $this->database->update(
                            Core::getT('unit'),
                            $aInsert,
                            'domain_id ='.Core::getDomainId().' AND id ='.$id
                        );
                        $stt = $id;
                    }
                    else
                    {
                        //$aInsert['ngay_thang_tao'] = CORE_TIME;
                        $aInsert['domain_id'] = Core::getDomainId();
                        
                        $stt =$this->database->insert(Core::getT('unit'), $aInsert);
                        
                        $data['id'] = $stt;
                        $id = $stt;
                    }
                    // ghi log hệ thống
                    Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$id,'content' => 'phpinfo',));
                    // end
                    $status=3;
                    //redirect page
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
                    $rows = $this->database->select('id, name, name_code, status, description')
                        ->from(Core::getT('unit'))
                        ->where('id = '.$id.' AND domain_id ='.Core::getDomainId().' AND status != 2')
                        ->execute('getRow');
                    
                    if (isset($rows['id'])) {
                        $data['name'] = $rows['name'];
                        $data['name_code'] = $rows['name_code'];
                        $data['status'] = $rows['status'];
                        $data['description'] = $rows['description'];
                        
                    }
                    else {
                        $errors[] = 'Đơn vị tính không tồn tại';
                        $status=2;
                    }
                }
                else
                {
                    $data['status'] = 1;
                }
            }
        }
        
        $return = array();
        $output = array(
            'data',
            'errors',
            'id',
            'status',
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
