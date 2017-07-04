<?php
class Print_Component_Controller_Index extends Component
{
    public function process()
    {
        $sReq2 = $this->request()->get('req2');
        $iReq3 = $this->request()->getInt('req3');

        if (empty($sReq2) || !$iReq3) {
            return false;
        }
        
        $aMap = array(
            'order' => 'print.order'
        );
        if (!isset($aMap[$sReq2])) {
            return false;
        }
        
        Phpfox::getComponent($aMap[$sReq2], array('bNoTemplate' => true), 'controller');
        return false;
    }
}
?>
