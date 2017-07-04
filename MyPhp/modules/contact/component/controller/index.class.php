<?php
class Contact_Component_Controller_Index extends Component
{
	public function process()
	{
		//Điều hướng page
	    if (isset($aVals['req2'])) {
	        if ($aVals['req2'] == 'edit' || $aVals['req2'] == 'add') {
	            return Core::getLib('module')->setController('contact.edit');
	        }
	    }
	    
		$aPage = array(
			'page' => $this->request()->get('page'), 
			'page_size' => $this->request()->get('page_size')
		);
		$aData = array();

		$aData = Core::getService('contact')->getData($aPage);

		$this->template()->setHeader(array(
	        'marketing.css' => 'site_css',
	        'contact.js' => 'site_script',
		));

		$this->template()->assign(array(
            'aData' => $aData,
        ));
	}
}
?>