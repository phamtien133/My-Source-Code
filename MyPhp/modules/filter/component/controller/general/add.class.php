<?php
class Filter_Component_Controller_General_Add extends Component
{
    public function process()
    {
        $aVals = Core::getLib('request')->getRequests();
        $page['title'] = Core::getPhrase('language_tao-trich-loc-tong');
        

        if (!empty($_POST)) {
            if (isset($aVals['id'])) {
                $_POST['id'] = $aVals['id'];
            }
            $aParam = $_POST;
            $aData = Core::getService('filter')->createGeneral($aParam);      
            if ($aData['status'] == 'success') {
                //re-direct page
                $sDir = $_SERVER['REQUEST_URI'];
                $aTmps = explode('/', $sDir, 3);
                $sDir = '/'.$aTmps[1].'/general/';
                header('Location: '.$sDir);
            }        
        } else {
            if (isset($aVals['id'])) {
                $aParam['id'] = $aVals['id'];
            }
            $aData = Core::getService('filter')->initCreateGeneral($aParam); 

        }
        $this->template()->setTitle($page['title']);

        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>