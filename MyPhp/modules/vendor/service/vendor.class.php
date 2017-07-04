<?php
class Vendor_Service_Vendor extends Service 
{
    public function __construct()
    {
        $this->_sTable = Core::getT('vendor');
    }
    //**************************************************************
    //**************************************************************
    public function getVendor($aParam = array())
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
        $sConds = 'status != 2 AND domain_id = '.Core::getDomainId().$query; //Điều kiện select

        $sLinkFull = '/vendor/?page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/vendor/?page_size='.$iPageSize;  //Đường dẫn phân trang

        //Tìm kiếm 
        $sKeyword = isset($aParam['q']) ? $aParam['q'] : '';
        if (!empty($sKeyword)) {
            $sKeyword = urldecode($sKeyword);
            $sKeyword = trim(Core::getLib('input')->removeXSS($sKeyword));
            if (mb_strlen($sKeyword) > 100) {
                $sKeyword = '';
            }
        }
        if (!empty($sKeyword)) {
            $sConds .= ' AND (name LIKE "%'.$this->database()->escape($sKeyword).'%" OR code LIKE "%'.$this->database()->escape($sKeyword).'%")';
            $sPagination .= '&q='.urlencode($sKeyword);
            $sLinkFull .= '&q='.urlencode($sKeyword);
        }

        //Sắp xếp dữ liệu
        $sOrder = '';
        $iSort = isset($aParam['order']) ? $aParam['order'] : '';
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
            ->from(Core::getT('vendor'))
            ->where($sConds)
            ->execute('getField');

        //Lấy danh sách nhà cung cấp
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('vendor'))
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

                if(!in_array($aRow["custom_label_id"], $aListType)) { //List type
                    $aListType[] = $aRow["custom_label_sid"];
                }                        

                $aData[] = array(
                    'id' => $aRow["id"],
                    'name' => $aRow["name"],
                    'status' => $aRow["status"],
                    'status_text' => $aRow["status_text"],
                    'list_type' => $aListType,
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

        $aAreas = array(
            'city' => array(),
            'district' => array(),
            'ward' => array(),
        );

        $iStatus = 1;
        if($iId > 0) {
            // lấy đề tài stt và tên miền stt
            $aRows = $this->database()->select('id, name, name_code, status, content, image_path, found_time, open_hour, close_hour, area_id, address')
                ->from(Core::getT('vendor'))
                ->where('id = '.$iId.' AND domain_id ='.Core::getDomainId().' AND status != 2')
                ->execute('getRow');
            
            if (isset($aRows['id'])) {
                $aData['name'] = $aRows['name'];
                $aData['name_code'] = $aRows['name_code'];
                $aData['description'] = $aRows['content'];
                $aData['status'] = $aRows['status'];
                $aData['image_path'] = $aRows['image_path'];
                if ($aRows['found_time'] > 0) {
                    $aData['found_time'] = date('d.m.Y', $aRows['found_time']);
                } else {
                    $aData['found_time'] = '';
                }
                
                $aData['open_hour'] = $aRows['open_hour'];
                $aData['close_hour'] = $aRows['close_hour'];
                $iFlag = false;
                if ($aRows['area_id'] >0) {
                    //Tách địa chỉ
                    $aAddress = Core::getService('core.area')->getAddressByWard($aRows['area_id']);
                    $sTmp = Core::getService('core.area')->parse($aRows['area_id']);
                    
                    $aData['street'] = str_replace(', '.$sTmp, '', $aRows['address']);
                    
                    if (!empty($aAddress)) {
                        $aData['city'] = $aAddress['city']['id'];
                        $aData['district'] = $aAddress['district']['id'];
                        $aData['ward'] = $aAddress['ward']['id'];
                        //Lấy danh sách tinh thành
                        $aRows = $this->database()->select('*')
                            ->from(Core::getT('area'))
                            ->where('status = 1 AND parent_id ='.$aAddress['city']['parent_id'])
                            ->execute('getRows');
                        foreach ($aRows as $aRow) {
                            $aAreas['city'][] = array(
                                'id' => $aRow['id'],
                                'name' => $aRow['name'],
                            );
                        }
                        //Lấy danh sách quận huyện
                        $aRows = $this->database()->select('*')
                            ->from(Core::getT('area'))
                            ->where('status = 1 AND parent_id ='.$aAddress['city']['id'])
                            ->execute('getRows');
                        foreach ($aRows as $aRow) {
                            $aAreas['district'][] = array(
                                'id' => $aRow['id'],
                                'name' => $aRow['name'],
                            );
                        }
                        
                        //Lấy danh sách phường xã
                        $aRows = $this->database()->select('*')
                            ->from(Core::getT('area'))
                            ->where('status = 1 AND parent_id ='.$aAddress['district']['id'])
                            ->execute('getRows');
                        foreach ($aRows as $aRow) {
                            $aAreas['ward'][] = array(
                                'id' => $aRow['id'],
                                'name' => $aRow['name'],
                            );
                        }
                    }
                } else {
                    $aData['street'] = '';
                }
                
                // lấy giá trị
                $aRows = $this->database()->select('id, user_id, permission, status')
                    ->from(Core::getT('vendor_user'))
                    ->where('vendor_id ='.$iId.' AND status != 2')
                    ->order('id DESC')
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    $aData['val_id'][] = $aRow['id'];
                    $aData['val_user_id'][] = $aRow['user_id'];
                    $aData['val_permission'][] = $aRow['permission'];
                    $aData['val_status'][] = $aRow['status'];
                }
                //lấy thông tin user
                if (!empty($aData['val_user_id'])) {
                    $aMappingUser = Core::getService('user.community')->getUserInfo(array(
                        'user_list' => $aData['val_user_id'],
                    ));
                }
                //maping thành 1 array user_name (đúng theo thứ tự val_user_id)
                foreach ($aData['val_user_id'] as $sKey => $sVal) {
                    $aData['val_user_name'][$sKey] = $aMappingUser[$sVal]['username'];
                }

            }
            else {
                $iStatus = 2;
            }
        }
        else
        {
            $iStatus = 1;
        }
        
        //Lấy danh sách khu vục mặc định
        if (empty($aAreas['city'])) {
            $aVn = $this->database()->select('id')
                ->from(Core::getT('area'))
                ->where('code = \'vn\'')
                ->execute('getRow');
            if (isset($aVn['id'])) {
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('area'))
                    ->where('status = 1 AND parent_id ='.$aVn['id'])
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aAreas['city'][] = array(
                        'id' => $aRow['id'],
                        'name' => $aRow['name'],
                    );
                }
            }
        }
        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aData,
                'areas' => $aAreas,
                'status' => $iStatus,
            ),
        );
    }

    public function Create($aParam = array())
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

        $aAreas = array(
            'city' => array(),
            'district' => array(),
            'ward' => array(),
        );

        //Kiểm tra giá trị truyền vào   
        $sVal = $aParam['name'];        
        $sVal = Core::getLib('input')->removeDuplicate(array('text' => $sVal));
        $sVal = str_replace('#', '', $sVal);
        $sVal = str_replace('.', '', $sVal);
        
        $sVal = Core::getLib('input')->removeXSS(stripslashes(trim($sVal)));
        
        $sVal = Core::getLib('input')->removeBreakLine(array('text' => $sVal));
        $aParam['name'] = $sVal;
        
        $aParam['name_code'] = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($aParam['name_code'])));
        
        $aParam['description'] = isset($aParam['description']) ? $aParam['description'] : '';
        if (!empty($aParam['description'])) {
            $aParam['description'] = stripslashes(Core::getLib('input')->removeBreakLine(array('text' => $aParam['description'])));
        }
        
        $aParam['image_path'] = isset($aParam['image_path']) ? $aParam['image_path'] : '';
        $aParam['hotline'] = isset($aParam['hotline']) ? $aParam['hotline'] : '';
        $aParam['ward'] = isset($aParam['ward']) ? $aParam['ward'] : -1;
        $aParam['street'] = isset($aParam['street']) ? $aParam['street'] : '';

        //Fill areas when error
        $aAddress = Core::getService('core.area')->getAddressByWard($aParam['ward']);
        
        if (!empty($aAddress)) {
            //Lấy danh sách tinh thành
            $aRows = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where('status = 1 AND parent_id ='.$aAddress['city']['parent_id'])
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aAreas['city'][] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                );
            }
            //Lấy danh sách quận huyện
            $aRows = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where('status = 1 AND parent_id ='.$aAddress['city']['id'])
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aAreas['district'][] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                );
            }
            
            //Lấy danh sách phường xã
            $aRows = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where('status = 1 AND parent_id ='.$aAddress['district']['id'])
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aAreas['ward'][] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                );
            }
        }

        $aParam['status'] = $aParam['status']*1;
        if($aParam['status'] != 1) {
            $aParam['status'] = 0;
        } 
        //extend
        $bIsExtend = false;
        if (isset($aParam['extend']) && $aParam['extend'] == 'on') {
            $bIsExtend = true;
        }
        
        if ($bIsExtend) {
            $aParam['found_time'] = isset($aParam['found_time']) ? $aParam['found_time'] : '';
            $aParam['open_hour'] = isset($aParam['open_hour']) ? $aParam['open_hour'] : 0;
            $aParam['close_hour'] = isset($aParam['close_hour']) ? $aParam['close_hour'] : 0;
            
            //chuyển về thời gian GMT
            if (!empty($aParam['found_time'])) {
                $aParam['found'] = strtotime($aParam['found_time']);
                if ($aParam['found'] === false) {
                    $aParam['found'] = 0;
                } else {
                    //$aParam['found'] = Core::getLib('date')->convertToGmt($aParam['found_time']);
                }
            }

            if ($aParam['open_hour'] < 1) {
                $aParam['open_hour'] = 0;
            }

            if ($aParam['close_hour'] < 1) {
                $aParam['close_hour'] = 0;
            }
        }            
        
        $iCnt = 0;

        foreach($aParam['val_user_id'] as $iIndex => $sVal) {
            $sVal = Core::getLib('input')->removeDuplicate(array('text' => $sVal));
            $sVal = str_replace('#', '', $sVal);
            //$sVal = str_replace('.', '', $sVal);
            
            $sVal = Core::getLib('input')->removeXSS(stripslashes(trim($sVal)));
            if(empty($sVal)){
               continue; 
            } 
            
            $sVal = Core::getLib('input')->removeBreakLine(array('text' => $sVal));
            
            if(empty($sVal)) {
                continue;
            }
            
            $aParam['val_user_id'][$iCnt] = $sVal;
            
            $iTmp = $aParam["val_id"][$iIndex]*1;
            if($iTmp < 1) {
                $iTmp = 0;
            }

            $aParam['val_id'][$iCnt] = $iTmp;
            
            $iCnt++;
        }
        // global
        if($iId < 1) {
            $aParam['action'] = 'create';
        } else {
            $aParam['action'] = 'update';
        }

        $aParam['id'] = $iId;
        $aParam['type'] = $sType;

        if(mb_strlen($aParam['name']) < 1 || mb_strlen($aParam['name']) > 225) {
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225).'(1)',
                'data' => array(
                    'list' => $aParam,
                    'areas' => $aAreas,
                ),
            );
        }

        if(mb_strlen($aParam['name_code']) < 1 || mb_strlen($aParam['name_code']) > 225) {
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225).'(2)',
                'data' => array(
                    'list' => $aParam,
                    'areas' => $aAreas,
                ),
            );
        } 

        if(!empty($aParam['image_path']) && (mb_strlen($aParam['image_path'])>225)){
            return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_duong-dan-hinh'), 1, 225),
                'data' => array(
                    'list' => $aParam,
                    'areas' => $aAreas,
                ),
            );
        } 
        
        if ($aParam['ward'] < 1 || empty($aParam['street'])) {
            return array(
                'status' => 'error',
                'message' => 'Chưa nhập đầy đủ thông tin địa chỉ',
                'data' => array(
                    'list' => $aParam,
                    'areas' => $aAreas,
                ),
            );
        } else {            
            $aParam['address'] = $aParam['street']. ', '. Core::getService('core.area')->parse($aParam['ward']);
        }

        for($iIndex = 0; $iIndex < count($aParam['val_permission']); $iIndex++) {
            if(mb_strlen($aParam['val_permission'][$iIndex]) < 1 || mb_strlen($aParam['val_permission'][$iIndex]) > 225) {
                return array(
                    'status' => 'error',
                    'message' => sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225).'(3)',
                    'data' => array(
                        'list' => $aParam,
                    'areas' => $aAreas,
                    ),
                );
            }

            if(mb_strlen($aParam['val_user_id'][$iIndex]) < 1 || mb_strlen($aParam['val_user_id'][$iIndex]) > 225){
                return array(
                    'status' => 'error',
                    'message' => sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225).'(4)',
                    'data' => array(
                        'list' => $aParam,
                        'areas' => $aAreas,
                    ),
                );
            }
        }

        if($iId > 0) // kiểm tra id khi update
        {
            // lấy đề tài stt và tên miền stt
            $aRows = $this->database()->select('count(id)')
                ->from(Core::getT('vendor'))
                ->where('id ='.$iId.' AND domain_id ='.Core::getDomainId().' AND status != 2')
                ->execute('getField');
            
            if($aRows == 0) {
                return array(
                    'status' => 'error',
                    'message' => sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu')).'(5)',
                    'data' => array(
                        'list' => $aParam,
                        'areas' => $aAreas,
                    ),
                );
            }
        }

        $sSql = '';

        if($iId > 0) {
            $sSql = ' AND id != '.$iId;
        }
        // kiểm tra mã tên đã tồn tại chưa
        $aRows = $this->database()->select('count(id)')
            ->from(Core::getT('vendor'))
            ->where('name_code =\''.addslashes($aParam['name_code']).'\' AND domain_id ='.Core::getDomainId().' AND status != 2'.$sSql)
            ->execute('getField');

        if(!empty($aRows)) {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_ten-da-ton-tai').'(6)',
                'data' => array(
                    'list' => $aParam,                    
                    'areas' => $aAreas,
                ),
            );
        }

        // kiểm tra trích lọc dữ liệu        
        for($iIndex = 0; $iIndex < count($aParam['val_permission']); $iIndex++) {// lọc các phần tử có tên trùng nhau
            for($iIndex2 = $iIndex + 1; $iIndex2 < count($aParam['val_permission']); $iIndex2++) {
                if($aParam['val_permission'][$iIndex] == $aParam['val_permission'][$iIndex2]){
                   unset($aParam['val_permission'][$iIndex2]); 
                } 
            }
        }        
        
        for($iIndex = 0; $iIndex < count($aParam['val_user_id']); $iIndex++){ // lọc các phần tử có mã tên trùng nhau
            for($iIndex2 = $iIndex + 1; $iIndex2 < count($aParam['val_user_id']); $iIndex2++) {
                if($aParam['val_user_id'][$iIndex] == $aParam['val_user_id'][$iIndex2]){
                    unset($aParam['val_permission'][$iIndex2]);
                }
            }
        }
        /*
             check các ô stt của giá trị xem có tồn tại và đúng với nha_cung_cap_stt. nếu không cảnh báo lỗi.
        */

        if(!empty($aParam['val_id'])) {
            // tách giá trị dãy để nạp
            $aIdList = array();

            $aRows = $this->database()->select('id')
                ->from(Core::getT('vendor_user'))
                ->where('vendor_id ='.$iId.' AND status != 2')
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                $aIdList[] = $aRow['id'];
            }
            
            for($iIndex = 0; $iIndex < count($aParam['val_id']); $iIndex++) {
                if($aParam['val_id'][$iIndex] < 1) {
                    continue;
                }
                
                if(!in_array($aParam['val_id'][$iIndex], $aIdList)) {
                    $aParam['val_id'][$iIndex] = 0;
                }                 
            }
            unset($aIdList);
        }

        //Xử lý giá trị truyền vào
        $aInsert = array();

        $aInsert = array(
            'name' =>  $aParam['name'],
            'name_code' => $aParam['name_code'],
            'content' => $aParam['description'],
            'path' => $aParam['name_code'],
            'detail_path' => '/'.$aParam['name_code'].'/',
            'image_path' => $aParam['image_path'],
            'hotline' => $aParam['hotline'],
            'address' => $aParam['address'],
            'area_id' => $aParam['ward'],
            'status' => $aParam['status'],
        );

        if ($bIsExtend) {
            $aInsert['found_time'] = $aParam['found'];
            $aInsert['open_hour'] = $aParam['open_hour'];
            $aInsert['close_hour'] = $aParam['close_hour'];
        }
        if($iId > 0) { //Update dữ liệu                
            
            $this->database()->update(
                Core::getT('vendor'),
                $aInsert,
                'domain_id ='.Core::getDomainId().' AND id ='.$iId
            );
            //update status for category_display
            $this->database()->update(
                Core::getT('category_display'),
                array(
                    'status' => $aParam['status'],
                ),
                'object_type = 2 AND object_id ='.$iId.' AND status != 2'
            );
            // update clear cache for homepage & category
            $this->database()->update(
                Core::getT('category_display'),
                array(
                    'status' => $aParam['status'],
                ),
                'object_type = 1 AND item_type = 2 AND item_id ='.$iId.' AND status != 2'
            );
        } else { //Insert dữ liệu
            //$aInsert['ngay_thang_tao'] = CORE_TIME;
            $aInsert['domain_id'] = Core::getDomainId();
            $iId =$this->database()->insert(Core::getT('vendor'), $aInsert);
            
            if ($iId > 0) {
                //cập nhật parent, child
                $aUpdate = array(
                    'parent_id' => -1,
                    'parent_list' => $iId.','.'-1',
                    'child_list' => $iId.','.'-1',
                );
                $this->database()->update(Core::getT('vendor'), $aUpdate, 'id ='.$iId);
                
                //add to category_display
                //$aInsertExtra = array(
//                                'object_id' => $iId,
//                                'object_type' => 2,
//                                'total_dislay_item' => 10,
//                                'item_id' => -1,
//                                'item_type' => 1,
//                                'status' => $aParam['status'],
//                                'domain_id' => Core::getDomainId(),
//                            );
//                            $this->database()->insert(Core::getT('category_display'), $aInsertExtra);
            }
            $aParam['id'] = $iId;
           
        }
        //clear cache
        Core::getService('core')->removeCache();
        
        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array('action' => $sType.'-'.$iId,'content' => 'phpinfo',));
        // end
        $iStatus=3;
        // xử lý phần thêm giá trị
        if($iStatus == 3) {
            /*
             Kiểm tra trong bảng nha_cung_cap_tv xem dòng nào dư hay thiếu. Vì dữ liệu được liên kết với các bảng trong bài viết
             B1. Lấy stt để quét dò trong db.Nếu có cập nhật các cột còn lại theo stt đó và lưu stt tồn tại lại. Nếu ko bỏ qua.
             B2. Nếu có dòng ko thuộc B1, check db xem có bản rel nào từ nha_cung_cap_de_tai và nha_cung_cap_bai_viet mốc về ko.
                B2.1 Nếu ko. Tiến hành xóa
                B2.2 Nếu có. Hiện cảnh báo cho người quản trị.
                B2.2.1 Nếu quản trị xóa. Xóa các bản rel
                B2.2.2 Nếu quản trị ko xóa. Vẫn giữ nguyên các dòng đó.
             B3. Thêm những dùng còn lại vào.
            */
            // kiểm tra từng dòng và cập nhật dữ liệu nếu tồn tại
            if($iId > 0) { //Update dữ liệu
                // lấy tổng trích lọc giá trị
                $aRows = $this->database()->select('id')
                    ->from(Core::getT('vendor_user'))
                    ->where('vendor_id ='.$iId.' AND status !=2')
                    ->execute('getRows');
                
                foreach ($aRows as $aRows) {
                    $aVendor[$aRow['id']] = true;
                }
                foreach($aParam['val_id'] as $iIndex => $sVal) {
                    if($aParam['val_id'][$iIndex] > 0 && $aVendor[$aParam['val_id'][$iIndex]]) {
                        $this->database()->update(
                            Core::getT('vendor_user'),
                            array(
                                //'permission' => $aParam['val_permission'][$i],
                                'user_id' => $aParam['val_user_id'][$iIndex],
                                //'status' => $aParam['val_status'][$i],
                            ),
                            'id ='.$aParam['val_id'][$iIndex]
                        );
                       
                        unset($aVendor[$aParam['val_id'][$iIndex]]);
                    }
                }
                // tiến hành xóa các dòng ko tồn tại
                if(!empty($aVendor)) {
                    $sTmp = '';
                    foreach($aVendor as $sKey => $sVal) {
                        $sTmp .= $sKey.',';
                    }
                    $sTmp = rtrim($sTmp, ',');
                    $this->database()->update(Core::getT('vendor_user'), array('status' => 2), 'id IN ('.$sTmp.')');
                }
            }
            // thêm các dòng mới
            foreach($aParam['val_id'] as $iIndex => $sVal) {
                if($aParam['val_id'][$iIndex] == 0) {
                    $aParam['val_id'][$iIndex] = $this->database()->insert(
                        Core::getT('vendor_user'),
                        array(
                            'vendor_id' => $iId,
                            'user_id' => $aParam['val_user_id'][$iIndex],
                            //'permission' => $aParam['val_permission'][$i],
                            //'status' => $aParam['val_status'][$i],
                            'domain_id' => Core::getDomainId(),
                        )
                    );                        
                }
            }
            /* Liên kết kho
            if(!empty($config['erpLink'])) chayNgam($config['erpLink'], $aParam[;
            */
            
            //re-direct page
            $sDir = $_SERVER['REQUEST_URI'];
            $aTmps = explode('/', $sDir, 3);
            $sDir = '/'.$aTmps[1].'/';
            header('Location: '.$sDir);
        }
        $iStatus = 1;

        return array(
            'status' => 'success',
            'data' => $aParam['id'],
        );
    }

    public function getVendorStore($aParam = array())
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
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 1;

        if ($iVendorId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Không có thông tin nhà cung cấp'
            );
        }

        $aVendor = $this->database()->select('id')
            ->from(Core::getT('vendor'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iVendorId)
            ->execute('getRow');

        if (!isset($aVendor['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhà cung câp không hợp lệ'
            );
        }

        //Xử lý giá trị truyền vào
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 1;
        }

        //Khai báo biến
        $iCnt = 0;
        $aData = array();
        $sConds = 'status != 2 AND domain_id = '.Core::getDomainId().' AND vendor_id ='.$iVendorId; //Điều kiện select

        $sLinkFull = '/vendor/store/?&vendor_id='.$iVendorId.'&page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/vendor/store/?&vendor_id='.$iVendorId.'&page_size='.$iPageSize;  //Đường dẫn phân trang

        //Sắp xếp dữ liệu
        $sOrder = '';
        $iSort = isset($aParam['order']) ? $aParam['order'] : '';
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
            ->from(Core::getT('vendor_store'))
            ->where($sConds)
            ->execute('getField');

        //Lấy danh sách kho hàng của nhà cung cấp
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('vendor_store'))
                ->where($sConds)
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');

            foreach ($aRows as $aRow) {
                if ($aRow['create_time'] > 0) {
                    $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
                    $aRow['create_time_txt'] = date('H:i d/m/Y', $aRow['create_time']);
                } else {
                    $aRow['create_time_txt'] = '';
                }

                $aData[] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                    'address' => $aRow['address'],
                    'lat' => $aRow['latittude'],
                    'lng' => $aRow['longtitude'],
                    'status' => $aRow['status'],
                    'create_time_txt' => $aRow['create_time_txt'],
                    'vendor_id' => $aRow['vendor_id'],
                );
            }
        }

        return array(
            'status' => 'success',
            'data' => array(
                'total' => $iCnt,
                'page' => $iPage,
                'page_size' => $iPageSize,
                'list' => $aData,
                'link_sort' => $sLinkSort,
            ),
            
        );
    }

    public function initCreateStore($aParam = array())
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
        $iId = isset($aParam['id']) ? $aParam['id'] : -1; //id của store chứa hàng
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;

        if ($iId < 1 && $iVendorId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Thiếu thông tin đầu vào',
            );
        }

        //Khai báo biến
        $aData = array();
        $aVendor = array();

        //Xử lý
        if ($iId < 1) {
            $aVendor = $this->database()->select('id, name, content, area_id, address')
                ->from(Core::getT('vendor'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id='.$iVendorId)
                ->execute('getRow');

            if (!isset($aVendor['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Nhà cung cấp không hợp lệ',
                );
            }
        } else {
            //Lấy thông tin kho hàng và nhà cung cấp theo store đã lưu
            $aStore = $this->database()->select('*')
                ->from(Core::getT('vendor_store'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId)
                ->execute('getRow');
            if (!isset($aStore['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Kho hàng không hợp lệ',
                );
            }

            $aVendor = $this->database()->select('id, name, content, area_id, address')
                ->from(Core::getT('vendor'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id='.$aStore['vendor_id'])
                ->execute('getRow');

            $aData['id'] = $iId;
            $aData['name'] = $aStore['name'];
            $aData['address'] = $aStore['address'];
            $aData['lat'] = $aStore['latitude'];
            $aData['lng'] = $aStore['longtitude'];
        }

        $aData['vendor_id'] = $aVendor['id'];
        $aData['vendor_name'] = $aVendor['name'];

        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    public function CreateStore($aParam = array())
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
        $aData['vendor'] = array();
        $iId = isset($aParam['id']) ? $aParam['id'] : -1; //id của store chứa hàng
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;

        if ($iVendorId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Không có thông tin nhà cung cấp',
            );
        }

        $aVendor = $this->database()->select('id')
            ->from(Core::getT('vendor'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id='.$iVendorId)
            ->execute('getRow');

        if (!isset($aVendor['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhà cung cấp không hợp lệ',
            );
        }

        $sName = isset($aParam['name']) ? $aParam['name'] : '';
        $sAddress = isset($aParam['address']) ? $aParam['address'] : '';
        $sLat = isset($aParam['lat']) ? $aParam['lat'] : '';
        $sLng = isset($aParam['lng']) ? $aParam['lng'] : '';
        
        if (strlen($sName) < 1 || strlen($sName) > 255) {
            return array(
                'status' => 'error',
                'message' => 'Tên kho hàng không hợp lệ',
                'data' => $aParam,
            );
        } else if (strlen($sAddress) < 1 || strlen($sAddress) > 255) {
            return array(
                'status' => 'error',
                'message' => 'Địa chỉ kho hàng không hợp lệ',
                'data' => $aParam,
            );
        } else if (empty($sLat) || empty($sLng)) {
            return array(
                'status' => 'error',
                'message' => 'Vị trí kho hàng không hợp lệ',
                'data' => $aParam,
            );
        }

        $aInsert = array(
            'name' => $sName,
            'latitude' => $sLat,
            'longtitude' => $sLng,
            'address' => $sAddress,
        );

        if ($iId > 0) {

            $this->database()->update(Core::getT('vendor_store'), $aInsert, 'id ='.$iId);
        } else {
            $aInsert['vendor_id'] = $iVendorId;
            $aInsert['create_time'] = CORE_TIME;
            $aInsert['status'] = 1;
            $aInsert['domain_id'] = Core::getDomainId();
            $iId = $this->database()->insert(Core::getT('vendor_store'), $aInsert);
        }

        return array(
            'status' => 'success',
            'data' => array(
                'id' => $iId,
            ),
        );
    }
    //**************************************************************
    //**************************************************************

    /**
    * get list supplier in domain
    * save to cache to use again if necessary
    * @return array()
    */
    public function get($aParam = array())
    {
        if(!Core::getDomainId())
            return array();
        // get search param

        $sParam = '';
        $sCond = 'status = 1';
        $sCond .= ' AND domain_id ='. Core::getDomainId();
        if (isset($aParam['area']) && !empty($aParam['area'])) {
            $sParam .= '&area='.$aParam['area'];
        }
        // for default, only get supplier same as a store.
        if (isset($aParam['is_sell']) && !$aParam['is_sell']) {
            $sCond .= ' AND is_sell = 0';
            $sParam .= '&is_sell=0';
        }
        else {
            $sCond .= ' AND is_sell = 1';
            $sParam .= '&is_sell=1';
        }

        if(isset($aParam['get_all']) && $aParam['get_all'])
            $iLimit = 5000;
        else
            $iLimit = (isset($aParam['limit'])) ? $aParam['limit'] : 20;
        $sParam .= '&limit='.$iLimit;

        //build cache id from param.
        $sCacheId = $this->cache()->set('supplier|'. Core::getDomainId().'|'. md5($sParam));
        $aSuppliers = $this->cache()->get($sCacheId);
        if(!$aSuppliers) {
            // build a condition for search with location
            if (isset($aParam['area']) && !empty($aParam['area'])) {
                // check location exist or not.
                $aLocation = $this->database()->select('id, child_list')
                    ->from($this->_sTable)
                    ->where('status = 1 AND domain_id ='.Core::getDomainId(). ' AND name_code = \''. $this->database()->escape($aParam['area']).'\'')
                    ->execute('getRow');
                if (!isset($aLocation['id'])) {
                    return array(); // no location found, so wont have supplier in this search.
                }
                // format child list.
                $sChildList = '';
                $aTmps = explode(',', $aLocation['child_list']);
                foreach ($aTmps as $sValue) {
                    $sValue *= 1;
                    if($sValue == 0)
                        continue;
                    $sChildList .= $sValue.',';
                }
                if(!empty($sChildList))
                    $sChildList = substr($sChildList, 0, -1);
                if(empty($sChildList))
                    return array(); // not found child item.
                // build condition.
                $sCond .= 'id IN ('. $sChildList.')';
            }
            // do not have data in cache, we will query to database and get list of supplier.
            // then save them to cache.
            $aSuppliers = $this->database()->select('*')
                ->from($this->_sTable)
                ->where($sCond)
                ->limit($iLimit)
                ->order('position DESC')
                ->execute('getRows');
            foreach ($aSuppliers as $iKey => $aSupplier) {
                $aSuppliers[$iKey]['display_found_time'] = date("Y",$aSupplier['found_time']);
            }
            $this->cache()->save($sCacheId, $aSuppliers);
        }
        return $aSuppliers;
    }

    public function getForView($aParam = array())
    {

        $iId = isset($aParam['id']) ? $aParam['id'] : 0;
        if (!$iId && isset($aParam['path']) && !empty($aParam['path'])) {
            $sCacheId = $this->cache()->set('vendor|'. Core::getDomainId().'|'. $aParam['path']);
            $iId = $this->cache()->get($sCacheId);
            if(mb_substr($aParam['path'], -1, 1) == '/') {
                $sPath = mb_substr($aParam['path'], mb_strrpos(mb_substr($aParam['path'], 0, -1), '/') + 1, -1);
            }
            else {
                $sPath = mb_substr($aParam['path'], mb_strrpos($aParam['path'], '/') + 1);
            }

            if($iId < 1) {
                $aLists = $this->database()->select('*')
                    ->from($this->_sTable)
                    ->where('domain_id = '.Core::getDomainId() . ' AND status = 1 AND path = \''. $this->database()->escape($sPath).'\'')
                    ->execute('getRows');
                if(count($aLists)) {
                    $bIsFindPath = false;
                    foreach($aLists as $aList) {
                        if($aList['detail_path'] == $aParam['path']){
                            $bIsFindPath = true;
                            break;
                        }
                    }

                    if(!$bIsFindPath) {
                        $aList = $aLists[0];
                        //fix a redirect error.
                        if(empty($aList['detail_path'])) {
                            // set cache = 0
                            $this->cache()->save($sCacheId, 0);
                            // redirect to home page.
                            Core::getLib('url')->send($aList['detail_path']);
                            exit;
                        }
                        else if(substr($aList['detail_path'], 0, 1) != '/') {
                            Core_Error::errorHandler(E_ERROR, 'URL REDIRECT-'.$aParam['path']."\n", $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 191);
                            return false;
                        }
                    }

                    unset($aLists);
                    $iId = $aList['id'];
                    // save cache
                    $this->cache()->save($sCacheId, $iId, 604800);
                }
            }
        }
        if ($iId > 0) {
            $sCacheId = $this->cache()->set('vendor|'.Core::getDomainId().'|'. $iId);
            $aVendor = $this->cache()->get($sCacheId);

            if (!$aVendor) {
                $aVendor = $this->database()->select('id, name, name_code, path, detail_path, parent_id, parent_list, child_list, image_path, total_child, total_product, total_buy, total_customer, total_cart, is_sell')
                    ->from($this->_sTable)
                    ->where('id = '. (int) $iId. ' AND status = 1')
                    ->execute('getRow');
                if (!isset($aVendor['id'])) {
                    return array();
                }

                $aVendor['name'] = htmlspecialchars(stripslashes($aVendor['name']), ENT_NOQUOTES);
                if (empty($aRow['browser_title'])) {
                    $aVendor['title'] = $aVendor['name'];
                }
                else {
                    $aVendor['browser_title'] = htmlspecialchars(stripslashes($aVendor['browser_title']), ENT_NOQUOTES);
                    $aVendor['title'] = $aVendor['browser_title'];
                }

                // check detail path format.
                if ($aVendor['detail_path'] == '')
                    $sPath = '/';
                else
                    $sPath = str_replace('//', '/', $aVendor['detail_path']);
                if ($sPath != $aVendor['detail_path']) {
                   // update path to database.
                   $this->database()->update($this->_sTable, array('detail_path' => $sPath), 'id ='. $iId);
                }
                $aVendor['detail_path'] = $sPath;

                $aSelect = array(
                    'name',
                    'name_code',
                    'path',
                    'detail_path',
                    'image_path',
                    'child_list',
                    'total_product',
                );
                // get parent to create breadcrumb
                $sParentList = '';
                $aParentListTmp = explode(',', $aVendor['parent_list']);
                foreach ($aParentListTmp as $sValue) {
                    $sValue *= 1;
                    if($sValue == 0)
                        continue;
                    $sParentList .= $sValue.',';
                }
                if(!empty($sParentList))
                    $sParentList = substr($sParentList, 0, -1);
                if(empty($sParentList))
                    $sParentList = '-1';

                $aRows = $this->database()->select('id, parent_id, '. implode(',', $aSelect))
                    ->from($this->_sTable)
                    ->where('status = 1 AND domain_id = '.Core::getDomainId() . ' AND parent_id IN ('. $sParentList. ')')
                    ->order('position DESC')
                    ->execute('getRows');

                $aParents = array();
                $aParentLists = array();
                foreach($aRows as $aValue) {
                    foreach($aSelect as $sValue) {
                        $aParentLists[$aValue['parent_id']][$aValue['id']][$sValue] = $aValue[$sValue];
                    }
                    $aParents[$aValue['id']] = $aValue['parent_id'];
                }

                $iTmp = $iId;
                $aTmps = array_keys($aParentLists[$iTmp]);
                if (!empty($aTmps) && $bViewHomePage) {
                    $sTmp = implode(',', $aTmps);
                    $aRows = $this->database()->select('id, name, path, detail_path, parent_id, total_product')
                        ->from(Core::getT('vendor'))
                        ->where('status = 1 AND domain_id = '. Core::getDomainId(). ' AND parent_id IN ('. $sTmp.')')
                        ->order('position DESC')
                        ->execute('getRows');
                    foreach ($aRows as $aValue) {
                        if($aValue['id'] == $aParam['id'])
                            continue;
                        $aParentLists[$aValue['parent_id']][$aValue['id']] = array(
                            'name' => $aValue['name'],
                            'path' => $aValue['path'],
                            'detail_path' => $aValue['detail_path'],
                            'total_product' => $aValue['total_product']
                        );
                        $aParents[$aValue['id']] = $aValue['parent_id'];
                    }
                }

                $bExist = false;
                while(!$bExist) {
                    $bExist = true;
                    for($iCnt = 0; $iCnt < count($aParentListTmp); $iCnt++) {
                        $sValue = $aParentListTmp[$iCnt];
                        if ($sValue == $iTmp) {
                            $iTmp = $aParents[$sValue];
                            $bExist = false;
                            $aVendor['breadcrumb'][] = array(
                                'id' => $sValue,
                                'parent_id' => $iTmp,
                                'name' => $aParentLists[$iTmp][$sValue]['name'],
                                'path' => $aParentLists[$iTmp][$sValue]['detail_path'],
                                'total_product' => $aParentLists[$iTmp][$sValue]['total_product'],
                            );
                            if ($iTmp == '-1') {
                                $bExist = true;
                                break;
                            }
                        }
                    }
                }
                if(is_array($aVendor['breadcrumb']) && count($aVendor['breadcrumb'])) {
                    krsort($aVendor['breadcrumb']);
                    //re-index
                    $aVendor['breadcrumb'] = array_values($aVendor['breadcrumb']);
                }

                // get categories.
                $aRows = $this->database()->select('category_id')
                    ->from(Core::getT('vendor_category'))
                    ->where('vendor_id = '. $iId)
                    ->execute('getRows');

                $Tmp = array();
                foreach ($aRows as $aRow) {
                    $Tmp[$aRow['category_id']] = $aRow['category_id'];
                }
                if(count($Tmp)) {
                    // get category info
                    $aRows = $this->database()->select('id, name, path, detail_path, image_path')
                        ->from(Core::getT('category'))
                        ->where('status = 1 AND id IN ('. implode(',', array_keys($Tmp)).') ')
                        ->execute('getRows');

                    foreach ($aRows as $aRow) {
                        $aRow['url'] = Core::getParam('core.path'). $aRow['detail_path'] . '?vendor='.$iId;
                        $Tmp[$aRow['id']] = $aRow;
                    }
                }

                $aVendor['category'] = $Tmp;
                $this->cache()->save($sCacheId, $aVendor);
            }

            return $aVendor;
        }
        return array();
    }

    public function getParentCategories($aParam = array())
    {
        $iVendorId = isset($aParam['vid']) ? $aParam['vid'] : 0;
        if (!$iVendorId) {
            return array();
        }
        $aRows = $this->database()->select('id, name, parent_id')
            ->from(Core::getT('category'))
            ->where('status = 1')
            ->execute('getRows');
        $aParents = array();
        $aCategories = array();
        foreach ($aRows as $aRow) {
            $aCategories[$aRow['id']] = $aRow;
            if ($aRow['parent_id'] == -1 )
                $aParents[] = $aRow['id'];
        }
        $aRows = $this->database()->select('category_id')
            ->from(Core::getT('vendor_category'))
            ->where('vendor_id = '. $iVendorId)
            ->execute('getRows');
        $aData = array();
        foreach ($aRows as $aRow) {
            if (in_array($aRow['category_id'], $aParents)) {
                $aData[$aRow['category_id']] = $aCategories[$aRow['category_id']];
            }
        }
        return $aData;
    }

    /**
    * check url is a supplier page or not.
    * @param mixed $aParam
    * input param:
    *  - domain-path: path of url (removed domain)
    *
    */
    public function isVendorUrl($aParam = array())
    {
        if (!isset($aParam['domain-path'])) {
            return false;
        }
        if(mb_substr($aParam['domain-path'], -1, 1) == '/') {
            $sPath = mb_substr($aParam['domain-path'], mb_strrpos(mb_substr($aParam['domain-path'], 0, -1), '/') + 1, -1);
        }
        else {
            $sPath = mb_substr($aParam['domain-path'], mb_strrpos($aParam['domain-path'], '/') + 1);
        }
        if(empty($sPath))
            return false;
        if(substr($sPath, 0, 1) == '@') {
            return false;
        }

        $sCacheId = $this->cache()->set('supplier|'.Core::getDomainId().'|'.$sPath);
        $iId = $this->cache()->get($sCacheId);
        if ($iId > 0) {
           return $iId;
        }
        else {
            $iId = $this->database()->select('id')
                ->from($this->_sTable)
                ->where('domain_id = '. Core::getDomainId() . ' AND status = 1 AND name_code = \''.$this->database()->escape($sPath).'\'')
                ->execute('getField');
            if(!$iId) {
                return false;
            }
            $this->cache()->save($sCacheId, $iId, 604800);
            return $iId;
        }
    }
    /**
    * hàm cập nhật lại danh mục khi cập nhật sản phẩm
    *
    * @param mixed $aParam
    */
    public function updateCategory($aParam = array())
    {
        $iOldCategoryId = isset($aParam['old_category']) ? $aParam['old_category'] : 0;
        $iCategoryId = isset($aParam['category_id']) ? $aParam['category_id'] : 0;
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : 0;
        $iStatus = isset($aParam['status']) ? $aParam['status'] : 0;

        if (!$iCategoryId || !$iVendorId) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu.'
            );
        }

        $aVendor = $this->database()->select('*')
            ->from(Core::getT('vendor'))
            ->where('status != 2 AND id ='.$iVendorId)
            ->execute('getRow');
        if (!isset($aVendor['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhà cung cấp không tồn tại.'
            );
        }
        $iStatusTmp = 0;
        if ($aVendor['status'] == 1 && $iStatus == 1) {
            $iStatusTmp = 1;
        }
        if ($iOldCategoryId != 0) {
            // kiểm tra với category cũ xem có sản phẩm nào còn trong vendor hay không.
            $iCount = $this->database()->select('COUNT(*)')
                ->from(Core::getT('article'))
                ->where('category_id ='. $iOldCategoryId)
                ->execute("getField");

            if (!count($iCount)) {
                // trường hợp không có dữ liệu thì xem xét để xóa các category không có sản phẩm.
                // tuy nhiên để kiểm tra hết tất cả trường hợp thì kha phuc tap và ton xu ly. viec kiem tra phai thuc hien tren cac category cha, con. va chong cheo lan nhau.  Ngoai ra, neu sieu thi tao danh muc rieng chua co san pham thi viec tu dong xoa se khong dung cau truc.
                // do do, khi hien thi theo danh muc, se thuc hien vien check nêu khong co du lieu thi khong hien thi
            }
        }

        // tien hanh them cac danh muc cha.
        $aCategories = array();
        $sParentLisit = $this->database()->select('parent_list')
            ->from(core::getT('category'))
            ->where('id = '. $iCategoryId)
            ->execute('getField');

        $aValue = explode(',', $sParentLisit);
        foreach ($aValue as $iValue) {
            if ($iValue == -1)
                continue;
            $aCategories[$iValue] = $iValue;
        }
        // kkiem tra category moi co trong du lieu vendor hay chua
        $iField = $this->database()->select('COUNT(*)')
            ->from(Core::getT('vendor_category'))
            ->where('vendor_id ='. $iVendorId. ' AND category_id ='. $iCategoryId)
            ->execute('getField');
        // neu co roi, thi dong nghia voi cac category cha da con trong du lieu, truong hop cap nhat danh mục thi se tien hanh cap nhat theo quy trinh cua danh muc
        if (!$iField) {
            // tien hanh them category hien tai vao du lieu
            $this->database()->insert(Core::getT('vendor_category'), array(
                'vendor_id' =>  $iVendorId,
                'category_id' => $iCategoryId
            ));

            foreach ($aCategories as $iValue) {
                $iField = $this->database()->select('COUNT(*)')
                    ->from(Core::getT('vendor_category'))
                    ->where('vendor_id = '. $iVendorId . ' AND category_id = '. $iValue)
                    ->execute("getField");

                if (!$iField) {
                    $this->database()->insert(Core::getT('vendor_category'), array(
                        'vendor_id' =>  $iVendorId,
                        'category_id' => $iValue
                    ));
                }
            }
        }

        //tiến hành cập nhật hiển thị cho nhà cung cấp
        foreach ($aCategories as $iValue) {
            $aDisplay = $this->database()->select('*')
                ->from(Core::getT('category_display'))
                ->where('item_type = 2 AND item_id ='. $iVendorId. ' AND object_type = 1 AND object_id ='. $iValue.' AND domain_id ='.Core::getDomainId())
                ->execute('getRow');
            if (!isset($aDisplay['id'])) {
                $aInsert = array(
                    'object_id' => $iValue,
                    'object_type' => 1,
                    'item_id' => $iVendorId,
                    'item_type' => 2,
                    'total_dislay_item' => 10,
                    'status' => $iStatusTmp,
                    'domain_id' => Core::getDomainId(),
                );
                $this->database()->insert(Core::getT('category_display'),$aInsert);
            }
        }
    }



    public function updateDatabase()
    {
        //$aRows = $this->database()->select('*')
//            ->from(Core::getT('filter_influence'))
//            ->execute('getRows');
//
//        foreach ($aRows as $aRow) {

//            $this->database()->update(Core::getT('filter_influence'), array(
//                'sku' => Core::getDomainId(). '|'. $aRow['sku']
//            ), 'id ='. $aRow['id']);
//        }

        $aRows = $this->database()->select('*')
            ->from(Core::getT('vendor'))
            ->execute('getRows');

        foreach ($aRows as $aRow) {
            $sPath = Core::getLib('url')->cleanTitle($aRow['name']);
            $sDetailPath = '/'. $sPath .'/';
            $this->database()->update(Core::getT('vendor'), array(
                'path' => $this->database()->escape($sPath),
                'detail_path' => $this->database()->escape($sDetailPath),
            ), 'id ='. $aRow['id']);
        }
    }

    public function addSampleData()
    {
        $aListVendor = array(80,83, 84, 78, 73, 71, 69, 68, 56, 61, 62, 63);
        $aListCategory = array();
        foreach ($aListVendor as $iVendorId) {
            // add danh sách các vendor vào trang chủ
            $iId = $this->database()->insert(Core::getT('category_display'), array(
                'object_id' => 134,
                'object_type' => 1,
                'total_dislay_item' => 10,
                'item_id' => $iVendorId,
                'item_type' => 2,
                'domain_id' => 1
            ));

            // add các danh sách con cho siêu thị, bao gồm các category và producer.
            // lấy danhs sách các sản phẩm trong siêu thị.
            $aTmps = $this->database()->select('*')
                ->from(Core::getT('filter_influence'))
                ->where('vendor_id = '. $iVendorId. ' AND status = 1')
                ->execute('getRows');

            $aTmpId = array();
            foreach ($aTmps as $aTmp) {
                $aTmpId[$aTmp['article_id']] = $aTmp;
            }
            // lấy hết danh sách bài viết để cập nhật category
            $aArticles = $this->database()->select('id, title, category_id, title_browser, image_path, detail_path, total_buy, total_view, total_like')
                ->from(Core::getT('article'))
                ->where('id IN ('. implode(',', array_keys($aTmpId)).') AND status = 1')
                ->order('position DESC')
                ->execute('getRows');

            $iPosition = 1;
            $aCategory = array();
            foreach ($aArticles as $aArticle) {
                if(!$aArticle['category_id'])
                    continue;
                if(isset($aCategory[$aArticle['category_id']]))
                    contine;
                $aCategory[$aArticle['category_id']][] = $aArticle;
            }
            foreach ($aCategory as $iKey => $aValue) {
                $iObjectId = $this->database()->insert(Core::getT('category_display_detail'), array(
                    'parent_id' => $iId,
                    'item_id' => $iKey,
                    'item_type' => 1,
                    'display' => 1,
                    'total_item' => 5,
                    'position' => $iPosition
                ));
                $iPosition++;
                // add 5 sản phẩm mới nhất vào dữ liệu\
                $iCnt = 1;
                foreach ($aValue as $aItem) {
                    if($iCnt > 4)
                        break;
                    $this->database()->insert(core::getT('category_display_article'), array(
                        'parent_id' => $iObjectId,
                        'article_id' => $aItem['id'],
                        'sku' => str_replace(Core::getDomainId().'|', '', $aTmpId[$aItem['id']]['sku']),
                        'position' => $iCnt
                    ));
                    $iCnt++;
                }
            }
        }
    }

    public function updateVendorCategory()
    {
        /**
        * Step 1: get all vendor.
        * Step 2: search all article for each vendor.
        * Step 3: get category id of each article and save to vendor_category table.
        */

        $aVendors = $this->database()->select('id')
            ->from(Core::getT('vendor'))
            ->where('status = 1')
            ->execute('getRows');

        foreach ($aVendors as $aVendor) {
            // get article
            $aRows = $this->database()->select('article_id')
                ->from(Core::getT('filter_influence'))
                ->where('vendor_id = '. $aVendor['id'].' AND status = 1')
                ->execute('getRows');
            $aTmp = array();
            foreach ($aRows as $aRow) {
                $aTmp[$aRow['article_id']] = $aRow['article_id'];
            }
            if (count($aTmp)) {
                $aArticles = $this->database()->select('id, category_id')
                    ->from(Core::getT('article'))
                    ->where('id IN ('. implode(',', array_keys($aTmp)).') AND status = 1')
                    ->execute('getRows');
                $aTmps = array();
                foreach ($aArticles as $aArticle) {
                    if(!in_array($aArticle['category_id'], $aTmps)) {
                        $aTmps[] = $aArticle['category_id'];
                        $this->database()->insert(Core::getT('vendor_category'), array(
                            'vendor_id' =>  $aVendor['id'],
                            'category_id' => $aArticle['category_id']
                        ));
                    }
                }
            }
        }
    }

    public function updateProduction()
    {
        /**
        * Step 1: get all vendor.
        * Step 2: search all article for each vendor get .
        * Step 3: get 10 production which top number of article, and add to database.
        */
        $aVendors = array(80,83, 84, 78, 73, 71, 69, 68, 56, 61, 62, 63);
        foreach ($aVendors as $iVendor) {
            // get object id in display table.
            $iParentId = $this->database()->select('id')
                ->from(Core::getT('category_display'))
                ->where('object_id = 134 AND object_type = 1 AND item_id = '.$iVendor)
                ->execute('getField');
            // get article
            $aRows = $this->database()->select('article_id')
                ->from(Core::getT('filter_influence'))
                ->where('vendor_id = '. $iVendor.' AND status = 1')
                ->execute('getRows');
            $aTmp = array();
            foreach ($aRows as $aRow) {
                $aTmp[$aRow['article_id']] = $aRow['article_id'];
            }
            if (count($aTmp)) {
                $aArticles = $this->database()->select('filter_value_id, article_id')
                    ->from(Core::getT('filter_value_article'))
                    ->where('article_id IN ('. implode(',', array_keys($aTmp)).') AND filter_id = 1 AND status = 1')
                    ->execute('getRows');
                $aTmps = array();
                foreach ($aArticles as $aArticle) {
                    if(isset($aTmps[$aArticle['filter_value_id']]))
                        $aTmps[$aArticle['filter_value_id']]++;
                    else
                        $aTmps[$aArticle['filter_value_id']] = 1;
                }

                // sort $aTmps be value.
                arsort($aTmps);
                $iCnt = 0;
                foreach ($aTmps as $iKey => $iValue) {
                    if( $iCnt > 10)
                        break;
                    $this->database()->insert(Core::getT('category_display_detail'), array(
                        'parent_id' => $iParentId,
                        'item_id' => $iKey,
                        'item_type' => 3,
                        'display' => 1,
                        'total_item' => 5,
                        'position' => $iCnt
                    ));
                    $iCnt++;
                }
            }
        }
    }

    public function updateUnit()
    {
        $aMap = array(
            1 => 'Gram',
            2 => 'Kilogam',
            3 => 'Hộp',
            4 => 'Gói',
            5 => 'Lốc',
            6 => 'Cây',
            7 => 'Cái',
            8 => 'Khác',
            9 => 'Khay',
            10 => 'Chai',
            11 => 'Lọ',
            12 => 'Quả',
            13 => 'Túi',
            14 => 'Bó',
            15 => 'Bộ',
            16 => 'Dây',
            17 => 'Đôi',
            18 => 'Lít',
            19 => 'Que',
            20 => 'Thanh',
            21 => 'Lon',
        );

    }

    public function getStoreByVendor($aParam = array())
    {
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;

        $sOrder = isset($aParam['order']) ? $aParam['order'] : '';
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 5;

        $iCnt = 0;
        $aData = array();
        if ($iVendorId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Không có thông tin nhà cung cấp'
            );
        }
        $aVendor = $this->database()->select('id')
            ->from(Core::getT('vendor'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iVendorId)
            ->execute('getRow');
        if (!isset($aVendor['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhà cung câp không hợp lệ'
            );
        }

        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('vendor_store'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND vendor_id ='.$iVendorId)
            ->execute('getField');
        if ($iCnt > 0) {
            $aRows = $this->database()->select('*')
                ->from(Core::getT('vendor_store'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND vendor_id ='.$iVendorId)
                ->order('id DESC')
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');

            foreach ($aRows as $aRow) {
                if ($aRow['create_time'] > 0) {
                    $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
                    $aRow['create_time_txt'] = date('H:i d/m/Y', $aRow['create_time']);
                }
                else {
                    $aRow['create_time_txt'] = '';
                }
                $aData[] = array(
                    'id' => $aRow['id'],
                    'name' => $aRow['name'],
                    'address' => $aRow['address'],
                    'lat' => $aRow['latittude'],
                    'lng' => $aRow['longtitude'],
                    'status' => $aRow['status'],
                    'create_time_txt' => $aRow['create_time_txt'],
                );
            }
        }
        return array(
            'status' => 'success',
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aData,
        );
    }

    public function getStoreById($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1; //id của store chứa hàng
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;

        if ($iId < 1 && $iVendorId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Thiếu thông tin đầu vào',
            );
        }
        $aData = array();
        $aVendor = array();
        if ($iId < 1) {
            $aVendor = $this->database()->select('id, name, content, area_id, address')
                ->from(Core::getT('vendor'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id='.$iVendorId)
                ->execute('getRow');
            if (!isset($aVendor['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Nhà cung cấp không hợp lệ',
                );
            }
        }
        else {
            //Lấy thông tin kho hàng và nhà cung cấp theo store đã lưu
            $aStore = $this->database()->select('*')
                ->from(Core::getT('vendor_store'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId)
                ->execute('getRow');
            if (!isset($aStore['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Kho hàng không hợp lệ',
                );
            }
            $aVendor = $this->database()->select('id, name, content, area_id, address')
                ->from(Core::getT('vendor'))
                ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id='.$aStore['vendor_id'])
                ->execute('getRow');
            $aData['id'] = $iId;
            $aData['name'] = $aStore['name'];
            $aData['address'] = $aStore['address'];
            $aData['lat'] = $aStore['latitude'];
            $aData['lng'] = $aStore['longtitude'];
        }
        $aData['vendor_id'] = $aVendor['id'];
        $aData['vendor_name'] = $aVendor['name'];
        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    public function updateVendorStore($aParam = array())
    {
        $aData = array();
        $aData['vendor'] = array();
        $iId = isset($aParam['id']) ? $aParam['id'] : -1; //id của store chứa hàng
        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;

        if ($iVendorId < 1) {
            return array(
                'status' => 'error',
                'message' => 'Không có thông tin nhà cung cấp',
            );
        }
        $aVendor = $this->database()->select('id')
            ->from(Core::getT('vendor'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id='.$iVendorId)
            ->execute('getRow');
        if (!isset($aVendor['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhà cung cấp không hợp lệ',
            );
        }

        $sName = isset($aParam['name']) ? $aParam['name'] : '';
        $sAddress = isset($aParam['address']) ? $aParam['address'] : '';
        $sLat = isset($aParam['lat']) ? $aParam['lat'] : '';
        $sLng = isset($aParam['lng']) ? $aParam['lng'] : '';

        if (strlen($sName) < 1 || strlen($sName) > 255) {
            Core_Error::set('error', 'Tên kho hàng không hợp lệ');
        }
        else if (strlen($sAddress) < 1 || strlen($sAddress) > 255) {
            Core_Error::set('error', 'Địa chỉ kho hàng không hợp lệ');
        }
        else if (empty($sLat) || empty($sLng)) {
            Core_Error::set('error', 'Vị trí kho hàng không hợp lệ');
        }

        if (Core_Error::isPassed()) {
            $aInsert = array(
                'name' => $sName,
                'latitude' => $sLat,
                'longtitude' => $sLng,
                'address' => $sAddress,
            );
            if ($iId > 0) {
                $this->database()->update(Core::getT('vendor_store'), $aInsert, 'id ='.$iId);
            }
            else {
                $aInsert['vendor_id'] = $iVendorId;
                $aInsert['create_time'] = CORE_TIME;
                $aInsert['status'] = 1;
                $aInsert['domain_id'] = Core::getDomainId();
                $iId = $this->database()->insert(Core::getT('vendor_store'), $aInsert);
            }
            //save log
            Core::getService('core.tools')->saveLogSystem(array('action' => 'create_edit_vendor_store'.'-'.$iId,'content' => 'phpinfo',));
            return array(
                'status' => 'success',
                'data' => array(
                    'id' => $iId,
                ),
            );
        }
        else {
            return array(
                'status' => 'error',
                'message' => Core_Error::get('error'),
            );
        }
    }

    public function updateStatusVendorStore($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        $iVendorId = isset($aParam['vid']) ? $aParam['vid'] : -1;
        $iStatus = isset($aParam['status']) ? $aParam['status'] : 0;

        if ($iVendorId < 1 || $iId < 1 ) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu, vui lòng thử lại.'
            );
        }
        try {
            $this->database()->update(Core::getT('vendor_store'), array(
                'status' => $iStatus
            ), 'vendor_id ='. $iVendorId . ' AND id = '. $iId);

            $sTmp = 'update_status';
            if ($iStatus == 2) {
                $sTmp = 'delete';
            }
            //save log
            Core::getService('core.tools')->saveLogSystem(array('action' => $sTmp.'_vendor_store'.'-'.$iId,'content' => 'phpinfo',));
            return array(
                'status' => 'success',
                'data' => 1
            );
        }
        catch(Exception $e) {
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
    
    public function getAreaByVendor($aParam = array())
    {
        $aVendorId = isset($aParam['vid_list']) ? $aParam['vid_list'] : array();
        $aData = array();
        if (empty($aVendorId)) {
            return $aData;
        }
        
        //Lấy thông tin danh sách vendor
        $aVendors = $this->database()->select('id, area_id')
            ->from(Core::getT('vendor'))
            ->where('status = 1 AND area_id > 0 AND id IN ('.implode(',', $aVendorId).')')
            ->execute('getRows');
        
        $aAreaId = array();
        foreach ($aVendors as $aRow) {
            $aAreaId[$aRow['id']] = $aRow['area_id'];
        }
        
        if (!empty($aAreaId)) {
            //Lấy thông tin các thành phố
            $aRows = $this->database()->select('*')
                ->from(Core::getT('area'))
                ->where('status = 1 AND degree = 4 AND id IN ('.implode(',', $aAreaId).')')
                ->execute('getRows');
            
            $aMappingDegree4 = array();
            $aDegree3Id = array();
            foreach ($aRows as $aRow) {
                if (!in_array($aRow['parent_id'], $aDegree3Id)){
                    $aDegree3Id[] = $aRow['parent_id'];
                }
                $aMappingDegree4[$aRow['id']] = $aRow['parent_id'];
            }
            $aMappingDegree3 = array();
            if (!empty($aDegree3Id)) {
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('area'))
                    ->where('status = 1 AND degree = 3 AND id IN ('.implode(',', $aDegree3Id).')')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aMappingDegree3[$aRow['id']] = $aRow['parent_id'];
                }
            }
            $aMappingArea = array();
            foreach ($aMappingDegree4 as $sKey => $iVal) {
                $aMappingArea[$sKey] = isset($aMappingDegree3[$iVal]) ? $aMappingDegree3[$iVal] : 0;
            }
            foreach ($aVendors as $aRow) {
                $aData[$aRow['id']] = isset($aMappingArea[$aRow['area_id']]) ? $aMappingArea[$aRow['area_id']] : 0;
            }
        }
        
        return $aData;
    }
    
    public function getSurchargeByVendor($aParam = array())
    {
        $aData = array(
            'total_money' => 0,
            'total_coin' => 0,
        );
        $iVendorId = isset($aParam['vid']) ? $aParam['vid'] : -1;
        if ($iVendorId < 1) {
            return $aData;
        }
        
        $aSurcharge = Core::getService('surcharge')->calculatorSurcharge();
        if (isset($aSurcharge[1][$iVendorId])) {
            $aData['total_money'] = $aSurcharge[1][$iVendorId]['total_money'];
            $aData['total_coin'] = $aSurcharge[1][$iVendorId]['total_coin'];
        }
        else {
            $aArea = $this->getAreaByVendor(array('vid_list' => array($iVendorId)));
            if (isset($aArea[$iVendorId]) && $aArea[$iVendorId] > 0) {
                if (isset($aSurcharge[0][$aArea[$iVendorId]])) {
                    $aData['total_money'] = $aSurcharge[0][$aArea[$iVendorId]]['total_money'];
                    $aData['total_coin'] = $aSurcharge[0][$aArea[$iVendorId]]['total_coin'];
                }
            }
        }
        return $aData;
    }
}
?>