<?php
class Vendor_Component_Controller_Index extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        
        //Điều hướng page
        if (isset($aVals['req2'])) {
            if ($aVals['req2'] == 'edit' || $aVals['req2'] == 'add') {
                return Core::getLib('module')->setController('vendor.add');
            }
        }

        $page['title'] = 'Quản lý nhà cung cấp';

        $aParam = array(
            'page' => $this->request()->get('page'), 
            'page_size' => $this->request()->get('page_size'),
            'order' => $this->request()->get('sort'),
        );

        $aData = array();

        $aData = Core::getService('vendor')->getVendor($aParam);        
        
        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));
        
        $this->template()->setTitle($page['title']);

        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>
