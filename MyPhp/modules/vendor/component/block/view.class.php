<?php
class Vendor_Component_Block_View extends Component
{
    public function process()
    {
        $bIsAjax = false;
        if (defined('CORE_IS_AJAX') && CORE_IS_AJAX) {
            $bIsAjax = true; 
        }
        $iVendorId = $this->getParam('iVendorId');
        if (!$iVendorId) {
             $iVendorId = $this->request()->get('vendor');
        }
        $aVendor = array();
        if ($bIsAjax && !$iVendorId) {
            return false;
        }

        if($iVendorId > 0) {
            $aVendor = Core::getService('vendor')->getForView(array(
                'id' => $iVendorId
            ));
        }
        
        $this->template()->assign(array(
            'aArticleVendor' => $aVendor,
            'bIsAjax' => $bIsAjax
        ));
        
        return 'block';
    }
}
?>
