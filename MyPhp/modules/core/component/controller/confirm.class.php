<?php
class Core_Component_Controller_Confirm extends Component
{
    public function process()
    {
        $sMessage  = '';
        $oSession = Core::getLib('session');
        $iVendorSession = $oSession->get('session-vendor');
        $iVendorSession = 1;
        if ($iVendorSession == -1 && Core::getParam('core.main_server') == 'sup.') {
            $sMessage = 'Bạn đang chuyển trang từ hệ thống siêu thị đến hệ thống quản trị. Thao tác này sẽ cập nhật lại quyền hiện tại và bạn sẽ không thể tiêp tục thao tác trên hệ thống siêu thị. Bấm "Xác nhận" để tiếp tục chuyển trang.';
        }
        if ($iVendorSession > 0 && Core::getParam('core.main_server') == 'cms.') {
            $sMessage = 'Bạn đang chuyển trang từ hệ thống quản trị đến hệ thống siêu thị. Thao tác này sẽ cập nhật lại quyền hiện tại và bạn sẽ không thể tiêp tục thao tác trên hệ thống quản trị. Bấm "Xác nhận" để tiếp tục chuyển trang.';
        }
        $this->template()->setHeader(array(
            'select.js' => 'site_script'
        ));
        $sMessage = 'Vui lòng chọn siêu thị để tiếp tục.';
        $this->template()->assign(array(
            'sMessage' => $sMessage
        ));
    }
}
?>
