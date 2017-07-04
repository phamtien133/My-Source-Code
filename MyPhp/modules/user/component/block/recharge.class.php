<?php
class User_Component_Block_Recharge extends Component
{
    public function process()
    {
        $aErrors = array();
        $iUserId = $this->getParam('uid');
        $aPage['title'] = '';
        $aUser = array();
        $aUserPoint = array();
        $aPage['title'] = 'Nạp tiền vào tài khoản';
        if ($iUserId > 0) {
            $aUser = Core::getService('user')->getFullUserInfo(array('uid' => $iUserId));
            $aUserPoint = Core::getService('user.account')->checkAccount(array('uid' => $iUserId));
            if (!isset($aUser['id'])) {
                $aErrors[] = 'Thành viên không tồn tại';
            }
        }
        else {
            $aErrors[] = 'Thành viên không tồn tại';
        }
        
        $this->template()->assign(array(
            'aErrors' => $aErrors,
            'aPage' => $aPage,
            'aUser' => $aUser,
            'aUserPoint' => $aUserPoint,
        ));
    }
    
}
?>
