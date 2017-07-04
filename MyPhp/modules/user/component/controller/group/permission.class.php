<?php
class User_Component_Controller_Group_Permission extends Component
{
    public function process()
    {
        $aRequests = Core::getLib('request')->getRequests();
        $aPage = array();
        $aPage['title'] = 'Phân quyền nhóm thành viên';
        $aData = array();
        $sError = '';
        
        $aReturn = Core::getService('user.permission')->initCreatePermissionGroup($aRequests);
        
        if ($aReturn['status'] == 'success') {
            $aData = $aReturn['data'];
        }
        else {
            $sError = $aReturn['message'];
        }
        
        $this->template()->setTitle($aPage['title']);
        $this->template()->setHeader(array(
            'permission_group.js' => 'site_script',
            'phanquyen.css' => 'site_css',
        ));
        
        $this->template()->assign(array(
            'aData' => $aData,
            'sError' => $sError,
            'aPage' => $aPage,
        ));
    }
}
?>
