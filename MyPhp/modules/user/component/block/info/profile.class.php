<?php
class User_Component_Block_Info_Profile extends Component
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
            
            $aNotice = Core::getService('user')->getNotice($aRequest);
            
            $aActivityFriends = Core::getService('user')->getActivityFriends($aRequest);
        }
        else {
            $aProfile = array();
            $aNotice = array();
            $aActivityFriends = array();
        }
        
        $this->template()->assign(array(
            'aProfile' => $aProfile,
            'aNotice' => $aNotice,
            'aActivityFriends' => $aActivityFriends,
        ));
        
        return 'block';
    }
}
?>