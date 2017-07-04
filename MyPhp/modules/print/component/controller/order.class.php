<?php
class Print_Component_Controller_Order extends Component
{
    public function process()
    {
        $this->template()->setTemplate('blank');
        
        $iOrderId = $this->request()->getInt('req3');
        $iVendorId =  $this->request()->getInt('req4, 0');
        if (!$iOrderId) {
            return false;
        }
        
        $aContent = Core::getService('print')->printOrder(array(
            'order_id' => $iOrderId,
            'vendor_id' => $iVendorId
        ));
        //d($aContent);
        $this->template()->setHeader(array(
            'print.css' => 'site_css'
        ));
        $this->template()->assign(array(
            'aContent' => $aContent
        ));
    }
}
?>
