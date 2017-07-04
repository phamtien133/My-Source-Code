<?php
class Menu_Component_Controller_Add extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        //d($aVals); die();
        $aTmps = explode('_', $aVals['req3'], 2);
        $type = 'create_edit_menu';
        $id = 0; //category id
        $sCode = ''; //code of catrgory
        if ($aTmps[0] == 'id')
            $id = $aTmps[1]*1;
        else
            $sCode = $aTmps[1];

        $page['title'] = Core::getPhrase('language_tao-menu');

        $iPerManageMenu = $oSession->getArray('session-permission', 'create_menu');
        $iPerEditMenu = $oSession->getArray('session-permission', 'edit_menu');

        $output = array(
            'id',
            'ten',
            'ma_ten',
            'thoi_gian',
            'errors',
            'trang_thai',
            'status'
        );

if($iPerManageMenu!=1)
{
    // check xem có quyền chi tiết ko
    if(!empty($iPerEditMenu))
    {
        $tmp = explode(',', $iPerEditMenu);
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

if(!empty($_POST))
{

    if(empty($errors))
    {
        $ten = $_POST['name'];
        $ma_ten = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($_POST['name_code'])));

        $trang_thai = $_POST['status']*1;
        if($trang_thai != 1) $trang_thai = 0;
    }

    if(empty($errors))
    {
        if(mb_strlen($ten) < 1 || mb_strlen($ten) > 225) $errors[] = sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225);
        if(mb_strlen($ma_ten) < 1 || mb_strlen($ma_ten) > 225) $errors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225);
    }
    // kiểm tra id của menu
    if(empty($errors) && $id > 0)
    {
        //$s = 'domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$id;
        $iCnt = $this->database->select('count(*)')
            ->from(Core::getT('menu'))
            ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$id)
            ->execute('getField');
        if ($iCnt < 1) {
            $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_menu'));
        }
    }
    // kiểm tra mã tên của menu
    if(empty($errors) && $id > 0)
    {
        $sql = '';
        if($id > 0) $sql .= 'AND id != '.$id;

        $iCnt = $rows = $this->database->select('count(*)')
            ->from(Core::getT('menu'))
            ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND name_code = "'.addslashes($ma_ten).'"'.$sql)
            ->execute('getField');
        if ($iCnt > 0)
            $errors[] = Core::getPhrase('language_ma-ten-da-ton-tai').'--';

    }
    if(empty($errors))
    {
        $aInsert = array(
            'name' => $ten,
            'name_code' => $ma_ten,
            'status' => $trang_thai,
            'time' => CORE_TIME,
        );

        // cập nhật dữ liệu
        if($id > 0)
        {
            $this->database->update(Core::getT('menu'), $aInsert, 'id ='.$id);
            $stt = $id;
        }
        else
        {
            $aInsert['domain_id'] = Core::getDomainId();
            $stt  = $this->database->insert(Core::getT('menu'), $aInsert);
        }
        // xóa cache
        Core::getService('core.tools')->updateMenu();
        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array(
            'action' => $type.'-'.$id,
            'content' => 'phpinfo',
        ));
        //if($stt > 0)
//        {
            // xóa cache file
            // xóa cache cũ trang chủ
//            $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.Core::getDomainId();
//            $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
//            xoa_cache_thu_muc(array(
//            'link' => Core::getDomainId(),
//            'type' => 'all',
//        ));
//            xoaGiaTriSession(0);
            // end;
            // end
//        }
        $status=3;
        //redirect page
        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/';
        header('Location: '.$sDir);
    }
}
elseif(empty($errors))
{
    $trang_thai = 1;
    if($id > 0)
    {
        $tmps = array(
            'id' => 'stt',
            'name' => 'ten',
            'name_code' => 'ma_ten',
            'time' => 'thoi_gian',
            'status' => 'trang_thai'
        );
        // lấy đề tài stt và tên miền stt
        $rows = $this->database->select(implode(',', array_keys($tmps)))
            ->from(Core::getT('menu'))
            ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$id)
            ->execute('getRow');

        foreach($tmps as $key =>  $v)
        {
            $$v = $rows[$key];
        }
        // convert thoi_gian_bat_dau
        $thoi_gian = date('d-m-Y H:i:s', $rows["time"]);
        //
    }
    $status=1;
}
else
    $status = 2;

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