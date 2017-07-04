<?php
class Filter_Service_Filter extends Service
{
    private $_aMap = array();
    private $_aFilters = array();
    private $_aFilterValues = array();
    private $_aGeneralFilters = array();
    private $_iLoopCount = 0;

    public function __construct()
    { 

    }

    public function getFilter($aParam = array())
    {
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');
        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        //Kiểm tra dữ liệu truyền vào
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 10;

        //Xử lý giá trị truyền vào
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 10;
        }

        //Khai báo biến
        $iCnt = 0; //Tổng trang
        $aData = array(); //Dữ liệu
        $sConds = 'status != 2 AND domain_id = '.Core::getDomainId().$query; //Điều kiện select

        $sLinkFull = '/filter/?page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/filter/?page_size='.$iPageSize;  //Đường dẫn phân trang

        //Sắp xếp dữ liệu
        $sOrder = '';
        $iSort = isset($aParam['sort']) ? $aParam['sort'] : '';
        if ($iSort == 'id_d') {
            $sOrder = 'id DESC';
        } else if ($iSort == 'name_d') {
            $sOrder = 'name DESC';
        } else {
            $sOrder = 'id DESC';
        }

        if (!empty($iSort)) {
            $sPagination .= '&sort='.$iSort;
            $sLinkFull .= '&sort='.$iSort;
        }

        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sort');
        // tìm kiếm
        $sKeyword = isset($aParam['q']) ? $aParam['q'] : '';
        if (!empty($sKeyword)) {
            $sKeyword = urldecode($sKeyword);
            $sKeyword = trim(Core::getLib('input')->removeXSS($sKeyword));
            if (mb_strlen($sKeyword) > 100) {
                $sKeyword = '';
            }
        }

        if(!empty($sKeyWord)) {
            $sConds .= ' AND (name LIKE "%'.addslashes($sKeyWord).'%" OR name_code LIKE "%'.addslashes($sKeyWord).'%")';
            $sPagination .= '&q='.urlencode($sKeyword);
            $sLinkFull .= '&q='.urlencode($sKeyword);
        }

        //Tính tổng dữ liệu
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('filter'))
            ->where($sConds)
            ->execute('getField');

        //Lấy danh sách trích lọc
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('filter'))
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

                if (!in_array($aRow["custom_label_id"], $aListType)) { //List type
                    $aListType[] = $aRow["custom_label_sid"];
                }                        

                $aData[] = array(
                    'id' => $aRow["id"],
                    'name' => $aRow["name"],
                    'status' => $aRow["status"],
                    'status_text' => $sTmp,
                    'list_type' => $aListType,
                    'position' => ($aRow["position"]*1),
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
                'link_sort' => $sLinkSort,
            ),
        );
    }

    public function initCreate($aParam = array())
    {
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');

        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }

        if ($iId > 0) {
            $aRows = $this->database()->select('name, name_code, status')
                ->from(Core::getT('tab'))
                ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId)
                ->limit(1)
                ->execute('getRow');
            foreach ($aRows as $aRow) {
                $aData = array(
                    'name' => $aRow['name'],
                    'name_code' => $aRow['name_code'],
                    'status' => $aRow['status'],
                );
            }
        } else {
            $aData['status'] = 1;
        }
        $iStatus = 1;

        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aData,
                'status' => $iStatus,
            ),
        );
    }

    public function create($aParam = array())
    {

        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');

        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        $iId = 0;
        $aParentFilterValueList = array();
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }

        $aData['name'] = $aParam['name'];
        $aData['name_code'] = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($aParam['name_code'])));
        $aData['status'] = $aParam['status']*1;
        if ($aData['status'] != 1) {
            $aData['status'] = 0;
        }

        if(mb_strlen($aData['name']) < 1 || mb_strlen($aData['name']) > 225) $errors[] = sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225);
        if(mb_strlen($aData['name_code']) < 1 || mb_strlen($aData['name_code']) > 225) $errors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225);

        // kiểm tra id
        if($id > 0)
        {
            $rows = $this->database->select('count(id)')
                    ->from(Core::getT('tab'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$id)
                    ->execute('getField');
            if($rows == 0) $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu'));
            
        }

        $sql = '';
        if($id > 0) $sql = ' AND id != '.$id;
        // kiểm tra mã tên đã tồn tại chưa
        $rows = $this->database->select('count(id)')
                    ->from(Core::getT('tab'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND name_code = "'.addslashes($aData['name_code']).'"'.$sql)
                    ->execute('getField');
        if($rows > 0) $errors[] = Core::getPhrase('language_ten-da-ton-tai');

        if($id > 0)
        {
            $this->database->update(
                Core::getT('tab'),
                array(
                    'name' => $aData['name'],
                    'name_code' => $aData['name_code'],
                    'status' => $aData['status'],
                ),
                'domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$id
            );
            $stt = $id;
            if($this->database->affectedRows() > 0)
            {
                $tmps = array();
                
                // lấy giá trị thẻ
                $sql = $this->database->select(' group_concat("", article_id)')
                            ->from(Core::getT('tab_article'))
                            ->where('tab_id ='.$id.' AND status != 2')
                            ->group('article_id')
                            ->execute('getField');
                
                // lấy đề tài stt các bài viết liên quan đến thẻ trên.
                $trows = array();
                if(!empty($sql))
                {
                    $trows = $this->database->select('category_id')
                        ->from(Core::getT('article'))
                        ->where('domain_id ='. Core::getDomainId() .' AND id IN ('.$sql.')')
                        ->group('category_id')
                        ->execute('getRows');
                }
                foreach($trows as $rows)
                {
                    $tmps[] = $rows['category_id'];
                    xoa_cache_thu_muc(array(
                        'link' => Core::getDomainId().'/de_tai/'.$rows['de_tai_stt'],
                        'type' => 'de_tai',
                        'id' => $rows['de_tai_stt'],
                    ));
                }
                /*
                if(!empty($tmps))
                {
                    $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.Core::getDomainId().' AND loai = "de_tai" AND loai_stt IN ('.implode(',', $tmps).')';
                    $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
                }
                */
            }
        }    
        else
        {
            $this->database->insert(
                Core::getT('tab'),
                array(
                    'name' => $aData['name'],
                    'name_code' => $aData['name_code'],
                    'status' => $aData['status'],
                    'domain_id ' => Core::getDomainId(),
                )
            );
            $stt = $this->database->getLastId();
        }     
        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array('action' => 'create_edit_tab'.'-'.$id,'content' => 'phpinfo',));;
        // end
        $status=3; 
        //if(!empty($errors)) $status=1;  
        return array(
            'status' => 'success',
            'data' => $aParam['id'],
        );
    }

    public function getView($aParam = array())
    {
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');
        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        //Kiểm tra dữ liệu truyền vào
        $iFilterId = isset($aParam['id']) ? $aParam['id'] : 0;

        if (!$iFilterId) {
            return array(
                'status' => 'error',
                'message' => 'Dữ liệu không tồn tại',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }

        //Khai báo biến
        $aData = array();
        $iCnt = 0;
        $sConds = 'filter_id ='.$iFilterId.' AND status != 2';
        //Lấy tổng trích lọc
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('filter_value'))
            ->where($sConds)
            ->execute('getField');
        //Lấy giá trị trích lọc
        if ($iCnt > 0) {
            $aRows = $this->database()->select('id, name, name_code, parent_filter_value_list, status, position, description, image_path')
                ->from(Core::getT('filter_value'))
                ->where($sConds)
                ->order('position DESC')
                ->execute('getRows');

            // lấy thêm các giá trị trích lọc cha được cho kế thừa
            $aParent = array();
            foreach ($aRows as $aRow) {
                $aTmp = unserialize($aRow['parent_filter_value_list']);
                $aRow['parent_filter_value_list'] = array();
                if (!empty($aTmp)) {
                    foreach ($aTmp as $iValue) {
                        $aParent[] = $iValue;
                        $aRow['parent_filter_value_list'][$iValue] = '';
                    }
                }
                $aRow['note'] = unserialize($aRow['note']);
                $aData[] = $aRow;
            }

            if (!empty($aParent)) {
                $aRows = $this->database()->select('id, name')
                    ->from(Core::getT('filter_value'))
                    ->where('id IN ('.implode(',', $aParent).') AND status != 2')
                    ->execute('getRows');

                $aTmps = array();

                foreach ($aRows as $aRow) {
                    $aTmps[$aRow['id']] = $aRow['name'];
                }

                // cập nhật trích lọc cho kế thừa vào danh sách trích lọc
                foreach ($aData as $iKey => $aTmp) {
                    if (!empty($aTmp['parent_filter_value_list'])) {
                        foreach ($aTmp['parent_filter_value_list'] as $iTmp => $iValue) {
                            $aData[$iKey]['parent_filter_value_list'][$iTmp] = $aTmps[$iTmp];
                        }
                    }
                }
            }
        }        

        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aData,
                'total' => $iCnt,
            ),
        );    
    }

    public function getFilterGeneral($aParam = array())
    {
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');
        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        //Kiểm tra dữ liệu truyền vào
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 10;

        //Xử lý giá trị truyền vào
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 10;
        }

        //Khai báo biến
        $iCnt = 0; //Tổng trang
        $aData = array(); //Dữ liệu
        $sConds = 'status !=2 AND domain_id ='.Core::getDomainId(); //Điều kiện select
        $iUpdatePos = 0;
        $sLinkFull = '/filter/general/?page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/filter/general/?page_size='.$iPageSize;  //Đường dẫn phân trang

        //Sắp xếp dữ liệu
        $sOrder = '';
        $iSort = isset($aParam['sort']) ? $aParam['sort'] : '';
        if ($iSort == 'id_d') {
            $sOrder = 'id DESC';
        } else if ($iSort == 'name_d') {
            $sOrder = 'name DESC';
        } else {
            $sOrder = 'id DESC';
        }

        if (!empty($iSort)) {
            $sPagination .= '&sort='.$iSort;
            $sLinkFull .= '&sort='.$iSort;
        }

        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sort');
        
        //Lấy tổng dữ liệu
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('filter_general'))
            ->where($sConds)
            ->execute('getField');

        //Lấy danh sách trích lọc tổng
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('filter_general'))
                ->where($sConds)
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
        
            foreach ($aRows as $aRow)
            {
                if ($aRow["status"] == 0) {
                    $sTmp = 'status_no';
                } else {
                    $sTmp = 'status_yes';
                }

                if (!in_array($aRow["custom_label_stt"], $sListType)) {
                    $sListType[] = $aRow["custom_label_stt"];
                }

                if($iUpdatePos == 0)
                {
                    if($aRow["position"] > $iPreviousPos && $iPreviousPos > 0)
                    {
                        $iUpdatePos = 1;
                        unset($iPreviousPos);
                    }
                    else $iPreviousPos = $aRow["position"];
                }

                $aData[] = array(
                    'id' => $aRow["id"],
                    'name' => $aRow["name"],
                    'status' => $aRow["status"],
                    'status_text' => $sTmp,
                    'list_type' => $aListType,
                    'position' => ($aRow["position"]*1),
                    'update_position' => $iUpdatePos,
                );
            }
            $iStatus = 1;
        } else {
            $iStatus = 4;
            return array(
                'status' => 'error',
                'message' => 'Dữ liệu không tồn tại',
                'data' => array(
                    'status' => $iStatus,
                ),
            );            
        }

        return array(
            'status' => 'success',
            'data' => array(
                'page' => $iPage,
                'page_size' => $iPageSize,
                'total' => $iCnt,
                'list' => $aData,
                'pagination' => $sPagination, 
                'link_sort' => $sLinkSort,
                'status' => $iStatus,
            ),
        );        
    }

    public function initCreateGeneral($aParam = array())
    {
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');

        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        //Xử lý giá trị truyền vào
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }

        //Khai báo biến
        $aData = array();
        $sConds = 'id ='.$iId.' AND domain_id ='.Core::getDomainId().' AND status !=2';

        if ($iId > 0) {
            // lấy đề tài stt và tên miền stt
            $aRow = $this->database()->select('name, status')
                ->from(Core::getT('filter_general'))
                ->where($sConds)
                ->execute('getRow');

            if (isset($aRow['name'])) {
                $aData['name'] = $aRow['name'];
                $aData['status'] = $aRow['status'];
            }
        } else {
            $aParam['status'] = 1;
            return array(
                'status' => 'error',
                'message' => 'Id không tồn tại',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }

        $iStatusGlobal = 1;

        // lấy giá trị
        $aRows = $this->database()->select('id, name, filter_general_id')
                ->from(Core::getT('filter'))
                ->where('domain_id ='.Core::getDomainId())
                ->execute('getRows');
        
        foreach ($aRows as $aRow)
        {
            $aData['val_id'][] = $aRow['id'];
            $aData['val_name'][] = $aRow['name'];
            if ($iId > 0 && $aRow['filter_general_id'] == $iId) {
                $aData['val_select'][] = $aRow['id'];
            }
        }

        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aData,
                'global_status' => $iStatusGlobal,
            ),
        );
    }

    public function createGeneral($aParam = array())
    {
        //Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');

        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        //Khai báo biến
        $iId = 0;
        $aData = array();
        $aOldSelect = array();
        $iCnt = 0;
        $sConds = '';

        //Kiểm tra giá trị truyền vào
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }
        $sConds = 'id ='.$iId.' AND domain_id ='.Core::getDomainId().' AND status != 2';

        //Xử lý giá trị truyền vào
        $sName = $aParam['name'];
        $iStatus = $aParam['status']*1;
        if ($iStatus != 1) {
            $iStatus = 0;
        }
        
        foreach ($aParam['val'] as $iKey => $iVal) {
            $iVal *= 1;
            if ($iVal < 1) {
                continue;
            }
            if (!in_array($iVal, $iValId)) {
                $iValId[] = $iVal;
            }
        }

        if (mb_strlen($sName) < 1 || mb_strlen($sName) > 225) {
            // lấy giá trị
            $aRows = $this->database()->select('id, name, filter_general_id')
                    ->from(Core::getT('filter'))
                    ->where('domain_id ='.Core::getDomainId())
                    ->execute('getRows');
            
            foreach ($aRows as $aRow)
            {
                $aParam['val_id'][] = $aRow['id'];
                $aParam['val_name'][] = $aRow['name'];
                if ($iId > 0 && $aRow['filter_general_id'] == $iId) {
                    $aParam['val_select'][] = $aRow['id'];
                }
            }
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 255),
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }

        

        // kiểm tra id
        if ($iId > 0) {
            // lấy đề tài stt và tên miền stt
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('filter_general'))
                ->where($sConds)
                ->execute('getField');
            if ($iCnt == 0) {
                // lấy giá trị
                $aRows = $this->database()->select('id, name, filter_general_id')
                        ->from(Core::getT('filter'))
                        ->where('domain_id ='.Core::getDomainId())
                        ->execute('getRows');
                
                foreach ($aRows as $aRow)
                {
                    $aParam['val_id'][] = $aRow['id'];
                    $aParam['val_name'][] = $aRow['name'];
                    if ($iId > 0 && $aRow['filter_general_id'] == $iId) {
                        $aParam['val_select'][] = $aRow['id'];
                    }
                }
                return array(
                    'status' => 'error',
                    'message' => sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu')),
                    'data' => array(
                        'list' => $aParam,
                    ),
                );
            } else {
                //Láy danh sách các trích lọc đã dc chọn trước đó
                $aRows = $this->database()->select('id')
                    ->from(Core::getT('filter'))
                    ->where('filter_general_id ='.$iId.' AND domain_id ='.Core::getDomainId())
                    ->execute('getRows');

                foreach ($aRows as $aRow) {
                    $aOldSelect[$aRow['id']] = $aRow['id'];
                }
            }
            
        }

        $aInsert = array(
            'name' => $sName,
            'status' => $iStatus,
        );

        if ($iId > 0) {
            $this->database()->update(Core::getT('filter_general'), $aInsert, 'id ='.$iId);
        }
        else {
            $aInsert = array(
                'create_time' => CORE_TIME,
                'user_id' => Core::getUserId(),
                'user_fullname' => Core::getUserName(),
                'domain_id' => Core::getDomainId(),
            );
            $iId = $this->database()->insert(Core::getT('filter_general'), $aInsert);
        }

        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array('action' => 'create_edit_filter_general'.'-'.$iId,'content' => 'phpinfo',));

        $iStatusGlobal = 3;
        for ($i = 0; $i < count($iValId); $i++) {
            $this->database()->update(
                Core::getT('filter'),
                array(
                    'filter_general_id' => $iId
                ),
                'id ='.$iValId[$i].' AND domain_id ='.Core::getDomainId()
            );
            if (isset($aOldSelect[$iValId[$i]])) {
                unset($aOldSelect[$iValId[$i]]);
            }
            
        }
        
        if (!empty($aOldSelect)) {
            //Xóa những dòng ko dc chọn so với trước đó
            $this->database()->update(
                Core::getT('filter'),
                array(
                    'filter_general_id' => 0,
                ),
                'id IN ('.implode(',', $aOldSelect).') AND domain_id ='.Core::getDomainId()
            );
        }

        //re-direct page
        // $sDir = $_SERVER['REQUEST_URI'];
        // $aTmps = explode('/', $sDir, 3);
        // $sDir = '/'.$aTmps[1].'/general/';
        // header('Location: '.$sDir);

        $iStatusGlobal = 1;

        return array(
            'status' => 'success',
            'data' => $aParam['id'],
        );

    }
    /**
    * map value from filters, general filters with filter value.
    *
    * @param mixed $aParam
    */
    public function mapFilter($aParam)
    {
        $this->_aMap = array();
        $this->_aFilters = $aParam['filter'];
        unset($aParam['filter']);
        $this->_aFilterValues = $aParam['filter_value'];
        unset($aParam['filter_value']);
        $this->_aGeneralFilters = $aParam['general_filter'];
        unset($aParam['general_filter']);
        $this->_iLoopCount = 0;
        $this->_mapFilter($aParam);
        return $this->_aMap;
    }



    public function _mapFilter($aParam)
    {
        $this->_iLoopCount++;
        if($aParam['loop_max'] > 0 && $this->_iLoopCount > $aParam['loop_max'])
            return ;
        foreach (array_keys($this->_aGeneralFilters) as $iGeneralKey) {
            foreach ($this->_aFilters[$iGeneralKey] as $aFilter) {
                if($aFilter['name_code'] == 'price' || empty($this->_aFilterValues[$aFilter['id']]) )
                    continue;
                foreach ($this->_aFilterValues[$aFilter['id']] as $iValueKey => $aValue) {
                    $aTmp = array();
                    $aTmpQuery = array();
                    $aTmpQueryValue = array();
                    foreach (array_keys($this->_aGeneralFilters) as $iNewKey) {
                        foreach ($this->_aFilters[$iNewKey] as $aNFilter) {
                            if($aNFilter['name_code'] == 'price' || empty($this->_aFilterValues[$aNFilter['id']]))
                                continue;
                            foreach ($this->_aFilterValues[$aNFilter['id']] as $iTmpKey => $aTmpValue) {
                                if(!isset($aParam['query_value_tmp'][$aNFilter['id']]) || !is_array($aParam['query_value_tmp'][$aNFilter['id']])) {
                                    continue;
                                }
                                $iTmp = in_array($iTmpKey, $aParam['query_value_tmp'][$aNFilter['id']]);
                                if ($iTmpKey == $iValueKey && $iTmp != 1) {
                                    $iTmp = 1;
                                }
                                if(!$iTmp)
                                    continue;

                                $aTmpQueryValue[$aNFilter['id']][] = $iTmpKey;
                                if(!in_array($aNFilter['id'], $aTmpQuery))
                                    $aTmpQuery[] = $aNFilter['id'];
                                $aTmp[$aNFilter['name_code']][] = $aTmpValue['name_code'];
                            }
                        }
                    }

                    if ($aTmpQuery == $aParam['query_tmp'] && $aTmpQueryValue == $aParam['query_value_tmp']) {
                        continue;
                    }

                    $this->_aMap[] = $aTmp;
                    if(empty($aTmpQuery) || empty($aTmpQueryValue))
                        continue;
                    $this->_mapFilter(array(
                        'query_tmp' => $aParam['query_tmp'],
                        'query_value_tmp' => $aParam['query_value_tmp'],
                        'loop_max' => $aParam['loop_max']
                    ));
                }
            }
        }
    }

    public function get($aParam = array())
    {
        $iCategoryId = isset($aParam['cid']) ? $aParam['cid'] : 0;
        //$sCacheId = $this->cache()->set('filter|'.Core::getDomainId().'|'. $iCategoryId);
        //$aFilters = $this->cache()->get($sCacheId);
        $sConds = 'status = 1 AND domain_id = '. Core::getDomainId();
        $aFilterValueList = array();
        $aFilterId = array();
        $aFilterDisplay = array();
        $aFilterInfluencePrice = array();

        if ($iCategoryId > 0) {
            // kiểm tra xem đề tài có nằm trong tên miền hay không.
            // không để trong cache.
            $iField = $this->database()->select('id')
                ->from(Core::getT('category'))
                ->where('id ='. $iCategoryId. ' AND domain_id ='. Core::getDomainId())
                ->execute('getField');
            if (!$iField) {
                return array(
                    'status' => 'error',
                    'message' => 'Thao tác bị từ chối, vui lòng liên hệ quản trị.'
                );
            }

            // lấy danh sách các trích lọc đề tài.
            $aRows = $this->database()->select('filter_value_id, filter_id, is_display_search, is_influence_price')
                ->from(Core::getT('filter_value_category'))
                ->where('category_id = '.$iCategoryId)
                ->execute('getRows');
            if (empty($aRows)) {
                // chỉ lấy các trích lọc thuộc danh mục.
                $sConds .= ' AND id < 0';
            }

            foreach ($aRows as $aRow) {
                if (!in_array($aRow['filter_value_id'], $aFilterValueList))
                    $aFilterValueList[] = $aRow['filter_value_id'];
                if (!in_array($aRow['filter_id'], $aFilter))
                    $aFilterId[] = $aRow['filter_id'];
                // $aRow['hien_thi_tim_kiem'] = 2 || 3
                if ($aRow['is_display_search'] > 1)
                    $aRow['is_display_search'] = 1;
                else
                    $aRow['is_display_search'] = 0;
                $aFilterDisplay[$aRow['filter_id']] = $aRow['is_display_search'];
                $aFilterInfluencePrice[$aRow['filter_id']] = $aRow['is_influence_price'];
            }
            if (empty($aFilterId)) {
                return array();
            }
            $sConds .= ' AND id IN ('.implode(",", $aFilterId).')';
        }

        // lấy các trích lọc
        $aRows = $this->database()->select('id, filter_general_id, name, name_code, type')
            ->from(Core::getT('filter'))
            ->where($sConds)
            ->execute('getRows');

        if (empty($aRows)) {
            return array();
        }

        // lấy giá trị các trích lọc. lấy cả các giá trị để thực hiện map bên dưới
        // có thể phải thêm domain id vào trong bảng value để hạn chế kết quả truy vấn.
        $aTmps = $this->database()->select('id, filter_id, parent_filter_value_list, name, name_code')
            ->from(Core::getT('filter_value'))
            ->where('status = 1 ')
            ->execute('getRows');

        $aParents = array();
        foreach ($aTmps as $iKey => $aTmp) {
            // lấy danh sách các dữ liệu cha có ảnh hưởng đến giá trị hiện tại.
            $aListParent = unserialize($aTmp['parent_filter_value_list']);
            $aTmps[$iKey]['parent_list'] = $aListParent;
            unset($aTmps[$iKey]['parent_filter_value_list']);
            if (is_array($aListParent) && !empty($aListParent)) {
                foreach ($aListParent as $iItem) {
                    if (isset($aParents[$iItem])) {
                        $aParents[$iItem][$aTmp['filter_id']][] = $aTmp['id'];
                        continue;
                    }
                    $aParents[$iItem] = array();
                    $aParents[$iItem][$aTmp['filter_id']][] = $aTmp['id'];
                }
            }
        }
        foreach ($aTmps as $iKey => $aTmp) {
            $aTmps[$iKey]['affect'] = array();
            if (isset($aParents[$aTmp['id']])) {
                $aTmps[$iKey][''] =
                $aTmps[$iKey]['affect'] = $aParents[$aTmp['id']];
            }
        }
        $aFilters = array();
        foreach ($aRows as $iKey => $aRow) {
            $aRow['parent_list'] = unserialize($aRow['parent_list']);
            $aFilters[$aRow['id']] = $aRow;
            foreach ($aTmps as $aTmp) {
                if ($aRow['id'] != $aTmp['filter_id'])
                    continue;
                $aFilters[$aRow['id']]['detail'][$aTmp['id']] = $aTmp;
            }
            unset($aRows[$iKey]);
        }

        return $aFilters;
    }

    public function addFilterValue($aParam = array())
    {
        if (!isset($aParam['filter_id']) || empty($aParam['filter_id'])) {
            return array(
                'status' => 'error',
                'message' => 'Dữ liệu lỗi.'
            );
        }
        if (!isset($aParam['name']) || empty($aParam['name'])) {
            return array(
                'status' => 'error',
                'message' => 'Vui lòng nhập giá trị trích lọc.'
            );
        }
        $sNameCode = '';
        $bManualCode = false;
        if (isset($aParam['name_code']) && !empty($aParam['name_code'])) {
            $sNameCode = $aParam['name_code'];
            $bManualCode = true;
        }
        else {
            // đọc từ tên để tạo mã code.
            $sNameCode = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($aParam['name'])));
        }
        // kiểm tra mã code đã có trong dữ liệu hay chưa.
        $iField = $this->database()->select('id')
            ->from(Core::getT('filter_value'))
            ->where('name_code =\''.$this->database()->escape($sNameCode).'\' AND status != 2')
            ->execute('getField');
        if ($iField) {
            if ($bManualCode) {
                return array(
                    'status' => 'error',
                    'message' => 'Mã tên đã tồn tại, vui lòng chọn giá trị khác.'
                );
            }
            // thêm một số ký tự vào name code tự sinh để tạo ra một chuổi code khác.
            $sNameCode = $sNameCode . '-1'; // phần này sẽ xem xét để check kỹ hơn
        }
        $aListParent = array();
        if (isset($aParam['list_parent'])) {
            $aListParent = explode(',', $aParam['list_parent']);
        }
        $iStatus = 1;
        if (isset($aParam['status'])) {
            $iStatus = $aParam['status'];
        }
        $aInsert = array(
            'filter_id' => (int) $aParam['filter_id'],
            'parent_filter_value_list' => serialize($aListParent),
            'name' => $aParam['name'],
            'name_code' => $sNameCode,
            'note' => '',
            'position' => 0,
            'status' => 1
        );

        try {
            $iId = $this->database()->insert(Core::getT('filter_value'), $aInsert);
            $aInsert['id'] = $iId;
            $this->cache()->remove();
            return array(
                'status' => 'success',
                'data' => $aInsert
            );
        }
        catch(Exception $e) {
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }

    }

    public function getValue($aParam = array())
    {
        $iFilterId = isset($aParam['filter_id']) ? $aParam['filter_id'] : 0;
        if (!$iFilterId)
            return array();

        $iField = $this->database()->select('id')
            ->from(Core::getT('filter'))
            ->where('id ='. (int) $iFilterId .' AND status = 1')
            ->execute('getField');

        if (!$iField)
            return array();

        $aRows = $this->database()->select('*')
            ->from(Core::getT('filter_value'))
            ->where('filter_id ='. $iField . ' AND status = 1')
            ->execute('getRows');
        foreach ($aRows as $iKey => $aRow) {
            if (!empty($aRow['note'])) {
                $aRows[$iKey]['note'] = unserialize($aRow['note']);
            }
        }
        return $aRows;
    }

    public function getValueById($aParam = array())
    {
        $iFilterId = isset($aParam['id']) ? $aParam['id'] : 0;
        if (!$iFilterId) {
            return array();
        }
        $aDatas = array();
        $aRows = $this->database()->select('id, name, name_code, parent_filter_value_list, status, position, description, image_path')
            ->from(Core::getT('filter_value'))
            ->where('filter_id ='.$iFilterId.' AND status != 2')
            ->order('position DESC')
            ->execute('getRows');

        // lấy thêm các giá trị trích lọc cha được cho kế thừa
        $aParent = array();
        foreach ($aRows as $aRow) {
            $aTmp = unserialize($aRow['parent_filter_value_list']);
            $aRow['parent_filter_value_list'] = array();
            if (!empty($aTmp)) {
                foreach ($aTmp as $iValue) {
                    $aParent[] = $iValue;
                    $aRow['parent_filter_value_list'][$iValue] = '';
                }
            }
            $aRow['note'] = unserialize($aRow['note']);
            $aDatas[] = $aRow;
        }

        if (!empty($aParent)) {
            $aRows = $this->database()->select('id, name')
                ->from(Core::getT('filter_value'))
                ->where('id IN ('.implode(',', $aParent).') AND status != 2')
                ->execute('getRows');
            $aTmps = array();
            foreach ($aRows as $aRow) {
                $aTmps[$aRow['id']] = $aRow['name'];
            }
            // cập nhật trích lọc cho kế thừa vào danh sách trích lọc
            foreach ($aDatas as $iKey => $aData) {
                if (!empty($aData['parent_filter_value_list'])) {
                    foreach ($aData['parent_filter_value_list'] as $iTmp => $iValue) {
                        $aDatas[$iKey]['parent_filter_value_list'][$iTmp] = $aTmps[$iTmp];
                    }
                }
            }
        }

        return $aDatas;
    }
}
?>
