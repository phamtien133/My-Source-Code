<?
class Tab_Service_Tab extends Service
{
    public function __construct()
    {

    }

    public function getTab($aParam = array())
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
        $sConds = 'domain_id ='. Core::getDomainId() .' AND status != 2'; //Điều kiện select

        $sLinkFull = '/tab/?page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/tab/?page_size='.$iPageSize;  //Đường dẫn phân trang

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

        //Tính tổng dữ liệu
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('tab'))
            ->where($sConds)
            ->execute('getField');

        //Lấy danh sách trích lọc
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('tab'))
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
            $aRow = $this->database()->select('name, name_code, status')
                ->from(Core::getT('tab'))
                ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId)
                ->limit(1)
                ->execute('getRow');
            $aData = array(
                'name' => $aRow['name'],
                'name_code' => $aRow['name_code'],
                'status' => $aRow['status'],
            );

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

        if (mb_strlen($aData['name']) < 1 || mb_strlen($aData['name']) > 225) {
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225),
                'data' => array(
                    'list' => $aParam,
                    'status' => 1,
                ),
            );
        }
        if (mb_strlen($aData['name_code']) < 1 || mb_strlen($aData['name_code']) > 225) {
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225),
                'data' => array(
                    'list' => $aParam,
                    'status' => 1,
                ),
            );
        }
        // kiểm tra id
        if ($iId > 0) {
            $aRows = $this->database()->select('count(id)')
                    ->from(Core::getT('tab'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId)
                    ->execute('getField');
            if ($aRows == 0) {
                return array(
                    'status' => 'error',
                    'message' => sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu')),
                    'data' => array(
                        'list' => $aParam,
                    ),
                );
            }
            
        }

        $sSql = '';
        if ($iId > 0) {
            $sSql = ' AND id != '.$iId;
        }

        // kiểm tra mã tên đã tồn tại chưa
        $aRows = $this->database()->select('count(id)')
                    ->from(Core::getT('tab'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND name_code = "'.addslashes($aData['name_code']).'"'.$sSql)
                    ->execute('getField');
        if ($aRows > 0) {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_ten-da-ton-tai'),
                'data' => array(
                    'list' => $aParam,
                    'status' => 1,
                ),
            );
        }

        if($iId > 0) {
            $this->database()->update(
                Core::getT('tab'),
                array(
                    'name' => $aData['name'],
                    'name_code' => $aData['name_code'],
                    'status' => $aData['status'],
                ),
                'domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId
            );
            if ($this->database()->affectedRows() > 0) {
                $aTmp = array();
                
                // lấy giá trị thẻ
                $sSql = $this->database()->select(' group_concat("", article_id)')
                            ->from(Core::getT('tab_article'))
                            ->where('tab_id ='.$iId.' AND status != 2')
                            ->group('article_id')
                            ->execute('getField');
                
                // lấy đề tài stt các bài viết liên quan đến thẻ trên.
                $aRows = array();
                if (!empty($sSql)) {
                    $aRows = $this->database()->select('category_id')
                        ->from(Core::getT('article'))
                        ->where('domain_id ='. Core::getDomainId() .' AND id IN ('.$sSql.')')
                        ->group('category_id')
                        ->execute('getRows');
                }
                foreach($aRows as $aRow)
                {
                    $aTmp[] = $aRow['category_id'];
                    xoa_cache_thu_muc(array(
                        'link' => Core::getDomainId().'/de_tai/'.$aRow['category_id'],
                        'type' => 'de_tai',
                        'id' => $aRow['category_id'],
                    ));
                }
                /*
                if(!empty($aTmp))
                {
                    $sSql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.Core::getDomainId().' AND loai = "de_tai" AND loai_stt IN ('.implode(',', $aTmp).')';
                    $result = $db->query($sSql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sSql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
                }
                */
            }
        }    
        else
        {
            $this->database()->insert(
                Core::getT('tab'),
                array(
                    'name' => $aData['name'],
                    'name_code' => $aData['name_code'],
                    'status' => $aData['status'],
                    'domain_id ' => Core::getDomainId(),
                )
            );
            $iId = $this->database()->getLastId();
        }     
        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array('action' => 'create_edit_tab'.'-'.$iId,'content' => 'phpinfo',));;
        // end
        $iStatus = 3; 
        //if(!empty($errors)) $status=1;  
        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aParam['id'],
                'status' => $iStatus,
            ),
        );
    }
}