<?php
class Filter_Component_Block_Search extends Component
{
    public function process()
    {
        $aFilters = Core::getService('filter')->get(array());

        $iFilterValue = $this->getParam('iFilterValue');
        $this->template()->assign(array(
            'aFilters' => $aFilters,
            'iFilterValue' => $iFilterValue
        ));
    }
}
?>
