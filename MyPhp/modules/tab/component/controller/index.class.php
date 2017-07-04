<?
class Tab_Component_Controller_Index extends Component
{
    public function process()
    {
    	//Lấy dữ liệu method get
		$aVals = Core::getLib('request')->getRequests();
		//Chuyển trang
        if (!empty($aVals['req2'])) {
            if ($aVals['req2'] == 'edit') {
            	return Core::getLib('module')->setController('tab.add');
            }
        }
		//Set time out
		set_time_limit(120);
		
		//Lấy dữ liệu từ service
		$aData = array();
        $aData = Core::getService('tab')->getTab($aVals);

		$aPage['title'] = Core::getPhrase('language_the');
        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));        
        $this->template()->setTitle($aPage['title']);
		$this->template()->assign(array(
			'aData' => $aData,
		));
    }
}
?>