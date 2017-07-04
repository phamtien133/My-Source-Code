<?php
class ImageExtend_Component_Controller_Add extends Component
{
    public function process()
    {
		$this->database = Core::getLib('database');
		$oSession = Core::getLib('session');
		$aVals = Core::getLib('request')->getRequests();
		
        $sArticleId = $this->request()->get('req3');
		$sTmps = explode('_', $sArticleId, 2);
		
		$iId = 0;
		$sKey = '';
		if($sTmps[0] == 'id'){
			$iId = $sTmps[1]*1;
		} else {
			$sKey = $sTmps[1];
		}
		
		$page['title'] = Core::getPhrase('language_hinh-mo-rong-tao-sua');
		
		// if($oSession->getArray('session-permission', 'manage_extend') != 1)
		// {
		// 	$errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
		// }
		
		if(!empty($_POST))
		{
            if (isset($aVals['id'])) {
                $_POST['id'] = $aVals['id'];
            }
            $aParam = $_POST;
            $aData = Core::getService('imageextend')->create($aParam);            
		} else {
			if (isset($aVals['id'])) {
                $aParam['id'] = $aVals['id'];
            }
            $aData = Core::getService('imageextend')->initCreate($aParam); 
		}
		
        
        $this->template()->setTitle('Thêm hình mở rộng');
		
		$this->template()->assign(array(
			'aData' => $aData,
		));
    }
}
?>