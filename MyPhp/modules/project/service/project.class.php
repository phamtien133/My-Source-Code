<?php
class Project_Service_Project extends Service
{    
    public function __construct()
    {

    }
    public function getDomanInfo()
    {
        try {
            $aRows = $this->database()->select('*')
            ->from(Core::getT('domain'))
            ->execute('getRows');

            foreach ($aRows as $rows) {
                $aData[] = array(
                    'id' => $rows["id"],
                    'code' => $rows["code"],
                    'domain' => $rows["domain"],
                    'path' => $rows["path"],
                    'enable_firewall' => $rows["enable_firewall"],
                    'create_time' => $rows["create_time"],
                    'ip_create' => $rows["ip_create"],
                    'sys_user_id' => $rows["sys_user_id"],
                    'status' => $rows["status"]
                );
            }
            return $aData;   
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        
    }

    public function setDBSite($aParam = array(), $bUpdate)
    {
        if($bUpdate) {
            try {
                $this->database()->update(Core::getT('site'), array(
                        'name' => $aParam['name'],
                        'name_code' => $aParam['name_code'],
                        'status' => $aParam['status']
                    ), 'id ='. $aParam['id']);
                return array(
                    'status' => 'success',
                    'message' => 'Chỉnh sửa thành công'
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
        //kiem tra mang $aParam
        if(empty($aParam)) {
            return array(
                'status' => 'error',
                'message' => 'Dữ liệu truyền vào không hợp lệ'
            );
        }

        //Kiem tra nam_code
        if(!isset($aParam['name_code']) || empty($aParam['name_code'])) {
            return array(
                'status' => 'error',
                'message' => 'Mã tên phải được truyền vào'
            );
        }

        $aRows = $this->database()->select('count(*) as soluong')
                ->from(Core::getT('site'))
                ->where("name_code = '".$aParam['name_code']."'")
                ->execute('getField');//
        
        if($aRows[0]['count(*)']) {
            return array(
                'status' => 'error',
                'message' => 'Mã tên không được trùng'
            );
        }

        //Kiem tra name
        if(!isset($aParam['name']) && !empty($aParam['name'])) {
            return array(
                'status' => 'error',
                'message' => 'Tên phải được truyền vào'
            );
        }
        
        //Kiem tra status
        $iStatus = isset($aParam['status']) ? (int) $aParam['status'] : -1;
        if ($iStatus == -1 ) {
            // truong hop co yeu cau bat buoc co status
            return array(
                'status' => 'error',
                'message' => 'Status không tồn tại.'
                );
        }
        if (!in_array($iStatus, array(0,1))) {
            return array(
                'status' => 'error',
                'message' => 'status không hợp lệ.'
                );
        }
        //ktra quyen` neu co
        //them data vao db site
        try{
            $aRows = $this->database()->insert(Core::getT('site'), array(
                    'name' => $aParam['name'],
                    'name_code' => $aParam['name_code'],
                    'status' => $aParam['status']
            ));
            return array(
                'status' => 'success',
                'message' => 'Thêm thành công'
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

    public function getDBSite($aParam)
    {
        try{
            if(isset($aParam)) {
                $aRows = $this->database()->select('*')
                ->from(Core::getT('site'))
                ->where('id ='. $aParam['id'] )
                ->execute('getRow');
                return $aRows;
            } else {
                $aRows = $this->database()->select('*')
                ->from(Core::getT('site'))
                ->execute('getRows');
                foreach ($aRows as $rows) {
                $aData[] = array(
                    'id' => $rows["id"],
                    'name' => $rows["name"],
                    'name_code' => $rows["name_code"],
                    'status' => $rows["status"]
                );
                }
                return $aData;
            }  
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }   
    }

    public function deleteDBSite($aParam = array())
    {
        if(!empty($aParam)) {
            $this->database()->delete(Core::getT('site'), 'id = '. $aParam['id']);
        }
    }

    public function updateStatusProject($aParam = array()) {
         try {
                $this->database()->update(Core::getT('site'), array(
                        'status' => $aParam['status']
                    ), 'id ='. $aParam['id']);
                return array(
                    'status' => 'success',
                    'message' => 'Chỉnh sửa status thành công'
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
    // public function updateDBSite($aParam = array())
    // {
    //     try {
    //         $this->database()->update(Core::getT('site'), array(
    //                 'name' => $aParam['name'],
    //                 'name_code' => $aParam['name_code'],
    //                 'status' => $aParam['status']
    //             ), 'id ='. $aParam['id']);
    //         return array(
    //             'status' => 'success',
    //             'message' => 'Thêm thành công'
    //             );
    //     }
    //     catch(Exception $e) {
    //         Core_Error::log('Error');
    //         return array(
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         );
    //     }           
    // }
}
?>