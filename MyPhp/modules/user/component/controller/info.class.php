<?php
class User_Component_Controller_Info extends Component
{
    public function process()
    {
        //check user login
        $this->template()->setTitle('Trang cá nhân');
        
        $this->template()->setHeader(array(
            'profile.js' => 'site_script',
            'info.css' => 'site_css'
        ));
    }
}
?>
