<?php
class User_Service_Delivery extends Service
{
    public function __construct()
    {
        
    }
    
    public function getDefault()
    {
        $iUserId = Core::getUserId();
        if(!$iUserId) 
            return array();
        
        $sCacheId = $this->cache()->set('delivery|'. Core::getDomainId().'|'.$iUserId);
        $aData = $this->cache()->get($sCacheId); 
        if (!$aData) {
            $aRow = $this->database()->select('*')
                ->from(Core::getT('shop_user'))
                ->where('domain_id ='. Core::getDomainId(). ' AND user_id ='. $iUserId. ' AND is_default = 1')
                ->execute('getRow');
                
            if (!isset($aRow['id']) || $aRow['id'] < 0) {
               // get info from table user
               $aRow = $this->database()->select('id, email, fullname, address, city, phone_number')
                    ->from(Core::getT('user'))
                    ->where('id = '. $iUserId. ' AND domain_id = '. Core::getDomainId())
                    ->execute('getRow');
               if (!isset($aRow['id']) || $aRow['id'] < 0) {
                    return array();   
               }
               $aRow['map_location'] = '';
               $aRow['payment_gateway'] = 0;
            }
            $aData = $aRow;
            $this->cache()->save($sCacheId, $aData);
        }
        return $aData;
    }
    
    public function getById($iId)
    {
        if(!$iId)
            return array();
        $sCacheId = $this->cache()->set('delivery|'.Core::getDomainId().'|'.$iId);
        $aData = $this->cache()->get($sCacheId);
        if (!$aData) {
            $aData = $this->database()->select('*')
                ->from(Core::getT('shop_user'))
                ->where('id = '. (int) $iId)
                ->execute('getRow');
            if (!count($aData))
                return array();
            // tách trường địa chỉ thành các trường thông tin chi tiết.
            $sStr = Core::getService('core.area')->parse($aData['area_id']);
            $aData['street'] = str_replace($sStr, '', $aData['address']);
            $aData['street'] = trim($aData['street'], ' ');
            $aData['street'] = trim($aData['street'], ',');
            $aRow = $this->database()->select('id, name, parent_id')
                ->from(Core::getT('area'))
                ->where('id = '. $aData['area_id'])
                ->execute('getRow');
            if ($aRow['id'] > 0) {
                $aData['district']['id'] = $aData['area_id'];
                $aData['district']['name'] = $aRow['name'];
                $aRow = $this->database()->select('id, name')
                    ->from(Core::getT('area'))
                    ->where('id = '. $aRow['parent_id'])
                    ->execute('getRow');
                $aData['city']['id'] = $aRow['id'];
                $aData['city']['name'] = $aRow['name'];
            }
            
            $this->cache()->save($sCacheId, $aData);
        }
        
        return $aData;
    }
}
?>
