<?php
class User_Component_Controller_Changepwd extends Component
{
    public function process()
    {
        
        // redirect to login server
        $sMainServer = Core::getParam('core.main_server');
        if (!empty($sMainServer) && $_SERVER["SERVER_NAME"] != 's.*' && !Core::isAdminPanel()) {
            //header('Location: http://'.$sMainServer.Core::getDomainName().'/dang_nhap.html');
            //exit;
        }
        $oSession = Core::getLib('session');
        //Check login
        if (Core::getUserId() < 1) {
            //header('Location: http://'.$sMainServer.Core::getDomainName().'/dang_nhap.html');
            //exit;
        }
        //check permission change password
        if ($oSession->getArray('session-permission', 'block_change_password') == 1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
        }
        else {
            //Check openid
            $bIsOpenId = Core::getService('user')->isOpenId();
            if (!$bIsOpenId) {
                Core_Error::set('error', Core::getPhrase('language_chuc-nang-doi-mat-khau-khong-danh-cho-openid'));
            }
        }
        $aResult = array();
        if (Core_Error::isPassed()) {
            //check submit
            $bIsSubmit = false;
            $aVal = $aVals = $this->request()->getArray('val');
            if (!empty($aVal)) {
                $bIsSubmit = true;
            }
            if ($bIsSubmit) {
                $aResult = Core::getService('user.proccess')->changePassword($aVal);
            }
            else {
                $aResult['status'] = 1;
            }
        }
        else {
            $aResult['status'] = 2;
        }
        $aError = array();
        if(!Core_Error::isPassed()) {
            $aError = Core_Error::get();
        }
        
        $this->template()->setTitle(Core::getPhrase('language_doi-mat-khau'));
        $this->template()->assign(array(
            'aError' => $aError,
            'aResult' => $aResult
        ));
    }
}
?>
