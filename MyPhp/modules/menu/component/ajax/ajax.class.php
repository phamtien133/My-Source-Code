<?php
class Menu_Component_Ajax_Ajax extends Ajax
{
    public function savePositionMenu()
    {
        $aVals = Core::getLib('request')->getArray('val');
        
        Core::getService('menu')->savePositionMenu($aVals);
        exit;
    }
}
?>