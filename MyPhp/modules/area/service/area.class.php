<?php
class Area_Service_Area extends Service
{
    private $_aDegrees = array();
    public function __construct()
    {
        $this->_aDegrees = array(
            1 => 'Quốc gia',
            2 => 'Tỉnh thành',
            3 => 'Quận huyện',
        );
    }

    public function loadCities($aParam = array())
    {
        //country id
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        if ($iId < 1) {
            return array();
        }
        $iLevel = 2;
        $sConds = '';
        $sConds = ' parent_id ='. $iId . ' AND degree = '. $iLevel;

        $aDatas = $this->database()->select('*')
            ->from(Core::getT('area'))
            ->where($sConds. ' AND status != 2')
            ->execute('getRows');

        return $aDatas;
    }

    public function initCreate($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : 0;
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');
        if ($aPermission['manage_extend'] != 1) {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
            );
        }
        if ($iId > 0) {
            $aArea = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where('status != 2 AND id ='.$iId)
                ->execute('getRow');
            if (!isset($aArea['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Khu vực không tồn tại',
                );
            }
        }


    }

    public function gets($aParam = array())
    {
        /* Giả lập *
        $aParam = array();
        $aParam['q'] = 'moi';
        $aParam['page_size'] = 4;
        $aParam['order'] = 'id_a';
        /* */
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');
        if ($aPermission['manage_extend'] != 1) {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
            );
        }
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
        $aFilter = array();
        $aFilter['degree'] = $this->_aDegrees;
        $aFilterSelect = array();
        $sConds = 'status != 2';

        $sLinkFull = '/area/?page_size='.$iPageSize;
        $sPagination = '/area/?page_size='.$iPageSize;

        $sKeyword = isset($aParam['q']) ? $aParam['q'] : '';
        if (!empty($sKeyword)) {
            $sKeyword = urldecode($sKeyword);
            $sKeyword = trim(Core::getLib('input')->removeXSS($sKeyword));
            if (mb_strlen($sKeyword) > 100)
                $sKeyword = '';
        }
        if (!empty($sKeyword)) {
            $sConds .= ' AND (name LIKE "%'.$this->database()->escape($sKeyword).'%" OR code LIKE "%'.$this->database()->escape($sKeyword).'%")';
            $sPagination .= '&q='.urlencode($sKeyword);
            $sLinkFull .= '&q='.urlencode($sKeyword);
        }

        $iDegree = isset($aParam['lvl']) ? $aParam['lvl'] : 0;
        if ($iDegree < 1 || $iDegree > 3) {
            $iDegree = 2;

        }
        $aFilterSelect['degree'] = $iDegree;
        $iCountryId = isset($aParam['country']) ? $aParam['country'] : 0;
        if ($iDegree == 2 && $iCountryId < 1) {
            $sCode = 'vn';
            $aRow = $this->database()->select('id')
                ->from(Core::getT('area'))
                ->where('status != 2 AND code =\''.$sCode.'\'')
                ->execute('getRow');
            if (isset($aRow['id'])) {
                $iCountryId = $aRow['id'];
            }
            else {
                $iCountryId = -1;
            }
        }
        $iCityId = isset($aParam['city']) ? $aParam['city'] : 0;

        $iParentId -1;
        if ($iDegree == 1) {
            $iParentId = -1;
        }
        elseif ($iDegree == 2) {
            $iParentId = $iCountryId;
        }
        elseif ($iDegree == 3) {
            $iParentId = $iCityId;
        }

        $sLinkFull .= '&lvl='.$iDegree;
        $sPagination .= '&lvl='.$iDegree;
        if ($iCountryId > 0) {
            $sLinkFull .= '&country='.$iCountryId;
            $sPagination .= '&country='.$iCountryId;
        }
        if ($iCityId > 0) {
            $sLinkFull .= '&city='.$iCityId;
            $sPagination .= '&city='.$iCityId;
        }
        $sConds .= ' AND degree ='.$iDegree.' AND parent_id ='.$iParentId;

        //danh sách tất cả các quốc gia
        $aCountries = array();
        $aRows = $this->database()->select('id, name')
            ->from(Core::getT('area'))
            ->where('status != 2 AND degree = 1')
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aCountries[] = array(
                'id' => $aRow['id'],
                'name' => $aRow['name'],
            );
        }
        $aFilter['country'] = $aCountries;
        $aFilterSelect['country'] = $iCountryId;
        //danh sách tất cả các tỉnh thành
        $aCities = array();
        $aRows = $this->database()->select('id, name')
            ->from(Core::getT('area'))
            ->where('status != 2 AND degree = 2 AND parent_id ='.$iCountryId)
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aCities[] = array(
                'id' => $aRow['id'],
                'name' => $aRow['name'],
            );
        }
        $aFilter['city'] = $aCities;
        $aFilterSelect['city'] = $iCityId;
        $sOrder = '';
        $sSort = isset($aParam['order']) ? $aParam['order'] : '';
        if ($sSort == 'id_d') {
            $sOrder = 'id DESC';
        }
        else if ($sSort == 'id_a') {
            $sOrder = 'id ASC';
        }
        else if ($sSort == 'name_d') {
            $sOrder = 'name DESC';
        }
        else if ($sSort == 'name_a') {
            $sOrder = 'name ASC';
        }
        else if ($sSort == 'code_d') {
            $sOrder = 'code DESC';
        }
        else if ($sSort == 'code_a') {
            $sOrder = 'code ASC';
        }
        else {
            $sOrder = 'id DESC';
        }

        if (!empty($sSort)) {
            $sPagination .= '&sort='.$sSort;
            $sLinkFull .= '&sort='.$sSort;
        }
        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sort');

        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('area'))
            ->where($sConds)
            ->execute('getField');
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where($sConds)
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $sTmp = 'status_no';
                if ($aRow["status"] == 1) {
                    $sTmp = 'status_yes';
                }
                $aRow['status_text'] = $sTmp;
                $aData[] = array(
                    'id' => $aRow["id"],
                    'name' => $aRow["name"],
                    'code' => $aRow["code"],
                    'status' => $aRow["status"],
                    'status_text' => $aRow["status_text"],
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
                'pagination' => $sPagination,
                'filter' => $aFilter,
                'filter_select' => $aFilterSelect,
            ),
        );
    }
}
?>
