<?php
class Contact_Component_Ajax_Ajax extends Ajax
{
    public function updateStatus()
    {
    	$aParam = $this->get('val', array());
        $aReturn = Core::getService('contact')->updateStatus($aParam);
        echo json_encode($aReturn);exit;
    }
}
?>
