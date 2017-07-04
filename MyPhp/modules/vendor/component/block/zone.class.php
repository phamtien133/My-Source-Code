<?php
class Supplier_Component_Block_Zone extends Component
{
    public function process()
    {
        $sCurrent = '';
        // get current supplier. 
        $sMap = Core::getLib('module')->getFullControllerName();
        if ($sMap == 'core.index' || $sMap == 'category.index' || $sMap == 'supplier.index') {
            // this block only display in these controllers. 
            $sCurrent = $this->request()->get('req1');
        }

        $aZones = Core::getService('supplier')->getZone();

        if (!empty($sCurrent)) {
            $bIsExist = false;
            foreach ($aZones as $aZone) {
                if($aZone['name_code'] == $sCurrent) {
                    $sCurrent = $aZone['name'];
                    $bIsExist = true;
                    break;
                }
            }
            if(!$bIsExist)
                $sCurrent = 'Việt Nam';
            unset($aZone);
        }
        else {
            $sCurrent = 'Việt Nam';
        }

        $this->template()->assign(array(
            'aZones' => $aZones,
            'sZone' => $sCurrent
        ));
        unset($sCurrent);
        unset($aZones);
        return 'block';
    }
}
?>
