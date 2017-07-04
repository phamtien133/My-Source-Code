<?php
class Core_Component_Block_Activity_Buy extends Component
{
    public function process()
    {
        $iPage = $this->getParam('iPage', 1);
        $iPageSize = 20;
        
        $aActivities = Core::getService('user.activity')->getActivityBuy(array(
            'page' => $iPage,
            'page_size' => $iPageSize,
            'order' => 'time DESC'
        ));
        
        $this->template()->assign(array(
            'aActivities' => $aActivities
        ));
        unset($aActivities);
        return 'block';
    }
}
?>
