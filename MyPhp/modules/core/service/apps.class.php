<?php
class Core_Service_Apps extends Service
{
    private $_aMapFunction = array();
    private $_aNotRequireLogin = array();
    public function __construct()
    {
        $this->_aMapFunction = array(
            'reg-device-token' => 'app.user:registerDeviceToken',
            'login' => 'app.user:login',
            'register' => 'app.user:register',
            'userinfo-set' => 'app.user:register',
            'logout' => 'app.user:logout',
            'forgot' => 'app.user:forgotPassword',
            'regis-place' => 'app.user:registerZone',
            'userinfo-get' => 'app.user:getUserInfo',
            'userconfig-get' => 'app.user:getZoneByUser',
            'upload-image-verify' => 'app.user:uploadImageVerify',
            'del-image-verify' => 'app.user:deleteImageVerify',
            'get-image-verify' => 'app.user:getImageVerify',
            'wallet-config-set' => 'app.user:settingBankingInfo',
            'wallet-config-get' => 'app.user:getBankingInfo',
            'send-feedback' => 'app.user:sendResponseOrder',
            'feedback-list' => 'app.user:getResponseOrders',
            'feedback-detail' => 'app.user:getResponseByOrder',
            'image-verify' => 'app.user:sendRequestVerify',
            'history-list' => 'app.user:getActivityHistory',
            'driveinfo-set' => 'app.user:setVehicles',
            'driveinfo-get' => 'app.user:getVehicles',
            'shipper-assess' => 'app.user:rating',
            'list-exchange' => 'app.user:getHistoryWork',
            'list-money-pay' => 'app.user:getPaymented',
            'money-request' => 'app.user:requestPayment',
            'list-money-receive' => 'app.user:getReceiveMoney',
            'money-send-verify' => 'app.user:returnMoney',
            'detail-money-receive' => 'app.user:getReceiveMoneyDt',
            'set-location-shipper-move' => 'app.user:saveLogLocation',
            'get-location-shipper-move' => 'app.user:getLogLocation',
            //order
            'order-list' => 'app.order:getOrdersByType',
            'order-detail' => 'app.order:getOrderDetail',
            'product-need-buy' => 'app.order:getProductNeedBuy',
            'product-need-buy-search' => 'app.order:searchProduct',
            'product-need-buy-add' => 'app.order:addProductToOrder',
            'product-need-buy-edit' => 'app.order:editProduct',
            'product-need-buy-remove' => 'app.order:removeProduct',
            'order-dimiss' => 'app.order:dimissOrder',
            'order-accept' => 'app.order:requestAcceptOrder',
            'order-had-buy' => 'app.order:updateOrderBuy',
            'order-had-send' => 'app.order:updateOrderDelivery',
            'product-need-buy-update' => 'app.order:updateDeliveryProduct',
            'order-complete' => 'app.order:updateOrderComplete',
            'order-receive' => 'app.order:getVerifyCode',
            'date-time-shipping' => 'app.order:getScheduleShipper',
            'get-vendors' => 'app.order:getVendors',
            'get-from-barcode' => 'app.order:getProductBySku',
            'order-remove' => 'app.order:updateOrderCancel',
            'get-code-return' => 'app.order:getVerifyCodeReturn',
            'complete-return-order' => 'app.order:completeReturnOrder',
            'get-route' => 'app.route:get',
            'route-detail' => 'app.route:getById',
            'accept-route' => 'app.route.process:accept',
            'complete-route' => 'app.order:updateCompleteRoute',
            'dimiss-route' => 'app.order:dismissRoute',
            'direction-route' => 'app.route:getDirectionRoute',
            'get-notification' => 'app.notification:get',
            'check-notification' => 'app.notification:checkNotification',
            'get-product-return' => 'app.order:getReturnProduct',
            'comfirm-order-route' => 'app.route.process:confirmAddOrderToRoute',
        );
        
        $this->_aNotRequireLogin = array(
            'login', 'register', 'forgot'
        );
    }
    
    public function getMapFunction()
    {
        return $this->_aMapFunction;
    }
    
    public function call($aParmas)
    {
        $sLogPath = Core::getService('core.log')->getApiFile();
        if(!isset($aParmas['call']) || empty($aParmas['call'])) {
            Core_Error::log('Không có thao tác thực thi được gọi.', $sLogPath);
            return array(
                'status' => 'error',
                'message' => 'Không có thao tác thực thi được gọi.'
            );
        }
        if (!in_array($aParmas['call'], $this->_aNotRequireLogin)) {
            if (!Core::getUserId()) {
                return array(
                    'status' => 'error',
                    'code' => 403,
                    'message' => 'Vui lòng đăng nhập trước khi thực hiện.'
                ); 
            }
        }
        if(!isset($this->_aMapFunction[$aParmas['call']])) {
            Core_Error::log('Hàm gọi không đúng.', $sLogPath);
            return array(
                'status' => 'error',
                'message' => 'Hàm gọi không đúng.'
            );
        }
        
        $sCall = $this->_aMapFunction[$aParmas['call']];
        $aCall = explode(':', $sCall);
        try {
            $aReturn = Core::getService($aCall[0])->$aCall[1]($aParmas);
            //core_log($aReturn, 'a+');
            if (isset($aReturn['status'])) {
                if (isset($aReturn['redirect']) && $aReturn['redirect'] != '') {
                    Core::getLib('url')->send($aReturn['redirect'], null, $aReturn['message']);
                    //header('Location: '. $aReturn['redirect']);
                    return false;
                }
                return $aReturn;
            }
            else {
                return array(
                    'status' => 'success',
                    'data' => $aReturn
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Lỗi thực thi API: '. $aCall[0] .'-> '.$aCall[1] .', .', $sLogPath);
            return array(
                'status' => 'error',
                'message' => 'Lỗi thực thi API: '. $aCall[0] .'-> '.$aCall[1] .', .', $sLogPath
            );
        }
    }
}
?>
