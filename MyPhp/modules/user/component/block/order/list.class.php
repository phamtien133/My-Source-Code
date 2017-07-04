<?php
class User_Component_Block_Order_List extends Component
{
    public function process()
    {
        $bIsAjax = false;
        if (defined('CORE_IS_AJAX') && CORE_IS_AJAX) {
            $bIsAjax = true; 
        }
        
        $aRequest = Core::getLib('request')->getRequests();
        
        //giả lập input
        $aRequest['uid'] = 40;
        if ($bIsAjax) {
            $aOrders = Core::getService('user')->getPurchaseHistory($aRequest);
        }
        else {
            $aOrders = array();
        }
        
        $this->template()->assign(array(
            'aOrders' => $aOrders,
        ));
        
        return 'block';
    }
}
?>
