<?php
class Filter_Component_Controller_View extends Component
{
    public function process()
    {
        //Lấy id từ url
        $aParam['id'] = $this->request()->getInt('req3', 0);

        //Chuyển hướng page về filter nếu id không tồn tài
        if (!$aParam['id']) {
            $this->url()->send('filter');
        }

        $aData = Core::getService('filter')->getView($aParam);

        $this->template()->setTitle('Filter value');

        $this->template()->setHeader(array(
            'trichloc.css' => 'site_css'
        ));
        
        $this->template()->assign(array(
            'aData' => $aData
        ));
    }
}
?>
