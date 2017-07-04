<?php
class Core_Component_Block_Search extends Component
{
    public function process()
    {
        // if exist supplier. 
        $iVendorSession = Core::getLib('session')->get('session-vendor');
        $sMainServer = Core::getParam('core.main_server');
        if (!$iVendorSession)
            return false;
        if ($iVendorSession > 0 && $sMainServer != 'sup.')
            return false;
        if ($iVendorSession == -1 && $sMainServer != 'cms.')
            return false;
            
        // get list search category
        $aSearchCategories = Core::getService('category')->getForSearch(array(
            'parent_id' => -1
        ));
 
        $this->template()->assign(array(
            'aSearchCategories' => $aSearchCategories
        ));
        
    }
}
?>
