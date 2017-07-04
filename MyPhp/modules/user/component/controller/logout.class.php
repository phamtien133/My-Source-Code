<?php
class User_Component_Controller_Logout extends Component
{
    public function process()
    {
        if($_SERVER['REMOTE_ADDR'] == '116.106.152.95') {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            //d(12312321);die;
        }
        Core::getService('user.auth')->logout();
            
        $this->url()->send('user.login');
        
        $this->template()->setTitle('Đăng xuất');
        //$this->template()->setHeader(array(
//            'login.js' => 'site_script'
//        ));
    }
}
?>
