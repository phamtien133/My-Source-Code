<?php
class User_Component_Block_Info_Product extends Component
{
    public function process()
    {
        $bIsAjax = false;
        if (defined('CORE_IS_AJAX') && CORE_IS_AJAX) {
            $bIsAjax = true; 
        }
        //get infomation product
        $aRequest = Core::getLib('request')->getRequests();
        //Giả lập input xem profile của 1 user khác
        //$aRequest['uid'] = 40;
        if ($bIsAjax) {
            $aPurchaseProducts = Core::getService('user')->getPurchaseProductHistory($aRequest);
        
            $aViewProducts = Core::getService('user')->getViewProduct($aRequest);;
            
            $aLikeProducts =  Core::getService('user')->getLikeProductHistory($aRequest);
        }
        else {
            $aPurchaseProducts = array();
            $aViewProducts = array();
            $aLikeProducts = array();
        }
        
        
        $this->template()->assign(array(
            'aPurchaseProducts' => $aPurchaseProducts,
            'aViewProducts' => $aViewProducts,
            'aLikeProducts' => $aLikeProducts,
        ));
        
        return 'block';
    }
}
?>