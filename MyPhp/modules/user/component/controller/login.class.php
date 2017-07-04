<?php
class User_Component_Controller_Login extends Component
{
    public function process()
    {
        $sAct = $this->request()->get('act');
        // redirect to login server
        $sMainServer = Core::getParam('core.main_server');
        if (!empty($sMainServer) && $_SERVER["SERVER_NAME"] != 's.*' && !Core::isAdminPanel()) {
            //header('Location: http://'.$sMainServer.Core::getDomainName().'/dang_nhap.html');
            //exit;
        }
        //d(Core::getDomainId());die;
        $sRefer = $this->request()->get('refer');
        $sReferEncode = '';
        if (!empty($sRefer)) {
            $sReferEncode = $sRefer;
            $sRefer = base64_decode($sRefer);
        }
        else {
            $sRefer = $_SERVER['HTTP_REFERER'];
            $sReferEncode = base64_encode($sRefer);
        }

        $sLoginLink = '/tools/loginopenid.php';
        if($sMainServer == 'sup.') {
            $this->template()->setTitle(Core::getPhrase('language_dang-nhap'));
        }
        else {
            $this->template()->setTitle(Core::getPhrase('language_dang-nhap'));
        }
            $sLoginLink = '//cms.'. Core::getParam('core.path').':8080'.$sLoginLink;


        $this->template()->setHeader(array(
            'login.js' => 'site_script',
        ));
        $this->template()->assign(array(
            'sLoginLink' => $sLoginLink,
            'sRefer' => $sRefer,
            'sReferEncode' => $sReferEncode,
            'sAct' => $sAct,
            'aNotices' => Core_Error::get()
        ));
    }
}
?>
