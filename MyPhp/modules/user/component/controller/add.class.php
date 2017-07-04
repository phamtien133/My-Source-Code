<?php
class User_Component_Controller_Add extends Component
{
    public function process()
    {
        //echo 'Trang đang cập nhật, vui lòng quay lại sau!';exit;
        $aVals = Core::getLib('request')->getRequests();
        $iStatus = 0;
        $aError = array();
        $sType = isset($aVals['type']) ? '?type='.$aVals['type'] : '';
        if (isset($aVals['is_submit']) && $aVals['is_submit']) {
            $aVals['iAcp'] = 1;
            $iStatus = Core::getService('user.process')->addUser($aVals);
            if ($iStatus == 1) {
                $aError = Core_Error::get();
            }
            else if ($iStatus == 2) {
                //re-direct page
                $sDir = $_SERVER['REQUEST_URI'];
                $aTmps = explode('/', $sDir, 3);
                $sDir = '/'.$aTmps[1].'/'.$sType;
                header('Location: '.$sDir);
            }
            //Trang thái trả về khi đăng ký user:1: Lỗi, 2: thành công
        }
        
        $page['title'] = Core::getPhrase('language_tao-thanh-vien');
        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'iStatus' => $iStatus,
            'aError' => $aError,
            'sType' => $sType,
            'aVals' => $aVals,
        ));
    }
}
?>
