<?php
class Filter_Component_Controller_General_Index extends Component
{
    public function process()
    {
        //Lấy dữ liệu method get
        $aVals = Core::getLib('request')->getRequests();

        //Chuyển hướng page
        if (isset($aVals['req3']) && !empty($aVals['req3'])) {
            if ($aVals['req3'] == 'add' || $aVals['req3'] == 'edit') {
                return Core::getLib('module')->setController('filter.general.add');
            }
        }
        
        //Set time phản hồi
        set_time_limit(120);

        //Title page
        $page['title'] = Core::getPhrase('language_quan-ly-trich-loc-tong');

        //Lấy dữ liệu từ service
        $aData = Core::getService('filter')->getFilterGeneral($aVals);

        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));
        
        $this->template()->setTitle($page['title']);
        
        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>