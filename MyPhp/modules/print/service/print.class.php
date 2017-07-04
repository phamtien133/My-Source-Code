<?php
class Print_Service_Print extends Service
{
    public function __construct()
    {
        
    }
    
    public function printOrder($aParam = array())
    {
        if (!isset($aParam['order_id']) || $aParam['order_id'] == 0) {
            return array();
        }
        // lấy thông tin order
        $aOrder = Core::getService('shop.order')->getOrderDetail(array(
            'id' => $aParam['order_id']
        ));
        
        if (!isset($aOrder['general']['id'])) {
            return array();
        }
        
        
        return $aOrder;
    }
}
?>
