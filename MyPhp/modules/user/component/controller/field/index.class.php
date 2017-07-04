<?php
class User_Component_Controller_Field_Index extends Component
{
    public function process()
    {
        if (!Core::isUser())
            return false;
        
        $aVals = Core::getLib('request')->getRequests();
        $aData = array();
        $aReturn = Core::getService('user.field')->get($aVals);
        
        if ($aReturn['status'] == 'success') {
            $aData = $aReturn['data'];
        }
        
		$page['title'] = 'Custom field';
        $this->template()->setTitle($page['title']);
        $this->template()->setHeader(array(
            'user_field.js' => 'site_script',
        ));
		$this->template()->assign(array(
            'aData' => $aData,
		));
    }
}
?>