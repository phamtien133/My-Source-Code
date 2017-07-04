<?php
class Filter_Component_Block_Filter_Value extends Component
{
    public function process()
    {
        $iFilterId = $this->getParam('iFilterId', 0);
        
        $aFilterValues = Core::getService('filter')->getValue(array(
            'filter_id' => $iFilterId
        ));
        $this->template()->assign(array(
            'aFilterValues' => $aFilterValues
        ));
    }
}  
?>
