<?php
class User_Component_Controller_Forgot extends Component
{
    public function process()
    {
        $sAct = $this->request()->get('act');
        
        // redirect to login server
        $sMainServer = Core::getParam('core.main_server');
        if (!empty($sMainServer) && $_SERVER["SERVER_NAME"] != 's.*' && !Core::isAdminPanel()) {
            //header('Location: http://'.$sMainServer.Core::getDomainName().'/quen_mat_khau.html');
            //exit;
        }
        
        $sType = 'forgot';
        
        $this->template()->setTitle(Core::getPhrase('language_quen-mat-khau'));
        $this->template()->assign(array(
            'sAct' => $sAct
        ));
    }
}
?>
