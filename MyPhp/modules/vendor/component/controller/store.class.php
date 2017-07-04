<?php
class Vendor_Component_Controller_Store extends Component
{
    public function process()
    {
        $aPage['title'] = 'Danh sách kho hàng';
        $aData = array();
        $sError = '';
        $sNotice = '';
        $aVals = Core::getLib('request')->getRequests();
        $iVendorId = isset($aVals['vendor_id']) ? $aVals['vendor_id'] : -1;

        $aParam = array(
            'page' => $this->request()->get('page'), 
            'page_size' => $this->request()->get('page_size'),
            'order' => $this->request()->get('sort'),
            'vendor_id' => $iVendorId,
        );
        
        $aData = Core::getService('vendor')->getVendorStore($aParam);
        
        $this->template()->setHeader(array(
            'vendor_store.js' => 'site_script',
        ));
        
        $this->template()->setTitle($aPage['title']);

        $this->template()->assign(array(
            'aData' => $aData,
        )); 
    }
}
?>
