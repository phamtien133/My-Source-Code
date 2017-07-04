<?php
class User_Service_Field_Field extends Service
{
    private $_aMapType = array();
    public function __construct()
    {
        $this->_sTable = Core::getT('user_field');
        $this->_aMapType = array(
            'text' => 'Text',
            'checkbox' => 'CheckBox',
            'radio' => 'RadioBox',
            'select' => 'SelectBox',
            'multiselect' => 'Multi Select',
        );
    }

    public function get($aParam = array())
    {
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 20;
        $sOrder = isset($aParam['order']) ? $aParam['order'] : 'id DESC';
        $iParentId = isset($aParam['parent_id']) ? $aParam['parent_id'] : 0;
        if (empty($sOrder)) {
            $sOrder = 'id DESC';
        }
        $sQuery = ' status != 2 AND domain_id ='. Core::getDomainId(); // loại bỏ các nhóm đã bị xóa.

        if (isset($aParam['keyword']) && !empty($aParam['keyword'])) {
            $sQuery .= ' AND name LIKE \'%'. $this->database()->escape($aParam['keyword']) .'%\' ';
        }
        if ($iPage < 1) {
            $iPage = 1;
        }

        if ($iPageSize < 1 || $iPageSize > 50) {
            $iPageSize = 20;
        }

        $iCnt = $this->database()->select('COUNT(*)')
            ->from($this->_sTable)
            ->where($sQuery)
            ->execute("getField");
        $aData = array();
        if ($iCnt) {
            $aRows = $this->database()->select('*')
                ->from($this->_sTable)
                ->where($sQuery)
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            $aTmpId = array();
            foreach ($aRows as $aRow) {
                $aRow['type_txt'] = $this->_aMapType[$aRow['type']];
                $aData[] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                    'name_code' => $aRow['name_code'],
                    'type' => $aRow['type'],
                    'type_txt' => $aRow['type_txt'],
                    'display_in_register' => $aRow['display_in_register'],
                    'is_require' => $aRow['is_require'],
                    'status' => $aRow['status'],
                );
            }
        }

        return array(
            'status' => 'success',
            'page' => $iPage,
            'page_size' => $iPageSize,
            'total' => $iCnt,
            'data' => $aData,
        );
    }

    public function delete($aParam = array())
    {
        if (!Core::isUser()) {
            return array(
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập trước khi thực hiện.'
            );
        }
        //  kiểm tra quyền
        $oSession = Core::getLib('session');
        if ($oSession->getArray('session-permission', 'edit_user') != 1) {
            return array(
                'status' => 'error',
                'message' => 'Bạn không đủ quyền thực hiện thao tác này.'
            );
        }

        // thực hiện move các user trong nhóm qua nhóm mặc định

    }

    public function add($aParam = array())
    {
        $aData = array();
        $aData['id'] = isset($aParam['id']) ? $aParam['id'] : -1;
        $iId = $aData['id'];
        $aData['group_id'] = isset($aParam['group_id']) ? $aParam['group_id'] : 0;
        $aData['name'] = isset($aParam['name']) ? $aParam['name'] : '';
        $aData['name_code'] = isset($aParam['name_code']) ? $aParam['name_code'] : '';
        $aData['type'] = isset($aParam['type']) ? $aParam['type'] : '';
        $aData['display_in_register'] = isset($aParam['display_in_register']) ? $aParam['display_in_register'] : 0;
        $aData['is_require'] = isset($aParam['is_require']) ? $aParam['is_require'] : 0;
        $aData['status'] = isset($aParam['status']) ? $aParam['status'] : 0;

        $aData['type_list'] = array(
            'text' => 'Text',
            'checkbox' => 'CheckBox',
            'radio' => 'RadioBox',
            'select' => 'SelectBox',
            'multiselect' => 'Multi Select',
        );

        if (strlen($aData['name'])  < 2 || strlen($aData['name'] > 225)) {
            return array(
                'status' => 'error',
                'message' => 'Tên ít nhất là 2 ký tự và tối đa là 225 ký tự'
            );
        }
        if (!isset($aData['type_list'][$aData['type']])) {
            return array(
                'status' => 'error',
                'message' => 'Loại dữ liệu không hợp lệ'
            );
        }
        $aData['list_name'] = isset($aParam['list_name']) ? $aParam['list_name'] : array();
        $aData['list_code'] = isset($aParam['list_code']) ? $aParam['list_code'] : array();
        $aData['list_id'] = isset($aParam['list_id']) ? $aParam['list_id'] : array();
        if ($aData['type'] != 'text') {
            if (empty($aData['list_name'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Không có thông tin các giá tri'
                );
            }
        }
        else {
            $aData['list_name'] = array();
            $aData['list_code'] = array();
            $aData['list_id'] = array();
        }

        //Check name code
        $sConds = ' AND name_code = \''.$aData['name_code'].'\'';
        if ($aData['id'] > 0) {
            $aOld = $this->database()->select('id')
                ->from(Core::getT('user_field'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$aData['id'])
                ->execute('getRow');
            if (!isset($aOld['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Không tồn tại Custom field này',
                );
            }
            $sConds .= ' AND id !='.$aData['id'];
        }
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('user_field'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().$sConds)
            ->execute('getField');
        if ($iCnt > 0) {
            return array(
                'status' => 'error',
                'message' => 'Mã tên đã tồn tại',
            );
        }

        //add
        $aInsert = array(
            'group_id' => $aData['group_id'],
            'name' => $aData['name'],
            'name_code' => $aData['name_code'],
            'type' => $aData['type'],
            'display_in_register' => $aData['display_in_register'],
            'is_require' => $aData['is_require'],
            'status' => $aData['status'],
        );

        if ($aData['id'] > 0) {
            $this->database()->update(Core::getT('user_field'), $aInsert,' id ='.$aData['id']);
        }
        else {
            $aInsert['domain_id'] = Core::getDomainId();
            $aData['id'] = $this->database()->insert(Core::getT('user_field'), $aInsert);
        }

        //insert value
        if ($aData['id'] > 0) {
            $aOldValue = array();
            if ($iId > 0) {
                $aRows = $this->database()->select('id')
                    ->from(Core::getT('user_field_option'))
                    ->where('status != 2 AND field_id ='.$iId)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aOldValue[$aRow['id']] = $aRow['id'];
                }
            }

            foreach ($aData['list_name'] as $sKey => $sVal) {
                $iIdTmp = $aData['list_id'][$sKey];
                $aTemps = array(
                    'name' => $sVal,
                    'name_code' => $aData['list_code'][$sKey],
                );

                if ($iIdTmp > 0 && $iId > 0) {
                    if (isset($aOldValue[$iIdTmp])) {
                        unset($aOldValue[$iIdTmp]);
                    }
                    //update
                    $this->database()->update(Core::getT('user_field_option'), $aTemps, 'id ='.$iIdTmp);
                }
                else {
                    //insert
                    $aTemps['field_id'] = $aData['id'];
                    $aTemps['status'] = 1;
                    $this->database()->insert(Core::getT('user_field_option'), $aTemps);
                }
            }
            if ($iId > 0 && !empty($aOldValue)) {
                //xóa hết những giá trị bị xóa đi
                $this->database()->update(Core::getT('user_field_option'), array('status' => 2), 'id IN ('.implode(',', $aOldValue).')');
            }
        }

        //redirect page
        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/field/';
        header('Location: '.$sDir);

        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    public function getById($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        if ($iId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }

        $aRow = $this->database()->select('*')
            ->from(Core::getT('user_field'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId)
            ->execute('getRow');
        if (!isset($aRow['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Dữ liệu không tồn tại',
            );
        }
        $aData = array();
        $aData['id'] = $aRow['id'];
        $aData['group_id'] = $aRow['group_id'];
        $aData['name'] = $aRow['name'];
        $aData['name_code'] = $aRow['name_code'];
        $aData['type'] = $aRow['type'];
        $aData['display_in_register'] = $aRow['display_in_register'];
        $aData['is_require'] = $aRow['is_require'];
        $aData['status'] = $aRow['status'];
        $aData['option'] = array();
        if ($aData['type'] != 'text') {
            //Lấy thông tin option
            $aRows = $this->database()->select('*')
                ->from(Core::getT('user_field_option'))
                ->where('status != 2 AND field_id ='.$aData['id'])
                ->order('id ASC')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aData['option'][] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                    'name_code' => $aRow['name_code'],
                );
            }
        }

        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    public function updateStatus($aParams = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $iId=@$aParams["id"]*1;
        if ($iId == 0)
            $aList=@$aParams["list"];
        //check quyền chung
        /*
        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Error::set('error', 'Deny(1)');
            $bIsReturn = true;
        }
        elseif ($iId == 0 && $aList == '') {
            Error::set('error', 'Deny(2)');
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'manage_extend') != 1) {
            Error::set('error', 'Deny(3)');
            $bIsReturn = true;
        }
        */
        $sType = '';
        if (isset($aParams['type'])) {
            $sType = $aParams['type'];
        }

        $aTypeList = array (
            'user_field',
        );
        if (!in_array($sType, $aTypeList)) {
            Error::set('error', 'Deny(4)');
            $bIsReturn = true;
        }

        $iStatus = addslashes(@$aParams["status"]*1);
        if ($iStatus != 1 && $iStatus != 2)
            $iStatus=0;

        if (!$bIsReturn) {
            if ($iId == 0) {
                $aList = explode(',', $aList);
                foreach ($aList as $Val) {
                    if ($Val*1>0)
                    $aTmp[] = $Val*1;
                }
                $aList = implode(',', $aTmp);

                $sCond = 'in ('.$aList.')';
                unset($aList);
                $aRows = $this->database()->select('id')
                    ->from(Core::getT($sType))
                    ->where('domain_id = '.Core::getDomainId().' AND id '.$sCond)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    // tái tạo điều kiện
                    $aList[] = $aRow['id'];
                }
                $sCond = 'in ('.implode(',', $aList).')';
            }
            else {
                $aList[] = $iId;
                $sCond = '= '.$iId;
            }

            if ($sType == 'shopper' || $sType == 'shipper') {
                $this->database()->update(Core::getT('app_user_zone'), array('status' => $iStatus), 'domain_id ='.Core::getDomainId().' AND status != 2 AND id '.$sCond);
            }
            else {
                $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'domain_id ='.Core::getDomainId().' AND status != 2 AND id '.$sCond);
            }
            //save log
            Core::getService('app')->saveLogSystem(array(
                'action' => 'update_status',
                'id' => $iId,
                'data' => $aParams,
            ));
        }
        if ($bIsReturn) {
            return array(
                'status' => 'error',
                'message' => Error::get('error'),
            );
        }
        else {
            return array(
                'status' => 'success',
            );
        }
    }

    public function getGroup($aParam = array())
    {
        $aRows = $this->database()->select('*')
            ->from(Core::getT('user_field_group'))
            ->where('status = 1')
            ->execute('getRows');

        return $aRows;
    }
}
?>
