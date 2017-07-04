<?php
class Core_Component_Controller_Deny extends Component
{
    public function process()
    {
		$oSession = Core::getLib('session');
		$oSession->getArray('session-user', 'id');
		$output = array();
		if($oSession->getArray('session-user', 'priority_group') == -1)
		{
			$output['error'] = 'No permission';
		}
		else $output['error'] = 'No User';
		
		$output['post'] = false;
		if(!empty($_POST))
		{
			$output['post'] = true;
		}
		
		$this->template()->assign(array(
			'sType' => $sType,
			'sDomainName' => str_replace('cms.', '', $_SERVER['HTTP_HOST']),
			'output' => $output,
		));
    }
}
?>