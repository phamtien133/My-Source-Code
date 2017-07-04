<?php
class User_Service_Introduce extends Service
{
    public function __construct()
    {
        
    }
    
    public function getIntroduce($aParam = array())
    {
        if (!Core::isUser()) {
            return array(
                'status' => 'error',
                'message' => 'Please login frist' 
            );
        }
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 10;
        $sOrder = isset($aParam['order']) ? $aParam['order'] : 'create_time DESC';
        
        $iCnt = $this->database()->select('COUNT(*)')
            ->from(Core::getT('user_introduce'))
            ->where('user_introduce ='. Core::getUserId())
            ->execute('getField');
        $aDatas = array();
        if ($iCnt) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('user_introduce'))
                ->where('user_introduce ='. Core::getUserId())
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            // lấy thông tin thanh  viên
            $aUserId = array();
            foreach ($aRows as $aRow) {
                $aUserId[] = $aRow['user_id'];
            }
            $aUser = array();
            if (count($aUserId)) {
                $aTmps = $this->database()->select('id, code, fullname')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aUserId).')')
                    ->execute('getRows');
                
                foreach ($aTmps as $aTmp) {
                    $aUser[$aTmp['id']] = $aTmp;
                }
            }
            foreach ($aRows as $aRow) {
                $aRow['fullname'] =$aUser[$aRow['user_id']]['fullname'];
                $aRow['code'] =$aUser[$aRow['user_id']]['code'];
                $aDatas[] = $aRow;
            }
        }
        
        return array(
            'status' => 'success',
            'data' => array(
                'total' => $iCnt,
                'page' => $iPage,
                'page_size' => $iPageSize,
                'list' => $aDatas
            )
        );
    }
}
?>