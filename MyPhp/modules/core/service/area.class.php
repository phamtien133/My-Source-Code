<?php
class Core_Service_Area extends Service
{
    public function __construct()
    {
        
    }
    
    /**
    * get all area of input domain.
    * 
    * @param array $aParam
    * @return array
    */
    public function getForDomain($aParam = array())
    {
        if(!isset($aParam['domain_id']))
            return array();
            
        $sCacheId = $this->cache()->set('area|'. $aParam['domain_id']);
        $aAreas = $this->cache()->get($sCacheId);
        if(!$aAreas) {
            $aAreas = array();
            $aRows = $this->database()->select('area_id')
                ->from(Core::getT('domain_area'))
                ->where('domain_id ='. (int) $aParam['domain_id']. ' AND status = 1')
                ->execute('getRows');
            $aTmp = array();
            foreach($aRows as $aRow) {
                $aTmp[$aRow['area_id']] = $aRow['area_id'];
            }
            if(count($aTmp)) {
                $aAreas = $this->database()->select('id, name, code, path, detail_path, parent_id, parent_list, child_list')
                    ->from(Core::getT('area'))
                    ->where('status = 1 AND id IN ('. implode(',', array_keys($aTmp)).')')
                    ->execute('getRows');
                if(!count($aAreas))
                    return array();
            }
            $this->cache()->save($sCacheId, $aAreas);
        }
        return $aAreas;
        
    }
    
    public function updateArea()
    {
        
        $aParent = $this->database()->select('*')
            ->from(Core::getT('area'))
            ->where('id = 722')
            ->execute('getRow');
        $aRows = $this->database()->select('*')
            ->from(Core::getT('area'))
            ->where('status = 1 AND parent_id = '. $aParent['id'])
            ->execute('getRows');
        
        foreach ($aRows as $iKey => $aRow) {
           // $this->database()->insert(Core::getT('domain_area'), array(
//                'domain_id' => 1,
//                'area_id' => $aRow['id']
//            )); 
            $aRows[$iKey]['name1'] = mb_convert_encoding($aRow['name'], "UTF8");
        }
        d($aRows);die;
    }
    
    public function getCountries($sNameCode = '')
    {
        if (!empty($sNameCode)) {
            $sCacheId = $this->cache()->set('area|countries|'.$sNameCode);
        }
        else {
            $sCacheId = $this->cache()->set('area|countries');
        }
        $iLevel = 1;
        $aDatas = $this->cache()->get($sCacheId);
        if (!$aDatas) {
            $sConds = '';
            if (!empty($sNameCode))
                $sConds = ' code = \''.$this->database()->escape($sNameCode).'\' AND ';
            $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where($sConds. 'degree = '. $iLevel . ' AND status = 1');
            if (!empty($sNameCode))
                $aDatas = $this->database()->execute('getRow');
            else
                $aDatas = $this->database()->execute('getRows');
            
            $this->cache()->save($sCacheId, $aDatas);
        }
        return $aDatas;
    }
    
    public function getCities($iCountryId = 0)
    {
        $iLevel = 2;
        $sConds = '';
        if ($iCountryId != 0) {
            $sConds = ' parent_id ='. $iCountryId . ' AND degree = '. $iLevel;
        }
        else {
            $sConds = 'degree = '.$iLevel;
        }
        $sTmp = md5($sConds);
        $sCacheId = $this->cache()->set('area|'.$iLevel.'|'.$sTmp);
        $aDatas = $this->cache()->get($sCacheId);
        
        if (!$aDatas) {
            $aDatas = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where($sConds. ' AND status = 1')
                ->execute('getRows');
            
            $this->cache()->save($sCacheId, $aDatas);
        }
        
        return $aDatas;
    }
    
    public function getById($aParam = array())
    {
        $iParentId = isset($aParam['pid']) ? $aParam['pid'] : 0;
        $iLevel = isset($aParam['level']) ? $aParam['level'] : 0;
        
        if (!$iParentId || !$iLevel) {
            return array();
        }
        
        $sConds = ' parent_id ='. $iParentId . ' AND degree = '. $iLevel;
        $sCacheId = $this->cache()->set('area|'.$iLevel.'|'.$iParentId);
        $aDatas = $this->cache()->get($sCacheId);
        
        if (!$aDatas) {
            $aDatas = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where($sConds. ' AND status = 1')
                ->execute('getRows');
            if (!count($aDatas)) {
                return array();
            }
            $this->cache()->save($sCacheId, $aDatas);
        }
        
        return $aDatas;
    }
    
    public function getDistricts($iCitiId = 0)
    {
        $iLevel = 3;
        $sConds = '';
        if ($iCitiId != 0) {
            $sConds = ' parent_id ='. $iCitiId . ' AND degree = '. $iLevel;
        }
        else {
            $sConds = 'degree = '.$iLevel;
        }
        $sTmp = md5($sConds);
        $sCacheId = $this->cache()->set('area|'.$iLevel.'|'.$sTmp);
        $aDatas = $this->cache()->get($sCacheId);
        
        if (!$aDatas) {
            $aDatas = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where($sConds. ' AND status = 1')
                ->execute('getRows');
            
            $this->cache()->save($sCacheId, $aDatas);
        }
        
        return $aDatas;
    }
    
    public function getWards($DistrictId = 0)
    {
        $iLevel = 4;
        $sConds = '';
        if ($DistrictId != 0) {
            $sConds = ' parent_id ='. $DistrictId . ' AND degree = '. $iLevel;
        }
        else {
            $sConds = 'degree = '.$iLevel;
        }
        
        $sTmp = md5($sConds);
        $sCacheId = $this->cache()->set('area|'.$iLevel.'|'.$sTmp);
        $aDatas = $this->cache()->get($sCacheId);
        
        if (!$aDatas) {
            $aDatas = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where($sConds. ' AND status = 1')
                ->execute('getRows');
            
            $this->cache()->save($sCacheId, $aDatas);
        }
        
        return $aDatas;
    }
    
    /**
    * Thực hiện tái tạo địa chỉ đầy đủ từ một khu vực id bất kỳ.
    * hiện tại chỉ lấy tới cấp thành phố (bậc 2)
    * @param mixed $iId
    * @return mixed
    */
    public function parse($iId) 
    {
        if(!$iId)
            return '';
        $sCacheId = $this->cache()->set('area|'.$iId.'|ad');
        $sData = $this->cache()->get($sCacheId);
        if (!$sData) {
            // lấy thông tin khu vực hiện tại.
            $aRow = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where('id ='. $iId.' AND status = 1')
                ->execute('getRow');
            
            if (!isset($aRow['id']) || $aRow['id'] < 1) {
                return '';
            }
            // lấy thông tin những khu vực cha của khu vực hiện tại
            if (!empty($aRow['parent_list'])) {
                $aParents = $this->database()->select('*')
                    ->from(Core::getT('area'))
                    ->where('id IN ('.$aRow['parent_list'].') AND status = 1')
                    ->execute('getRows');
                $this->_aData = $aParents;
                $sStr = $this->_parse($aRow['parent_id'], '');
                $sData = $aRow['name'] .', '. $sStr;
            }
            else {
                $sData = $aRow['name'];
            }
            $sData = rtrim($sData, ', ');
            if(empty($sData))
                return '';
            $this->cache()->save($sCacheId, $sData);
        }
        return $sData;
    }
    /**
    * Hàm gọi đệ quy để thực hiện tái tạo địa chỉ đầy đủ từ những khu vực cha
    * 
    * @param mixed $iId
    * @param mixed $sStr
    */
    private function _parse($iId, $sStr)
    {
        foreach ($this->_aData as $aValue) {
            if ($iId != $aValue['id'])
                continue;
            if ($aValue['degree'] == 2) {
                // chỉ xét tới vị trí tỉnh thành.
                $sStr .= $aValue['name'] . ', ';
                return $sStr;
            }
            $sStr .=  $aValue['name']. ', ';
            $sStr = $this->_parse($aValue['parent_id'], $sStr);
        }
        return $sStr;
    }
    
    public function getAddressByWard($iId)
    {
        $aData = array();
        $aWard = $this->database()->select('id, parent_id, name')
            ->from(Core::getT('area'))
            ->where('id ='.$iId)
            ->execute('getRow');
        if (isset($aWard['id'])) {
            $aDistrict = $this->database()->select('id, parent_id, name')
                ->from(Core::getT('area'))
                ->where('id ='.$aWard['parent_id'])
                ->execute('getRow');
            if (isset($aDistrict['id'])) {
                $aCity = $this->database()->select('id, parent_id, name')
                    ->from(Core::getT('area'))
                    ->where('id ='.$aDistrict['parent_id'])
                    ->execute('getRow');
                if (isset($aCity['id'])) {
                    //có thông tin đầy đủ
                    $aData['ward'] = $aWard;
                    $aData['district'] = $aDistrict;
                    $aData['city'] = $aCity;
                }
            }
        }
        
        return $aData;
    }
}
?>