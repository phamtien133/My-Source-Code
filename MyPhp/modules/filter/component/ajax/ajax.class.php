<?php
class Filter_Component_Ajax_Ajax extends Ajax
{
    public function showSearchFilterBox()
    {
        $iFilterValueId = $this->get('value');
        Core::getBlock('filter.search', array('iFilterValue' => $iFilterValueId));
    }
    
    public function getFilterValue()
    {
        $iFilterId = $this->get('fid');
        Core::getBlock('filter.filter_value', array('iFilterId' => $iFilterId));
    }
}
?>
