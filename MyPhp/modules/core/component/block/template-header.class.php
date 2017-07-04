<?php
class Core_Component_Block_Template_Header extends Component
{
    public function process()
    {
        $oSession = Core::getLib('session');
        $this->template()->assign(array(
            'sDomainName' => $oSession->getArray('session-domain', 'domain'),
            'sMobile' => $oSession->get('session-mobile'),
            'versionExFile' => Core::getParam('core.versionExFile')
        ));
    }
}  
?>
