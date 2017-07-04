<?
class Tab_Component_Controller_Add extends Component
{
    public function process()
    {
		$aVals = Core::getLib('request')->getRequests();
		
        $sArticleId = $this->request()->get('req3');
		$aTmp = explode('_', $sArticleId, 2);

		$sKey = ''; 
		if ($aTmp[0] == 'id') {
			$aVals['id'] = $aTmp[1] * 1;
		} else {
			$key = $aTmp[1];
		}

		if (!empty($_POST)) {
            if (isset($aVals['id'])) {
                $_POST['id'] = $aVals['id'];
            }
            $aParam = $_POST;
            $aData = Core::getService('tab')->create($aParam);
            if ($aData['status'] == 'success') {
                //re-direct page
                $sDir = $_SERVER['REQUEST_URI'];
                $aTmps = explode('/', $sDir, 3);
                $sDir = '/'.$aTmps[1].'/';
                header('Location: '.$sDir);
            }            
        } else {
            if (isset($aVals['id'])) {
                $aParam['id'] = $aVals['id'];
            }
            $aData = Core::getService('tab')->initCreate($aParam); 
        }
		$aPage['title'] = Core::getPhrase('language_the');
        $this->template()->setTitle($aPage['title']);
		$this->template()->assign(array(
			'aData' => $aData,
		));
    }
}
?>