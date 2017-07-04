<?php
class User_Service_Account extends Service
{
    private $_oObject = null;
    
    public function __construct()
    {
        $aSesShopSetting = Core::getLib('session')->get('session-shop_setting');
        $this->_oObject = Core::getService('shop.gateway.wallet');
        $aArr['api_user'] = $aSesShopSetting['api_user'];
        $aArr['api_pass'] = $aSesShopSetting['api_pass'];
        $aArr['security'] = $aSesShopSetting['api_signature'];
        $aArr['merchant_id'] = 1;
        $aArr['url'] = Core::getParam('core.pay_sortpath');
        $this->_oObject->setup($aArr);
    }
    
    public function checkAccount($aParam = array())
    {
        $sUrl = Core::getParam('core.pay_path');
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        if ($iUserId < 1) {
            return array();
        }
        //$sUrl = 'http://pay.gomart.vn/tools/api.php';
        $sData = Core::getService('core.tools')->getDataWithCurl($sUrl, 20, array(
            'call' => 'getuserpoint',
            'return' => 1,
            'user_id' => $iUserId,
            'sid' => session_id()
        ));
        
        $aData = json_decode($sData, 1);
        if ($aData['status'] == 'error') {
            return array();
        }
        return $aData['data'];
    }
    
    public function getHistory($aParam = array())
    {
        $sOrder = 'create_time DESC';
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $iCnt = 0;
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        
        $aData = array();
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('shop_transaction'))
            ->where('user_id ='. $iUserId)
            ->execute('getField');
        
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('shop_transaction'))
                ->where('user_id ='. $iUserId)
                ->order('create_time DESC')
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
                $aRow['display_time'] = date('d/m/Y', $aRow['create_time']);
                $aRow['value'] = unserialize($aRow['value']);
                $aData[] = $aRow;
            }
        }
        
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aData,
        );
    }
    
    public function recharge($aParam = array())
    {
        $iUserId = isset($aParam['id']) ? $aParam['id'] : -1;
        $iMoney = isset($aParam['money']) ? $aParam['money'] : 0;
        $iCoin = isset($aParam['coin']) ? $aParam['coin'] : 0;
        $sNote = isset($aParam['note']) ? $aParam['note'] : '';
        $iMoney = $iMoney*1;
        $iCoin = $iCoin*1;
        if ($iMoney < 0) {
            $iMoney = 0;
        }
        if ($iCoin < 0) {
            $iCoin = 0;
        }
        
        $aErrors = array();
        if ($iUserId < 1) {
            $aErrors[] = 'Không có thành viên được chọn.';
        }
        else {
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('user'))
                ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$iUserId)
                ->execute('getField');
            if ($iCnt < 1) {
                $aErrors[] = 'Thành viên không tồn tại.';
            }
        }
        
        if (empty($aErrors)) {
            if ($iMoney <= 0 && $iCoin <= 0) {
                $aErrors[] = 'Số tiền hoặc số xu muốn nạp vào tài khoản phải lớn hơn 0';
            }
        }
        if (empty($aErrors)) {
            //add vào 1 bảng tạm chờ duyệt
            $aInsert = array(
                'user_id' => $iUserId,
                'user_id_recharge' => Core::getUserId(),
                'total_money' => $iMoney,
                'total_coin' => $iCoin,
                'status' => 0,
                'note' => $sNote,
                'create_time' => CORE_TIME,
                'domain_id' => Core::getDomainId(),
            );
            
            $iId = $this->database()->insert(Core::getT('recharge_temp'), $aInsert);
            if (!$iId) {
                $aErrors[] = 'Lỗi hệ thống';
            }
        }
        $aOutput = array();
        if (empty($aErrors)) {
            $aOutput['status'] = 'success';
        }
        else {
            $aOutput['status'] = 'error';
            $aOutput['message'] = $aErrors;
        }
        return $aOutput;
    }
    
    public function approvalRecharge($aParam = array())
    {
        $iStatus = isset($aParam['status']) ? $aParam['status'] : -1;
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        if ($iId < 1 || $iStatus < 1 || $iStatus > 2) {
            return array(
                'status' => 'error',
                'data' => 'Dữ liệu đầu vào không hợp lệ.',
            );
        }
        $sErrors = '';
        if ($iStatus == 2) {
            //hủy nạp tiền (ko cần xử lý vì sẽ cập nhật status bên dưới)
            
        }
        else if ($iStatus == 1){
            //Duyệt
            // gọi qua api account để cập nhật số tiền nạp.
            $aRow = $this->database()->select('*')
                ->from(Core::getT('recharge_temp'))
                ->where('id = '. $iId)
                ->execute('getRow');
            $sUrl = Core::getParam('core.pay_path');
            $aMoney = array(
                'tien_mat' => (int) $aRow['total_money'],
                'tien_xu' => (int) $aRow['total_coin']
            );
            $sData = Core::getService('core.tools')->getDataWithCurl($sUrl, 20, array(
                'call' => 'updatepoint',
                'return' => 1,
                'user_id' => $aRow['user_id'],
                'total_amount' => base64_encode(serialize($aMoney)),
                'shipping_fee' => base64_encode(serialize(array(
                    'tien_mat' => 0,
                    'tien_xu' => 0
                ))),
                'tax_fee' => base64_encode(serialize(array(
                    'tien_mat' => 0,
                    'tien_xu' => 0
                ))),
                'order_id' => -1,
                'convert_money' => 0,
                'p_type' => 'add',
                'sid' => session_id()
            ));
            if (empty($sData)) {
                $sErrors = 'Có lổi xảy ra khi cập nhật tài khoản thành viên.';
            }
            
            $aData = json_decode($sData, 1);
            if ($aData['status'] == 'error') {
                $sErrors = $aData['message'];
            }
            $this->database()->insert(Core::getT('shop_transaction'), array(
                'shop_order_id' => -1,
                'user_id' => $aRow['user_id'],
                'type' => 'add',
                'payment_method' => 'diem',
                'value' => serialize($aMoney),
                'create_time' => CORE_TIME,
                'note' => 'Nạp tiền từ cms',
                'status' => 1
            ));
        }
        
        $aOutput = array();
        if (empty($sErrors)) {
            //update status
            $this->database()->update(Core::getT('recharge_temp'), array('status' => $iStatus), 'id ='.$iId);
            $aOutput['status'] = 'success';
        }
        else {
            $aOutput['status'] = 'error';
            $aOutput['data'] = $sErrors;
        }
        return $aOutput;
     }
}
?>
