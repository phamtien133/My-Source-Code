<?php
class Filter_Component_Controller_Index extends Component
{
    public function process()
    {
        $sReqs = $this->request()->get('req2');
        if ($sReqs == 'view') {
            return Core::getLib('module')->setController('filter.view');
        }
        $this->database = Core::getLib('database');
        $aVals = Core::getLib('request')->getRequests();

        //Chuyển trang
        if (isset($aVals['req2']) && !empty($aVals['req2'])) {
            if ($aVals['req2'] == 'general') {
                return Core::getLib('module')->setController('filter.general.index');
            } else if ($aVals['req2'] == 'add' || $aVals['req2'] == 'edit') {
                return Core::getLib('module')->setController('filter.add');
            }
        }
       
        $sPage['title'] = Core::getPhrase('language_quan-ly-trich-loc');

        $aData = array();
        $aData = Core::getService('filter')->getFilter($aVals);

        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));

        $this->template()->setTitle($sPage['title']);

        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>