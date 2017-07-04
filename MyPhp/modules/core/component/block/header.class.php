<?php
class Core_Component_Block_Header extends Component
{
    public function process()
    {
        $sVendorName = '';
        if (Core::getParam('core.main_server') == 'sup.') {
            $oSession = Core::getLib('session');
            // lấy thông tin siêu thị 
            $iVendorSession = $oSession->get('session-vendor');
            if ($iVendorSession > 0) {
               $aVendor = Core::getService('vendor')->getForView(array(
                    'id' => $iVendorSession,
                ));
                if (isset($aVendor['name'])) {
                    $sVendorName = $aVendor['name'];
                }
            }
        }
        
        $this->template()->assign(array(
            'sVendorName' => $sVendorName
        ));
    }
}

?>
