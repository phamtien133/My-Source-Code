<?php
class Core_Service_Currency extends Service
{
    public function __construct()
    {
        
    }
    
    public function formatMoney($aParam)
    {
		if (!is_array($aParam))
		{
			$aParam = array(
				'money' => $aParam*1
			);
		}
		
        if (empty($aParam['money']) || !is_numeric($aParam['money']))
            return 0;
        $iMoney = $aParam['money'];
        $sType = $aParam['code'];
        if ($sType == 'usd') {
            $iTmp = 2;
            $iMoney = number_format($iMoney, $iTmp);
        }
        else {
            $iMoney = number_format($iMoney, $iTmp, ',', '.');
        }
        return $iMoney;
    }
}
?>
