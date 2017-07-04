<?php
class Menu_Service_Menu extends Service
{
    public function __construct()
    {

    }

    public function savePositionMenu($aParam = array())
    {
        $sType = 'position_menu';

        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $aArray  = array();
        foreach ($aParam as $Key => $Val) {
            $Val *= 1;
            $Key *= 1;
            if ($Val > 0 && $Key > 0 && !isset($aArray[$Key])) {
                $aArray[$Key] = $Val;
            }
        }

        $aSessionUser= $oSession->get('session-user');
        if ($aSessionUser['id'] < 1 || $aSessionUser['priority_group'] == -1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap').'(1)');
            $bIsReturn = true;
        }
        elseif (empty($aArray)) {
            Core_Error::set('error', 'Deny(1)');
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'edit_menu') != 1) {
            Core_Error::set('error', 'Deny(2)');
            $bIsReturn = true;
        }
        // kiểm tra có quyền tương tác với Menu gt ko
        if (!$bIsReturn) {
            // lấy menu stt bất kỳ
            foreach ($aArray as $Key => $Val) {
                $iTmp = $Key;
                break;
            }
            $aRow = $this->database()->select('menu_id')
                ->from(Core::getT('menu_value'))
                ->where('status != 2 AND id = '.$iTmp)
                ->execute('getRow');
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('menu'))
                ->where('domain_id='.Core::getDomainId().' AND id = '.($aRow['menu_id']*1).' AND status != 2')
                ->execute('getField');
            if ($iCnt < 1) {
                Core_Error::set('error', 'Deny(3)');
                $bIsReturn = true;
            }
        }
        if (!$bIsReturn) {
            //Đánh dấu cập nhật thành công
            $bFlag = false;
            foreach ($aArray as $Key => $Val) {
                //Cập nhật ưu tiên cho menu_value
                if ($this->database()->update(Core::getT('menu_value'), array('priority' => $Val), 'id ='.$Key)) {
                    $bFlag = true;
                }
            }
            if ($bFlag) {
                Core::getService('core.tools')->updateMenu();
                /* bỏ, liên quan đến table cache
                // xóa cache cũ tên miền
                $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$oSession->getArray('session-domain', 'id');
                $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                xoa_cache_thu_muc(array(
                    'link' => $oSession->getArray('session-domain', 'id'),
                    'type' => 'all',
                ));
                xoaGiaTriSession();
                */
                // end;
                // ghi log hệ thống
                Core::getService('core.tools')->saveLogSystem(array(
                    'action' => $sType.'-'.$id,
                    'content' => 'phpinfo',
                ));
            }
        }
        if ($bIsReturn) {
            echo '<-errorvietspider->'.Core_Error::get('error');
        }
        else {
            echo Core::getPhrase('language_da-cap-nhat-thanh-cong');
        }
    }
}
?>
