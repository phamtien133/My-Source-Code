<?php
class User_Service_Auth extends Service
{
    public function __construct()
    {
        $this->_sTable = core::getT('user');
    }

    public function handleStatus($aParam = array())
    {
        if (isset($aParams['affid'])) {
            $affid = Core::getLib('input')->removeXSS($aParams['affid']);
            // create cookie
            Core::setCookie('affid', $affid, CORE_TIME + 31536000, '/', ".".$_SERVER['HTTP_HOST']); //31536000 = 365*24*3600
            unset($affid);
        }
        $oSession = Core::getLib('session');
        if ($oSession->get('session-visitol') == 0) {
            Core::getService('user')->getOnlineUser();
            $iTmp = $oSession->get('session-visittotal');
            $oSession->set('session-visittotal', $iTmp++);
            $this->database()->update(Core::getT('domain_setting'), array(
                'total_view' => 'total_view + 1'
            ), 'domain_id = '. Core::getDomainId());
        }
        $aAdvertisement = array();
        if (!$oSession->isExist('ads')) {
            Core::getService('ads')->load();
        }
        foreach ($oSession->get('ads') as $Key => $Val) {
            if ($Val != 1) {
                if ($oSession->getArray('ads_appear_count', $Key) > 0) {
                    $iCount = $oSession->getArray('ads_ad_count',$Key);
                    if ($iCount >= $oSession->getArray('ads_appear_count', $Key)) {
                        $oSession->setArray('ads', $Key, 1); // disable quảng cáo
                    }
                    else {
                        $iCount++;
                        $oSession->setArray('ads_ad_count', $Key, $iCount);
                    }
                }
            }
            if($oSession->getArray('ads', $Key) == 2)
                $aAdvertisement[$Key] = 1;
        }
        // calculate time for support
        $iEnableChat = 0;
        if (!is_array($oSession->get('session-chat_config')) && !Core::isParam('setting_online_support')) {
            Core::getService('domain')->loadSession();
        }

        if (Core::getParam('setting_online_support') == 1 && ! is_array($oSession->get('session-chat_config'))) {
            // tính thứ hôm nay
            if ($oSession->getArray('session-chat_config', 'th') != date('N') + 1) {
                $oSession->remove('session-chat_config');
            }
            // end
            if (!is_array($oSession->get('session-chat_config'))
                && ( ($oSession->getArray('session-user', 'id') > 0
                && $oSession->getArray('session-user', 'priority_group') == -1)
                || $oSession->getArray('session-user', 'id') == 0)) {
                    $oSession->set('session-chat_config', array());
                    $oSession->setArray('session-chat_config', 'th', date('N') + 1);
                    $aRows = $this->database()->select('from_time, to_time, day_of_week')
                        ->from(Core::getT('chat_config'))
                        ->where('domain_id='. Core::getDomainId())
                        ->execute('getRows');

                    $iCount = 0;
                    foreach ($aRows as $aRow) {
                        $oSession->setArray('session-chat_config', $iCount, array(
                            $aRow['from_time'],
                            $aRow['to_time'],
                            $aRow['day_of_week']
                        ));
                        $iCount++;
                    }
            }
        }

        $aChatConfig = $oSession->get('session-chat_config');
        if (!empty($aChatConfig)
            && ( (Core::getUserId() > 0
            && $oSession->getArray('session-user', 'priority_group') == -1)
            || Core::getUserId() == 0)) {
            foreach ($aChatConfig as $aChatConfig) {
                $bIsExist = false;
                $iTimeTmp = date('H')*3600 + date('i')*60 + date('s'); // giờ phút giây
                if ($iTimeTmp >= $aChatConfig[0] && $iTimeTmp <= $aChatConfig[1]) {
                    $iEnableChat = 1;
                    break;
                }
            }
        }

        $aOutput = array(
            'visitol' => $oSession->get('session-visitol'),
            'visittotal' => $oSession->get('session-visittotal'),
            'uid' => Core::getUserId(),
            'ulink' => $oSession->getArray('session-user', 'path'),
            'ulike' => $oSession->getArray('session-user', 'total_like'),
            'uimage' => $oSession->getArray('session-user', 'image_path'),
            'uname' => Core::getUserName(),
            'chat' => $iEnableChat
        );
        foreach($aAdvertisement as $Key => $Val)
        {
            $aOutput['Advertisement'][] = $Key;
        }
        return array(
            'status' => 'success',
            'data' => $aOutput
        );
    }

    public function verify($aParam)
    {
        $sCaptcha = strtolower($aParam['captcha']);
        $oSession = Core::getLib('session');
        $aLoginSession = $oSession->get('session-login');

        $sVerifyCode = $aLoginSession['active_code'];
        $sVerifyCode = strtolower($sVerifyCode);
        if(!empty($sCaptcha)) {
            if($sCaptcha != $sVerifyCode)
                return array(
                    'status' => 'error',
                    'message' => Core::getPhrase('language_ma-xac-nhan-nhap-sai').'-'.$sVerifyCode
                );
        }
        else
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_ma-xac-nhan-nhap-sai')
            );

        if (!isset($aLoginSession['username']) || !isset($aLoginSession['passwd'])) {
            return array(
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra.'
            );
        }

        // remove active field in database
        $this->database()->update(Core::getT('active_code'),array(
            'status' => 2
        ), 'domain_id ='. Core::getDomainId(). ' AND type = \'auth_acc\' AND status = 1 AND note = \''. $this->database()->escape($aLoginSession['username']) .'\' AND active_code = \''. $this->database()->escape($sVerifyCode).'\'' );

        // create param login again and call login
        $aReturn = $this->login(array(
            'email' => $aLoginSession['username'],
            'passwd' => $aLoginSession['passwd'],
            'remember' => 1,
            'refer' => $aLoginSession['refer'],
            'verify' => 0
        ));
        return $aReturn;
    }

    public function loginSuccess($aParam)
    {
        $oSession = Core::getLib('session');

        $oSession->setArray('session-user', 'id', $aParam['id']);
        $oSession->setArray('session-user', 'code', $aParam['code']);
        if (isset($aParam['full_name']) && !empty($aParam['full_name'])) {
            $oSession->setArray('session-user', 'name', $aParam['full_name']);
        }
        else {
            $oSession->setArray('session-user', 'name', $aParam['username']);
        }
        $oSession->setArray('session-user', 'path', $aParam['path']);
        $oSession->setArray('session-user', 'image_path', $aParam['profile_image']);
        $oSession->setArray('session-user', 'like', $aParam['total_like']);
        $oSession->setArray('session-user', 'user_group_id', $aParam['user_group_id']);
        $oSession->setArray('session-user', 'last_visit', $aParam['last_visit']);
       // $oSession->setArray('session-user', 'last_visit', $aParam['last_visit']);
        $oSession->setArray('session-user', 'login_count', 0);
        $oSession->setArray('session-user', 'login_time', 0);
        $oSession->setArray('session-user', 'post_comment', 1);

        // update cart
        //Core::getService('shop.cart')->updateCartAfterLogin();
        // update permission
        if (Core::getDomainName() == 'marrybaby.com' || Core::getDomainName() == 'vdg.vn') {
            Core::getService('user.permission')->updatePermissionNew();
        }
        else {
            Core::getService('user.permission')->updatePermission();
        }
        $this->database()->update(core::getT('user'), array(
            'last_visit' => CORE_TIME
        ), 'domain_id ='. Core::getDomainId() .' AND id = '. $aParam['id']);

        //Core::getService('core.tools')->openProcess('/tools/cronjob/tuong_thanh_vien.php', array(
//            'sid' => session_id(),
//            'domain' => $_SERVER['HTTP_HOST']
//        ));

        // update log
        $this->database()->update(COre::getT('log_access_sid'), array(
            'user_id' => $aParam['id']
        ), 'domain_id ='. Core::getDomainId(). ' AND session_id = \''. $this->database()->escape(session_id()).'\'');

        $iPriorityGroup = $oSession->getArray('session-user', 'priority_group');
        // update session
        $this->database()->update('sessions', array(
            'user_id' => $aParam['id'],
            'user_fullname' => $aParam['username'],
            'priority_group' => $iPriorityGroup
        ), ' domain_id = '. Core::getDomainId(). ' AND id = \''. $this->database()->escape(session_id()).'\'');
    }

    /**
    * login to system.
    *
    * @param mixed $aParam
    * @return string refer.
    */
    public function login($aParam)
    {
        $sUserName = '';
        $sLoginType = 'username';
        $aParam['verify'] = 0;
        if (isset($aParam['email']) && !empty($aParam['email'])) {
            $sEmail = Core::getLib('input')->removeXSS($aParam['email']);
            $sEmail = trim($sEmail);
            // Check hop_thu
            if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $sEmail)==0)
                Core_Error::set('error', Core::getPhrase('language_hop-thu-khong-dung-cau-truc'));
            if (strlen($sEmail) <= 3 || strlen($sEmail) > 224)
                Core_Error::set('error', sprintf(Core::getPhrase('language_hop-thu-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 3, 224).'!<br />');
            $sEmail = strtolower($sEmail);
            $sLoginType ='email';
            $sUserName = $sEmail;
            // khi đăng nhâp bằng email, sẽ bị lỗi phần $ten_truy_cap
        }
        else if(isset($aParam['username'])) {
            $sUserName = htmlspecialchars(Core::getLib('input')->removeXSS($aParam['username']));
            $sUserName = strtolower($sUserName);
            $iRemember = 1;
            $sRefer = isset($aParam['refer']) ? $aParam['refer'] : '/';

            if (empty($sUserName) || empty($aParam['passwd'])) {
                Core_Error::set('error', Core::getPhrase('language_sai-ten-truy-cap-hoac-mat-khau'));
            }
            if (preg_match('/^[a-z0-9-.]+$/', $sUserName) == 0)
                Core_Error::set('error', Core::getPhrase('language_ten-dang-nhap-khong-duoc-cho-phep'));
        }
        // kiểm tra xem user có đang bị tạm khóa vì nhập sai pass không, bằng cách check bảng ma_xac_nhan
        if (Core_Error::isPassed()) {
            $oSession = Core::getLib('session');
            $aRow = array();
            if((isset($aParam['verify']) && $aParam['verify'] == 1) && !empty($sUserName)) {
                // lấy mã xác thực tồn tại, và lấy random
                $aRow = $this->database()->select('active_code')
                    ->from(Core::getT('active_code'))
                    ->where('domain_id = '. Core::getDomainId() .' AND type = "auth_acc" AND status = 1 AND note = "'. $this->database()->escape($sUserName).'" AND expire_time < '. (CORE_TIME + 900))
                    ->order('rand()')
                    ->limit(1)
                    ->execute('getRow');
            }
            if(!empty($aRow['active_code'])) {
                // save verify info to session.  chứng thực
                $oSession->set('session-login', array(
                    'username' => $sUserName,
                    'passwd' => $aParam['passwd'],
                    'remember' => $iRemember,
                    'refer' => $sRefer,
                ));
                $oSession->setArray('session-login', 'active_code', $aRow['active_code']);
                // require captcha to verify again.
                return array(
                    'status' => 'success',
                    'data' => array(
                        'captcha' => Core::getParam('core.main_path'). '/tools/image.php?id=login',
                    )
                );
            }

            // check login.
            $aReturn = $this->_verify(array(
                'username' => $sUserName,
                'passwd' => $aParam['passwd'],
                'refer' => $sRefer,
                'type' => $sLoginType
            ));

            return $aReturn;
        }
        return array(
            'status' => 'error',
            'message' => Core_Error::getString()
        );
    }


    public function loginWithApi($aParam)
    {
        $iStatus = 1;
        $bIsPass = false;
        $oSession = Core::getLib('session');
        $sType = $aParam['lgtype'];
        if (empty($sType))
            $sType = 'username';
        if($sType == 'email') {
            $sEmail = $aParam['username'];
            // check login with email (via openid)
            $iLoginDomain = $oSession->get('session-login_domain');
            if($iLoginDomain < 1)
                $iLoginDomain = Core::getDomainId();

            $aRow = $this->database()->select('id, code, user_group_id, username, email, password, password_security, last_visit, openid, total_like, old_id')
                ->from($this->_sTable)
                ->where('domain_id ='. $iLoginDomain .' AND email = \''. $this->database()->escape($sEmail).'\' AND status = 0 ')
                ->limit(1)
                ->execute('getRow');

            if (isset($aRow['id']) && $aRow['id'] > 0) {
                if($aRow['old_id'] == 1) {
                    // login with old account on backup site.
                    $sPassword = md5(mb_convert_encoding($aParam['passwd'], "UTF-16LE"));
                    $sPassword = trim($sPassword);
                    if ($sPassword == strtolower($aRow['password'])) {
                       // login success, create new security code and save new pass with security code.
                        $sSalt = rand(100,999);
                        $sNewPass = md5(md5($aParam['passwd']).$sSalt);
                        $this->database()->update(core::getT('user'), array(
                            'password' => $sNewPass,
                            'password_security' => $sSalt,
                            'old_id' => 0
                        ), 'id = '. $aRow['id']);
                        $bIsPass = true;
                    }
                    if (empty($aRow['username'])) {
                        $aRow['username'] = $aRow['id'];
                    }
                }
                else {
                    if ($aRow['openid'] > 0) {
                        return array(
                            'status' => 'error',
                            'message' => 'Thành viên đã tồn tại, vui lòng sử dụng tài khoản khác'
                        );
                    }
                    else {
                        if (empty($aRow['username']) && empty($aRow['password']) && empty($aParam['passwd'])) {
                            $bIsPass = true;
                            $aRow['email'] = $sEmail;
                            $sUserName = Core::getService('core.tools')->cutString($sEmail, 32);
                            $sUserName = 'E|'.$sUserName;
                            $aRow['username'] = $sUserName;
                            unset($sUserName);
                        }
                        else {
                            $sPassword = md5(md5($aParam['passwd']).$aRow["password_security"]);
                            if($sPassword == $aRow['password']) {
                                $bIsPass = true;
                                if (empty($aRow['username'])) {
                                    $aRow['username'] = $aRow['id'];
                                }
                            }
                        }
                    }
                }
                if (!$bIsPass) {
                    // not match password, increase login count to avoid detects password by tools.
                    unset($iTimes);
                    Core_Error::set('error', Core::getPhrase('language_sai-ten-truy-cap-hoac-mat-khau'));
                }
            }
            else {
                $sCode = Core::getService('core.tools')->getUniqueCode();
                $aInsert = array(
                    'code' => $this->database()->escape($sCode),
                    'username' => '',
                    'password' => '',
                    'email' => $this->database()->escape($sEmail),
                    'join_time' => CORE_TIME,
                    'birthday' => '',
                    'ip_address' => Core::getLib('request')->getIp(),
                    'password_security' => '',
                    'domain_id' => Core::getDomainId()
                );
                $iId = $this->database()->insert($this->_sTable, $aInsert);
                $aRow['id'] = $iId;
                $aRow['email'] = $sEmail;
                $sUserName = Core::getService('core.tools')->cutString($sEmail, 32);
                $sUserName = 'E|'.$sUserName;
                $aRow['username'] = $sUserName;
                unset($sUserName);
                $bIsPass = true;
            }
        }
        else {
            // check login with username.
            $aRow = $this->database()->select('id, code, user_group_id, username, email, password, password_security, last_visit, openid, total_like')
                ->from($this->_sTable)
                ->where('domain_id ='. Core::getDomainId() .' AND username = \''. $this->database()->escape($aParam['username']).'\' AND status = 0 ')
                ->limit(1)
                ->execute('getRow');

            $sPassword = md5($aParam['passwd'].$aRow["password_security"]); // $sPassword have been encode on browser before submit to server.

            if($sPassword == $aRow["password"])
                $bIsPass = true;
            if(!$bIsPass) {
                // not match password, increase login count to avoid detects password by tools.
                unset($iTimes);
                Core_Error::set('error', Core::getPhrase('language_sai-ten-truy-cap-hoac-mat-khau'));
            }
        }

        $aRow['user_group_id'] *= 1;
        $aPermission = array();
        if($bIsPass) {
            $iStatus = 3;
        }
        if ($iStatus == 1) {
            return array(
                'status' => 'error',
                'message' => Core_Error::getString()
            );
        }
        $sReferEncode = $aParam['lgrefer'];
        if (!empty($sReferEncode) && $iStatus == 3) {
            $sReferEncode = Core::getLib('input')->RemoveXSS($sReferEncode);
            if($sReferEncode == '' || !Core::getService('domain')->checkUrl($sReferEncode))
                $sReferEncode = '/';
        }

        $sReferEncode = base64_encode($sReferEncode);
        $aRow['refer'] = $sReferEncode;
        return array(
            'status' => 'success',
            'data' => $aRow
        );
    }

    /**
    * check login data.
    * this a step 2 when login process.
    *
    * @param array $aParam
    */
    private function _verify($aParam)
    {

        $iStatus = 1;
        $bIsPass = false;
        $oSession = Core::getLib('session');
        $sType = $aParam['type'];
        if (empty($sType))
            $sType = 'username';
        if($sType == 'email') {
            $sEmail = $aParam['username'];
            // check login with email (via openid)
            $iLoginDomain = $oSession->get('session-login_domain');
            if($iLoginDomain < 1)
                $iLoginDomain = Core::getDomainId();

            $aRow = $this->database()->select('id, code, user_group_id, username, email, password, password_security, last_visit, openid, total_like, old_id')
                ->from($this->_sTable)
                ->where('domain_id ='. $iLoginDomain .' AND email = \''. $this->database()->escape($sEmail).'\' AND status = 0 AND reference_id = 0')
                ->limit(1)
                ->execute('getRow');

            if (isset($aRow['id']) && $aRow['id'] > 0) {
                if($aRow['old_id'] == 1) {
                    // login with old account on backup site.
                    $sPassword = md5(mb_convert_encoding($aParam['passwd'], "UTF-16LE"));
                    $sPassword = trim($sPassword);
                    if ($sPassword == strtolower($aRow['password'])) {
                       // login success, create new security code and save new pass with security code.
                        $sSalt = rand(100,999);
                        $sNewPass = md5(md5($aParam['passwd']).$sSalt);
                        $this->database()->update(core::getT('user'), array(
                            'password' => $sNewPass,
                            'password_security' => $sSalt,
                            'old_id' => 0
                        ), 'id = '. $aRow['id']);
                        $bIsPass = true;
                    }
                    if (empty($aRow['username'])) {
                        $aRow['username'] = $aRow['id'];
                    }
                }
                else {
                    if ($aRow['openid'] > 0) {
                        Core_Error::set('username', 'Thành viên đã tồn tại, vui lòng sử dụng tài khoản khác');
                    }
                    else {
                        if (empty($aRow['username']) && empty($aRow['password']) && empty($aParam['passwd'])) {
                            $bIsPass = true;
                            $aRow['email'] = $sEmail;
                            $sUserName = Core::getService('core.tools')->cutString($sEmail, 32);
                            $sUserName = 'E|'.$sUserName;
                            $aRow['username'] = $sUserName;
                            unset($sUserName);
                        }
                        else {
                            $sPassword = md5(md5($aParam['passwd']).$aRow["password_security"]);
                            if($sPassword == $aRow['password']) {
                                $bIsPass = true;
                                if (empty($aRow['username'])) {
                                    $aRow['username'] = $aRow['id'];
                                }
                            }
                        }
                    }
                }
                if (!$bIsPass) {
                    // not match password, increase login count to avoid detects password by tools.
                    $oSession->setArray('session-user', 'login_time', CORE_TIME);
                    $iTimes = $oSession->getArray('session-user', 'login_count') + 1;
                    $oSession->setArray('session-user', 'login_count', $iTimes);
                    unset($iTimes);
                    Core_Error::set('error', Core::getPhrase('language_sai-ten-truy-cap-hoac-mat-khau'));
                }
            }
            else {
                Core_Error::set('error', Core::getPhrase('language_sai-ten-truy-cap-hoac-mat-khau'));
                //$sCode = Core::getService('core.tools')->getUniqueCode();
//                $aInsert = array(
//                    'code' => $this->database()->escape($sCode),
//                    'username' => '',
//                    'password' => '',
//                    'email' => $this->database()->escape($sEmail),
//                    'join_time' => CORE_TIME,
//                    'birthday' => '',
//                    'ip_address' => Core::getLib('request')->getIp(),
//                    'password_security' => '',
//                    'domain_id' => Core::getDomainId()
//                );
//                $iId = $this->database()->insert($this->_sTable, $aInsert);
//                $aRow['id'] = $iId;
//                $aRow['email'] = $sEmail;
//                $sUserName = Core::getService('core.tools')->cutString($sEmail, 32);
//                $sUserName = 'E|'.$sUserName;
//                $aRow['username'] = $sUserName;
//                unset($sUserName);
//                $bIsPass = true;
            }
        }
        else {
            // check login with username.
            $aRow = $this->database()->select('id, code, user_group_id, username, email, password, password_security, last_visit, openid, total_like')
                ->from($this->_sTable)
                ->where('domain_id ='. Core::getDomainId() .' AND username = \''. $this->database()->escape($aParam['username']).'\' AND status = 0 AND reference_id = 0')
                ->limit(1)
                ->execute('getRow');

            $sPassword = md5($aParam['passwd'].$aRow["password_security"]); // $sPassword have been encode on browser before submit to server.

            if($sPassword == $aRow["password"])
                $bIsPass = true;
            if(!$bIsPass) {
                // not match password, increase login count to avoid detects password by tools.
                $oSession->setArray('session-user', 'login_time', CORE_TIME);
                $iTimes = $oSession->getArray('session-user', 'login_count') + 1;
                $oSession->setArray('session-user', 'login_count', $iTimes);
                unset($iTimes);
                Core_Error::set('error', Core::getPhrase('language_sai-ten-truy-cap-hoac-mat-khau'));
            }
        }
        $aRow['user_group_id'] *= 1;

        if($bIsPass) {
            if (Core::getParam('core.main_server') == 'cms')
                $sDomain = 'cms.'.$_SERVER['HTTP_HOST'];
            else
                $sDomain = 'sup.'.$_SERVER['HTTP_HOST'];

            // set cookie
            $iTime = CORE_TIME+(3600*24*31);
            Core::setCookie('bb_id', $aRow['id'], $iTime, '/', $sDomain);
            Core::setCookie('bb_username', $aRow['username'], $iTime, '/', $sDomain);
            Core::setCookie('bb_email', $aRow['email'], $iTime, '/', $sDomain);

            if($iRemember) {
                $sActiveCode = Core::getService('core.tools')->getRandomCode(12);
                $aInsert = array(
                    'active_code' => $sActiveCode,
                    'type' => 'ghi_nho_mat_khau',
                    'note' => $aRow['id'],
                    'time' => CORE_TIME,
                    'expire_time' => CORE_TIME + 259200,
                    'status' => 1,
                    'domain_id' => Core::getDomainId()
                );

                $this->database()->update(Core::getT('active_code'), array('status' => 2), 'domain_id ='. Core::getDomainId(). ' AND type = \'ghi_nho_mat_khau\' AND note = \''.$this->database()->escape($aRow['id']).'\'');
                $this->database()->insert(Core::getT('active_code'), $aInsert);
                Core::setCookie('bb_password', $sActiveCode, $iTime, '/', $sDomain);
            }
            else {
                Core::setCookie('bb_password', '', 0, '/', $sDomain);
            }
            $aRow['path'] = '@'.$aRow["username"];
            $this->loginSuccess($aRow);
            // cập nhật quyền trước khi thực hiện các thao tác tiếp theo
            if (Core::getParam('core.main_server') == 'sup.') {
                //kiểm tra thành viên có được quản lý siêu thị nào hay không.
                $aVendorLists = Core::getService('user.permission')->getVendorsCurrentUser();
                if (count($aVendorLists) < 1) {
                    return array(
                        'status' => 'error',
                        'message' => 'Không có quyền truy cập.'
                    );
                }
            }
            if (Core::getParam('core.main_server') == 'cms.') {
                // kiểm tra xem thành viên có quyền để truy cập trang hay không.
                $iVendorSession = $oSession->get('session-vendor');
                if ($iVendorSession < 1 && $iVendorSession != -1) {
                    return array(
                        'status' => 'error',
                        'message' => 'Không có quyền truy cập.'
                    );
                }
                $aPermission = $oSession->get('session-permission');

                if (count($aPermission) < 1) {
                    return array(
                        'status' => 'error',
                        'message' => 'Không có quyền truy cập.'
                    );
                }
                else {
                    $iPriority = $oSession->getArray('session-user', 'priority_group');
                    if ($iPriority < 1) {
                        return array(
                            'status' => 'error',
                            'message' => 'Không có quyền truy cập.'
                        );
                    }

                    if (Core::getDomainName() == 'marrybaby.com' || Core::getDomainName() == 'vdg.vn') {
                        $iAccessCms = $aPermission['access_cms'];
                        if ($iAccessCms < 1) {
                            return array(
                                'status' => 'error',
                                'message' => 'Không có quyền truy cập.'
                            );
                        }
                    }

                }
            }
            $iStatus = 3;
        }
        else if(Core::getParam('core.local') != 1) {
            // login failed. create active code for this user to require captcha.
            $sActiveCode = Core::getService('core.tools')->getRandomCode(9);
            $sActiveCode = substr(strtolower($sActiveCode), 0, 12);
            $aInsert = array(
                'active_code' => $sActiveCode,
                'type' => 'auth_acc',
                'note' => $aParam['username'],
                'time' => CORE_TIME,
                'expire_time' => CORE_TIME + 900,
                'status' => 1,
                'domain_id' => Core::getDomainId()
            );
            $this->database()->insert(Core::getT('active_code'), $aInsert);
            $oSession->setArray('session-login', 'active_code', $sActiveCode);
            $iStatus = 1;
        }
        else
            $iStatus = 1;
        if ($iStatus == 1) {
            return array(
                'status' => 'error',
                'message' => Core_Error::getString()
            );
        }
        $sReferEncode = $aParam['refer'];
        if (!empty($sReferEncode) && $iStatus == 3) {
            $sReferEncode = Core::getLib('input')->RemoveXSS($sReferEncode);
            if($sReferEncode == '' || !Core::getService('domain')->checkUrl($sReferEncode))
                $sReferEncode = '/';
        }
        if (!empty($sReferEncode) && $sReferEncode != '/' && $iStatus == 3) {
            // login successful
            // check for active code.
            $sTmp = Core::getService('domain')->getDomainByUrl(array(
                'url' => $sReferEncode,
                'filter' => false
            ));
            if (!empty($sTmp) && $sTmp != Core::getDomainName() && $sTmp != 'cms.'.Core::getDomainName()) {
                $sActiveCode = Core::getService('core.tools')->getRandomCode(12);
                $this->database()->insert(Core::getT('active_code'), array(
                    'active_code' => $this->database()->escape($sActiveCode),
                    'type' => 'login_user_api',
                    'note' => $oSession->getArray('session-user', 'id'),
                    'time' => CORE_TIME,
                    'expire_time' => CORE_TIME + 259200, // 30 days
                    'status' => 1,
                    'domain_id' => Core::getDomainId()
                ));

                if(strpos($sReferEncode, '?') === false)
                    $sReferEncode .= '?';
                else
                    $sReferEncode .= '&';
                $sReferEncode .= 'kid='.$sActiveCode;
            }
        }

        $sReferEncode = base64_encode($sReferEncode);
        return array(
            'status' => 'success',
            'data' => array(
                'refer' => $sReferEncode,
                'session_id' => session_id(),
                'user' => $aRow,
            )
        );
    }

    public function statisticsAccess($aParams)
    {
        $oSession = Core::getLib('session');
        $iFlag = 1;
        $sRefer = $aParams['refer'];
        if (!empty($sRefer)) {
            $sRefer = urldecode($sRefer);
            $sRefer = trim($sRefer);
            $sRefer = Core::getLib('input')->removeXSS($sRefer);
        }
        // link direct
        $sUrl = $_SERVER['HTTP_REFERER'];
        if (empty($sUrl))
            $sUrl = $aParams['url'];
        if (empty($sUrl))
            $iFlag = 1;// để tắt chế độ tính thành viên
        else
            $iFlag = Core::getService('core')->checkBot();

        $sObjectType = $aParams['type'];
        $iParentObjectId = $aParams['pid']*1;

        if (!Core::isModule($sObjectType))
            $sObjectType= 'build';

        if ($sObjectType == 'category' || $sObjectType == 'core' || $sObjectType == 'article') {
            $iObjectId = $aParams['id'];
            $iObjectId *= 1;
            if ($iObjectId < -1)
                $iObjectId = 0;

            if ($iParentObjectId != -1 && $iParentObjectId < 0)
                $iParentObjectId = 0;
        }
        else {
            $iObjectId = -1;
        }
        // end
        $sLid = Core::getCookie('lid');
        if (!empty($sLid)) {
            $sLid = Core::getCookie('lid');
            $sLid = Core::getLib('input')->removeXSS($sLid);
            $sLid = substr($sLid, 0, 40);
        }
        if (!empty($sLid)) {
            // kiểm tra trong DB, đã tồn tại cookies
            $aRow = $this->database()->select('id')
                ->from(Core::getT('log_access'))
                ->where('domain_id = '.Core::getDomainId().' AND code = "'.$this->database()->escape($sLid).'"')
                ->execute('getRow');
            $iLogAccessId = $aRow['id'];
            if ($iLogAccessId < 1) {
                $sLid = '';
            }
        }

        if(empty($sLid)) {
            $sCode = Core::getService('core.tools')->getUniqueCode();
            // tiến hành insert
            $aInsert = array(
                'code' => $this->database()->escape($sCode),
                'create_time' => CORE_TIME,
                'count' => 1,
                'domain_id' => Core::getDomainId(),
            );
            $iLogAccessId = $this->database()->insert(Core::getT('log_access'), $aInsert);
            // tạo cookie cho 1 năm $iTime = time(); // 31536000 = 3600*24*365;
            Core::setCookie('lid', $sCode, CORE_TIME + 31536000,'/' , $_SERVER['HTTP_HOST']);
        }
        else {
            $aUpdate = array(
                'update_time' => CORE_TIME,
                'count' => 'count + 1'
            );
            $this->database()->update(Core::getT('log_access'), $aUpdate, 'id = '.$iLogAccessId);
        }
        // kiểm tra xem session đã được cập nhật vào bảng
        $sQuery = 'domain_id = '.Core::getDomainId().' AND log_access_id = '.$iLogAccessId.' AND session_id = "'.addslashes(session_id()).'"';

        $aRow = $this->database()->select('id')
            ->from(COre::getT('log_access_sid'))
            ->where($sQuery)
            ->execute('getRow');

        $iLogAccessSidId = isset($aRow['id']) ? $aRow['id'] : 0;
        if ($iLogAccessSidId < 1) {
            $sIp = Core::getLib('request')->getIp(true);
            $sCity = $oSession->get('session-city');

            if (empty($sCity)) {
                // Convert ip to city
                //d(DIR_LIB .'geoip.class.php');die;
                require DIR_LIB .'geoip.class.php';
                $aTmps = array();
                try {
                    $oGeoip = new geoip(long2ip($sIp));

                    $aTmps = $oGeoip->get();
                } catch (Exception $e) {
                    $aTmps = array();
                    Core_Error::errorHandler(E_ERROR, "IP: ".$e->getMessage(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
                }

                if (empty($aTmps['city']['names']['en']) && empty($aTmps['country']['names']['en'])) {
                    $sCity = '{none}';
                }
                elseif (empty($aTmps['city']['names']['en'])) {
                    $sCity = $aTmps['country']['names']['en'];
                }
                else
                    $sCity = $aTmps['city']['names']['en'].' - '.$aTmps['country']['names']['en'];
                $oSession->set('session-city', $sCity);
            }
            /**/
            // tiến hành insert
            $aInsert = array(
                'log_access_id' => $iLogAccessId,
                'session_id' => addslashes(session_id()),
                'browser' => addslashes($_SERVER['HTTP_USER_AGENT']),
                'ip' => $sIp,
                'city' => $sCity,
                'refer' => addslashes($sRefer),
                'key_word' => addslashes(Core::getService('core.tools')->searchEngineQueryString($sRefer)),
                'create_time' => CORE_TIME,
                'create_date' => date("Y-m-d"),
                'count' => 1,
                'domain_id' => Core::getDomainId()
            );
            $iLogAccessSidId = $this->database()->insert(Core::getT('log_access_sid'), $aInsert);
        }
        else {
            $aUpdate = array(
                'update_time' => time(),
                'count' => 'count + 1'
            );
            $this->database()->update(Core::getT('log_access_sid'), $aUpdate, $sQuery.'id = '.$iLogAccessSidId);
        }

        //  kiểm tra đường dẫn chi tiết
        // đọc cookie thời gian trên trang
        $aOutput['delete'] = array();
        foreach ($_COOKIE as $Key => $Val) {
            if($Key == 0 || strpos($Key, 'logdId-') === false)
                continue;
            $aOutput['delete'][] = $Key;
            unset($_COOKIE[$Key]);
            Core::setCookie($Key, NULL, CORE_TIME - 3600, '/', $_SERVER['HTTP_HOST']);
            $iTmp = str_replace('logdId-', '', $Key);
            $iTmp *= 1;
            if ($iTmp < 1)
                continue;
            // cập nhật thời gian cho log trước
            $this->database()->update(Core::getT('log_access_dt'), array('update_time' => CORE_TIME), 'domain_id = '.core::getDomainId().' AND `log_access_id` = '.$iLogAccessId.' AND id='.$iTmp);
        }
        // tạo log chi tiết
        $iTime = CORE_TIME; // 31536000 = 3600*24*365;
        $aInsert = array(
            'domain_id' => Core::getDomainId(),
            'log_access_id' => $iLogAccessSidId,
            'log_access_sid_id' => $iLogAccessSidId,
            'path' => addslashes($sUrl),
            'object_id' => addslashes($iObjectId),
            'object_type' => addslashes($sObjectType),
            'parent_object_id' => addslashes($iParentObjectId),
            'create_time' => $iTime,
        );
        $iLogAccessDtId = $this->database()->insert(Core::getT('log_access_dt'), $aInsert);

        // update thong tin chi tiet
        if ($sObjectType == 'article') {
            $aUpdate = array(
                'view_time' => time(),
                'total_view' => 'total_view + 1'
            );
            $this->database()->update(Core::getT('article'), $aUpdate, 'domain_id ='.Core::getDomainId().' AND id = '.$iObjectId);
        }
        $aOutput['lid'] = $iLogAccessDtId;
        return array(
            'status' => 'success',
            'data' => $aOutput
        );
    }

    public function checkEmailExist($aParam = array())
    {
        if (!isset($aParam['email']) || empty($aParam['email'])) {
            return false;
        }
        $sEmail = $aParam['email'];
        $sEmail = trim($sEmail);
        $sEmail = strtolower($sEmail);

        $iCnt = $this->database()->select('count(id)')
            ->from(Core::getT('user'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND email = "'.$sEmail.'" AND status != 2')
            ->execute('getField');
        if ($iCnt > 0) {
            return false;
        }
        return true;
    }

    public function logout()
    {
        if (Core::getUserId() > 0) {
            $sType = 'ghi_nho_mat_khau';
            $sNote = Core::getUserId();
            $this->database()->update(Core::getT('active_code'), array('status' => 2), 'domain_id ='. Core::getDomainId() . ' AND type = \''. $this->database()->escape($sType).'\' AND note = \''. $this->database()->escape($sNote).'\'');
            if(Core::getParam('core.main_server') == 'cms.')
                Core::setCookie('bb_password', '',  0, '/', 'cms.'.$_SERVER['HTTP_HOST']);
            else
                Core::setCookie('bb_password', '',  0, '/', 'sup.'.$_SERVER['HTTP_HOST']);

            $oSession = Core::getLib('session');
            // nếu có quyền quản trị gọi chạy ngầm để xóa quyền bên session server img
            if (Core::isAdminPanel() && Core::getUserId() > 0 && $oSession->getArray('session-user', 'priority_group') > -1) {
                Core::getService('core.tools')->openProcess(array('url' => 'http://img.'.Core::getDomainName().':8080/tools/api.php?type=logout'), array(
                    'sid' => session_id(),
                ));
            }
            $oSession->remove('session-user');
            $oSession->remove('session-permission');
            $oSession->remove('session-shop');
        }
        return array(
            'status' => 'success',
        );
    }
}
