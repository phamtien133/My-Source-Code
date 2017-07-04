<?php
class User_Component_Ajax_Ajax extends Ajax
{
    public function updateStatus()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('project')->updateStatusProject($aVals);
        echo json_encode($aReturn);exit;
    }
}
?>
