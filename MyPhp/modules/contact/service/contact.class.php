<?php
class Contact_Service_Contact extends Service
{    
    public function __construct()
    {

    }

    public function getData($aParam = array())
    {
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 1;

        if ($iPage < 1) { 
            $iPage = 1;
        }

        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 1;
        }

        $iCnt = 0;
        $aData = array();
        $sConds = 'status != 2 AND domain_id ='.Core::getDomainId();
        
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('contact'))
            ->where($sConds)
            ->execute('getField');
        
        if ($iCnt > 0) {   
            $aRows = $this->database()->select('*')
                ->from(Core::getT('contact'))
                ->where($sConds)
                ->order('create_time DESC')
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
        }

        foreach ($aRows as $aRow) {
            $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
            $aRow['create_time_txt'] = date('H:i:s d/m/Y', $aRow['create_time']);

            $aData[] = array(
                'id' => $aRow['id'],
                'fullname' => $aRow['fullname'],
                'email' => $aRow['email'],
                'phone_number' => $aRow['phone_number'],
                'note' => $aRow['note'],
                'create_time' => $aRow['create_time'],
                'create_time_txt' => $aRow['create_time_txt'],
                'status' => $aRow['status']                    
            );
        }
        

        return array(
            'status' => 'success',
            'data' => array(
                'page' => $iPage,
                'page_size' => $iPageSize,
                'total' => $iCnt,
                'list' => $aData,
            ),
        );
    }

    public function updateStatus($aParam = array()) {
         try {
                if (empty($aParam)) {
                    return array(
                        'status' => 'error',
                        'message' => 'Dữ liệu truyền vào bị rỗng'
                    );
                }                

                $iExistContact = $this->database()->select('count(*)')
                    ->from(Core::getT('contact'))
                    ->where("id = '".$aParam['id']."'")
                    ->execute('getField');

                if ($iExistContact <= 0) {
                    return array(
                        'status' => 'error',
                        'message' => 'Thông tin liên hệ này không tồn tại'
                    );
                }

                if ($$aParam['status'] != 0 && $aParam['status'] != 1) {
                    $aParam['status'] = 0;
                }
                
                $this->database()->update(Core::getT('contact'), array(
                        'status' => $aParam['status']
                    ), 'id ='. $aParam['id']);
                return array(
                    'status' => 'success',
                    'data' => $aParam['status']
                    );
            }   
            catch(Exception $e) {
                Core_Error::log('Error');
                return array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                );
            } 
    }
}
?>