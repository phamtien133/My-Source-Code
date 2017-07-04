<?php
class User_Component_Controller_Register extends Component
{
    public function process()
    {
        $sAct = $this->request()->get('act');
        $sType = $this->request()->get('type');
        $sTypeAct = 'register';
        // redirect to login server
        $sMainServer = Core::getParam('core.main_server');
        if (!empty($sMainServer) && $_SERVER["SERVER_NAME"] != 's.*' && !Core::isAdminPanel()) {
            //header('Location: http://'.$sMainServer.Core::getDomainName().'/dang_nhap.html');
            //exit;
        }
        
        $oSession = Core::getLib('session');
        
        $iStatus = 1;
        $sRefer = $this->request()->get('refer');
        if (!empty($sRefer)) 
            $sRefer = base64_decode($sRefer);
        else 
            $sRefer = $_SERVER['HTTP_REFERER'];
        
        $aAll = $this->request()->getRequests();
        $aVals = $this->request()->getArray('val');
        $bIsSubmit = false;
        if(!empty($aVals)) {
            //Validate data
            $bIsSubmit = true;
        }
        
        $iLoginFacebook = 0;
        $oSesFacebook = $oSession->get('session-facebook_appid');
        $oSesTwitter = $oSession->get('session-twitter_key');
        if (!empty($oSesFacebook))
            $iLoginFacebook = 1;
        
        // check xem có cho phép đăng nhập với twitter không
        $iLoginTwitter = 0;
        if (!empty( $oSesTwitter))
            $iLoginTwitter = 1;
        $sLoginLink = '/tools/loginopenid.php';
        if (Core::isAdminPanel()) {
            $sLoginLink = '//cms'.$_SERVER['HTTP_HOST'].':8080'.$sLoginLink;
        }
        
        $this->template()->setTitle(Core::getPhrase('language_dang-ky'));
        $this->template()->setHeader(array(
            'login.js' => 'site_script',
            'new_login.css' => 'global_css'
        ));
        $this->template()->assign(array(
            'sRefer' => $sRefer,
            'sReferEncode' => $sReferEncode,
            'sAct' => $sAct,
            'iAcp' => $iAcp,
            'sTypeAct' => $sTypeAct
        ));
    }
}
?>
