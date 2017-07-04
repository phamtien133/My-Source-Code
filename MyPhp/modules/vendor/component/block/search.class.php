<?php
class Vendor_Component_Block_Search extends Component
{
    public function process()
    {
        $sVendor = '';
        // get current supplier. 
        $sMap = Core::getLib('module')->getFullControllerName();
        if ($sMap == 'core.index' || $sMap == 'category.index' || $sMap == 'vendor.index') {
            // this block only display in these controllers. 
            $sVendor = $this->request()->get('req1');
        }
        
        $aVendors = Core::getService('vendor')->get(array(
            'get_all' => true,
        ));
        $iSelected = 0;
        $sDefault = 'DiSieuThi';
        foreach ($aVendors as $aVendor) {
            if($aVendor['path'] == $sVendor) {
                $sDefault = $aVendor['name'];
                $iSelected = $aVendor['id'];
                break;
            }
        }
        $this->template()->assign(array(
            'aVendors' => $aVendors,
            'sDefault' => $sDefault,
            'iSelected' => $iSelected
        ));
        unset($aVendors);
        unset($sDefault);
        return 'block';
    }
}
?>
