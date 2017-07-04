<?php
class Imageextend_Service_Imageextend extends Service
{
    public function __construct()
    {
        
    }

    public function getImageExtend($aParam = array())
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
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 15;

        //Xử lý giá trị truyền vào
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 3;
        }

        //Khai báo biến
        $iCnt = 0; //Tổng trang
        $aData = array(); //Dữ liệu
        $sConds = 'status != 2 AND domain_id = '.Core::getDomainId(); //Điều kiện select
        $iStatus = 4;

        $sLinkFull = '/imageextend/?page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/imageextend/?page_size='.$iPageSize;  //Đường dẫn phân trang

        //Sắp xếp dữ liệu
        $sOrder = '';
        $iSort = isset($aParam['order']) ? $aParam['order'] : '';
        if ($iSort == 1) {
            $sOrder = 'id DESC';
        } else if ($iSort == 3) {
            $sOrder = 'name DESC';
        } else {
            $sOrder = 'id ASC';
        }

        if (!empty($iSort)) {
            $sPagination .= '&sort='.$iSort;
            $sLinkFull .= '&sort='.$iSort;
        }

        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sort');

        $iUpdatePos = 0;

        //Tính tổng dữ liệu
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('image_extend'))
            ->where($sConds)
            ->execute('getField');

        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                    ->from(Core::getT('image_extend'))
                    ->where($sConds)
                    ->order($sOrder)
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->execute('getRows');

            foreach($aRows as $aRow)
            {
                if($aRow["status"]==0) {
                    $sTmp = 'status_no';
                } else {
                    $sTmp = 'status_yes';
                }

                if(!in_array($aRow["custom_label_id"], $aListType)) {
                    $aListType[] = $aRow["custom_label_id"];
                }

                if($iUpdatePos == 0) {
                    if($aRow["position"] > $iPrePos && $iPrePos > 0) {
                        $iUpdatePos = 1;
                        unset($iPrePos);
                    } else {
                        $iPrePos = $aRow["position"];
                    }
                }

                $aData[] = array(
                    'id' => $aRow["id"],
                    'name' => $aRow["name"],
                    'status' => $aRow["status"],
                    'status_text' => $sTmp,
                    'position' => ($aRow["position"]*1),
                    'list_type' => $aListType,
                    'previous_position' => $iPrePos,
                );
            }

            $iStatus = 1;
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

    public function initCreate($aParam = array())
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
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }

        $aInfluence = array(
            array(
                'name' => 'Bài viết',
                'name_code' => 0,
            ),
            array(
                'name' => 'Đề tài',
                'name_code' => 1,
            ),
        );

        $aCategories = array(
            array(
                'name' => 'Hình ảnh',
                'name_code' => 'co-dinh-hinh-anh',
            ),
            array(
                'name' => 'Chữ',
                'name_code' => 'co-dinh-chu',
            ),
            array(
                'name' => 'Yes/No',
                'name_code' => 'yes-no',
            ),
        );

        if($iId > 0)
        {
            // lấy đề tài stt và tên miền stt
            $aRow = $this->database()->select('*')
                ->from(Core::getT('image_extend'))
                ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId)
                ->limit(1)
                ->execute('getRow');
            
            $aData = array(
                'name' => $aRow['name'],
                'type' => $aRow['type'],
                'influence_to' => $aRow['influence_to'],
                'name_code' => $aRow['name_code'],
                'status' => $aRow['status'],
            );
        }

        $iStatus = 1;

        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aData,
                'influence' => $aInfluence,
                'categories' => $aCategories,
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
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }
        //Kiểm tra giá trị truyền vào
        $sName = $aParam['name'];
        $sNameCode = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removexss($aParam['name_code'])));

        $iStatus = $aParam['status']*1;

        if($iStatus != 1) {
            $iStatus = 0;
        }
        
        // ảnh hưởng đến Đề tài OR bài viết
        $aInfluence = array(
            array(
                'name' => 'Bài viết',
                'name_code' => 0,
            ),
            array(
                'name' => 'Đề tài',
                'name_code' => 1,
            ),
        );

        $aCategories = array(
            array(
                'name' => 'Hình ảnh',
                'name_code' => 'co-dinh-hinh-anh',
            ),
            array(
                'name' => 'Chữ',
                'name_code' => 'co-dinh-chu',
            ),
            array(
                'name' => 'Yes/No',
                'name_code' => 'yes-no',
            ),
        );

        $iInfluence = $aParam['influence'];

        if($iInfluence != 1) {
            $iInfluence = 0;
        } else {
            $iInfluence = 1;
        }
        
        $sType = $aParam['type'];
        $iIndex = 0;

        foreach($aCategories as $sVal) {
            if($sVal['name_code'] != $sType) {
                continue;
            }

            $iIndex = 1;
            break;
        }

        if(!$iIndex) {
            // set default value
            $sType = $aCategories[0]['name_code'];
        }

        if(mb_strlen($sName) < 1 || mb_strlen($sName) > 225) {
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225),
                'data' => array(
                    'list' => $aParam,
                    'influence' => $aInfluence,
                    'categories' => $aCategories,
                    'status' => $iStatus,
                ),
            );
        } 
        if(mb_strlen($sNameCode) < 1 || mb_strlen($sNameCode) > 225) {            
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225),
                'data' => array(
                    'list' => $aParam,
                    'influence' => $aInfluence,
                    'categories' => $aCategories,
                    'status' => $iStatus,
                ),
            );
        } 

        // kiểm tra id
        if($iId > 0) {
            // lấy đề tài stt và tên miền stt
            $rows = $this->database()->select('count(id)')
                ->from(Core::getT('image_extend'))
                ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId)
                ->execute('getField');

            if($rows == 0) {
                return array(
                    'status' => 'error',
                    'message' => Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu'),
                    'data' => array(
                        'list' => $aParam,
                        'influence' => $aInfluence,
                        'categories' => $aCategories,
                        'status' => $iStatus,
                    ),
                );
            } 
        }

        $sSql = '';
        if($iId > 0) {
            $sSql = ' AND id != '.$iId;   
        } 

        // kiểm tra mã tên đã tồn tại chưa
        $rows = $this->database()->select('count(id)')
            ->from(Core::getT('image_extend'))
            ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND name_code = "'.addslashes($sNameCode).'"'.$sSql)
            ->execute('getField');

        if(!empty($rows)) {
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_ten-da-ton-tai')),
                'data' => array(
                    'list' => $aParam,
                    'influence' => $aInfluence,
                    'categories' => $aCategories,
                    'status' => $iStatus,
                ),
            );
        } 

        $aUpdate['name'] = $sName;
        $aUpdate['name_code'] = $sNameCode;
        $aUpdate['status'] = $iStatus;
        $aUpdate['type'] = $sType;
        $aUpdate['influence_to'] = $iInfluence;
        
        if($iId > 0) {
            $this->database()->update(
                Core::getT('image_extend'),
                $aUpdate,
                'domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId
            );
        } else {
            $aUpdate['domain_id'] = Core::getDomainId();
            $iId = $this->database()->insert(Core::getT('image_extend'), $aUpdate);
        }
        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array(
            'action' => 'create_imageextend'.'-'.$iId,
            'content' => 'phpinfo',
        ));
        // end
        //re-direct page
        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/';
        header('Location: '.$sDir);

        return array(
            'status' => 'success',
            'data' => $aParam['id'],
        );
    }

    public function getImageExtendGeneral($aParam = array())
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

        //Kiểm tra giá trị truyền vào
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 1;
        $sPaginatePath = '';
        
        //Xử lý giá trị truyền vào
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 50) {
            $iPageSize = 1;
        }

        
        //Khai báo biến
        $iCnt = 0;
        $aData = array();
        $sLinkFull = '/imageextend/general/?&page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/imageextend/general/?&page_size='.$iPageSize;  //Đường dẫn phân trang
        
        $iSort = isset($aParam['sort']) ? $aParam['sort'] : '';

        if ($iSort == 1) {
            $sOrder = 'id DESC';
        }
        else if ($iSort == 3) {
            $sOrder = 'name DESC';
        } else {
            $sOrder = 'id ASC';
        }

        if (!empty($iSort)) {
            $sPagination .= '&sort='.$iSort;
            $sLinkFull .= '&sort='.$iSort;
        }
        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sort');
        //Tính tổng dữ liệu
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId())
            ->execute('getField');

        //Xử lý
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('image_extend_general'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId())
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aData[] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                    'status' => $aRow['status'],
                );
            }
        }
        
        // $sDir = $_SERVER['REQUEST_URI'];
        // $aTmps = explode('/', $sDir, 3);
        // $sDir = '/'.$aTmps[1].'/';
        
        // $sPagination = $sDir.'general/?'.$sPagination;
        
        return array(
            'status' => 'sucess',
            'data' => array(
                'page' => $iPage,
                'page_size' => $iPageSize,
                'total' => $iCnt,
                'list' => $aData,
                'paginate_path' => $sPagination,
            ),
            
        );
    }

    public function initCreateGeneral($aParam = array())
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

        $iId = isset($aParam['id']) ? $aParam['id'] : -1;

        if ($iId < 1) {
            return array(
                'status' => 'error',
                'messgae' => 'Lỗi dữ liệu',
            );
        }
        
        $aData = $this->database()->select('id, name, status')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId)
            ->execute('getRow');

        if (!isset($aData['id'])) {
            return array(
                'status' => 'error',
                'messgae' => 'Nội dung mở rộng tổng không tòn tại',
            );
        }
        
        return array(
            'status' => 'success',
            'data' => $aData,
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

        //Kiểm tra giá trị truyền vào
        $aData = array();
        $aData['id'] = isset($aParam['id']) ? $aParam['id'] : -1;
        $aData['name'] = isset($aParam['name']) ? $aParam['name'] : '';
        $aData['status'] = isset($aParam['status']) ? $aParam['status'] : 0;
        
        if (strlen($aData['name']) < 1 || strlen($aData['name']) > 100) {
            return array(
                'status' => 'error',
                'message' => 'Tên phải có ít nhất 1 ký tự và tối đa 100 ký tự',
                'data' => $aParam,
            );
        }

        $aData['name_code'] = Core::getService('core.tools')->encodeUrl($aData['name']);
        $aData['name_code'] = Core::getLib('input')->removeXSS(trim($aData['name_code']));
        $sConds = '';

        if ($aData['id'] > 0) {
            $aOld = $this->database()->select('id')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND id ='.$aData['id'].' AND domain_id ='.Core::getDomainId())
            ->execute('getRow');
            if (!isset($aOld['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Mở rộng tổng không tồn tại',
                    'data' => $aParam,
                );
            }
            $sConds .= ' AND id !='.$aData['id'];
        }

        $iExist = $this->database()->select('count(*)')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND name_code =\''.$aData['name_code'].'\' AND domain_id ='.Core::getDomainId().$sConds)
            ->execute('getField');

        if ($iExist > 0) {
            return array(
                'status' => 'error',
                'message' => 'Tên này đã được sử dụng',
                'data' => $aParam,
            );
        }

        $aUpdate = array(
            'name' => $aData['name'],
            'name_code' => $aData['name_code'],
            'status' => $aData['status'],
        );

        if ($aData['id'] > 0) {
            $this->database()->update(Core::getT('image_extend_general'), $aUpdate, 'id ='.$aData['id']);
        } else {
            $aUpdate['create_time'] = CORE_TIME;
            $aUpdate['domain_id'] = Core::getDomainId();
            $aData['id'] = $this->database()->insert(Core::getT('image_extend_general'), $aUpdate);
        }
        
        //chuyển trang
        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/general/';
        header('Location: '.$sDir);
        
        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    public function getGeneralById($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        if ($iId < 1) {
            return array(
                'status' => 'error',
                'messgae' => 'Lỗi dữ liệu',
            );
        }
        
        $aData = $this->database()->select('id, name, status')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId)
            ->execute('getRow');
        if (!isset($aData['id'])) {
            return array(
                'status' => 'error',
                'messgae' => 'Nội dung mở rộng tổng không tòn tại',
            );
        }
        
        return array(
            'status' => 'success',
            'data' => $aData
        );
    }
    
    public function createGeneral2($aParam = array())
    {
        $aData = array();
        $aData['id'] = isset($aParam['id']) ? $aParam['id'] : -1;
        $aData['name'] = isset($aParam['name']) ? $aParam['name'] : '';
        $aData['status'] = isset($aParam['status']) ? $aParam['status'] : 0;
        
        if (strlen($aData['name']) < 1 || strlen($aData['name']) > 100) {
            return array(
                'status' => 'error',
                'message' => 'Tên phải có ít nhất 1 ký tự và tối đa 100 ký tự',
            );
        }
        $aData['name_code'] = Core::getService('core.tools')->encodeUrl($aData['name']);
        $aData['name_code'] = Core::getLib('input')->removeXSS(trim($aData['name_code']));
        $sConds = '';
        if ($aData['id'] > 0) {
            $aOld = $this->database()->select('id')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND id ='.$aData['id'].' AND domain_id ='.Core::getDomainId())
            ->execute('getRow');
            if (!isset($aOld['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Mở rộng tổng không tồn tại',
                );
            }
            $sConds .= ' AND id !='.$aData['id'];
        }
        $iExist = $this->database()->select('count(*)')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND name_code =\''.$aData['name_code'].'\' AND domain_id ='.Core::getDomainId().$sConds)
            ->execute('getField');
        if ($iExist > 0) {
            return array(
                'status' => 'error',
                'message' => 'Tên này đã được sử dụng',
            );
        }
        $aUpdate = array(
            'name' => $aData['name'],
            'name_code' => $aData['name_code'],
            'status' => $aData['status'],
        );
        if ($aData['id'] > 0) {
            $this->database()->update(Core::getT('image_extend_general'), $aUpdate, 'id ='.$aData['id']);
        }
        else {
            $aUpdate['create_time'] = CORE_TIME;
            $aUpdate['domain_id'] = Core::getDomainId();
            $aData['id'] = $this->database()->insert(Core::getT('image_extend_general'), $aUpdate);
        }
        
        //chuyen trang
        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/general/';
        header('Location: '.$sDir);
        
        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }
    
    public function getExtendGeneral($aParam = array())
    {
        
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 15;
        $sPaginatePath = '';
        $sOrder = 'id DESC';
        
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 50) {
            $iPageSize = 15;
        }
        
        $sPaginatePath .= '&page_size='.$iPageSize;
        $iCnt = 0;
        $aData = array();
        
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('image_extend_general'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId())
            ->execute('getField');
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('image_extend_general'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId())
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aData[] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                    'status' => $aRow['status'],
                );
            }
        }
        
        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/';
        
        $sPaginatePath = $sDir.'general/?'.$sPaginatePath;
        
        return array(
            'status' => 'sucess',
            'page' => $iPage,
            'page_size' => $iPageSize,
            'total' => $iCnt,
            'data' => $aData,
            'paginate_path' => $sPaginatePath,
        );
    }
}
?>
