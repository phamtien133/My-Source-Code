<?php
class Vendor_Component_Ajax_Ajax extends Ajax
{
    public function display()
    {
        $aParam = Core::getLib('request')->getArray('val');
        if (!isset($aParam['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Invalid data'
            );
        }
        Core::getBlock('vendor.view', array('iVendorId' => $aParam['id']));

        //$this->call('alert(1111);');
        $this->call('showVendorBlock("'. $this->getContent().'");');
        return true;
    }
    
    public function getAll()
    {
        $aLists = Core::getService('vendor')->get(array(
            'is_sell' => 1,
            'get_all' => 1
        ));
        if (count($aLists)) {
            $aReturn = array(
                'status' => 'success',
                'data' => $aLists
            );
        }
        else {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Không có siêu thị được tìm thấy.'
            );
        }
        echo json_encode($aReturn);exit;
    }
    
    public function updateStatusVendorStore()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('vendor')->updateStatusVendorStore($aVals);
        echo json_encode($aReturn);exit;
    }
}
?>
