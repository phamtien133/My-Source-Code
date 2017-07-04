<?php
class Vendor_Component_Controller_Add extends Component
{
    public function process()
    {
        $type = 'create_edit_vendor';
        $this->database = Core::getLib('database');
        $aVals = Core::getLib('request')->getRequests();
        $aData = array();
        
        $page['title'] = Core::getPhrase('language_nha_cung_cap-tao-sua');
        $page['title'] = 'Nhà cung cấp';

        //$_POST
        if(!empty($_POST)) {
            $_POST['post'] = 1;
            if (isset($aVals['id'])) {
                $_POST['id'] = $aVals['id'];
            }
            $aParam = $_POST;
            $aData = Core::getService('vendor')->Create($aParam);

        } else {
            if (isset($aVals['id'])) {
                $aParam['id'] = $aVals['id'];
            }
            $aData = Core::getService('vendor')->initCreate($aParam); 
        }        

        $this->template()->setTitle($page['title']);

        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>
