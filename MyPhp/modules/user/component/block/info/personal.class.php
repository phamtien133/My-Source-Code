<?php
class User_Component_Block_Info_Personal extends Component
{
    public function process()
    {
        $bIsAjax = false;
        if (defined('CORE_IS_AJAX') && CORE_IS_AJAX) {
            $bIsAjax = true; 
        }
        $aRequest = Core::getLib('request')->getRequests();
        //giả lập input
        //$aRequest['uid'] = 40;
        if ($bIsAjax) {
            $aProfile = Core::getService('user')->getFullUserInfo($aRequest);
        }
        else {
            $aProfile = array();
        }
        
        $this->template()->assign(array(
            'aProfile' => $aProfile,
            'bIsAjax' => $bIsAjax,
        ));
        
        return 'block';
    }
}
?>