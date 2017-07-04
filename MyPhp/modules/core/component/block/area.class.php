<?php
class Core_Component_Block_Area extends Component
{
    public function process()
    {
        // get current selected area.
        $sArea = $this->request()->get('area');
        // get category if have. If in category controller, it will have param set from controller. 
        $sModule = Core::getLib('module')->getModuleName();
        $sUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // get all area.
        
        $aAreas = Core::getService('core.area')->getForDomain(array(
            'domain_id' => Core::getDomainId()
        ));
        
        if(!count($aAreas))
            return false;

        if(!empty($sArea)) {
            // move to current area
            $bIsExist = false;
            foreach ($aAreas as $aArea) {
                if($aArea['path'] == $sArea) {
                    $sArea = $aArea['name'];
                    $bIsExist = true;
                    break;
                }
            }
        }
        if(!$bIsExist){
            $sArea = 'Chọn khu vực';
        }
        
        $this->template()->assign(array(
            'aAreas' => $aAreas,
            'sArea' => $sArea
        ));
    }
}
?>
