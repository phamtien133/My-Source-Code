<?php
class User_Service_Permission extends Service
{
    private $_aPermission = array();
    private $_aPermissionGOne = array();
    private $_aPermissionGTwo = array();
    private $_aPermissionGroup = array();
    private $_aFields = array();
    private $_aRecursiveTmp = array();
    public function __construct()
    {
        $this->_aPermissionGroup = array(
            'cate' => array(
                'create_category',
                'edit_category',
                'delete_category',
            ),
            'article' => array(
                'create_article',
                'edit_article',
                'edit_other_article',
                'delete_article',
            ),
            'cmt' => array(
                'create_comment',
                'edit_comment',
                'delete_comment',
            ),
            'approve_atc' => array(
                'approve_article',
                'was_approved_article',
            ),
            'approve_cmt' => array(
                'approve_comment',
                'was_approved_comment',
            ),
            'support' => array(
                'online_support',
                'setting_online_support',
            ),
            'slide' => array(
                'setting_main_slide',
                'setting_sub_slide',
            ),
            'menu' => array(
                'create_menu',
                'edit_menu',
                'delete_menu',
            ),
            'ads' => array(
                'create_ads',
                'edit_ads',
                'delete_ads',
                'approve_ads',
            ),
            'statistics' => array(
                'view_statistics',
                'export_statistics',
            ),
            'user' => array(
                'create_user',
                'edit_user',
                'ban_user',
                'permission_user',
                'deni_change_passwd',
            ),
            'file' => array(
                'upload_img',
                'upload_file',
                'manage_file',
            ),
            'system' => array(
                'manage_tags',
                'manage_template',
                'manage_language',
                'edit_setting',
                'change_image_system',
                'manage_extend',
                'access_cms',
            ),
        );
        //Các quyền theo danh sách đề tài
        $this->_aPermissionGOne = array(
            'create_category',
            'edit_category',
            'delete_category',
            'create_article',
            'edit_article',
            'edit_other_article',
            'delete_article',
            'create_comment',
            'edit_comment',
            'delete_comment',
            'approve_article',
            'was_approved_article',
            'approve_comment',
            'was_approved_comment',
        );

        $this->_aPermissionGTwo = array(
            'online_support',
            'setting_online_support',
            'setting_main_slide',
            'setting_sub_slide',
            'create_menu',
            'edit_menu',
            'delete_menu',
            'create_ads',
            'edit_ads',
            'delete_ads',
            'approve_ads',
            'view_statistics',
            'export_statistics',
            'create_user',
            'edit_user',
            'ban_user',
            'permission_user',
            'deni_change_passwd',
            'upload_img',
            'upload_file',
            'manage_file',
            'manage_tags',
            'manage_template',
            'manage_language',
            'edit_setting',
            'change_image_system',
            'manage_extend',
            'access_cms',
        );

        /*
        $this->_aPermission = array(
            'create_category',
            'edit_category',
            'delete_category',
            'create_article',
            'edit_article',
            'edit_other_article',
            'delete_article',
            'create_comment',
            'edit_comment',
            'approve_article',
            'was_approved_article',
            'approve_comment',
            'was_approved_comment',
            'online_support',
            'setting_online_support',
            'setting_main_slide',
            'setting_sub_slide',
            'create_menu',
            'edit_menu',
            'delete_menu',
            'create_ads',
            'edit_ads',
            'delete_ads',
            'approve_ads',
            'view_statistics',
            'export_statistics',
            'create_user',
            'edit_user',
            'ban_user',
            'permission_user',
            'deni_change_passwd',
            'upload_img',
            'upload_file',
            'manage_file',
            'manage_tags',
            'manage_template',
            'manage_language',
            'change_setting',
            'change_image_system',
            'manage_extend',
        );
        */
        $this->_aFields = array(
            'cate' => 'Danh mục',
            'article' => 'Bài viết',
            'cmt' => 'Bình luận',
            'approve_atc' => 'Kiểm duyệt bài viết',
            'approve_cmt' => 'Kiểm duyệt bình luận',
            'support' => 'Hổ trợ trực tuyến',
            'slide' => 'Slide',
            'menu' => 'Menu',
            'ads' => 'Quảng cáo',
            'statistics' => 'Thống kê',
            'user' => 'Thành viên',
            'file' => 'File',
            'system' => 'Hệ thống',
            'create_category' => 'Tạo đề tài',
            'edit_category' => 'Sửa đề tài',
            'delete_category' => 'Xóa đề tài',
            'create_article' => 'Tạo bài viết',
            'edit_article' => 'Sửa bài viết',
            'edit_other_article' => 'Sửa bài viết của thành viên khác (sửa khác)',
            'delete_article' => 'Xóa bài viết',
            'create_comment' => 'Gửi bình luận',
            'edit_comment' => 'Sửa bình luận',
            'delete_comment' => 'Xóa bình luận',
            'approve_article' => 'Kiểm duyệt bài viết',
            'was_approved_article' => 'Bị kiểm duyệt bài viết',
            'approve_comment' => 'Kiểm duyệt bình luận',
            'was_approved_comment' => 'Bị kiểm duyệt bình luận',
            'online_support' => 'Hỗ trợ trực tuyến',
            'setting_online_support' => 'Thiết lập hỗ trợ trực tuyến',
            'setting_main_slide' => 'Sửa slide chính',
            'setting_sub_slide' => 'Sửa slide phụ',
            'create_menu' => 'Tạo Menu',
            'edit_menu' => 'Sửa Menu',
            'delete_menu' => 'Xóa Menu',
            'create_ads' => 'Tạo quảng cáo',
            'edit_ads' => 'Sửa quảng cáo',
            'delete_ads' => 'Xóa quảng cáo',
            'approve_ads' => 'Duyệt quảng cáo',
            'view_statistics' => 'Xem thông kê',
            'export_statistics' => 'Xuất thông kê',
            'create_user' => 'Tạo thành viên',
            'edit_user' => 'Sửa thành viên',
            'ban_user' => 'Cấm truy cập thành viên',
            'permission_user' => 'Phân quyền thành viên',
            'deni_change_passwd' => 'Chặn đổi mật khẩu',
            'upload_img' => 'Tải hình lên',
            'upload_file' => 'Tải tập tin lên',
            'manage_file' => 'Quản lý tập tin',
            'manage_tags' => 'Quản lý Tags',
            'manage_template' => 'Quản lý Giao diện',
            'manage_language' => 'Quản lý ngôn ngữ',
            'edit_setting' => 'Sửa thiết lập',
            'change_image_system' => 'Đổi hình hệ thống',
            'manage_extend' => 'Quản lý mở rộng',
            'access_cms' => 'Truy cập CMS',
        );
    }
    /**
    * update user permission
    *
    */
    public function set()
    {

    }

    public function updatePermission($aParam = array())
    {
        $bIsApi = false;
        if (isset($aParam['api']) && $aParam['api']) {
            $bIsApi = true;
        }

        $oSession = Core::getLib('session');

        $iObjectType = 0;

        $iVendorId = -1;
        if (isset($aParam['vendor_id'])) {
            $iVendorId = $aParam['vendor_id'];
        }
        if ($iVendorId < 1) {
            $iVendorId = -1;
        }

        $aRegions = array(
            'create_category',
            'edit_category',
            'create_article',
            'edit_article',
            'edit_other_article',
            'comment',
            'edit_comment',
            'approve_comment',
            'was_approved_comment',
            'approve_article',
            'was_approved_article',
            'de_tai_chinh_stt'
        );

        $aPermissionField = array(
            'online_support',
            'setting_online_support',
            'setting_main_slide',
            'setting_extra_slide',
            'create_menu',
            'manage_ads',
            'manage_statistics',
            'create_user',
            'edit_user',
            'permission_user',
            'block_change_password',
            'edit_setting',
            'manage_tags',
            'change_image_system',
            'manage_template',
            'manage_language',
            'upload_image',
            'upload_file',
            'manage_extend',
            'manage_file'
            );
        $sConds = '';
        $aSessionUser = $oSession->get('session-user');

        $iObjectId = 0;
        $sQuery = '';
        if ($iVendorId > 0) {
            $iObjectType = 2;
            //Lấy thông tin phân quyền trong vendor permission: ưu tiên quyền của thành viên trước => nhóm thành viên
            $sConds = ' AND vendor_id ='.$iVendorId.' AND object_type = 0 AND object_id = '.(int)Core::getUserId();
            $aRow = $this->database()->select('id')
                ->from(Core::getT('vendor_permission'))
                ->where('domain_id ='.(int)Core::getDomainId().$sConds)
                ->execute('getRow');

            if (isset($aRow['id'])) {
                $iObjectId = $aRow['id'];
            }
            else {
                $aSessionUser['user_group_id']  = 6;

                //Kiểm tra quyền của nhóm thành viên
                if ($aSessionUser['user_group_id'] > 0) {
                    $sConds = ' AND vendor_id ='.$iVendorId.' AND object_type = 1 AND object_id = '.$aSessionUser['user_group_id'];
                    $aRow = $this->database()->select('id')
                        ->from(Core::getT('vendor_permission'))
                        ->where('domain_id ='.(int)Core::getDomainId().$sConds)
                        ->execute('getRow');
                    if (isset($aRow['id'])) {
                        $iObjectId = $aRow['id'];
                    }
                    else {
                        $iObjectId = -1;
                    }
                }
                else {
                    //Không có quyền truy cập
                    $iObjectId = -1;
                }
            }
            $sQuery = ' AND object_type = 2 AND object_id = '.$iObjectId;
        }
        else {
            if ($aSessionUser['user_group_id'] > 0) {
                $sQuery = ' AND object_type = 1 AND object_id = '.$aSessionUser['user_group_id'];
            }
            else {
                $sQuery = ' AND object_type = 0 AND object_id = '.(int)Core::getUserId();
            }
           // $sQuery = ' AND object_type = 0 AND object_id = '.(int)Core::getUserId();
        }

        $aRow = $this->database()->select('*')
            ->from(Core::getT('permission'))
            ->where('domain_id ='.(int)Core::getDomainId().$sQuery)
            ->execute('getRow');

        if (empty($aRow) && $bIsApi) {
            //Trả về lỗi không có quyền truy cập khi gọi api
            return array(
                'status' => 'error',
                'message' => 'Không có quyền truy cập',
                'code' => 401,
            );
        }

        // nếu ko tồn tại thiết lập quyền và không phải là trang supplier, lấy quyền mặc định của thành viên -1
        if (empty($aRow) && $iVendorId < 1) {
            $aRow = $this->database()->select('*')
                ->from(Core::getT('permission'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND object_type = 0 AND object_id = -1')
                ->execute('getRow');
        }
        if (!isset($aRow["priority"]))
            $aRow["priority"] = -1;
        //set seesion group priority
        $oSession->setArray('session-user', 'priority_group',  $aRow["priority"]*1);

        //Lấy danh sách những đề tài hiện có (danh cho cms)
        $sCategoryCMS = '';
        if (Core::getParam('core.main_server') == 'cms.') {
            $aCategoryCMS = array();
            $aTmps = $this->database()->select('id')
                ->from(Core::getT('category'))
                ->where('status = 1 AND domain_id ='.Core::getDomainId())
                ->execute('getRows');
            foreach ($aTmps as $aTmp) {
                $aCategoryCMS[] = $aTmp['id'];
            }
            if (!empty($aCategoryCMS)) {
                $sCategoryCMS = implode(',', $aCategoryCMS);
            }
        }



        $aRegions[] = 'edit_menu';
        $aRegions[] = 'edit_slide';

        foreach ($aRegions as $sPermission) {
            //set session permission
            if ($aRow[$sPermission] == '-1' && !empty($sCategoryCMS)) {
                $aRow[$sPermission] = $sCategoryCMS;
            }
            $oSession->setArray('session-permission', $sPermission, $aRow[$sPermission]);
        }

        $aPermissionFieldValue = $this->getPermissions($aPermissionField, $aRow["extend"]);

        foreach ($aPermissionField as $sKey => $sVal) {
            //set value for session permission
            $oSession->setArray('session-permission', $sVal, $aPermissionFieldValue[$sKey]*1);
        }
        $iSessionTmp = $oSession->getArray('session-permission', 'manage_comment');
        if ($iSessionTmp == 1) {
            //set session permission post article confirmed
            $oSession->setArray('session-permission', 'post_article_confirmed', 1);
        }
        //set permission page_type
        $sPageType = ($iVendorId > 0) ? 'sup.' : 'cms.';
        $oSession->setArray('session-permission', 'page_type', $sPageType);
        //if ($sPageType == 'sup.') {
            $oSession->setArray('session-permission', 'vendor_id', $iVendorId);
            $oSession->set('session-vendor', $iVendorId);
        //}

        //set store permission
        $aStorePermission = array();
        $aRows = $this->database()->select('stt, kho_stt, phan_quyen')
            ->from(Core::getT('phan_quyen_kho'))
            ->where('thanh_vien_stt ='.Core::getUserId())
            ->execute('getRows');

        foreach ($aRows as $aRow) {
            if ($aRow['phan_quyen'] > 0) {
                $aStorePermission[$aRow['kho_stt']] = Core::getService('store.permission')->convertPermission($aRow['phan_quyen']);
            }
        }
        $oSession->set('session-store_permission', $aStorePermission);

        if ($bIsApi) {
            return array(
                'status' => 'success',
                'message' => 'Thiết lập quyền thành công',
            );
        }
    }

    /**
    * Hàm cập nhật phân quyền mới
    *
    * @param mixed $aParam
    * @return mixed
    */
    public function updatePermissionNew($aParam = array())
    {
        /**
        * CMS:
        *   + Lấy tất cả các quyền của user (riêng + nhóm)
        *   + Gộp quyền các nhóm
        *   + Gán quyền riêng lên quyền nhóm thành quyền cuối cùng
        * SUP:
        *   + Chỉ lấy theo quyền riêng được phân theo sup
        */

        $bIsApi = false;
        if (isset($aParam['api']) && $aParam['api']) {
            $bIsApi = true;
        }

        $oSession = Core::getLib('session');

        $iObjectType = 0;

        $iVendorId = -1;
        if (isset($aParam['vendor_id'])) {
            $iVendorId = $aParam['vendor_id'];
        }
        if ($iVendorId < 1) {
            $iVendorId = -1;
        }

        $aFullPermission = array_merge($this->_aPermissionGOne, $this->_aPermissionGTwo);

        $aAllCategory = array();
        $aRows = $this->database()->select('id')
            ->from(Core::getT('category'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId())
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aAllCategory[] = $aRow['id'];
        }

        $sConds = '';
        $aSessionUser = $oSession->get('session-user');

        $aPermissionGOne = $this->_aPermissionGOne;
        $aPermissionGTwo = $this->_aPermissionGTwo;

        $aPermissionSelected = array();
        $iObjectId = 0;
        $sQuery = '';
        if ($iVendorId > 0) {
            $iObjectType = 2;
            //Lấy thông tin phân quyền trong vendor permission: ưu tiên quyền của thành viên trước => nhóm thành viên
            $sConds = ' AND vendor_id ='.$iVendorId.' AND object_type = 0 AND object_id = '.(int)Core::getUserId();
            $aRow = $this->database()->select('id')
                ->from(Core::getT('vendor_permission'))
                ->where('domain_id ='.(int)Core::getDomainId().$sConds)
                ->execute('getRow');

            if (isset($aRow['id'])) {
                $iObjectId = $aRow['id'];
            }
            else {
                $aSessionUser['user_group_id']  = 6;

                //Kiểm tra quyền của nhóm thành viên
                if ($aSessionUser['user_group_id'] > 0) {
                    $sConds = ' AND vendor_id ='.$iVendorId.' AND object_type = 1 AND object_id = '.$aSessionUser['user_group_id'];
                    $aRow = $this->database()->select('id')
                        ->from(Core::getT('vendor_permission'))
                        ->where('domain_id ='.(int)Core::getDomainId().$sConds)
                        ->execute('getRow');
                    if (isset($aRow['id'])) {
                        $iObjectId = $aRow['id'];
                    }
                    else {
                        $iObjectId = -1;
                    }
                }
                else {
                    //Không có quyền truy cập
                    $iObjectId = -1;
                }
            }
            $sQuery = ' AND object_type = 2 AND object_id = '.$iObjectId;
        }
        else {
            //Lấy quyền riêng của user
            $aPermissionModify = array();
            $sConds = 'object_type = 0 AND object_id ='.Core::getUserId().' AND domain_id ='.Core::getDomainId();
            $aModify = $this->database()->select('*')
                ->from(Core::getT('permission_modify'))
                ->where($sConds)
                ->execute('getRow');

            if (isset($aModify['object_id'])) {
                $aPermissionModify['priority'] = $aModify['priority'];
                $aExtend = array();
                if ($aModify['extend_2'] > 0) {
                    $aTemps = $this->getPermissions($aPermissionGTwo, $aModify['extend_2']);
                    foreach ($aPermissionGTwo as $sKey => $sVal) {
                        $aExtend[$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
                    }

                }
                foreach ($aPermissionGOne as $sField) {
                    $aExtend[$sField] = array();
                    if (isset($aModify[$sField])) {
                        $aModify[$sField] = unserialize($aModify[$sField]);
                        $aTmps = array();
                        foreach ($aAllCategory as $iCategoryTmp) {
                            if (isset($aModify[$sField][$iCategoryTmp])) {
                                if ($aModify[$sField][$iCategoryTmp] > 0) {
                                    $aTmps[] = $iCategoryTmp;
                                }
                            }
                            else {
                                //Ko gộp quyền riêng của 1 user
                                //$aTmps[] = $iCategoryTmp;
                            }
                        }
                        $aExtend[$sField] = $aTmps;
                    }
                }
                $aPermissionModify['extend'] = $aExtend;
            }
            $aPermissionModify = array();

            if (!empty($aPermissionModify)) {
                //Nếu có quyền riệng thì áp lên quyền của nhóm, nhưng hiên tại 2 quyền có cấu trúc giống nhau nên lấy quyền riêng
                $aPermissionSelected = $aPermissionModify;
            }
            else {
                //Lấy tất cả các quyền của User trong nhóm (lấy quyền các nhóm tiến hành gộp lại)
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('user_group_member'))
                    ->where('status = 1 AND user_id ='.Core::getUserId())
                    ->execute('getRows');
                $aGroupId = array();
                foreach ($aRows as $aRow) {
                    $aGroupId[] = $aRow['group_id'];
                }
                $aGroupPermission = array();
                if (!empty($aGroupId)) {
                    $aRows = $this->database()->select('*')
                        ->from(Core::getT('user_group'))
                        ->where('status = 1 AND id IN ('.implode(',', $aGroupId).')')
                        ->execute('getRows');

                    foreach ($aRows as $aRow) {
                        $aTemp = $this->getPermissionByGroup(array('gid' => $aRow['id']));
                        if (!empty($aTemp)) {
                            $aGroupPermission[] = $aTemp;
                        }
                    }
                }

                if (!empty($aGroupPermission)) {
                     //gộp
                    $aCurrentTmp = array();
                    $aCurrentTmp['priority'] = 0;
                    $aCurrentTmp['extend'] = array();
                    foreach ($aPermissionGOne as $sField) {
                        $aCurrentTmp['extend'][$sField] = array();
                    }
                    foreach ($aPermissionGTwo as $sField) {
                        $aCurrentTmp['extend'][$sField] = 0;
                    }
                    foreach ($aGroupPermission as $aVals) {
                        if ($aVals['priority'] > $aCurrentTmp['priority']) {
                            $aCurrentTmp['priority'] = $aVals['priority'];
                        }
                        foreach ($aVals['extend'] as $sKey => $oVals) {
                            if (in_array($sKey, $aPermissionGOne)) {
                                if (strpos($sKey, 'was_approved') === false) {
                                    foreach ($oVals as $iTmp) {
                                        if (!in_array($iTmp, $aCurrentTmp['extend'][$sKey])) {
                                            $aCurrentTmp['extend'][$sKey][] = $iTmp;
                                        }
                                    }
                                }
                                else {
                                    //những quyền bị kiểm duyệt thì loại bỏ
                                    if (is_array($aCurrentTmp['extend'][$sKey])) {
                                        if (empty($oVals)) {
                                            $aCurrentTmp['extend'][$sKey] = 0;
                                        }
                                        else {
                                            if (empty($aCurrentTmp['extend'][$sKey])) {
                                                //lần đầu tiên
                                                $aCurrentTmp['extend'][$sKey] = $oVals;
                                            }
                                            else {
                                                foreach ($aCurrentTmp['extend'][$sKey] as $sKeyTmp => $iTmp) {
                                                    if (!in_array($iTmp, $oVals)) {
                                                        unset($aCurrentTmp['extend'][$sKey][$sKeyTmp]);
                                                    }
                                                }
                                                if (empty($aCurrentTmp['extend'][$sKey])) {
                                                    $aCurrentTmp['extend'][$sKey] = 0;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                if ($aCurrentTmp['extend'][$sKey] < 1) {
                                    if ($oVals > 0) {
                                        $aCurrentTmp['extend'][$sKey] = $oVals;
                                    }
                                }
                            }

                        }
                    }

                    $aPermissionSelected = $aCurrentTmp;
                }
            }
            //Gán quyền riêng của user lên quyền nhóm

            //lấy quyền mặc định của user nếu chưa có phân quyền hay không thuộc nhóm nào (ko cần thiết tại CMS)

            //Tạo lại bộ quyền đầy đủ
            foreach ($aFullPermission as $sField) {
                if (in_array($sField, $aPermissionGOne)) {
                    if (isset($aPermissionSelected['extend'][$sField]) && is_array($aPermissionSelected['extend'][$sField])) {
                        $aPermissionSelected['extend'][$sField] = implode(',', $aPermissionSelected['extend'][$sField]);
                    }
                    else {
                        $aPermissionSelected['extend'][$sField] = 0;
                    }
                }
            }


        }
        if (empty($aPermissionSelected) && $bIsApi) {
            //Trả về lỗi không có quyền truy cập khi gọi api
            return array(
                'status' => 'error',
                'message' => 'Không có quyền truy cập',
                'code' => 401,
            );
        }

        //set seesion group priority
        $oSession->setArray('session-user', 'priority_group',  $aPermissionSelected['priority']*1);

        foreach ($aFullPermission as $sKey => $sVal) {
            //set value for session permission
            $iTmp = (isset($aPermissionSelected['extend'][$sVal])) ? $aPermissionSelected['extend'][$sVal] : 0;
            $oSession->setArray('session-permission', $sVal, $iTmp);
        }

        $iSessionTmp = $oSession->getArray('session-permission', 'manage_comment');
        if ($iSessionTmp == 1) {
            //set session permission post article confirmed
            $oSession->setArray('session-permission', 'post_article_confirmed', 1);
        }
        //set permission page_type
        $sPageType = ($iVendorId > 0) ? 'sup.' : 'cms.';
        $oSession->setArray('session-permission', 'page_type', $sPageType);
        //if ($sPageType == 'sup.') {
            $oSession->setArray('session-permission', 'vendor_id', $iVendorId);
            $oSession->set('session-vendor', $iVendorId);
        //}

        //set store permission
        $aStorePermission = array();
        $aRows = $this->database()->select('stt, kho_stt, phan_quyen')
            ->from(Core::getT('phan_quyen_kho'))
            ->where('thanh_vien_stt ='.Core::getUserId())
            ->execute('getRows');

        foreach ($aRows as $aRow) {
            if ($aRow['phan_quyen'] > 0) {
                $aStorePermission[$aRow['kho_stt']] = Core::getService('store.permission')->convertPermission($aRow['phan_quyen']);
            }
        }
        $oSession->set('session-store_permission', $aStorePermission);

        if ($bIsApi) {
            return array(
                'status' => 'success',
                'message' => 'Thiết lập quyền thành công',
            );
        }
    }

    //public function getPermission($sPermission)
//    {
//        if(!isset($this->_aPermission[$sPermission]))
//            return array();
//
//        return $this->_aPermission[$sPermission];
//    }


    /**
    * get value permission
    *
    * @param array $aPermissions
    * @param int $iBitMask
    */
    public function getPermissions($aPermissions, $iBitMask = 0) {
        $iCount = 0;
        $aOutput = array();
        foreach ($aPermissions as $sKey => $Value) {
            $aOutput[$sKey] = (($iBitMask & pow(2, $iCount)) != 0) ? true : false;
            //uncomment the next line if you would like to see what is happening.
            //echo $key . " i= ".strval($i)." power=" . strval(pow(2,$i)). "bitwise & = " . strval($bitMask & pow(2,$i))."<br>";
            $iCount++;
        }
        return $aOutput;
    }

    /**
     * This function will create and return and integer bitmask based on the permission values set in
    *the class variable $permissions. To use you would want to set the fields in $permissions to true for the permissions you want to grant.
    *Then call toBitmask() and store the integer value.  Later you can pass that integer into getPermissions() to convert it back to an assoicative
    *array.
    *@return int an integer bitmask represeting the users permission set.
    */
    public function createPermissions($permissions)
    {
        $bitmask =0;
        $i =0;
        foreach($permissions as $key => $value)
        {
            if($value)
            {
                $bitmask += pow(2, $i);
            }
            $i++;
        }
        return $bitmask;
    }

    public function deleteUserPermission($aParam = array())
    {
        $type = 'delete_user_permission';
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $iId = $aParam["id"]*1;


        if (!$oSession->getArray('session-user', 'id')) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'permission_user')!=1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($iId < 1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($iId == $oSession->getArray('session-user', 'id')) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        else {

            //Xóa quyền thành viên
            $this->database()->delete(Core::getT('permission'), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND object_type = 0 AND object_id = '.$iId);
            // xóa session của thành viên
            $this->database()->delete('sessions', 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND user_id = '.$iId);
            // end
            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$iId,'content' => 'phpinfo',));
        }
        if ($bIsReturn) {
            echo '<-errorvietspider->'.Core_Error::get('error');
        }
        else {
            echo '<b>'.Core::getPhrase('language_da-cap-nhat-thanh-cong').'</b>';
        }
    }


    public function getVendorsCurrentUser()
    {
        $oSession = Core::getLib('session');

        $aVendorSelect = array();
        $aVendorSelect = $this->database()->select('v.id, v.name')
            ->from(Core::getT('vendor_permission'), 'p')
            ->join(Core::getT('vendor'), 'v', 'p.vendor_id = v.id')
            ->where('p.object_id ='.Core::getUserId().' AND p.object_type = 0 AND p.status = 1 AND p.domain_id ='.Core::getDomainId())
            ->execute('getRows');

        $aSessionUser = $oSession->get('session-user');
        if ($aSessionUser['user_group_id'] > 0) {
            //check permission vendor this group
            $aVendorGroup = $this->database()->select('v.id, v.name')
                ->from(Core::getT('vendor_permission'), 'p')
                ->join(Core::getT('vendor'), 'v', 'p.vendor_id = v.id')
                ->where('p.object_id ='.$aSessionUser['user_group_id'].' AND p.object_type =1 AND p.status = 1 AND p.domain_id ='.Core::getDomainId())
                ->execute('getRows');

            if (empty($aVendorSelect)) {
                $aVendorSelect = $aVendorGroup;
            }
            else {
                if (!empty($aVendorGroup)) {
                    $aId = array();
                    foreach ($aVendorSelect as $sKey => $aVal) {
                        $aId[] = $aVal['id'];
                    }
                    foreach ($aVendorGroup as $aValue) {
                        if (!in_array($aValue['id'], $aId)) {
                            $aVendorSelect[] = $aValue;
                        }
                    }
                }
            }
        }

        return $aVendorSelect;
    }

    public function initCreatePermissionGroup($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;

        $aData = array();

        //check phân quyền
        $oSession = Core::getLib('session');
        $aCurrentPermission = $oSession->get('session-permission');

        //.....

        if ($iId < 0) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }
        $sConds = 'status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId;
        $aGroup = $this->database()->select('*')
            ->from(Core::getT('user_group'))
            ->where($sConds)
            ->execute('getRow');
        if (!isset($aGroup['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhóm thành viên không tồn tại',
            );
        }
        $aData['info'] = $aGroup;
        $aData['info_permission'] = array_merge($this->_aPermissionGOne, $this->_aPermissionGTwo);
        $aData['field_permission'] = $this->_aFields;
        $aData['group_permission'] = $this->_aPermissionGroup;
        $aData['permission'] = array();
        //Lấy thông tin quyền đang xét hiện tại
        $sConds = 'domain_id ='.Core::getDomainId().' AND group_id ='.$iId;
        $aPermission = $this->database()->select('*')
            ->from(Core::getT('user_group_permission'))
            ->where($sConds)
            ->execute('getRow');
        //d($aPermission);die;
        if (isset($aPermission['id'])) {
            $aData['priority'] = $aPermission['priority'];
            $aTemps = array();
            if ($aPermission['extend_1'] > 0) {
                //lấy lại chi tiết các quyền được set
                $aTemps = $this->getPermissions($this->_aPermissionGOne, $aPermission['extend_1']);
                foreach ($this->_aPermissionGOne as $sKey => $sVal) {
                    $aData['permission'][$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
                }
            }

            $aTemps = array();
            if ($aPermission['extend_1'] > 0) {
                //lấy lại chi tiết các quyền được set
                $aTemps = $this->getPermissions($this->_aPermissionGTwo, $aPermission['extend_2']);
                foreach ($this->_aPermissionGTwo as $sKey => $sVal) {
                    $aData['permission'][$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
                }
            }

        }
        else {
            //chưa được set quyền
        }

        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    public function settingPermissionGroup($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;

        //check phân quyền
        $oSession = Core::getLib('session');
        $aCurrentPermission = $oSession->get('session-permission');

        //.....

        if ($iId < 0) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }
        $aList = isset($aParam['list']) ? $aParam['list'] : array();
        $iPriority = isset($aParam['priority']) ? $aParam['priority'] : 0;


        $sConds = 'status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId;
        $aGroup = $this->database()->select('*')
            ->from(Core::getT('user_group'))
            ->where($sConds)
            ->execute('getRow');
        if (!isset($aGroup['id'])) {
            return array(
                'status' => 'error',
                'message' => 'Nhóm thành viên không tồn tại',
            );
        }

        //so khớp, lấy lại những quyền được thiết lập
        $aPerSelected = array();
        foreach($this->_aPermissionGOne as $sVals) {
            $aPerSelected[$sVals] = isset($aList[$sVals]) ? $aList[$sVals] : 0;
        }
        $iExtend1 = $this->createPermissions($aPerSelected);

        $aPerSelected = array();
        foreach($this->_aPermissionGTwo as $sVals) {
            $aPerSelected[$sVals] = isset($aList[$sVals]) ? $aList[$sVals] : 0;
        }
        $iExtend2 = $this->createPermissions($aPerSelected);

        $sConds = 'domain_id ='.Core::getDomainId().' AND group_id ='.$iId;
        $aOldPermission = $this->database()->select('*')
            ->from(Core::getT('user_group_permission'))
            ->where($sConds)
            ->execute('getRow');
        $aUpdate = array(
            'priority' => $iPriority,
            'extend_1' => $iExtend1,
            'extend_2' => $iExtend2,
        );
        if (isset($aOldPermission['id'])) {
            $this->database()->update(Core::getT('user_group_permission'), $aUpdate, 'id ='.$aOldPermission['id']);
        }
        else {
            $aUpdate['group_id'] = $iId;
            $aUpdate['domain_id'] = Core::getDomainId();
            $this->database()->insert(Core::getT('user_group_permission'), $aUpdate);
        }

        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array(
            'action' => 'update_user_group_permission'.'-'.$iId,
            'content' => 'phpinfo',
        ));
        //cập nhật lại quyền cho chính user đang đăng nhập ?

        return array(
            'status' => 'success',
            'data' => array(),
        );

    }

    public function initCreatePermissionModify($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        //type 0: user, 1: group
        $iType = isset($aParam['otype']) ? $aParam['otype'] : 0;
        $iVendorId = isset($aParam['vid']) ? $aParam['vid'] : 0;
        $aData = array();

        //check phân quyền
        $oSession = Core::getLib('session');
        $aCurrentPermission = $oSession->get('session-permission');

        //.....

        if ($iId < 0) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }
        $aData = array();
        $sConds = '';
        $iObjType = 0;
        if ($iType == 1) {
            $sCondTmp = 'status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId;
            $aGroup = $this->database()->select('*')
                ->from(Core::getT('user_group'))
                ->where($sCondTmp)
                ->execute('getRow');
            if (!isset($aGroup['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Nhóm thành viên không tồn tại',
                );
            }
            $aData['info'] = array(
                'id' => $aGroup['id'],
                'name' => $aGroup['name'],
            );
            $iObjType = 1;
        }
        else if ($iType == 0){
            //user
            $sCondTmp = 'status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId;
            $aUser = $this->database()->select('id, fullname, username, email')
                ->from(Core::getT('user'))
                ->where($sCondTmp)
                ->execute('getRow');
            if (!isset($aUser['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Thành viên không tồn tại',
                );
            }
            $aData['info'] = array(
                'id' => $aUser['id'],
                'name' => (!empty($aUser['fullname'])) ? $aUser['fullname'] : $aUser['email'],
            );
            $iObjType = 0;
        }
        $aAllCategory = array();
        $iCategoryIndex = Core::getParam('setting_category_index');
        $aCateList = array();
        $aRows = $this->database()->select('id, name, parent_id, path')
            ->from(Core::getT('category'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId())
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aAllCategory[] = $aRow['id'];
            $aCateList[] =  array($aRow["id"], $aRow["name"], $aRow["parent_id"], 'path' => $row["path"]);
        }
        $sListAllCategory = implode(',', $aAllCategory);

        //đệ quy sắp xếp đề tài;
        $aCategories = array();
        $this->_aRecursiveTmp = array();
        $this->recursiveCategory(-1, $aCateList);
        $aCategories = $this->_aRecursiveTmp;

        $aPermissionGOne = $this->_aPermissionGOne;
        $aPermissionGTwo = $this->_aPermissionGTwo;
        //Lấy thông tin quyền Chung (dành cho Nhóm)
        $aPermissionGeneral = array();
        //Lấy quyền chung
        $iPriorityTmp = 0;
        $aExtend = array();
        $iExtend1 = 0;
        $iExtend2 = 0;
        if ($iObjType == 1) {
            $sCondTmp = 'domain_id ='.Core::getDomainId().' AND group_id ='.$iId;
            $aRow = $this->database()->select('*')
                ->from(Core::getT('user_group_permission'))
                ->where($sCondTmp)
                ->execute('getRow');

            if (isset($aRow['id'])) {
                $iPriorityTmp = $aRow['priority'];
                $iExtend1 = $aRow['extend_1'];
                $iExtend2 = $aRow['extend_2'];
            }
        }
        else {

        }

        if ($iExtend1 > 0) {
            //lấy lại chi tiết các quyền được set
            $aTemps = $this->getPermissions($aPermissionGOne, $iExtend1);
            foreach ($aPermissionGOne as $sKey => $sVal) {
                $aExtend[$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
            }
        }

        if ($iExtend2 > 0) {
            //lấy lại chi tiết các quyền được set
            $aTemps = $this->getPermissions($aPermissionGTwo, $iExtend2);
            foreach ($aPermissionGTwo as $sKey => $sVal) {
                $aExtend[$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
            }
        }
        /*
        foreach ($aExtend as $sKey => $iVal) {
            if (in_array($sKey, $aPermissionGOne) && $iVal > 0) {
                $aExtend[$sKey] = $sListAllCategory;
            }
        }
        */
        $aPermissionGeneral['priority'] = $iPriorityTmp;
        $aPermissionGeneral['extend'] = $aExtend;
        $aPermission = array();
        $aPermission['priority'] = 0;
        $aPermission['extend'] = array();
        //Lấy thông tin quyền được custom => Nếu chưa có thì lấy quyền chung
        $sConds = 'object_type ='.$iObjType.' AND object_id ='.$iId.' AND domain_id ='.Core::getDomainId();
        $aModify = $this->database()->select('*')
            ->from(Core::getT('permission_modify'))
            ->where($sConds)
            ->execute('getRow');
        if (isset($aModify['object_id'])) {
            $aApprovedField = array(
                'was_approved_article',
                'was_approved_comment',
            );
            //Quyền riêng
            $aPermission['priority'] = $aModify['priority'];
            $aExtend = array();
            if ($aModify['extend_2'] > 0) {
                $aTemps = $this->getPermissions($aPermissionGTwo, $aModify['extend_2']);
                foreach ($aPermissionGTwo as $sKey => $sVal) {
                    $aExtend[$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
                }
            }
            foreach ($aPermissionGOne as $sField) {
                $aExtend[$sField] = 0;
                if (isset($aModify[$sField])) {
                    $aModify[$sField] = unserialize($aModify[$sField]);
                    $aTmps = array();
                    $iHasAllPermission = 0;
                    if (isset($aPermissionGeneral['extend'][$sField]) && $aPermissionGeneral['extend'][$sField]) {
                        $iHasAllPermission = 1;
                    }
                    foreach ($aAllCategory as $iCategoryTmp) {
                        if (isset($aModify[$sField][$iCategoryTmp])) {
                            if ($aModify[$sField][$iCategoryTmp] > 0) {
                                $aTmps[] = $iCategoryTmp;
                            }

                        }
                        else if (!in_array($sField, $aApprovedField) && $iHasAllPermission){
                            $aTmps[] = $iCategoryTmp;
                        }
                    }
                    if (!empty($aTmps)) {
                        $aExtend[$sField] = implode(',', $aTmps);
                    }
                }
                else {
                    $aExtend[$sField] = 0;
                }
            }
            $aPermission['extend'] = $aExtend;
        }
        else {
            //Chưa có quyền riêng thì lấy quyền chung
            if (isset($aPermissionGeneral['extend'])) {
                foreach ($aPermissionGeneral['extend'] as $sKey => $iVal) {
                    if (in_array($sKey, $aPermissionGOne) && $iVal > 0) {
                        $aPermissionGeneral['extend'][$sKey] = $sListAllCategory;
                    }
                }
            }
            $aPermission = $aPermissionGeneral;
        }

        //Tách extend 2 nhóm: nhóm theo danh mục và nhóm còn lại
        $aData['priority'] = $aPermission['priority'];
        $aData['type'] = $iType;
        $aData['category'] = array();
        $aData['extend'] = array();
        foreach ($aPermissionGOne as $sField) {
            $aData['category'][$sField] = isset($aPermission['extend'][$sField]) ? $aPermission['extend'][$sField] : 0;
        }
        foreach ($aPermissionGTwo as $sField) {
            $aData['extend'][$sField] = isset($aPermission['extend'][$sField]) ? $aPermission['extend'][$sField] : 0;
        }

        $aData['vendor_id'] = $iVendorId;
        $aData['category_list'] = $aCateList;
        $aData['category_recursive'] = $aCategories;
        $aData['category_field'] = $aPermissionGOne;

        $aData['field_permission'] = $this->_aFields;
        $aData['group_permission'] = $this->_aPermissionGroup;

        //Loại bỏ các trường theo danh mục
        unset($aData['group_permission']['cate']);
        unset($aData['group_permission']['article']);
        unset($aData['group_permission']['cmt']);
        unset($aData['group_permission']['approve_atc']);
        unset($aData['group_permission']['approve_cmt']);

        return array(
            'status' => 'success',
            'data' => $aData,
        );
    }

    private function recursiveCategory($iParentId, $aMenu, $res = '', $sep = '')
    {
        foreach($aMenu as $aVal) {
            if ($aVal[2] == $iParentId) {
                $aVal['permission'] = 1;
                if ($aVal[1] == '***') {
                    $aVal['permission'] = 0;
                }

                $this->_aRecursiveTmp[] = array(
                    $aVal[0],
                    $sep.$aVal[1],
                    'parent_id' => $iParentId,
                    'path' => $aVal["path"],
                    'permission' => $aVal['permission'],
                    'image_path' => $aVal['image_path'],
                );
                $res.=$this->recursiveCategory($aVal[0],$aMenu,$re,$sep."&nbsp;&nbsp;&nbsp;");
            }
        }
    }

    public function settingPermissionModify($aParam = array())
    {
        $iId = isset($aParam['id']) ? $aParam['id'] : -1;
        //type 0: user, 1: group
        $iType = isset($aParam['otype']) ? $aParam['otype'] : 0;
        $iVendorId = isset($aParam['vid']) ? $aParam['vid'] : 0;

        if ($iId < 0) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu',
            );
        }
        $aList = isset($aParam['list']) ? $aParam['list'] : array();
        $iPriority = isset($aParam['priority']) ? $aParam['priority'] : 0;
        $aPermissionCategory = isset($aParam['category']) ? $aParam['category'] : array();

        if ($iType == 0) {
            $sConds = 'status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId;
            $aUser = $this->database()->select('id, fullname, username, email')
                ->from(Core::getT('user'))
                ->where($sConds)
                ->execute('getRow');
            if (!isset($aUser['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Thành viên không tồn tại',
                );
            }
        }
        else if ($iType == 1) {
            $sConds = 'status != 2 AND domain_id ='.Core::getDomainId().' AND id ='.$iId;
            $aGroup = $this->database()->select('*')
                ->from(Core::getT('user_group'))
                ->where($sConds)
                ->execute('getRow');
            if (!isset($aGroup['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Nhóm thành viên không tồn tại',
                );
            }
        }




        $aPermissionGOne = $this->_aPermissionGOne;
        $aPermissionGTwo = $this->_aPermissionGTwo;
        //so khớp, lấy lại những quyền được thiết lập
        $iExtend1 = 0;
        /*
        $aPerSelected = array();
        foreach($aPermissionGOne as $sVals) {
            $aPerSelected[$sVals] = isset($aList[$sVals]) ? $aList[$sVals] : 0;
        }
        $iExtend1 = $this->createPermissions($aPerSelected);
        */

        $aPerSelected = array();
        foreach($aPermissionGTwo as $sVals) {
            $aPerSelected[$sVals] = isset($aList[$sVals]) ? $aList[$sVals] : 0;
        }
        $iExtend2 = $this->createPermissions($aPerSelected);

        //Tạo danh sách accept and deny theo danh mục
        $aDataCategory = array();
        foreach ($aPermissionGOne as $sVals) {
            $aDataCategory[$sVals] = array();
            if (isset($aPermissionCategory[$sVals])) {
                if (isset($aPermissionCategory[$sVals]['access']) && !empty($aPermissionCategory[$sVals]['access'])) {
                    $aTmps = explode(',', $aPermissionCategory[$sVals]['access']);
                    foreach ($aTmps as $iTmp) {
                        $aDataCategory[$sVals][$iTmp] = 1;
                    }
                }
                if (isset($aPermissionCategory[$sVals]['deny']) && !empty($aPermissionCategory[$sVals]['deny'])) {
                    $aTmps = explode(',', $aPermissionCategory[$sVals]['deny']);
                    foreach ($aTmps as $iTmp) {
                        $aDataCategory[$sVals][$iTmp] = 0;
                    }
                }
                if (!empty($aDataCategory[$sVals])) {
                    ksort($aDataCategory[$sVals]);
                }
            }
        }

        $sConds = 'domain_id ='.Core::getDomainId().' AND object_id ='.$iId.' AND object_type ='.$iType;
        $aOldPermission = $this->database()->select('*')
            ->from(Core::getT('permission_modify'))
            ->where($sConds)
            ->execute('getRow');
        $aUpdate = array(
            'priority' => $iPriority,
            'extend_1' => $iExtend1,
            'extend_2' => $iExtend2,
        );

        foreach ($aDataCategory as $sKey => $aVals) {
            $aUpdate[$sKey] = serialize($aVals);
        }

        if (isset($aOldPermission['object_id'])) {
            $this->database()->update(Core::getT('permission_modify'), $aUpdate, $sConds);
        }
        else {
            $aUpdate['object_id'] = $iId;
            $aUpdate['object_type'] = $iType;
            $aUpdate['status'] = 1;
            $aUpdate['domain_id'] = Core::getDomainId();
            $this->database()->insert(Core::getT('permission_modify'), $aUpdate);
        }

        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array(
            'action' => 'update_modify_permission'.'-'.$iId.'-'.$iType,
            'content' => 'phpinfo',
        ));
        //cập nhật lại quyền cho chính user đang đăng nhập ?

        return array(
            'status' => 'success',
            'data' => array(),
        );
    }

    public function getPermissionByGroup($aParam = array())
    {
        $iGroupId = isset($aParam['gid']) ? $aParam['gid'] : -1;
        $aData = array();
        if ($iGroupId < 1) {
            return $aData;
        }
        $aPermissionModify = array();
        //Lấy danh sách danh mục
        $aAllCategory = array();
        $aRows = $this->database()->select('id')
            ->from(Core::getT('category'))
            ->where('status != 2 AND domain_id ='.Core::getDomainId())
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aAllCategory[] = $aRow['id'];
        }
        $aPermissionGOne = $this->_aPermissionGOne;
        $aPermissionGTwo = $this->_aPermissionGTwo;
        //Lấy thông tin quyền chung
        $aPermissionGeneral = array();
        $sConds = 'group_id ='.$iGroupId.' AND domain_id ='.Core::getDomainId();
        $aGeneral = $this->database()->select('*')
            ->from(Core::getT('user_group_permission'))
            ->where($sConds)
            ->execute('getRow');
        //d($aGeneral);die;
        if (isset($aGeneral['id'])) {
            $aPermissionGeneral['priority'] = $aGeneral['priority'];
            $aExtend = array();
            if ($aGeneral['extend_1'] > 0) {
                $aTemps = $this->getPermissions($aPermissionGOne, $aGeneral['extend_1']);
                foreach ($aPermissionGOne as $sKey => $sVal) {
                    if (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) {
                        $aExtend[$sVal] = $aAllCategory;
                    }
                    else {
                        $aExtend[$sVal] = 0;
                    }
                }
            }

            if ($aGeneral['extend_2'] > 0) {
                $aTemps = $this->getPermissions($aPermissionGTwo, $aGeneral['extend_2']);
                foreach ($aPermissionGTwo as $sKey => $sVal) {
                    $aExtend[$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
                }
            }
            $aPermissionGeneral['extend'] = $aExtend;
        }

        //Lấy thông tin Quyền riêng
        $sConds = 'object_id ='.$iGroupId.' AND object_type = 1 AND domain_id ='.Core::getDomainId();
        $aModify = $this->database()->select('*')
            ->from(Core::getT('permission_modify'))
            ->where($sConds)
            ->execute('getRow');

        if (isset($aModify['object_id'])) {
            $aApprovedField = array(
                'was_approved_article',
                'was_approved_comment',
            );
            $aPermissionModify['priority'] = $aModify['priority'];
            $aExtend = array();
            if ($aModify['extend_2'] > 0) {
                $aTemps = $this->getPermissions($aPermissionGTwo, $aModify['extend_2']);
                foreach ($aPermissionGTwo as $sKey => $sVal) {
                    $aExtend[$sVal] = (isset($aTemps[$sKey]) && $aTemps[$sKey] > 0) ? $aTemps[$sKey] : 0;
                }

            }
            foreach ($aPermissionGOne as $sField) {
                $aExtend[$sField] = array();
                if (isset($aModify[$sField])) {
                    $aModify[$sField] = unserialize($aModify[$sField]);
                    $aTmps = array();
                    $iHasAllPermission = 0;
                    if (isset($aPermissionGeneral['extend'][$sField]) && !empty($aPermissionGeneral['extend'][$sField])) {
                        $iHasAllPermission = 1;
                    }
                    foreach ($aAllCategory as $iCategoryTmp) {
                        if (isset($aModify[$sField][$iCategoryTmp])) {
                            if ($aModify[$sField][$iCategoryTmp] > 0) {
                                $aTmps[] = $iCategoryTmp;
                            }
                        }
                        else if (!in_array($sField, $aApprovedField) && $iHasAllPermission){
                            //Không gộp quyền bị kiểm duyệt
                            $aTmps[] = $iCategoryTmp;
                        }
                    }
                    if (!empty($aTmps)) {
                        $aExtend[$sField] = $aTmps;
                    }
                }
            }
            $aPermissionModify['extend'] = $aExtend;
        }
        //Áp quyền riêng lên quyền chung

        if (!empty($aPermissionGeneral) && !empty($aPermissionModify)) {
            //bước này cần áp quyền riêng lên quyền chung, nhưng hiên tại cấu trúc 2 quyền này giống nhau nêu chỉ cần áp dụng quyền riêng
            $aData = $aPermissionModify;
        }
        else if (!empty($aPermissionGeneral)){
            $aData = $aPermissionGeneral;
        }
        else if (!empty($aPermissionModify)){
            $aData = $aPermissionModify;
        }
        /*
        if (isset($aData['extend']) && !empty($aData['extend'])) {
            foreach ($aPermissionGOne as $sKey => $sVal) {
                if (isset($aData['extend'][$sVal])) {
                    $aData['extend'][$sVal] = implode(',', $aData['extend'][$sVal]);
                }
            }
        }
        */
        return $aData;
    }
}
?>
