<?php
class User_Component_Controller_View extends Component
{
    public function process()
    {
        //echo 'Trang đang cập nhật, vui lòng quay lại sau!';exit;
        $aVals = Core::getLib('request')->getRequests();
        $iStatus = 0;
        $aErrors = array();
        $sType = isset($aVals['type']) ? '?type='.$aVals['type'] : '';
        
        $iId = isset($aVals['id']) ? $aVals['id'] : -1;
        $sType =  isset($aVals['type']) ? $aVals['type'] : '';
        if ($sType != 'user') {
            $sType = 'member';
        }
        
        if ($iId < 0) {
            //return;
        }
        
        $aParam = array(
            'uid' => $iId,
        );
        
        $aUser = array();
        $aHistory = array();
        $aTransactions = array();
        $aUserPoint = array();
        $aUser = Core::getService('user')->getFullUserInfo($aParam);
        if (!empty($aUser) && isset($aUser['id'])) {
            $aParam['page'] = 1;
            $aParam['page_size'] = 5;
            $aHistory = Core::getService('user')->getPurchaseHistory($aParam);
            $aTransactions = Core::getService('user.account')->getHistory($aParam);
            $aUserPoint = Core::getService('user.account')->checkAccount($aParam);
        }
        else {
            $aErrors[] = 'Thành viên không tồn tại';
        }
        
        $Page['title'] = 'Thông tin chi tiết';
        $this->template()->setTitle($Page['title']);
        
        $this->template()->setHeader(array(
            'view_user.js' => 'site_script',
        ));
        
        $this->template()->assign(array(
            'iStatus' => $iStatus,
            'aErrors' => $aErrors,
            'sType' => $sType,
            'aVals' => $aVals,
            'aUser' => $aUser,
            'aHistory' => $aHistory,
            'aTransactions' => $aTransactions,
            'aUserPoint' => $aUserPoint,
            'sType' => $sType,
        ));
    }
}
?>