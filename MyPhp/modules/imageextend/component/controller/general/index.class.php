<?php
class Imageextend_Component_Controller_General_Index extends Component
{
    public function process()
    {
        $aPage['title'] = 'Danh sách mở rộng tổng';

        $aVals = Core::getLib('request')->getRequests();
        
        $aData = Core::getService('imageextend')->getImageExtendGeneral($aVals);
        
        $this->template()->setTitle($aPage['title']);

        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));
        
        $this->template()->assign(array(
            'aPage' => $aPage,
            'aData' => $aData,
        ));
    }
}
?>