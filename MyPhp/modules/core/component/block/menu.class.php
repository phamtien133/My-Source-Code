<?php
class Core_Component_Block_Menu extends Component
{
    public function process()
    {
        $iVendorSession = Core::getLib('session')->get('session-vendor');
        $sMainServer = Core::getParam('core.main_server');
        if (!$iVendorSession)
            return false;
        if ($iVendorSession > 0 && $sMainServer != 'sup.')
            return false;
        if ($iVendorSession == -1 && $sMainServer == 'cms.')
            return false;
        
        // get menu of domain
        $aMenus = Core::getService('core')->getMenus();
        $this->template()->assign(array(
            'aMenus' => $aMenus
        ));
        
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');
        $aSessionUser = $oSession->get('session-user');
        
        $this->template()->assign(array(
            'aPermission' => $aPermission,
            'aSessionUser' => $aSessionUser
        ));
        
        unset($aMenus);
        return 'block';
    }
}  
?>
