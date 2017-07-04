<? php
public function gets($aParam = array())
    {
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 15;
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 15;
        }
        $iCnt = 0;
        $aData = array();
        $sConds = 'status != 2 AND domain_id ='.Core::getDomainId();
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('notices'))
            ->where($sConds)
            ->execute('getField');
        if ($iCnt > 0) {
            $aMappingType = array(
                'promotion' => 'CT Khuyến mãi',
                'product' => 'Sản phẩm',
            );
            $aRows = $this->database()->select('*')
                ->from(Core::getT('notices'))
                ->where($sConds)
                ->order('id DESC')
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            $aUserId = array();
            foreach ($aRows as $aRow) {
                if (!in_array($aRow['user_id'], $aUserId)) {
                    $aUserId[] = $aRow['user_id'];
                }
            }
            $aMappingUser = array();
            if (!empty($aUserId)) {
                $aTmps = $this->database()->select('id, fullname')
                    ->from(Core::getT('user'))
                    ->where('id IN ('.implode(',', $aUserId).')')
                    ->execute('getRows');
                foreach ($aTmps as $aTmp) {
                    $aMappingUser[$aTmp['id']] = array(
                        'id' => $aTmp['id'],
                        'fullname' => $aTmp['fullname'],
                    );
                }
            }
            foreach ($aRows as $aRow) {
                $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
                $aRow['create_time_txt'] = date('H:i:s d/m/Y', $aRow['create_time']);

                $aData[] = array(
                    'id' => $aRow['id'],
                    'title' => $aRow['title'],
                    'notice_type' => $aRow['notice_type'],
                    'notice_type_txt' => $aMappingType[$aRow['notice_type']],
                    'user_id' => $aRow['user_id'],
                    'create_time' => $aRow['create_time'],
                    'create_time_txt' => $aRow['create_time_txt'],
                    'status' => $aRow['status'],
                    'user' => isset($aMappingUser[$aRow['user_id']]) ? $aMappingUser[$aRow['user_id']] : array(),
                );
            }
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
?>