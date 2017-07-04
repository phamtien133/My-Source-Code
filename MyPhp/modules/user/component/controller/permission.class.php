<?php
class User_Component_Controller_Permission extends Component
{
    public function process()
    {
        $aRequests = Core::getLib('request')->getRequests();
        $aParam = array();
        $aParam['otype'] = isset($aRequests['otype']) ? $aRequests['otype'] : 0;
        $aParam['id'] = isset($aRequests['id']) ? $aRequests['id'] : 0;
        $aReturn = Core::getService('user.permission')->initCreatePermissionModify($aParam);
        
        $aData = array();
        if ($aReturn['status'] == 'success') {
            $aData = $aReturn['data'];
        }
        //d($aData);die;
        $this->template()->setTitle($page['title']);
        $this->template()->setHeader(array(
            'permission.js' => 'site_script',
        ));
        
        
        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>
