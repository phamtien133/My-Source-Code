<?php
class Vendor_Component_Block_Display extends Component
{
    public function process()
    {
        $sCurrent = '';
        // get current supplier. 
        $sMap = Core::getLib('module')->getFullControllerName();
        if ($sMap == 'core.index' || $sMap == 'category.index' || $sMap == 'supplier.index') {
            // this block only display in these controllers. 
            $sCurrent = $this->request()->get('req1');
        }

        // get list supplier to view
        $aSuppliers = Core::getService('vendor')->get(array(
            'limit' => 17,
            'location' => $sCurrent
        ));
        if(!count($aSuppliers))
            return false;
        $bIsViewMore = false;
        if (count($aSuppliers) == 17) {
            $bIsViewMore =true;
        }
        $this->template()->assign(array(
            'aSuppliers' => $aSuppliers,
            'bIsViewMore' => $bIsViewMore,
            'sCurrent' => $sCurrent
        ));
        return 'block';
    }
}
?>
