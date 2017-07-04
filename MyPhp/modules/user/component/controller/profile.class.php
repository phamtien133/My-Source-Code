<?php
class User_Component_Controller_Profile extends Component
{
    public function process()
    {
        
        
        $this->template()->setHeader(array(
            'profile.css' => 'site_css',
            'profile.js' => 'site_script'
        ));
    }
}
?>
