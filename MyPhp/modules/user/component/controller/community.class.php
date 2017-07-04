<?php
class User_Component_Controller_Community extends Component
{
    public function process()
    {
        $sDomainPath = Core::getLib('request')->get('domain-path');
        $iId = Core::getService('category')->isCategoryUrl(array(
            'domain-path' => $sDomainPath
        ));
        if (!$iId) {
            return Core::getLib('module')->setController('core.build');
            exit;
        }
        
        //$aVals = Core::getLib('request')->getArray('val');
        
        //get category community
        $iCategoryId = Core::getService('user.community')->getId();
        if ($iCategoryId < 1) {
            return Core::getLib('module')->setController('core.build');
            exit;
        }
        //get list of category 
        $aReturn = Core::getService('category')->get(array(
            'request' => Core::getLib('request')->getRequests(),
            'bIsHomePage' => false,
            'id' => $iId,
            'bGetChilds' => true,
            'bGetFilter' => false
            
        ));
        if ($aReturn['status'] == 'error' && $aReturn['code'] == 404) {
            return Core::getLib('module')->setController('core.build');
            exit;
        }
        if($aReturn['status'] == 'success') {
            $aCategory = $aReturn['data'];
            $this->template()->setTitle($aCategory['title']);
            $this->template()->assign(array(
                'aCategory' => $aCategory
            ));
        }
        
        $this->template()->setHeader(array(
            'community.css' => 'site_css',
            'community.js' => 'site_script',
        ));
        
        $this->template()->assign(array(
            'iId' => $iId,
            'iCategoryId' => $iCategoryId
        ));
    }
}
?>
