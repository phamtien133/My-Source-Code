<?php
class Core_Component_Controller_Select extends Component
{
    public function process()
    {
        $sMessage = '';
        $oSession = Core::getLib('session');
        $iVendorSession = $oSession->get('session-vendor');
        if ($iVendorSession == -1) {
            // chuyển từ trang admin sang.
            $sMessage = 'Bạn đang chuyển trang từ hệ thống quản trị đến hệ thống siêu thị. Thao tác này sẽ cập nhật lại quyền hiện tại và bạn sẽ không thể tiêp tục thao tác trên hệ thống quản trị. ';
            $sMessage .= 'Chọn siêu thị để tiếp tục.';
        }

        $aVendorLists = array();
        $aVendorLists = Core::getService('user.permission')->getVendorsCurrentUser();
        
        if (count($aVendorLists) == 1) {
            // xử lý chọn vendor này và chuyển trang luôn.
            $aParam = array(
                'vendor_id' => $aVendorLists[0]['id'],
                'api' => true
            );
            $aReturn = Core::getService('user.permission')->updatePermission($aParam);
            
            if ($aReturn['status'] == 'success') {
                $oSession->set('session-scount', -1);
                $this->url()->send(Core::getParam('core.main_path'), null, '');
            }
        }
        $this->template()->setHeader(array(
            'select.js' => 'site_script'
        ));
        $sMessage = 'Vui lòng chọn siêu thị để tiếp tục.';
        $this->template()->assign(array(
           'sMessage' => $sMessage,
           'aVendorLists' => $aVendorLists
        ));
    }
}
?>
