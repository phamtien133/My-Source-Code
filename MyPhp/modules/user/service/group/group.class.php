<?php
class User_Service_Group_Group extends Service
{

    private $_aMapStatus = array();
    public function __construct()
    {
        $this->_sTable = Core::getT('user_group');
        $this->_aMapStatus = array(
            0 => 'Cấm truy cập',
            1 => 'Đã kích hoạt',
            2 => 'Đã xóa',
            5 => 'Cấm truy cập'
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
        $sQuery = ' status != 2 AND domain_id ='. Core::getDomainId() . ' AND parent_id = '. $iParentId; // loại bỏ các nhóm đã bị xóa.

        if (isset($aParam['keyword']) && !empty($aParam['keyword'])) {
            $sQuery .= ' AND name LIKE \'%'. $this->database()->escape($aParam['keyword']) .'%\' ';
        }

        $iCnt = $this->database()->select('COUNT(*)')
            ->from($this->_sTable)
            ->where($sQuery)
            ->execute("getField");
        $aLists = array();
        if ($iCnt) {
            $aRows = $this->database()->select('id, name, name_code, status')
                ->from($this->_sTable)
                ->where($sQuery)
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            $aTmpId = array();
            foreach ($aRows as $aRow) {
                $aTmpId[] = $aRow['id'];
                $aRow['total_member'] = 0;
                $aRow['display_status'] = $this->_aMapStatus[$aRow['status']];
                $aLists[$aRow['id']] = $aRow;
            }
            // đếm tổng số thành viên trong nhóm
            if (count($aTmpId)) {
                $aTmps = $this->database()->select('group_id, COUNT(id) as total')
                    ->from(Core::getT('user_group_member'))
                    ->where('status != 2 AND group_id IN ('. implode(',', $aTmpId).')')
                    ->group('group_id')
                    ->execute('getRows');

                foreach ($aTmps as $aValue) {
                    $aLists[$aValue['group_id']]['total_member'] = $aValue['total'];
                }
            }
        }

        return array(
            'status' => 'success',
            'data' => array(
                'total' => $iCnt,
                'list' => $aLists
            )
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


    public function getMemberByGroup($aParam = array())
    {
        $iId = isset($aParam['gid']) ? $aParam['gid'] : 0;
        if ($iId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }
        //check permission .....

        $aGroup = $this->database()->select('*')
            ->from(Core::getT('user_group'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId)
            ->execute('getRow');
        if (!isset($aGroup['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhóm thành viên không tồn tại',
            );
        }
        $aData = array();
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page-size']) ? $aParam['page-size'] : 10;
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 50) {
            $iPageSize = 10;
        }
        $iCnt = 0;
        $sConds = 'status != 2 AND group_id ='.$iId;
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('user_group_member'))
            ->where($sConds)
            ->execute('getField');
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('user_group_member'))
                ->where($sConds)
                ->order('id DESC')
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            $aUserId = array();
            foreach ($aRows as $aRow) {
                $aUserId[] = $aRow['user_id'];
            }
            $aMappingInfo = array();
            $aMappingHighLight = array();
            if (!empty($aUserId)) {
                $aTmps = $this->database()->select('*')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aUserId).')')
                    ->execute('getRows');
                foreach ($aTmps as $aTmp) {
                    $aMappingInfo[$aTmp['id']] = array(
                        'id' => $aTmp['id'],
                        'fullname' => $aTmp['fullname'],
                        'email' => $aTmp['email'],
                        'phone_number' => $aTmp['phone_number'],
                        'status' => $aTmp['status'],
                    );
                }
                //hightlight
                $sCondsTmp = 'status != 2 AND object_type = 1 AND parent_id ='.$iId.' AND object_id IN ('.implode(',', $aUserId).')';
                $aTmps = $this->database()->select('*')
                    ->from(Core::getT('highlight'))
                    ->where($sCondsTmp)
                    ->execute('getRows');
                foreach ($aTmps as $aTmp) {
                    $aMappingHighLight[$aTmp['object_id']] = $aTmp['status'];
                }
            }
            foreach ($aRows as $aRow) {
                if (!isset($aMappingInfo[$aRow['user_id']])) {
                    continue;
                }
                $aData[] = array(
                    'id' => $aRow['id'],
                    'user_id' => $aRow['user_id'],
                    'group_id' => $aRow['group_id'],
                    'user_info' => $aMappingInfo[$aRow['user_id']],
                    'is_highlight' => isset($aMappingHighLight[$aRow['user_id']]) ? $aMappingHighLight[$aRow['user_id']] : 0,
                );
            }
        }

        return array(
            'status' => 'success',
            'total' => $iCnt,
            'page' => $iPage,
            'page_size'=> $iPageSize,
            'data' => $aData,
        );
    }

    public function addMember($aParam = array())
    {

    }

    public function removeMember($aParam = array())
    {
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : 0;
        $iGroupId = isset($aParam['gid']) ? $aParam['gid'] : 0;
        if ($iUserId < 1 || $iGroupId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }
        //permission ...

        $aGroup = $this->database()->select('*')
            ->from(Core::getT('user_group'))
            ->where('status != 2 AND id ='.$iGroupId)
            ->execute('getRow');
        if (!isset($aGroup['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhóm thành viên không tồn tại',
            );
        }
        $aMember = $this->database()->select('*')
            ->from(Core::getT('user_group_member'))
            ->where('status != 2 AND user_id ='.$iUserId.' AND group_id ='.$iGroupId)
            ->execute('getRow');
        if (!isset($aMember['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Thành viên bỉ xóa không nằm trong nhóm',
            );
        }
        $this->database()->update(Core::getT('user_group_member'), array('status' => 2), 'id ='.$aMember['id']);
        //xóa clone nếu có
        if ($aGroup['is_duplidate']) {
            $this->database()->update(Core::getT('user'), array('status' => 2), 'user_group_id ='.$iGroupId.' AND reference_id ='.$iUserId);
        }
        return array(
            'status' => 'success',
        );
    }

    public function setHighLight($aParam = array())
    {
        //$aParam['id'] = -6;
        //$aParam['status'] = 1;
        $oSession = Core::getLib('session');
        $iId = isset($aParam['oid']) ? $aParam['oid'] : 0;
        $iParentId = isset($aParam['pid']) ? $aParam['pid'] : 0;
        $iStatus = isset($aParam['status']) ? $aParam['status'] : 0;

        if ($iId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Không có đối tượng được chọn',
            );
        }

        $aObject = $this->database()->select('id')
            ->from(Core::getT('user'))
            ->where('domain_id ='.Core::getDomainId().' AND id ='.$iId)
            ->execute('getRow');
        if (!isset($aObject['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Đối tượng được chọn không tồn tại',
            );
        }
        //update
        $aHighLight = $this->database()->select('*')
            ->from(Core::getT('highlight'))
            ->where('status != 2 AND object_type = 1 AND object_id ='.$iId.' AND parent_id ='.$iParentId)
            ->execute('getRow');
        if ($iStatus == 0) {
            if (isset($aHighLight['id']) && $aHighLight['status'] == 1) {
                $this->database()->update(Core::getT('highlight'), array('status' => $iStatus), 'id ='.$aHighLight['id']);
            }
        }
        else if ($iStatus == 1) {
            if (isset($aHighLight['id'])) {
                if ($aHighLight['status'] != 1) {
                    $aUpdate = array(
                        'status' => $iStatus,
                        'update_time' => CORE_TIME,
                    );
                    $this->database()->update(Core::getT('highlight'), $aUpdate, 'id ='.$aHighLight['id']);
                }
            }
            else {
                $aInsert = array(
                    'object_id' => $iId,
                    'object_type' => 1,
                    'parent_id' => $iParentId,
                    'status' => $iStatus,
                    'update_time' => CORE_TIME,
                );
                $this->database()->insert(Core::getT('highlight'), $aInsert);
            }
        }

        return array(
            'status' => 'success',
            'data' => array(
                'status' =>  $iStatus,
            ),
        );
    }
}
?>
