<?php
class Imageextend_Component_Controller_General_Add extends Component
{
    public function process()
    {
        $aVals = Core::getLib('request')->getRequests();
        $iId = 0;

        if (isset($aVals['id'])) {
            $iId = $aVals['id'];
        }

        $aPage['title'] = 'Tạo nội dung mở rộng tổng';

        $aPage = array();
        $aData = array();

        if (isset($aVals['val']) && !empty($aVals['val'])) {
            $aPage['title'] = 'Cập nhật nội dung mở rộng tổng';
            $aData = Core::getService('imageextend')->createGeneral($aVals['val']);
            if ($aData['status'] == 'success') {
                $aData['status_global'] = 1;
            }
        }

        if ($iId > 0) {
            $aData = Core::getService('imageextend')->getGeneralById(array('id' => $iId));
        }
        $this->template()->setTitle($aPage['title']);
        
        $this->template()->assign(array(
            'aData' => $aData,
            'iId' => $iId,
        ));
    }
}
?>
