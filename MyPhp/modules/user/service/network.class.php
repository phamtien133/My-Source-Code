<?php
class User_Service_Network extends Service
{
    private $_sPayUrl = '';

    public function __construct()
    {
        $this->_sPayUrl = Core::getParam('core.pay_path');
    }

    public function get($aParam = array())
    {
        // kiểm tra user đăng nhập
        if (!Core::isUser()) {
            return array(
                'status' => 'error',
                'message' => 'Please login first.'
            );
        }
        // kiểm tra phân quyền

        // thực hiện lấy dữ liệu
        $iLevel = isset($aParam['level']) ? $aParam['level'] : 1;
        $iUserId = isset($aParam['user-id']) ? $aParam['user-id'] : 0;
        $iFLevel = isset($aParam['f-level']) ? $aParam['f-level'] : 0;
        $bIsGetTotal = isset($aParam['get-total']) ? $aParam['get-total'] : 0;
        $iActiveStatus = isset($aParam['activestatus']) ? $aParam['activestatus']: 0;
        $iPage = isset($aParam['page']) ? $aParam['page']: 1;
        $iPageSize = isset($aParam['page-size']) ? $aParam['page-size']: 10;

        if ($iLevel < 1) {
            $iLevel = 1;
        }
        if ($iUserId < 0) {
            $iUserId = 0;
        }
        $aDatas = array();
        if ($bIsGetTotal) {
            $aDatas['total'] = array();
            // lấy tổng F thì bỏ qua các trường hợp user bị banned
            $iTotaUser = $this->database()->select('COUNT(*)')
                ->from(Core::getT('user'))
                ->execute('getField');

            if (!$iTotaUser) {
                $iTotaUser = 0;
            }

            $iTotalActive = $this->database()->select('COUNT(*)')
                ->from(Core::getT('user'))
                ->where(' active_status = 1')
                ->execute('getField');
            if (!$iTotalActive) {
                $iTotalActive = 0;
            }

            $iTotalInActive = $this->database()->select('COUNT(*)')
                ->from(Core::getT('user'))
                ->where(' active_status = 0')
                ->execute('getField');
            if (!$iTotalInActive) {
                $iTotalInActive = 0;
            }

            $iTotalDegree = $this->database()->select('MAX(level_total)')
                ->from(Core::getT('user_network'))
                ->where('level != 0')
                ->execute('getField');
            if (!$iTotalDegree) {
                $iTotalDegree = 0;
            }
            $aDatas['total']['degree'] = $iTotalDegree;
            $aDatas['total']['total'] = $iTotaUser;
            $aDatas['total']['active'] = $iTotalActive;
            $aDatas['total']['inactive'] = $iTotalInActive;
        }

        $aDatas['user'] = array();
        $aDatas['level'] = array();
        if ($iUserId == 0) {
            // lấy theo level tổng của hệ thống
            $sConds = 'level_total = '. $iLevel .' AND status = 1';
            // lấy total theo level
            $iTotal = $this->database()->select('COUNT(*)')
                ->from(Core::getT('user_network'))
                ->where($sConds)
                ->execute('getField');

            // để kiểm tra active hay không thì phải lấy danh sách tất cả các user trong level để kiểm tra với trạng thái user.
            $iTotalActive = 0;
            $iTotalInActive = 0;
            $aTmp = array();
            if ($iTotal > 0) {
                $aRows = $this->database()->select('connect_id')
                    ->from(Core::getT('user_network'))
                    ->where($sConds)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aTmp[] = $aRow['connect_id'];
                }
                // tính tổng active không active.
                if (!empty($aTmp)) {
                    $iTotalActive = $this->database()->select('COUNT(id)')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', $aTmp).') AND active_status = 1')
                        ->execute('getField');
                    $iTotalInActive = $this->database()->select('COUNT(id)')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', $aTmp).') AND active_status = 0')
                        ->execute('getField');
                }
            }
            $aDatas['level']['total'] = $iTotal;
            $aDatas['level']['active'] = $iTotalActive;
            $aDatas['level']['inactive'] = $iTotalInActive;
            $iTotalDegree = 0;
            if ($bIsGetTotal) {
                $iTotalDegree = $aDatas['total']['degree'];
            }
            else {
                // thực hiện lấy tổng degree
                $iTotalDegree = $this->database()->select('MAX(level)')
                    ->from(Core::getT('user_network'))
                    ->where('user_id = '. Core::getUserId() .' AND level != 0')
                    ->execute('getField');
            }
            // số mạng lưới bên dưới tính theo tổng mạng lưới
            $aDatas['level']['degree'] = (($iTotalDegree - $iLevel) > 0) ? $iTotalDegree - $iLevel : 0;

            if ($iActiveStatus != 0) {
                if (empty($aTmp)) {
                    $aRows = $this->database()->select('connect_id')
                        ->from(Core::getT('user_network'))
                        ->where($sConds)
                        ->execute('getRows');
                    foreach ($aRows as $aRow) {
                        $aTmp[] = $aRow['connect_id'];
                    }
                }
                if ($iActiveStatus == 1) {
                    // lấy danh sách user id đang active
                    $aRows = $this->database()->select('id')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', $aTmp).') AND active_status = 1')
                        ->execute('getRows');
                    $aValue = array();
                    foreach ($aRows as $aRow) {
                        $aValue[] = $aRow['id'];
                    }

                    if (!empty($aValue)) {
                        $sConds .= ' AND connect_id IN ('.implode(',', $aValue).')';
                    }
                    else {
                        // thêm điều kiện để không trả về kết quả
                        $sConds .= ' AND id = -1';
                    }
                }
                if ($iActiveStatus == 2) {
                    // lấy danh sách user id đang active
                    $aRows = $this->database()->select('id')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', $aTmp).') AND active_status = 0')
                        ->execute('getRows');
                    $aValue = array();
                    foreach ($aRows as $aRow) {
                        $aValue[] = $aRow['id'];
                    }
                    if (!empty($aValue)) {
                        $sConds .= ' AND connect_id IN ('.implode(',', $aValue).')';
                    }
                    else {
                        // thêm điều kiện để không trả về kết quả
                        $sConds .= ' AND id = -1';
                    }
                }

                // cập nhật lại total
                $iTotal = $this->database()->select('COUNT(connect_id)')
                    ->from(Core::getT('user_network'))
                    ->where($sConds)
                    ->execute('getField');
            }

            // lấy danh sách các user trong điều kiện
            $aRows = $this->database()->select('*')
                ->from(Core::getT('user_network'))
                ->where($sConds)
                ->limit($iPage, $iPageSize, $iTotal)
                ->order('create_time DESC')
                ->execute('getRows');
            $aUser = array();
            foreach ($aRows as $aRow) {
                // do xét theo cây tổng nên user đang xét là user dạn connect id (mặc định lv 1 là connect với hệ thống)
                $aUser[$aRow['connect_id']] = array();
            }

            if (count($aUser)) {
                // thực hiện đếm tổng user ở bậc bên dưới
                $aRows = $this->database()->select('user_id, COUNT(user_id) as total_f')
                    ->from(Core::getT('user_network'))
                    ->where('user_id IN ('.implode(',', array_keys($aUser)).')')
                    ->group('user_id')
                    ->execute('getRows');

                if (empty($aRows)) {
                    foreach ($aUser as $iKey => $aValue) {
                        $aUser[$iKey]['total_id'] = 0;
                    }
                }
                else {
                    foreach ($aRows as $aRow) {
                        if (!$aRow['user_id']) {
                            continue;
                        }
                        $aUser[$aRow['user_id']]['total_id'] = $aRow['total_f'];
                    }
                    foreach ($aUser as $iKey => $aValue) {
                        if (!isset($aValue['total_id'])) {
                            $aUser[$iKey]['total_id'] = 0;
                        }
                    }
                }
                // lấy thông tin country
                $aRows = $this->database()->select('id, name')
                    ->from(Core::getT('area'))
                    ->where('status = 1 AND degree = 1')
                    ->execute('getRows');
                $aCountry = array();
                foreach ($aRows as $aRow) {
                    $aCountry[$aRow['id']] = $aRow['name'];
                }
                // thực hiện lấy thông tin user
                $aRows = $this->database()->select('id, code, email, fullname, country, join_time, active_status')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', array_keys($aUser)).')')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aUser[$aRow['id']]['code'] = $aRow['code'];
                    $aUser[$aRow['id']]['email'] = $aRow['email'];
                    $aUser[$aRow['id']]['fullname'] = $aRow['fullname'];
                    $aUser[$aRow['id']]['active_status'] = $aRow['active_status'];
                    $aUser[$aRow['id']]['country_name'] = ($aRow['country'] > 0) ?  $aCountry[$aRow['country']] : 'Not Selected';
                    $iTime = Core::getLib('date')->convertFromGmt($aRow["join_time"], Core::getParam('core.default_time_zone_offset'));
                    $aUser[$aRow['id']]["display_time"] = date('H:i:s d/m/Y ', $iTime);
                }
                // đếm tổng số ph
                $aRows = $this->database()->select('user_id, count(item_id) as total')
                    ->from(Core::getT('package_transaction'))
                    ->where('user_id IN ('.implode(',', array_keys($aUser)).') AND transaction_type =\'give\' AND status != 0')
                    ->group('user_id')
                    ->execute('getRows');

                if (empty($aRows)) {
                    foreach ($aUser as $iKey => $aValue) {
                        $aUser[$iKey]['total_ph'] = 0;
                    }
                }
                else {
                    foreach ($aRows as $aRow) {
                        if (!$aRow['user_id']) {
                            continue;
                        }
                        $aUser[$aRow['user_id']]['total_ph'] = $aRow['total'];
                    }
                    foreach ($aUser as $iKey => $aValue) {
                        if (!isset($aValue['total_ph'])) {
                            $aUser[$iKey]['total_ph'] = 0;
                        }
                    }
                }
                // xác định level hiện tại của các user
                $aRows = $this->database()->select('connect_id, level_total')
                    ->from(Core::getT('user_network'))
                    ->where('connect_id IN ('.implode(',', $aUser).') AND level = 1')
                    ->execute('getRows');

                foreach ($aRows as $aRow) {
                    $aUser[$aRow['connect_id']]['level'] = $aRow['level_total'];
                }

                // gọi qua account để lấy thông tin zen
                $aSendRequest = array(
                    'call' => 'getuserpoint',
                    'return' => 1,
                    'sid' => session_id(),
                    'user_id' => implode(',', $aUser),
                    'pass_login' => 1,
                    'p_code' => 'zen'
                );
                $sReturn = Core::getService('core.tools')->getDataWithCurl($this->_sPayUrl, 40, $aSendRequest);
                $aTmp = json_decode($sReturn, 1);
                if (!isset($aTmp['status']) || $aTmp['status'] == 'error') {
                    foreach ($aUser as $iKey => $aValue) {
                        $aUser[$iKey]['total_zen'] = 0;
                    }
                }
                else {
                    $aTmp = $aTmp['data'];
                    foreach ($aTmp as $aValue) {
                        $aUser[$aValue['user_id']]['total_zen'] = $aValue['point'];
                    }
                    foreach ($aUser as $iKey => $aValue) {
                        if (!isset($aValue['total_zen'])) {
                            $aUser[$iKey]['total_zen'] = 0;
                        }
                    }
                }


            }
            $aDatas['user']['total'] = $iTotal;
            $aDatas['user']['page'] = $iPage;
            $aDatas['user']['page_size'] = $iPageSize;
            $aDatas['user']['list'] = $aUser;
        }
        else {
            // lấy tổng user các cấp bên dưới của user hiện tại
            $iTotal = $this->database()->select('COUNT(connect_id)')
                ->from(Core::getT('user_network'))
                ->where('user_id = '. $iUserId .' AND status = 1')
                ->execute('getField');
            $iTotalActive = 0;
            $iTotalInActive = 0;
            if ($iTotal > 0) {
                $aRows = $this->database()->select('connect_id')
                    ->from(Core::getT('user_network'))
                    ->where('user_id = '. $iUserId .' AND status = 1')
                    ->execute('getRows');
                $aTmp = array();
                foreach ($aRows as $aRow) {
                    $aTmp[] = $aRow['connect_id'];
                }
                // tính tổng active không active.
                $iTotalActive = $this->database()->select('COUNT(id)')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aTmp).') AND active_status = 1')
                    ->execute('getField');
                $iTotalInActive = $this->database()->select('COUNT(id)')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aTmp).') AND active_status = 0')
                    ->execute('getField');
            }

            $aDatas['user']['general']['total'] = $iTotal;
            $aDatas['user']['general']['active'] = $iTotalActive;
            $aDatas['user']['general']['inactive'] = $iTotalInActive;
            // tính tổng số f user đang có
            $aDatas['user']['degree'] = $this->database()->select('MAX(level)')
                ->from(Core::getT('user_network'))
                ->where('user_id = '. $iUserId .' AND level != 0')
                ->execute('getField');
            if (!$aDatas['user']['degree']) {
                $aDatas['user']['degree'] = 0;
            }
            // lấy cụ thể thông tin f1 của user
            $iTotalF1 = $this->database()->select('COUNT(connect_id)')
                ->from(Core::getT('user_network'))
                ->where('user_id = '. $iUserId .' AND status =  1 AND level = 1')
                ->execute('getField');
            if (!$iTotalF1) {
                $iTotalF1 = 0;
            }
            $iTotalActive = 0;
            $iTotalInActive = 0;
            $aTmp = array();
            if ($iTotalF1 > 0) {
                $aRows = $this->database()->select('connect_id')
                    ->from(Core::getT('user_network'))
                    ->where('user_id = '. $iUserId .' AND status =  1 AND level = 1')
                    ->execute('getRows');

                foreach ($aRows as $aRow) {
                    $aTmp[] = $aRow['connect_id'];
                }
                // tính tổng active không active.
                $iTotalActive = $this->database()->select('COUNT(id)')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aTmp).') AND active_status = 1')
                    ->execute('getField');
                $iTotalInActive = $this->database()->select('COUNT(id)')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aTmp).') AND active_status = 0')
                    ->execute('getField');
            }
            $aDatas['user']['f1']['total'] = $iTotalF1;
            $aDatas['user']['f1']['active'] = $iTotalActive;
            $aDatas['user']['f1']['inactive'] = $iTotalInActive;

            // level hiện tại trên hệ thống
            $aDatas['user']['level'] = $this->database()->select('level_total')
                ->from(Core::getT('user_network'))
                ->where('connect_id = '. $iUserId .' AND level = 1 AND status = 1')
                ->execute('getField');
            if (!$aDatas['user']['level']) {
                $aDatas['user']['level'] = 0;
            }
            // lấy thông tin user
            $aDatas['user']['info'] = $this->database()->select('id, code, email, fullname, introduce_code ')
                ->from(Core::getT('user'))
                ->where('id = '. $iUserId)
                ->execute('getRow');

            // lấy danh sách các user trong mạng lưới
            if ($iFLevel < 0) {
                $iFLevel = 0;
            }
            $sCond = 'user_id = '. $iUserId;
            if ($iFLevel == 0) {
                $sCond .= ' AND level != 0';
            }
            else {
                $sCond .= ' AND level = '. $iFLevel;
            }
            $aDatas['user']['f_level'] = array();
            // đếm tổng user trong mạng lưới theo level
            if ($iFLevel > 0) {
                $iTotal = $this->database()->select('COUNT(connect_id)')
                    ->from(Core::getT('user_network'))
                    ->where($sCond)
                    ->execute('getField');
                $iTotalActive = 0;
                $iTotalInActive = 0;
                if ($iTotal > 0) {
                    $aRows = $this->database()->select('connect_id')
                        ->from(Core::getT('user_network'))
                        ->where($sCond)
                        ->execute('getRows');
                    $aTmp = array();
                    foreach ($aRows as $aRow) {
                        $aTmp[] = $aRow['connect_id'];
                    }
                    // tính tổng active không active.
                    $iTotalActive = $this->database()->select('COUNT(id)')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', $aTmp).') AND active_status = 1')
                        ->execute('getField');
                    $iTotalInActive = $this->database()->select('COUNT(id)')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', $aTmp).') AND active_status = 0')
                        ->execute('getField');
                }
                $aDatas['user']['f_level']['total'] = $iTotal;
                $aDatas['user']['f_level']['active'] = $iTotalActive;
                $aDatas['user']['f_level']['inactive'] = $iTotalInActive;

            }
            if ($iActiveStatus != 0) {
                $aTmp = array();
                $aRows = $this->database()->select('connect_id')
                    ->from(Core::getT('user_network'))
                    ->where($sCond)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aTmp[] = $aRow['connect_id'];
                }
                if (empty($aTmp)) {
                    $sCond .= ' AND id = -1';
                }
                else {
                    if ($iActiveStatus == 1) {
                        // lấy danh sách user id đang active
                        $aRows = $this->database()->select('id')
                            ->from(Core::getT('user'))
                            ->where('id IN ('.implode(',', $aTmp).') AND active_status = 1')
                            ->execute('getRows');
                        $aValue = array();
                        foreach ($aRows as $aRow) {
                            $aValue[] = $aRow['id'];
                        }

                        if (!empty($aValue)) {
                            $sCond .= ' AND connect_id IN ('.implode(',', $aValue).')';
                        }
                        else {
                            // thêm điều kiện để không trả về kết quả
                            $sCond .= ' AND id = -1';
                        }
                    }
                    if ($iActiveStatus == 2) {
                        // lấy danh sách user id đang active
                        $aRows = $this->database()->select('id')
                            ->from(Core::getT('user'))
                            ->where('id IN ('.implode(',', $aTmp).') AND active_status = 0')
                            ->execute('getRows');
                        $aValue = array();
                        foreach ($aRows as $aRow) {
                            $aValue[] = $aRow['id'];
                        }
                        if (!empty($aValue)) {
                            $sCond .= ' AND connect_id IN ('.implode(',', $aValue).')';
                        }
                        else {
                            // thêm điều kiện để không trả về kết quả
                            $sCond .= ' AND id = -1';
                        }
                    }
                }

            }

            $iCnt = $this->database()->select('COUNT(connect_id)')
                ->from(Core::getT('user_network'))
                ->where($sCond)
                ->execute('getField');

            $aUser = array();
            if ($iCnt > 0) {
                $aRows = $this->database()->select('connect_id')
                    ->from(Core::getT('user_network'))
                    ->where($sCond)
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->order('create_time DESC')
                    ->execute('getRows');

                foreach ($aRows as $aRow) {
                    // do xét theo cây tổng nên user đang xét là user dạn connect id (mặc định lv 1 là connect với hệ thống)
                    $aUser[$aRow['connect_id']] = array();
                }
                if (count($aUser)) {
                    // thực hiện đếm tổng user ở bậc bên dưới
                    $aRows = $this->database()->select('user_id, COUNT(user_id) as total_f')
                        ->from(Core::getT('user_network'))
                        ->where('user_id IN ('.implode(',', array_keys($aUser)).')')
                        ->group('user_id')
                        ->execute('getRows');
                    if (empty($aRows)) {
                        foreach ($aUser as $iKey => $aValue) {
                            $aUser[$iKey]['total_id'] = 0;
                        }
                    }
                    else {
                        foreach ($aRows as $aRow) {
                            if (!$aRow['user_id']) {
                                continue;
                            }
                            $aUser[$aRow['user_id']]['total_id'] = $aRow['total_f'];
                        }
                        foreach ($aUser as $iKey => $aValue) {
                            if (!isset($aValue['total_id'])) {
                                $aUser[$iKey]['total_id'] = 0;
                            }
                        }
                    }
                    // lấy thông tin country
                    $aRows = $this->database()->select('id, name')
                        ->from(Core::getT('area'))
                        ->where('status = 1 AND degree = 1')
                        ->execute('getRows');
                    $aCountry = array();
                    foreach ($aRows as $aRow) {
                        $aCountry[$aRow['id']] = $aRow['name'];
                    }
                    // thực hiện lấy thông tin user
                    $aRows = $this->database()->select('id, code, email, fullname, country, join_time, active_status')
                        ->from(Core::getT('user'))
                        ->where('id IN ('.implode(',', array_keys($aUser)).')')
                        ->execute('getRows');
                    foreach ($aRows as $aRow) {
                        $aUser[$aRow['id']]['code'] = $aRow['code'];
                        $aUser[$aRow['id']]['email'] = $aRow['email'];
                        $aUser[$aRow['id']]['fullname'] = $aRow['fullname'];
                        $aUser[$aRow['id']]['active_status'] = $aRow['active_status'];
                        $aUser[$aRow['id']]['country_name'] = ($aRow['country'] > 0) ?  $aCountry[$aRow['country']] : 'Not Selected';
                        $iTime = Core::getLib('date')->convertFromGmt($aRow["join_time"], Core::getParam('core.default_time_zone_offset'));
                        $aUser[$aRow['id']]["display_time"] = date('H:i:s d/m/Y ', $iTime);
                    }
                    // đếm tổng số ph
                    $aRows = $this->database()->select('user_id, count(item_id) as total')
                        ->from(Core::getT('package_transaction'))
                        ->where('user_id IN ('.implode(',', array_keys($aUser)).') AND transaction_type =\'give\' AND status != 0')
                        ->group('user_id')
                        ->execute('getRows');

                    if (empty($aRows)) {
                        foreach ($aUser as $iKey => $aValue) {
                            $aUser[$iKey]['total_ph'] = 0;
                        }
                    }
                    else {
                        foreach ($aRows as $aRow) {
                            if (!$aRow['user_id']) {
                                continue;
                            }
                            $aUser[$aRow['user_id']]['total_ph'] = $aRow['total'];
                        }
                        foreach ($aUser as $iKey => $aValue) {
                            if (!isset($aValue['total_ph'])) {
                                $aUser[$iKey]['total_ph'] = 0;
                            }
                        }
                    }
                    // xác định level hiện tại của các user
                    $aRows = $this->database()->select('connect_id, level_total')
                        ->from(Core::getT('user_network'))
                        ->where('connect_id IN ('.implode(',', $aUser).') AND level = 1')
                        ->execute('getRows');

                    foreach ($aRows as $aRow) {
                        $aUser[$aRow['connect_id']]['level'] = $aRow['level_total'];
                    }

                    // gọi qua account để lấy thông tin zen
                    $aSendRequest = array(
                        'call' => 'getuserpoint',
                        'return' => 1,
                        'sid' => session_id(),
                        'user_id' => implode(',', $aUser),
                        'pass_login' => 1,
                        'p_code' => 'zen'
                    );
                    $sReturn = Core::getService('core.tools')->getDataWithCurl($this->_sPayUrl, 40, $aSendRequest);
                    $aTmp = json_decode($sReturn, 1);
                    if (!isset($aTmp['status']) || $aTmp['status'] == 'error') {
                        foreach ($aUser as $iKey => $aValue) {
                            $aUser[$iKey]['total_zen'] = 0;
                        }
                    }
                    else {
                        $aTmp = $aTmp['data'];
                        foreach ($aTmp as $aValue) {
                            $aUser[$aValue['user_id']]['total_zen'] = $aValue['point'];
                        }
                        foreach ($aUser as $iKey => $aValue) {
                            if (!isset($aValue['total_zen'])) {
                                $aUser[$iKey]['total_zen'] = 0;
                            }
                        }
                    }
                }
            }
            $aDatas['user']['total'] = $iCnt;
            $aDatas['user']['page'] = $iPage;
            $aDatas['user']['page_size'] = $iPageSize;
            $aDatas['user']['list'] = $aUser;
        }

        return array(
            'status' => 'success',
            'data' => $aDatas
        );
    }

    public function searchUser($aParam = array())
    {
        // kiểm tra user đăng nhập
        if (!Core::isUser()) {
            return array(
                'status' => 'error',
                'message' => 'Please login first.'
            );
        }
        // kiểm tra phân quyền

        // lấy dữ liệu
        $sKeyWord = isset($aParam['keyword']) ? $aParam['keyword'] : '';
        $iLevel = isset($aParam['level']) ? $aParam['level'] : 1;

        if (empty($sKeyWord)) {
            return array(
                'status' => 'error',
                'message' => 'Please enter search keyword'
            );
        }
        $aDatas = array();
        // lấy danh sách user theo level của network
        $aRows = $this->database()->select('connect_id')
            ->from(Core::getT('user_network'))
            ->where('level_total = '. $iLevel. ' AND status = 1')
            ->execute('getRows');

        if (!empty($aRows)) {
            $aId = array();
            foreach ($aRows as $aRow) {
                $aId[] =  $aRow['connect_id'];
            }
            if (!empty($aId)) {
                $aRows = $this->database()->select('id, code, fullname')
                    ->from(Core::getT('user'))
                    ->where('status != 3 AND id IN ('.implode(',', $aId).') AND (code LIKE \'%'.$this->database()->escape($sKeyWord).'%\' OR fullname LIKE \'%'.$this->database()->escape($sKeyWord).'%\')')
                    ->limit(10)
                    ->execute('getRows');
                $aDatas = $aRows;
            }
        }

        return array(
            'status' => 'success',
            'data' => $aDatas
        );
    }

    public function getUserNetwork($aParam = array())
    {
        $iUserId = isset($aParam['user-id']) ? $aParam['user-id'] : 0;
        if (!$iUserId) {
            return array(
                'status' => 'error',
                'message' => 'User does not exist.'
            );
        }

        // lấy danh sách user cha
        $aRows = $this->database()->select('user_id, level')
            ->from(Core::getT('user_network'))
            ->where('connect_id = '. $iUserId . ' AND level != 0 AND status = 1')
            ->order('level DESC')
            ->execute('getRows');

        if (count($aRows)) {
            $aUser = array();
            foreach ($aRows as $aRow ) {
                $aUser[] = $aRow['user_id'];
            }
            // thm user hiện tại vào cây
            $aUser[] = $iUserId;
            $aRows[] = array(
                'user_id' => $iUserId,
                'level' => 0
            );
            if (count($aUser)) {
                $aTmps = $this->database()->select('id, code')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aUser).')')
                    ->execute('getRows');
                foreach ($aRows as $iKey => $aRow) {
                    foreach ($aTmps as $aTmp) {
                        if ($aRow['user_id'] != $aTmp['id'])
                            continue;

                        $aRows[$iKey]['code'] = $aTmp['code'];
                    }
                }
            }
        }

        return array(
            'status' => 'success',
            'data' => $aRows
        );
    }

    public function updateLevel0()
    {
        /// hàm cập nhật cho những user đăng ký tự do thì có cha là -1
        // chỉ xét những user có liên hệ trực tiếp
        $aRows = $this->database()->select('*')
            ->from(Core::getT('user_network'))
            ->where(' level = 1')
            ->execute('getRows');

        $aParents = array();
        foreach ($aRows as $aRow) {
            // tự thêm user id vào parent trước
            $aParents[$aRow['user_id']] = 1;
        }
        // duyệt lại để loại bỏ các user không phải là level 0
        foreach ($aRows as $aRow) {
            // nếu có tồn tại connect trong parent thì loại bỏ trong parent đi
            if (isset($aParents[$aRow['connect_id']])) {
                unset($aParents[$aRow['connect_id']]);
            }
        }
        if (!empty($aParents)) {
            foreach ($aParents as $iKey => $iValue) {
                $this->database()->insert(Core::getT('user_network'), array(
                    'user_id' => -1,
                    'connect_id' => $iKey,
                    'level' => 1,
                    'create_time' => CORE_TIME,
                    'status' => 1
                ));
            }
        }
    }

    public function updateLevelTotal($aParam = array())
    {
        // chạy đệ quy để tiến hành update.
        $iLevel = $aParam['level'];
        $iParentId = $aParam['parent_id'];

        $aRows = $this->database()->select('*')
            ->from(Core::getT('user_network'))
            ->where('level = 1 AND user_id = '. $iParentId)
            ->execute('getRows');

        foreach ($aRows as $aRow) {
            // cập nhật lại level total cho item
            $this->database()->update(Core::getT('user_network'), array(
                'level_total' => $iLevel
            ), ' id = '. $aRow['id']);
            // thực hiện gọi đệ quy để tiến hành cập nhật level total cho các cấp bên dưới
            $this->updateLevelTotal(array(
                'level' => $iLevel + 1,
                'parent_id' => $aRow['connect_id']
            ));
        }
    }
}
?>