<?php
class ImageExtend_Component_Controller_Index extends Component
{
	public function process()
    {
		$this->database = Core::getLib('database');
		$oSession = Core::getLib('session');
		$aVals = Core::getLib('request')->getRequests();
		
        set_time_limit(120);
		$page['title'] = Core::getPhrase('language_quan-ly-shop-custom');

		$aParam = array(
            'page' => $this->request()->get('page'), 
            'page_size' => $this->request()->get('page_size'),
            'order' => $this->request()->get('sort'),
        );

        $aData = array();

        $aData = Core::getService('imageextend')->getImageExtend($aParam);
        
        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));
        
        $this->template()->setTitle('Hình mở rộng');

		$this->template()->assign(array(
			'aData' => $aData,
		));
    }
}
?>