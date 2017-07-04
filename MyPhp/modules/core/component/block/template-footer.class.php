<?php
class Core_Component_Block_Template_Footer extends Component
{
    public function process()
    {
        $aAds = array();
        $oSession = Core::getLib('session');
        $aAds['content'] = $oSession->get('ads_content');
        $aAds['position'] = $oSession->get('ads_position');
        $aAds['minimize'] = $oSession->get('ads_minimize');
        $aAds['close'] = $oSession->get('ads_close');
        $aAds['appear_count'] = $oSession->get('ads_appear_count');
        $aAds['width'] = $oSession->get('ads_width');
        $aAds['height'] = $oSession->get('ads_height');
        $aAds['index'] = $oSession->get('ads');

        $this->template()->assign(array(
            'aAds' => $aAds
        ));
    }
}
?>
