<?php
class User_Service_Process extends Service
{
    public function __construct()
    {
        $this->_sTable = core::getT('user');
    }

    public function addUser($aParam)
    {
        $oSession = Core::getLib('session');
        $iStatus = 1;
        $iAcp = 0;
        $iId = 0;
        $iOpenId = 0;

        if (isset($aParam['iAcp'])) {
            $iAcp = $aParam['iAcp'];
        }
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }
        if (isset($aParam['iOpenId'])) {
            $iOpenId = $aParam['iOpenId'];
        }

        $aCustom = array();
        $aCustom['fullname'] = 1;
        $aCustom['birthday'] = 0;
        $aCustom['address'] = 0;
        $aCustom['sex'] = 1;
        $aCustom['phone_number'] = 0;
        $aTmps = array (
            'sUserName' => 'username',
            'sPassword' => 'passwd',
            'sEmail' => 'email',
            'sFullName' => 'fullname',
            'sAddress' => 'address',
            'sStreet' => 'street',
            'iBirthday' => 'birthday',
            'iSex' => 'sex',
            'sPhoneNumber' => 'phone_number',
            'sCity' => 'city',
            'sDistrict' => 'district',
            'sCountry' => 'country',
            'sIdentityNo' => 'identity_no',
            'iGroup' => 'user_group',
            'sCompany' => 'company',
            'sWard' => 'ward',
        );
        foreach ($aTmps as $sKey => $sVal) {
            $$sKey = htmlspecialchars(Core::getLib('input')->removeXSS(@$aParam[$sVal]));
        }
        if (!empty($sDistrict)) {
            // get info of area and build full address
            $sAddress = $sStreet. ', '. Core::getService('core.area')->parse($sDistrict);
        }

        if (!empty($iBirthday))
            $aCustom['birthday'] = 1;
        if (!empty($sAddress))
            $aCustom['address'] = 1;
        if (!empty($sPhoneNumber))
            $aCustom['phone_number'] = 1;

        $sUserName = trim($sUserName);
        $sUserName = strtolower($sUserName);

        //Những trường hợp bắt buộc  phải có thông tin (không được để trống)
        if ($iAcp && $iId > 0) {
            if (strlen($sPhoneNumber) < 6 || strlen($sPhoneNumber) > 20) {
                Core_Error::set('error', 'Số điện thoại không hợp lệ');
            }
        }

        //if ( ($iAcp && !empty($sUserName) && mb_strlen($sUserName) < 5)
//            || (!$iAcp && mb_strlen($sUserName)<5) || strlen($sUserName) > 32) {
//            Core_Error::set('error', sprintf(Core::getPhrase('language_ten-dang-nhap-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 5, 32).'!<br />');
//        }
        //if ( ($iAcp && !empty($sUserName) && preg_match('/^[a-z0-9.]+$/', $sUserName)==0)
//            || (!$iAcp && preg_match('/^[a-z0-9.]+$/', $sUserName)==0)) {
//            Core_Error::set('error', Core::getPhrase('language_ten-dang-nhap-khong-duoc-cho-phep'));
//        }

        if (!$iOpenId) {
            $sEmail = trim($sEmail);
            // Check hop_thu
            if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $sEmail)==0) {
                Core_Error::set('error', Core::getPhrase('language_hop-thu-khong-dung-cau-truc'));
            }
            if ((mb_strlen($sEmail) < 5) || strlen($sEmail) > 224) {
                 Core_Error::set('error', sprintf(Core::getPhrase('language_hop-thu-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 6, 224));
            }
            $sEmail = strtolower($sEmail);

            if ( ($iAcp && !empty($sPassword) && mb_strlen($sPassword) < 5)
                || (!$iAcp && mb_strlen($sPassword)<5) || strlen($sPassword) > 32) {
                Core_Error::set('error', sprintf(Core::getPhrase('language_mat-khau-phai-nho-hon-x-va-lon-hon-x'), 6, 32));
            }
            if (strlen($sPassword) != mb_strlen($sPassword,'utf-8')) {
                Core_Error::set('error', Core::getPhrase('language_mat-khau-khong-duoc-viet-co-dau'));
            }
        }

        $iSex *= 1;
        if ($iSex != 1 && $iSex != 2)
            $iSex = 0;

        // chcck ngay sinh
        if ($aCustom['birthday']) {
            // check birthday
            if (empty($iBirthday)) {
                if (!$iAcp) {
                    Core_Error::set('error', Core::getPhrase('language_ngay-sinh-chua-duoc-nhap'));
                }
            }
            else {
                $aTmp = explode("/", $iBirthday);
                if (count($aTmp) != 3) {
                    $aTmp = explode("-", $iBirthday);
                }

                if (count($aTmp) != 3) {
                    $aTmp = explode(".", $iBirthday);
                }
                @$sDD = $aTmp[0];
                @$sMM = $aTmp[1];
                @$sYY = $aTmp[2];
                $iBirthday_tmp = ($sYY.$sMM.$sDD)*1;
                if (!@checkdate($sMM,$sDD,$sYY)) {
                    Core_Error::set('error', Core::getPhrase('language_ngay-sinh-khong-ton-tai'));
                }
                else {
                    $iBirthday = $sDD.'-'.$sMM.'-'.$sYY;
                }
            }
        }
        // end
        if (isset($aCustom['fullname'])) {
            // check ho_va_ten
            //if ( ($iAcp && !empty($sFullName) && mb_strlen($sFullName) < 5)
//                || (!$iAcp && mb_strlen($sFullName) < 5) || mb_strlen($sFullName) > 225) {
//                    echo 2;
                //Core_Error::set('error', sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ho-va-ten'), 6, 225));
//            }
            // end
        }
        if (isset($aCustom['identity_no'])) {
            // check cmnd
            if (($iAcp && !empty($sIdentityNo) && strlen($sIdentityNo) < 5)
                || (!$iAcp && strlen($sIdentityNo) < 5) || strlen($sIdentityNo) > 20) {
                Core_Error::set('error', sprintf(Core::getPhrase('language_so-cmnd-phai-tu-x-den-x-so'), 6, 20));
            }
            // end
        }
        if ($aCustom['phone_number']) {
            // check dien_thoai
            if (($iAcp && !empty($sPhoneNumber) && strlen($sPhoneNumber)<5)
                || (!$iAcp && strlen($sPhoneNumber)<5) || strlen($sPhoneNumber)>20) {
                //Core_Error::set('error', sprintf(Core::getPhrase('language_so-dien-thoai-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 6, 20));
                Core_Error::set('error', 'Số điện thoại không hợp lệ');
            }
            // end
        }

        if ($aCustom['address']) {
            // check dia_chi
            if (( $iAcp && !empty($sAddress) && strlen($sAddress) < 2)
                || (!$iAcp && strlen($sAddress) < 2) || strlen($sAddress) > 225) {
                Core_Error::set('error', sprintf(Core::getPhrase('language_dia-chi-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 3, 225));
            }
            // end
        }
        //if (isset($aCustom['city'])) {
            // check tinh_thanh
//            if (( $iAcp && !empty($sCity) && strlen($sCity) < 2)
//                || (!$iAcp && strlen($sCity) < 2) || strlen($sCity) > 50) {
//                Core_Error::set('error', sprintf(Core::getPhrase('language_tinh-thanh-phai-tu-x-den-x-so'), 3, 50));
//            }
            // end
//        }
        if (isset($aCustom['country'])) {
            // check quoc_gia
            if (( $iAcp && !empty($sCountry) && strlen($sCountry) < 3)
                || (!$iAcp && strlen($sCountry)<3) || strlen($sCountry) > 50) {
                Core_Error::set('error', sprintf(Core::getPhrase('language_quoc-gia-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 3, 50));
            }
            // end
        }

        //if (Core_Error::isPassed() && !$iAcp) {
//            $sActiveCode = substr(strtolower($aParam['captcha']), 0, 12);
//            if (!empty($sActiveCode)) {
//                $sOrginalCode = strtolower($oSession->getArray('session-'.$aParam['type'], 'active_code'));
//                if ( $sOrginalCode != $sActiveCode) {
//                    Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai'));
//                }
//            }
//            else {
//                Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai'));
//            }
//        }

        // check phone number
        if (Core_Error::isPassed() && !empty($sPhoneNumber)) {
            // Tên truy cập nếu ko phải là trang acp bắt buộc phải nhập, đã check ở bước trên
            $sConds = '';
            if ($iAcp && $iId > 0) {
                $sConds = ' AND id != '.$iId;
            }

            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('user'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND phone_number = "'.$this->database()->escape($sPhoneNumber).'" AND status != 2'.$sConds)
                ->execute('getField');
            if ($iCnt > 0) {
                Core_Error::set('error', 'Số điện thoại đã được sử dụng.');
            }
        }

        if (Core_Error::isPassed() && !empty($sCompany)) {
            if (strlen($sCompany) > 225) {
                Core_Error::set('error', 'Tên công ty không được nhiều hơn 225 ký tự.');
            }
        }

        if (Core_Error::isPassed() && !empty($sWard)) {
            if (strlen($sWard) > 225) {
                Core_Error::set('error', 'Tên phường/ xã không được nhiều hơn 225 ký tự.');
            }
        }

        if (Core_Error::isPassed() && !empty($sUserName)) {
            // Tên truy cập nếu ko phải là trang acp bắt buộc phải nhập, đã check ở bước trên
            $sConds = '';
            if ($iAcp && $iId > 0) {
                $sConds = ' AND id != '.$iId;
            }
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('user'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND username = "'.addslashes($sUserName).'" AND status != 2'.$sConds)
                ->execute('getField');
            if ($iCnt > 0) {
                Core_Error::set('error', Core::getPhrase('language_ten-truy-cap-da-ton-tai'));
            }
        }

        // kiểm tra hộp thư
        if (Core_Error::isPassed() && !$iAcp) {
            /*
            Check xem hộp thư đã tồn tại hay chưa, nếu tồn tại và đang sử dụng theo dạng đăng nhập nhanh. Thì cập nhật phiên làm việc
            */
            $iId = 0;
            $iOpenId = 0;
            $sOpenIdProfile = '';
            $aRow = $this->database()->select('id, username, email, last_visit, openid')
                ->from(Core::getT('user'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND email = "'.$sEmail.'" AND openid = '.$iOpenId.' AND status != 2')
                ->execute('getRow');

            // ngược lại nếu hộp thư đã tồn tại, nhưng tên truy cập chưa tồn tại. Tiến hành update
            //$iId = $aRow['id'];
            // nếu tồn tại thành viên
            if ($aRow['id'] > 0 && !empty($aRow['username'])) {
                $iId = 0;
                Core_Error::set('error', Core::getPhrase('language_hop-thu-da-ton-tai'));
            }
        }
        elseif (Core_Error::isPassed() && !$iOpenId) // chỉ kiểm tra khi là trang quản trị, và có quyền sửa email
        {
            $sConds = '';
            if ($iAcp && $iId > 0) {
                $sConds = ' AND id != '.$iId;
            }
            // nếu là trang admin, check xem email đã tòn tại chưa
            $iCnt = $this->database()->select('count(id)')
                ->from(COre::getT('user'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND email = "'.$sEmail.'" AND status != 2'.$sConds)
                ->execute('getField');
            if ($iCnt > 0) {
                Core_Error::set('error', Core::getPhrase('language_hop-thu-da-ton-tai'));
            }
        }
        $iAreaId = 0;
        if ($sDistrict > 0) {
            $iAreaId = $sDistrict;
        }
        else if ($sCity  > 0){
            $iAreaId = $sCity;
        }
        else if ($sCountry  > 0){
            $iAreaId = $sCountry;
        }

        if (Core_Error::isPassed()) {
            $aInsert = array(
                //'username' => $sUserName,
                //'user_group_id' => $iGroup,
                'birthday' => $iBirthday,
                'fullname' => $sFullName,
                'sex' => (int)$iSex,
                'phone_number' => $sPhoneNumber,
                'address' => $sAddress,
                'city' => $sCity,
                'country' => $sCountry,
                'area_id' => $iAreaId,
                'identity_number' => $sIdentityNo,
                'email' => $sEmail,
                'company' => $sCompany,
                'ward' => $sWard,
            );

            if (!$iOpenId) {
                // mật khẩu nếu ko phải là trang acp bắt buộc phải nhập, đã check ở bước trên
                if (!empty($sPassword)) {
                    $sPasswordSecur = rand(100,999);
                    $sPasswordTmp = $sPassword;
                    $sPassword = addslashes(md5(md5($sPassword).$sPasswordSecur));

                    $sPasswordLength = mb_strlen($sPasswordTmp, "UTF8");
                    $sPasswordTmp = '';
                    for ( $i = 0; $i < $sPasswordLength; $i++) {
                        $sPasswordTmp .= '*';
                    }

                    $aInsert['password'] = addslashes($sPassword);
                    $aInsert['password_security'] = addslashes($sPasswordSecur);
                    $aInsert['openid'] = (int)$iOpenId;
                    $aInsert['openid_profile'] = addslashes($sOpenIdProfile);
                }

            }
            $aInsert['old_id'] = 0;
            if ($iId > 0) {
                //update thông tin
                $this->database()->update(Core::getT('user'), $aInsert, 'id = '.(int)$iId);
            }
            else {
                if (!empty($sUserName)) {
                    $aInsert['username'] = $sUserName;
                }

                $aRow['code'] = Core::getService('core.tools')->getUniqueCode();
                $sIpAddress = Core::getLib('request')->getIp();

                // danh cho đăng nhập với tên miền khác
                /*
                $iDomainId = $oSession->get('session-domain_login');
                if ($iDomainId < 1)
                    $iDomainId = Core::getDomainId();
                */
                $iDomainId = Core::getDomainId();
                $aInsert['code'] = addslashes($aRow['code']);

                $aInsert['join_time'] = time();
                $aInsert['ip_address'] = addslashes($sIpAddress);
                $aInsert['domain_id'] = $iDomainId;
                $iId = $this->database()->insert(Core::getT('user'), $aInsert);

                // nếu không có biến username. tạo user name mặc định
                if (!isset($sUserName) || empty($sUserName)) {
                    $sUserName = 'profile'.$iId;
                    $this->database()->update(Core::getT('user'), array('username' => $sUserName), 'id = '.(int)$iId);
                }
                // tạo địa chỉ giao hàng mặc định.
                /*
                Core::getService('user.delivery')->addAddress(array(
                    'user_id' => $iId,
                    'receiver' => $sFullName,
                    'phone_number' => $sPhoneNumber,
                    'street' => $sStreet,
                    'district' => $sDistrict,
                    'city' => $sCity,
                    'default_charge' => 1,
                    'default_delivery' => 1
                )); */
                // tái tạo SQL
                $aRow['username'] = $sUserName;
                $aRow['user_group_id'] = 0;
                $aRow['last_visit'] = 0;
                $aRow['id'] = $iId;
                $aRow['email'] = $sEmail;
                $oSession->removeArray('session-'.$aParam['type'], 'active_code');
            }
            $iStatus=  2;
        }
        else {
            $iStatus = 1;
        }

        // nếu ko phải quản trị,  tiến hành đăng nhập
        if ($iStatus == 2 && Core::getUserId() < 1 && !$iAcp) {
            $sPassword = $sPasswordTmp;
            // gửi email chào mừng
            $sSendUserName = rtrim($sSendUserName, ',');
            $sSendEmail = rtrim($sSendEmail, ',');

            $sSendTitle = Core::getPhrase('language_chao-mung-den-voi-trang-web').': '.Core::getDomainName();
            $sSendContent = Core::getPhrase('language_tieu-de').': <b>'.$sSendTitle.'</b><br />'.Core::getPhrase('language_ho-va-ten').': <b>'.$sFullName.'</b><br />'.Core::getPhrase('language_hop-thu').': <b>'.$sEmail.'</b><br />'.Core::getPhrase('language_so-dien-thoai').': <b>'.$sPhoneNumber.'</b><br />'.Core::getPhrase('language_noi-dung').': <br />'.$noi_dung;
            /*
            Core::getService('core.tools')->openProcess('/tools/gui_mail.php', array(
                'e' => $sEmail,
                'n' => $sUserName,
                't' => $sSendTitle,
                'c' => $sSendContent,
                'sid' => session_id(),
                'domain' => $_SERVER['HTTP_HOST']
            ));
            */
            //$thanh_vien_ten = $sUserName;

            $aRow['path'] = '@'.$aRow['username'];

            Core::getService('user.auth')->loginSuccess($aRow);

            $sEmail = $aRow['email'];

            if(Core::isAdminPanel())
                $sDomain = 'admin.'.$_SERVER['HTTP_HOST'];
            else
                $sDomain = $_SERVER['HTTP_HOST'];
            // cookie
            $iTime = CORE_TIME + (3600*24*31);
            Core::setCookie('bb_id', $aRow['id'], $iTime, '/', $sDomain);
            Core::setCookie('bb_username', $aRow['username'], $iTime, '/', $sDomain);
            Core::setCookie('bb_email', $aRow['email'], $iTime, '/', $sDomain);

            if ($iRemember) {
                $sActiveCode = Core::getService('core.tools')->getRandomCode(4);
                $aInsert = array(
                    'active_code' => $sActiveCode,
                    'type' => 'ghi_nho_mat_khau',
                    'note' => $aRow['id'],
                    'time' => CORE_TIME,
                    'expire_time' => CORE_TIME + 259200, // lưu mật khẩu 30 ngày,
                    'status' => 1,
                    'domain_id' => Core::getDomainId()
                );

                $this->database()->update(Core::getT('active_code'), array('status' => 2), 'domain_id ='. Core::getDomainId(). ' AND type = \'ghi_nho_mat_khau\' AND note = \''.$this->database()->escape($aRow['id']).'\'');
                $this->database()->insert(Core::getT('active_code'), $aInsert);
                // sử dụng nhóm stt để ghi thành viên chuyển nhóm sẽ yêu cầu đăng nhập lại.
                Core::setCookie('bb_password', $sActiveCode, $iTime, '/', $sDomain);
            }
            // - end
        }
        return $iStatus;
    }

    /**
    * forgot password
    * step 1: get active code
    * step 2: reset password
    *
    * @param mixed $aParam
    */
    public function forgotPassword($aParam)
    {
        $oSession = Core::getLib('session');
        $sType = 'forgot';
        $iStatus = 1;

        if (!empty($aParam["active_code"]) && !empty($aParam["password"])) {
            //Reset password (update new password with active code)
            $sCaptcha = substr(strtolower($aParam["captcha"]), 0, 12);
            if (!empty($sCaptcha)) {
                $sSesCaptcha = $oSession->getArray('session-'.$sType, 'captcha');
                if ($sSesCaptcha != $sCaptcha) {
                    Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai') );
                }
            }
            else {
                Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai') );
            }

            $aOutput['step'] = 2;
            $iStatus = 2;
            $aTmps = array (
                'sPassword' => 'password',
                'sRePassword' => 're_password',
                'sActiveCode' => 'active_code',
            );

            foreach ($aTmps as $sKey => $sVal) {
                $$sKey = htmlspecialchars(Core::getLib('input')->removeXSS(@$aParam[$sVal]));
            }

            if (Core_Error::isPassed()) {
                //Validate input
                //$uid = $uid*1;
                if ($sPassword <> $sRePassword) {
                    Core_Error::set('error', Core::getPhrase('language_mat-khau-khong-giong-nhau') );
                }
                if (strlen($sPassword) <= 3 | strlen($sPassword) > 32) {
                    Core_Error::set('error', sprintf(Core::getPhrase('language_mat-khau-phai-nho-hon-x-va-lon-hon-x'), 3, 32));
                }
                if (strlen($sPassword) <> mb_strlen($sPassword,'utf-8')) {
                    Core_Error::set('error', Core::getPhrase('language_mat-khau-khong-duoc-viet-co-dau') );
                }
            }

            if (Core_Error::isPassed()) {
                $aRow = $this->database()->select('note')
                    ->from(Core::getT('active_code'))
                    ->where('status = 2 AND active_code = "'.addslashes($sActiveCode).'" AND domain_id = '.(int)Core::getDomainId())
                    ->execute('getRow');
                if (!isset($aRow['note'])) {
                    Core_Error::set('error', Core::getPhrase('language_ma-kich-hoat-khong-dung') );
                }
                else {
                    $aTmp = explode('<->', $aRow['note']);
                    $iUserId = $aTmp[0]*1;
                    $sUserName = $aTmp[1];
                }
            }

            if (Core_Error::isPassed()) {
                $aRow = $this->database()->select('password_security')
                    ->from(Core::getT('user'))
                    ->where('id ='.$iUserId.' AND domain_id ='.(int)Core::getDomainId())
                    ->execute('getRow');
                if ($aRow['password_security'] == '') {
                    Core_Error::set('error', Core::getPhrase('language_thanh-vien-khong-ton-tai') );
                }
                else {
                    $sPasswordSecur = $aRow['password_security'];
                }
            }

            if (Core_Error::isPassed()) {
                $sPasswordTmp = $sPassword;
                $sPassword = addslashes(md5(md5($sPassword).$sPasswordSecur));
                //update password
                $aUpdate = array (
                    'password' => $sPassword,
                    'password_security' => $sPasswordSecur,
                );
                //$this->database()->update(Core::getT('user'), $aUpdate, 'id ='.$iUserId);
                //delete active code
                //$this->database()->delete(Core::getT('active_code'), 'status = 2 AND active_code = "'.addslashes($sActiveCode).'"');

                $sPassword = '';
                for ($i = 0; $i < strlen($sPasswordTmp); $i++) {
                    $sPassword .= '*';
                }
                $aOutput['password'] = $sPassword;
                //delete session captcha
                //$oSession->removeArray('session-'.$sType, 'captcha');
                $iStatus = 3;
            }
        }
        elseif (!empty($aParam["email"]) || !empty($aParam["username"])) {
            //forget password
            $sEmail = '';
            $sUserName = '';
            $aOutput['step'] = 1;
            if (isset($aParam["username"])) {
                $sUserName = $aParam["username"];
                $sUserName = Core::getLib('input')->removeXSS($sUserName);
                $sUserName = trim($sUserName);
                $sUserName = strtolower($sUserName);
            }
            else {
                $sEmail = $aParam["email"];
                $sEmail = Core::getLib('input')->removeXSS($sEmail);
                $sEmail = trim($sEmail);
                $sEmail = strtolower($sEmail);
            }

            $sCaptcha = substr(strtolower($aParam["captcha"]), 0, 12);

            if (!empty($sCaptcha)) {
                $sSesCaptcha = $oSession->getArray('session-'.$sType, 'captcha');
                if ($sSesCaptcha != $sCaptcha) {
                    Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai') );
                }
            }
            else {
                Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai') );
            }

            if (Core_Error::isPassed() && !empty($sEmail)) {
                // Check hop_thu
                if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $sEmail)==0) {
                    Core_Error::set('error', Core::getPhrase('language_hop-thu-khong-dung-cau-truc') );
                }
                if (strlen($sEmail) <= 3 || strlen($sEmail) > 224) {
                    Core_Error::set('error', sprintf(Core::getPhrase('language_hop-thu-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 3, 224) );
                }
            }

            if (Core_Error::isPassed() && !empty($sUserName)) {
                if (preg_match('/^[a-z0-9.]+$/', $sUserName)==0) {
                    Core_Error::set('error', Core::getPhrase('language_ten-dang-nhap-khong-duoc-cho-phep') );
                }
                if (strlen($sUserName)<=3 || strlen($sUserName)>224) {
                    Core_Error::set('error', sprintf(Core::getPhrase('language_hop-thu-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 3, 224) );
                }
            }

            if (Core_Error::isPassed()) {
                $sConds = '';
                if (!empty($sUserName)) {
                    $sConds = 'username = "'.addslashes($sUserName).'"';
                }
                else
                {
                    $sConds = 'email = "'.addslashes($sEmail).'"';
                }
                $aRow = $this->database()->select('id, username, email')
                    ->from(Core::getT('user'))
                    ->where($sConds.' AND password != "" AND openid = 0 AND domain_id ='.(int)Core::getDomainId())
                    ->execute('getRow');

                if ($aRow["id"] < 1) {
                    Core_Error::set('error', Core::getPhrase('language_thanh-vien-khong-ton-tai') );
                }
                else {
                    $iUserId = $aRow['id'];
                    $sUserName = $aRow['username'];
                    $sEmail = $aRow['email'];
                }

            }
            if (Core_Error::isPassed()) {
                $sActiveCode = Core::getService('core.tools')->getRandomCode(12);
                $iTime = CORE_TIME;
                $iExpireTime = $iTime + 259200;
                $sNote = $iUserId.'<->'.$sUserName;
                $aInsert = array(
                    'active_code' => addslashes($sActiveCode),
                    'type' => 'forgot_password',
                    'time' => $iTime,
                    'expire_time' => $iExpireTime,
                    'note' => addslashes($sNote),
                    'status' => 2,
                    'domain_id' => Core::getDomainId()
                );
                //$this->database()->insert(Core::getT('active_code'), $aInsert);

                //send active code to user email
                $sEmailTitle = Core::getPhrase('language_phuc-hoi-mat-khau-tai').': '.Core::getDomainName();
                $sPath = 'http://'.Core::getDomainName().'/quen_mat_khau.html#actcode='.$sActiveCode;
                $sEmailContent = Core::getPhrase('language_phuc-hoi-mat-khau-van-ban-yeu-cau-kich-hoat').'<a href="'.$sPath.'">'.$sPath.'</a>'.'<br />'.Core::getPhrase('language_ma-kich-hoat').':'.$sActiveCode;
                $sActiveCode = '';
                $aTmp = explode('@', $sEmail);
                $sEmailFilter = '@'.$aTmp[1];
                $iLength = strlen($aTmp[0]);
                if ($iLength > 3) {
                    $iLength = floor($iLength/30);
                    $sEmailFilter = '...'.substr($aTmp[0], $iLength*-1, $iLength).$sEmailFilter;
                }
                else
                    $sEmailFilter = '...'.$sEmailFilter;
                $sUserSendMail = 'Admin';
                $aTmps = array(
                    'e' => $sEmail,
                    'n' => $sUserSendMail,
                    't' => $sEmailTitle,
                    'c' => $sEmailContent,
                    'sid' => session_id(),
                    'domain' => $_SERVER['HTTP_HOST']
                );
                //Core::getService('core.tools')->openProcess('/tools/gui_mail.php', serialize($aTmps));
                //delete session captcha
                //$oSession->removeArray('session-'.$sType, 'captcha');
                $iStatus=2;
            }
        }
        else {
            Core_Error::set('error', 'Dữ liệu rỗng' );
        }
        /*
        elseif (!empty($aParam['actcode'])) {
            $iStatus = 2;
            $sActiveCode = $aParam['actcode'];
        }
        else {
            $sUserName = addslashes(Core::getLib('input')->removeXSS($aParam['uname']));
            $iStatus=1;
        }
        */
        return $iStatus;
    }

    /**
    * change password
    *
    * @param mixed $aParam
    *   - captcha
    *   - current_password
    *   - password
    *   - re_password
    */
    public function changePassword($aParam)
    {
        $iStatus = 1;
        $oSession = Core::getLib('session');
        $sCaptcha = strtolower(@$aParam["captcha"]);
        if ( @$sCaptcha) {
            //compare with session
            if ($sCaptcha != $oSession->get('session-captcha')) {
                Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai'));
            }
            else {
                //reset session
                $oSession->set('session-captcha', '');
            }
        }
        else {
            Core_Error::set('error', Core::getPhrase('language_ma-xac-nhan-nhap-sai'));
        }

        if (Core_Error::isPassed()) {
            $aTmps = array(
                'sCurrentPassword' => 'current_password',
                'sPassword' => 'password',
                'sRePassword' => 're_password',
                'sActiveCode' => 'active_code',
            );
            foreach ($aTmps as $sKey => $sVal) {
                $$sKey = addslashes(Core::getLib('input')->removeXSS(@$aParam[$sVal]));
            }

            //$uid = $uid*1;
            if ($sPassword <> $sRePassword) {
                Core_Error::set('error', Core::getPhrase('language_mat-khau-khong-giong-nhau'));
            }
            if (strlen($sPassword) <= 3 | strlen($sPassword) > 32) {
                Core_Error::set('error',sprintf(Core::getPhrase('language_mat-khau-phai-nho-hon-x-va-lon-hon-x'), 3, 32) );
            }
            if (strlen($sPassword) <> mb_strlen($sPassword,'utf-8')) {
                Core_Error::set('error', Core::getPhrase('language_mat-khau-khong-duoc-viet-co-dau'));
            }
            if ($sCurrentPassword == $sPassword) {
                Core_Error::set('error', Core::getPhrase('language_mat-khau-hien-tai-giong-mat-khau-muon-doi'));
            }
        }
        if (Core_Error::isPassed()) {
            //get from database
            $aRow = $this->database()->select('password, password_security')
                ->from(Core::getT('user'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND id ='.(int)Core::getUserId())
                ->execute('getRow');

            $sPasswordSecur = $aRow['password_security'];
            $sCurrentPassword = md5(md5($sCurrentPassword).$sPasswordSecur);
            if($sCurrentPassword != $aRow["password"]) {
                Core_Error::set('error', Core::getPhrase('language_mat-khau-hien-tai-khong-dung'));
            }
        }
        if (Core_Error::isPassed()) {
            $sPasswordTmp = $sPassword;
            $sPassword = addslashes(md5(md5($sPassword).$sPasswordSecur));
            //update password
            $aUpdate = array(
                'password' => $sPassword,
                'password_security' => $sPasswordSecur
            );
            $this->database()->update(Core::getT('user'), $aUpdate, 'domain_id ='.(int)Core::getDomainId().' AND id ='.(int)Core::getUserId());

            $sPassword = '';
            for ($i = 0;$i < strlen($sPasswordTmp); $i++) {
                $sPassword .= '*';
            }
            //update active code
            $sType = 'remember_password';
            $sNote = Core::getUserId();
            $this->database()->update(
                Core::getT('active_code'),
                array ('status' => 2),
                'domain_id ='.(int)Core::getDomainId().' AND type = \''.addslashes($sType).'\' AND note = \''.addslashes($sNote).'\''
            );
            if (Core::isAdminPanel())
                Core::setCookie('bb_password', '',  0, '/', 'cms.'.$_SERVER['HTTP_HOST']);
            else
                Core::setCookie('bb_password', '',  0, '/', $_SERVER['HTTP_HOST']);

            $iStatus = 3;
        }
        $aReturn = array (
            'status' => $iStatus
        );
        if (Core_Error::isPassed()) {
            $aReturn['sPassword'] = $sPassword;
        }
        return $aReturn;
    }

    public function actionFollow($aParam = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $iDomainId = Core::getDomainId();
        /*
            Theo đuôi sẽ có các lựa chọn
                Add        http://vodichgia.com/tools/api.php?type=theo_duoi&action=add
                Delete    http://vodichgia.com/tools/api.php?type=theo_duoi&action=delete&id=2

        */
        $iUserId = Core::getUserId();
        ///Giả lập iUserId
        //$iUserId = 50;

        if ($iUserId < 1) {
            Core_Error::set('error', 'Session time out');
            $bIsReturn = true;
        }

        $sAction = $aParam['action'];

        $aData = array();


        if (!$bIsReturn && $sAction == 'add') {
            if(!isset($aParam['uid']) || $aParam['uid'] < 1) {
                Core_Error::set('error', 'Empty ID');
                $bIsReturn = true;
            }

            $aData['user_id_to'] = $aParam['uid']; // thanh_vien_den_stt
            if (!isset($aParam['uname'])) {
                $aParam['uname'] = '';
            }
            $aData['user_fullname_to_suggest'] = $aParam['uname']; // tên được gợi ý

            // check db uid exists
            $aRow = $this->database()->select('id')
                ->from(Core::getT('follow'))
                ->where('domain_id = '.(int)$iDomainId.' AND status != 2 AND user_id_from = '.(int)$iUserId.' AND user_id_to = '.$aData['user_id_to'])
                ->execute('getRow');
            if ($aRow['id'] > 0) {
                Core_Error::set('error', 'Connection is exists');
                $bIsReturn = true;
            }

            if (!$bIsReturn) {
                // sử dụng trạng thái là 0, để thể hiện kết nối đc access,
                $aData['status'] = 0;
                $aInsert = array(
                    'user_id_from' => $iUserId,
                    'user_id_to' => $aData['user_id_to'],
                    'user_fullname_to_suggest' => addslashes($aData['user_fullname_to_suggest']),
                    'time' => CORE_TIME,
                    'status' => $aData['status'],
                    'domain_id' => $iDomainId,
                );

                $aData['id'] = $this->database()->insert(Core::getT('follow'), $aInsert);
                if ($aData['id']) {
                    //cập nhật số follow
                    $this->database()->update(Core::getT('user'),array('total_follow' => 'total_follow + 1'), 'id ='.(int)$iUserId);
                    //cập nhật số friend hiện (follow 2 chiều => friend, hiện tại friend dc tính như follow)

                }
                $aOutput['id'] = $aData['id'];
                $aOutput['action'] = 'add';
            }
        }
        elseif (!$bIsReturn && $sAction == 'delete') {
            // check stt
            $aData['id'] = $aParam['id'];
            if ($aData['id'] < 1) {
                Core_Error::set('error', 'Empty ID');
                $bIsReturn = true;
            }
            //kiểm tra có tồn tại quan hệ follow hay không
            $aRow = $this->database()->select('id, user_id_to')
                ->from(Core::getT('follow'))
                ->where('domain_id='.(int)$iDomainId.' AND user_id_from ='.(int)$iUserId.' AND id ='.$aData['id'].' AND status = 0')
                ->execute('getRow');
            if (!isset($aRow['user_id_to']) || $aRow['user_id_to'] < 0) {
                Core_Error::set('error', 'Không thể xóa mối quan hệ này');
                $bIsReturn = true;
            }
            else {
                $aData['user_id_to'] = $aRow['user_id_to'];
            }
            if (!$bIsReturn) {
                $aData['status'] = 2;
                // bắt buộc phải xuất phát đúng thành viên
                $this->database()->update(
                    Core::getT('follow'),
                    array('status' => $aData['status']),
                    'domain_id='.(int)$iDomainId.' AND user_id_from ='.(int)$iUserId.' AND id ='.$aData['id']
                );
                //cập nhật số follow
                $this->database()->update(Core::getT('user'),array('total_follow' => 'total_follow - 1'), 'id ='.(int)$iUserId);
                $aOutput['id'] = $aData['user_id_to'];
                $aOutput['action'] = 'delete';
            }
        }
        else {
            Core_Error::set('error', 'Deny');
            $bIsReturn = true;
        }
        if ($bIsReturn){
            $aOutput['status'] = 'error';
            $aOutput['message'] = Core_Error::get('error');
        }
        else {
            $aOutput['status'] = 'success';
        }
        return $aOutput;
    }

    public function updateStatusUser($aParams = array())
    {
        $type = 'update_status_user';
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $iId = @$aParams["id"]*1;
        $sList = @$aParams["list"];
        $iStatus = addslashes(@$aParams["status"]*1);
        if ($iStatus==1)
            $iStatus=0;
        else
            $iStatus=1;
        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($iId==0 && $sList == '') {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'edit_user') != 1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;;
        }
        else {
            if($sList != '') {
                $sList = explode(',', $sList);
                foreach ($sList as $Val) {
                    if ($Val == $oSession->getArray('session-user', 'id')) {
                        Core_Error::set('error', Core::getPhrase('language_khong-the-cap-nhat-chinh-ban'));
                        $bIsReturn = true;
                        break;
                    }
                    if ($Val*1 > 0)
                        $aTmp[] = $Val*1;
                }
                $sList = implode(',', $aTmp);
                $sCond = 'in ('.$sList.')';
            }
            else {
                $sCond = '= '.$iId;
                if ($iId == $oSession->getArray('session-user', 'id')) {
                    Core_Error::set('error', Core::getPhrase('language_khong-the-cap-nhat-chinh-ban'));
                    $bIsReturn = true;
                }
            }
            if (!$bIsReturn) {
                $bFlag = $this->database()->update(Core::getT('user'), array('status' => $iStatus), 'domain_id='.$oSession->getArray('session-domain', 'id').' AND id '.$sCond);
                if ($bFlag) {
                    // xóa session của thành viên\
                    $this->database()->delete('sessions', 'domain_id='.$oSession->getArray('session-domain', 'id').' AND user_id '.$sCond);
                    // end
                    //$status=1;
                }
                else {
                    Core_Error::set('error', Core::getPhrase('language_du-lieu-da-cap-nhat-truoc-do'));
                    $bIsReturn = true;
                }
                // ghi log hệ thống
                Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$iId,'content' => 'phpinfo',));
            }
        }
        if ($bIsReturn) {
            echo '<-errorvietspider->'.Core_Error::get('error');
        }
    }

    public function deleteUser($aParams)
    {
        $type = 'delete_user';
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        //id or list of user id
        $iId = @$aParams["id"]*1;
        $sList = @$aParams["list"];

        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('khong-co-quyen-truy-cap')
            );
        }
        elseif ($iId == 0 && $sList == '') {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('khong-co-quyen-truy-cap')
            );
        }
        elseif ($oSession->getArray('session-permission', 'edit_member')) {
            return array(
                'status' => 'error',
                'message' => Core::getPhrase('khong-co-quyen-truy-cap')
            );
        }
        else {
            if ($sList != '') {
                $sList = explode(',', $sList);
                foreach ($sList as $Val) {
                    if ($Val == $oSession->getArray('session-user', 'id')) {
                        return array(
                            'status' => 'error',
                            'message' =>  Core::getPhrase('khong-the-cap-nhat-chinh-ban')
                        );
                    }
                    if ($Val*1 > 0)
                        $aTmp[] = $Val*1;
                }
                $sList = implode(',', $aTmp);
                $sCond = 'in ('.$sList.')';
            }
            else {
                $sCond = '= '.$iId;
                if ($iId == $oSession->getArray('session-user', 'id')) {
                    return array(
                        'status' => 'error',
                        'message' =>  Core::getPhrase('khong-the-cap-nhat-chinh-ban')
                    );
                }
            }
            if (!$bIsReturn) {
                // xóa session của thành viên,
                $this->database()->delete('sessions', 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND user_id '.$sCond);
                // end
                $iFlag = $this->database()->update(Core::getT('user'), array('status' => 2), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND id '.$sCond);
                if ($iFlag > 0) {
                    //delete permission
                    $this->database()->delete(Core::getT('permission'), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND object_type = 0 AND object_id '.$sCond);
                    // ghi log hệ thống
                    Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$iId,'content' => 'phpinfo',));
                }
                else {
                    return array(
                        'status' => 'error',
                        'message' => 'Có lỗi xảy ra, vui lòng thử lại.'
                    );
                }
            }
        }
        return array(
            'status' => 'success',
            'data' => 1
        );
    }

    public function verifyUser($aParam = array())
    {
        $iUserId = (isset($aParam['uid'])) ? $aParam['uid'] : 0;
        $iOrderId = (isset($aParam['oid'])) ? $aParam['oid'] : 0;
        $iStatus = (isset($aParam['status'])) ? $aParam['status'] : -1;
        if ($iUserId == 0 || $iStatus == -1 || $iOrderId == 0) {
            return array(
                'status' => 'error',
                'message' => 'Dữ liệu lỗi'
            );
        }
        // check permission.

        // update verify status
        if ($iUserId == -1) {
            // ko login
            $this->database()->update(Core::getT('shop_order'), array(
                'confirm_user' => $iStatus
            ),'id ='. $iOrderId);
        }
        else {
            $this->database()->update(Core::getT('user'), array(
                'is_verify' => $iStatus
            ),'id ='. $iUserId);
        }

        return array(
            'status' => 'success',
            'data' => 1
        );
    }
}
?>
