<?php
class Vendor_Component_Controller_Addstore extends Component
{
    public function process()
    {
        $aPage['title'] = '';
        $aData = array();
        $sError = '';
        $sNotice = ''; 
        $aVals = Core::getLib('request')->getRequests();
        
        if (isset($aVals['id']) && $aVals['id'] > 0) {
            $aPage['title'] = 'Cập nhật kho hàng';
        }
        else {
            $aPage['title'] = 'Tạo kho hàng';
        }

        if (isset($aVals['val']) && !empty($aVals['val'])) {
        
            //call service create and update
            $aVals['id'] = $this->request()->get('id');
            $aData = Core::getService('vendor')->CreateStore($aVals['val']);

            if ($aData['status'] == 'success') {
                //chuyển trang
                $sDir = '/vendor/store/?vendor_id='.$aVals['val']['vendor_id'];
                header('Location: '.$sDir);
            } else {
                $sError = isset($aReturn['message']) ? $aReturn['message'] : 'Lỗi hệ thống';
            }
        } else {
            $aData = Core::getService('vendor')->initCreateStore($aVals);
        }

        $sBackLink = '';
        if (isset($aData['vendor_id']) && $aData['vendor_id'] > 0) {
            $sBackLink = '/vendor/store/?vendor_id='.$aData['vendor_id'];
        } else {
            $sBackLink = '/vendor/';
        }

        // $sApiKey = Core::getService('core.location')->getGMapApiKey();

        $this->template()->setHeader(array(
            'vendorstore.css' => 'site_css',
            'add_store.js' => 'site_script',
        ));

        $this->template()->setTitle($aPage['title']);

        $this->template()->assign(array(
            'aData' => $aData,
            'aPage' => $aPage,
            'sError' => $sError,
            'sNotice' => $sNotice,
            'sBackLink' => $sBackLink,
            'sApiKey' => $sApiKey,
        ));
    }
}
?>
