<?php
class User_Component_Block_Add extends Component
{
    public function process()
    {
        $aErrors = array();
        $iUserId = $this->getParam('uid');
        $aPage['title'] = '';
        $aUser = array();
        if ($iUserId > 0) {
            $aPage['title'] = 'Cập nhật thông tin';
            $aUser = Core::getService('user')->getFullUserInfo(array('uid' => $iUserId));
            
            if (!isset($aUser['id'])) {
                $aErrors[] = 'Thành viên không tồn tại';
            }
        }
        else {
            $aPage['title'] = 'Thêm thành viên';
        }
        
        $this->template()->assign(array(
            'iStatus' => $iStatus,
            'aErrors' => $aErrors,
            'aPage' => $aPage,
            'aVals' => $aVals,
            'aUser' => $aUser,
            'aHistory' => $aHistory,
            'aTransactions' => $aTransactions,
        ));
    }
    
}
?>
