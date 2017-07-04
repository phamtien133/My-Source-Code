<?php
class User_Service_User extends Service
{
    public function __construct()
    {
        $this->_sTable = Core::getT('user');
    }
    
    /**
    * check url to determine url is an user or not
    * input 
    * @param mixed $aParam
    *  - domain-path: path of domain.
    *  - 
    */
    public function isUserUrl($aParam)
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
        
        /**
        * if the path not exist special character, priority check for user
        *  - if the path have a structure same as m{code}, value of path is a user.
        *  - if the path don't have the "@" character, search this path. 
        */
        if(substr($sPath, 0, 1) == '@') {
            return $sPath;
        }
        $sCacheId = $this->cache()->set('tv|'.Core::getDomainId().'|'.$sPath);
        $iId = $this->cache()->get($sCacheId);
        if ($iId > 0) {
           return $iId; 
        }
        else if (preg_match('/^[a-z0-9.]+$/', $sPath)) {
            $aRow = $this->database()->select('username, code')
                ->from(Core::getT('user'))
                ->where('domain_id = '. Core::getDomainId() . ' AND status != 2 AND username =\''.$this->database()->escape($sPath).'\'')
                ->execute('getRow');
            if(isset($aRow['username'])) {
                $sParam = '@'. $aRow['username'];
                if(empty($aRow['username']))
                    $sParam = '@.'. $aRow['code'];
                $this->cache()->save($sCacheId, $sParam);
                Core::getLib('url')->send($sParam);
                exit;
            }
        }
        else if($bIsExist) {
            $aConds = array();
            $aConds[] = ' AND domain_id = '. Core::getDomainId();
            $aConds[] = ' AND status != 2';
            
            if(substr($sPath, 0, 1) == '-') {
                $sCode = substr($sPath, 1);
                $aConds[] = ' AND code = \''.$this->database->escape($sCode).'\'';
            }
            else {
                $aConds[] = ' AND username = \''.$this->database->escape($sPath).'\'';
            }
            
            $iUserId = $this->database()->select('id')
                ->from(Core::getT('user'))
                ->where($aConds)
                ->execute('getField');
            if($iUserId > 0) {
                $this->cache()->save($sCacheId, $iUserId);
                return $iUserId;
            }
        }
        
        return false;
    }
    
    /**
    * check current user is login by openid or not
    * 
    * @param mixed $aParam
    */
    public function isOpenId()
    {
        // check xem cÃ³ pháº£i openid khÃ´ng
        $aRow = $this->database()->select('id')
            ->from(Core::getT('user'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND id ='.(int)Core::getUserId().' AND password = ""')
            ->execute('getRow');
        if(isset($aRow['id']))
            return true;
        return false;
    }
    
    public function getOnlineUser()
    {
        $sCacheId = $this->cache()->set('userol|'. Core::getDomainId());
        $aData = $this->cache()->get($sCacheId);
        if ($aData === false) {
            $aData['view'] = $this->database()->select('total_view')
                ->from(Core::getT('domain_setting'))
                ->where('domain_id = '. Core::getDomainId())
                ->execute('getField');
            
            $iTotalOnline = $this->database()->select('count(id) as total')
                ->from('sessions')
                ->where('domain_id = '. Core::getDomainId(). ' AND bot = ""')
                ->execute('getField');
            
            if ($iTotalOnline == 0)
                $iTotalOnline = 1;
            $aData['online'] = $iTotalOnline;
            $this->cache()->save($sCacheId, $aData);
        }
        $oSession = Core::getLib('session');
        $oSession->set('session-online', $aData['online']);
        $oSession->set('session-total_view', $aData['view']);
    }
    
    public function checkLoginStatus($aParam = array())
    {
        $sAct = isset($aParam['act']) ? $aParam['act'] : '';
        $sRefer = isset($aParam['refer']) ? $aParam['refer'] : '';
        $sReferEncode = '';
        if (!empty($sRefer)) {
            $sReferEncode = $sRefer;
            $sRefer = base64_decode($sRefer);
        }
        else {
            $sRefer = Core::getParam('core.path');
            $sReferEncode = base64_encode($sRefer);
        }

        $iUserId = Core::getCookie('bb_id');
        if (Core::getUserId() > 0) {
            return array(
                'status' => 'error',
                'message' => 'Bạn đã đăng nhập. Vui lòng <a href="'.$sRefer.'">bấm vào đây</a> để chuyển trang, hoặc <a href="javascript:void(0);" class="js_logout">đăng xuất</a>.'
            );
        }
        
        $oSession = Core::getLib('session');
        if (strpos($sRefer, 'shop_') !== false) {
            $iQuantity = 0;
            $iAmount = 0;
            $aShop = $oSession->get('session-shop');
            if (!empty($aShop)) {
                foreach ($aShop as $aValue) {
                    $iQuantity++;
                    $iAmount += $aValue['price']* $aValue['quantity'];
                }
            }
            if ($iQuantity > 0 && $iAmount > 0) {
               $aCurrency = Core::getParam('setting_currency');
               
               $sMessage = 'Bạn đã đặt mua '.$iQuantity.' sản phẩm, với tổng tiền là ';
               $sMessage.= Core::getService('core.currency')->formatMoney(array(
                    'money' => $iAmount,
                    'code' => $aCurrency['code']
                   ));
               $sMessage .= ' '.$aCurrency['code'].'. Vui lòng đăng nhập để tiến hành thanh toán đơn hàng.';
               
               return array(
                    'status' => 'success',
                    'message' => $sMessage,
                    'show_message' => true
                );
            }
        }
        return array(
            'status' => 'success',
            'message' => ''
        );
    }
    
    public function getFullUserInfo($aParam = array())
    {
        
        $iDomainId = Core::getDomainId();
        $iMyId = Core::getUserId();
        ///Giả lập id 
        //$iMyId = 50;
        
        $iUserId = 0;
        $bIsMe = false;
        if (!isset($aParam['uid']) || $aParam['uid'] < 1) {
            return array();
        }
        $iUserId = $aParam['uid'];
        $bIsMe = false;
        if ($iUserId == $iMyId)
            $bIsMe = true;
        
        //Kiểm tra quyền hạn user trong trường hợp xem thông tin một user khác
        
        //Kiểm tra đã follow user này chưa
        $iFollow = -1; //-1: chưa follow, 0, là chính mình, > 0 là id follow
        if (!$bIsMe) {
            //check follow with status = 0
            $aFollow = $this->database()->select('id')
                ->from(Core::getT('follow'))
                ->where('domain_id ='.(int)$iDomainId.' AND user_id_from ='.(int)$iMyId.' AND user_id_to ='.(int)$iUserId.' AND status = 0')
                ->execute('getRow');
            if (isset($aFollow['id']) && $aFollow['id'] > 0) {
                $iFollow = $aFollow['id'];
            }
        }
        else {
            //không thể follow chính bản thân
            $iFollow = 0;
        }
        
        $aUserInfo = array();
        if (Core_Error::isPassed()) {
            //Lấy thông tin cá nhân
            $aUserInfo = $this->database()->select('id, code, user_group_id, username, fullname, email, profile_image, total_article, total_like, total_follow, join_time, last_visit, birthday, sex, address, phone_number, area_id, company, ward')
                ->from(Core::getT('user'))
                ->where('domain_id = '.(int)$iDomainId.' AND status != 2 AND id ='.(int)$iUserId)
                ->execute('getRow');
            if (!isset($aUserInfo['id'])) {
                Core_Error::set('error', 'User not found');
            }
            else {
                $aUserInfo['follow'] = $iFollow;
                if (empty($aUserInfo['fullname'])) {
                    //$aUserInfo['fullname'] = $aUserInfo['username'];
                }
                
                //check profile_image
                if (empty($aUserInfo['profile_image'])) {
                    //set default
                    if($aUserInfo['sex'] == 2)
                        $sFileName = 'female.png';
                    else
                        $sFileName = 'male.png';
                    $aUserInfo['profile_image'] = Core::getParam('core.image_path'). 'styles/web/global/images/noimage/'.$sFileName;
                }
                
                //convert time to GMT 7+
                $sTimeNone = '';
                if($aUserInfo['join_time'] > 0) {
                    $aUserInfo['join_time'] = Core::getLib('date')->convertFromGmt($aUserInfo['join_time'], Core::getParam('core.default_time_zone_offset'));
                    $aUserInfo['join_time'] = date("d/m/Y H:i:s",$aUserInfo['join_time']);
                }
                else {
                    $aUserInfo['join_time'] = $sTimeNone;
                }
                if($aUserInfo['last_visit'] > 0) {
                    $aUserInfo['last_visit'] = Core::getLib('date')->convertFromGmt($aUserInfo['last_visit'], Core::getParam('core.default_time_zone_offset'));
                    $aUserInfo['last_visit'] = date("d/m/Y H:i:s",$aUserInfo['last_visit']);
                }
                else {
                    $aUserInfo['last_visit'] = $sTimeNone;
                }
                //Lấy thông tin liên hệ
                $aContact = array();
                $aRows = $this->database()->select('id, fullname, address, phone_number')
                    ->from(Core::getT('shop_user'))
                    ->where('domain_id ='.(int)$iDomainId.' AND user_id ='.(int)$iUserId)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aContact[$aRow['id']] = $aRow;
                }
                $aUserInfo['contact_info'] = $aContact;
                
                //Lấy thông tin nhóm thành viên
                $aUserInfo['group'] = array();
                $aUserInfo['group_list'] = $this->database()->select('id, name')
                        ->from(Core::getT('user_group'))
                        ->where('status = 1')
                        ->execute('getRows');
                $aUserInfo['group_list'][] = array(
                    'id' => 0,
                    'name' => 'Nhóm mặc định',
                );
                if ($aUserInfo['user_group_id'] > 0) {
                    $aGroup = $this->database()->select('id, name')
                        ->from(Core::getT('user_group'))
                        ->where('id ='.$aUserInfo['user_group_id'])
                        ->execute('getRow');
                    if (isset($aGroup['id'])) {
                        $aUserInfo['group']['id'] = $aGroup['id'];
                        $aUserInfo['group']['name'] = $aGroup['name'];
                    }
                }
                else {
                    //Nhóm mặc định
                    $aUserInfo['group']['id'] = 0;
                    $aUserInfo['group']['name'] = 'Nhóm mặc định';
                }
                //Lấy thông tin địa chỉ
                $iDegree = -1;
                $iParentId = -1;
                $iAreaId = -1;
                if ($aUserInfo['area_id'] > 0) {
                    // tách trường địa chỉ thành các trường thông tin chi tiết.
                    $sStr = Core::getService('core.area')->parse($aUserInfo['area_id']);
                    
                    $aUserInfo['street'] = str_replace($sStr, '', $aUserInfo['address']); 
                    $aUserInfo['street'] = trim($aUserInfo['street'],', ');
                    
                    $aRow = $this->database()->select('id, name, parent_id, degree')
                        ->from(Core::getT('area'))
                        ->where('id = '. $aUserInfo['area_id'])
                        ->execute('getRow');  
                    
                    if ($aRow['id'] > 0) {
                        $iDegree = $aRow['degree'];
                        $iParentId = $aRow['parent_id'];
                        $iAreaId = $aRow['id'];
                        if ($iDegree > 2) {
                            $aUserInfo['district']['id'] = $aRow['id'];
                            $aUserInfo['district']['name'] = $aRow['name'];
                            
                            $aRow = $this->database()->select('id, name, parent_id')
                                ->from(Core::getT('area'))
                                ->where('id = '. $aRow['parent_id'])
                                ->execute('getRow');
                            
                        }
                        if ($iDegree > 1) {
                            $aUserInfo['city']['id'] = $aRow['id'];
                            $aUserInfo['city']['name'] = $aRow['name'];
                        }
                        if ($iDegree != 1) {
                            $aRow = $this->database()->select('id, name')
                                ->from(Core::getT('area'))
                                ->where('id = '. $aRow['parent_id'])
                                ->execute('getRow');
                        }
                        
                        $aUserInfo['country']['id'] = $aRow['id'];
                        $aUserInfo['country']['name'] = $aRow['name'];
                    }
                    
                }
                
                
                if ($iDegree < 1) {
                    //mặc dịnh lấy thông tin tỉnh thành quốc gia VN
                }
                else {
                    if ($iDegree == 1) {
                        //Chi lấy thông tin tất cả các tỉnh thành
                        
                    }
                    else if ($iDegree > 1) {
                        //Lấy thông tin các tỉnh thành + huyện
                        $iDistrict = -1;
                        $iCountry = $iParentId;
                        if ($iDegree == 3) {
                            $iCountry = $this->database()->select('parent_id')
                                ->from(Core::getT('area'))
                                ->where('id ='.$iParentId)
                                ->execute('getField');
                            $iDistrict = $iParentId;
                        }
                        else {
                            $iDistrict = $iAreaId;
                        }
                        //Thông tin tất cả các huyện
                        $aUserInfo['district_list'] = $this->database()->select('id, name')
                            ->from(Core::getT('area'))
                            ->where('status = 1 AND parent_id = '. $iDistrict)
                            ->execute('getRows');
                        //Thông tin tất cả các tỉnh thành
                        $aUserInfo['city_list'] = $this->database()->select('id, name')
                            ->from(Core::getT('area'))
                            ->where('status = 1 AND parent_id = '. $iCountry)
                            ->execute('getRows');
                    }
                }
                //Thông tin tất cả các quốc gia
                $aUserInfo['country_list'] = $this->database()->select('id, name')
                        ->from(Core::getT('area'))
                        ->where('status = 1 AND degree = 1')
                        ->execute('getRows');
            }
        }
        return $aUserInfo;
    }
    
    
    public function getPurchaseHistory($aParam = array())
    {
        $iDomainId = Core::getDomainId();
        $iUserId = 0;
        if (isset($aParam['uid']) && $aParam['uid'] > 0) {
            $iUserId = $aParam['uid'];
        }
        else {
            $iUserId = Core::getUserId();
        }
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        //Kiểm tra quyền hạn user trong trường hợp xem thông tin một user khác
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $iCnt = 0;
        $aOrders = array();
        if (Core_Error::isPassed()) {
            
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('shop_order'))
                ->where('domain_id ='.(int)$iDomainId.' AND status != 2 AND user_id ='.(int)$iUserId)
                ->execute('getField');
            
            if ($iCnt > 0) {
                $aRows = $this->database()->select('id, code, total_amount, total_product, fullname, address, phone_number, payment_gateway, deliver_time_from, deliver_time_to, create_time, surcharge, money_recieve, status_deliver, status')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='.(int)$iDomainId.' AND status != 2 AND user_id ='.(int)$iUserId)
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->order('create_time DESC')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    //convert time to GMT +7
                    $sTimeNone = '--/--/--';
                    if($aRow['deliver_time_from'] > 0) {
                        $aRow['deliver_time_from'] = Core::getLib('date')->convertFromGmt($aRow['deliver_time_from'], Core::getParam('core.default_time_zone_offset'));
                        $aRow['deliver_time_from'] = date("d/m/Y H:i:s",$aRow['deliver_time_from']);
                    }
                    else {
                        $aRow['deliver_time_from'] = $sTimeNone;
                    }
                    
                    if ($aRow['deliver_time_to'] > 0) {
                        $aRow['deliver_time_to'] = Core::getLib('date')->convertFromGmt($aRow['deliver_time_to'], Core::getParam('core.default_time_zone_offset'));
                        $aRow['deliver_time_to'] = date("d/m/Y H:i:s",$aRow['deliver_time_to']);
                    }
                    else {
                        $aRow['deliver_time_to'] = $sTimeNone;
                    }
                    
                    if ($aRow['create_time'] > 0) {
                        $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
                        $aRow['create_time'] = date("d/m/Y H:i:s",$aRow['create_time']);
                    }
                    else {
                        $aRow['create_time'] = $sTimeNone;
                    }
                    
                    //mapping deliver status
                    $aMappingStatus = array(
                        'dang-xu-ly' => 'Đang xử lý',
                        'da-xac-nhan' => 'Đã xác nhận',
                        'dang-thanh-toan' => 'Thanh toán một phần',
                        'da-thanh-toan' => 'Đã thanh toán',
                        'da-dong-goi' => 'Đã đóng gói',
                        'dang-giao-hang' => 'Đang giao hàng',
                        'hoan-thanh' => 'Hoàn thành',
                        'hoan-tra' => 'Hoàn trả',
                        'huy-bo' => 'Hủy bỏ',
                    );
                    
                    if (empty($aRow['status_deliver'])) {
                        $aRow['status_deliver'] = 'Đang chờ xử lý';
                    }
                    else {
                        $aRow['status_deliver'] = $aMappingStatus[$aRow['status_deliver']];
                    }
                    
                    //mapping payment gateway
                    $aOrders[$aRow['id']] = $aRow;
                }
            }
        }
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aOrders,
        );
    }
    
    public function getActivityFriends($aParam = array())
    {
        $iDomainId = Core::getDomainId();
        $iUserId = 0;
        if (isset($aParam['uid']) && $aParam['uid'] > 0) {
            $iUserId = $aParam['uid'];
        }
        else {
            $iUserId = Core::getUserId();
        }
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        //Kiểm tra quyền hạn user trong trường hợp xem thông tin một user khác
        
        //Lấy thông tin những user đang được follow
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $iCnt = 0;
        $aUserFollows = array();
        if (Core_Error::isPassed()) {
            $aRows = $this->database()->select('id, user_id_from, user_id_to')
                ->from(Core::getT('follow'))
                ->where('domain_id ='.(int)$iDomainId.' AND status = 0 AND user_id_from ='.(int)$iUserId)
                ->order('time DESC')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aUserFollows[] = $aRow['user_id_to'];
            }
            
            $aUserFollows[] = 2238;
            $aUserFollows[] = 2339;
            $aUserFollows[] = 4935;
            $aUserFollows[] = 4421;
            //d($aUserFollows); die();
            
            if (!empty($aUserFollows)) {
                $iCnt = $this->database()->select('count(*)')
                    ->from(Core::getT('emotion'))
                    ->where('user_id IN('.implode(',', $aUserFollows).')')
                    ->execute('getField');
                
                if ($iCnt > 0) {
                    //lấy thông tin mới nhất từ Emotion
                    $aEmotions = array();
                    $aUserId = array();
                    $aTmps = $this->database()->select('id, user_id, time, type')
                        ->from(Core::getT('emotion'))
                        ->where('user_id IN('.implode(',', $aUserFollows).')')
                        ->order('time DESC')
                        ->limit($iPage, $iPageSize, $iCnt)
                        ->execute('getRows');
                    
                    foreach ($aTmps as $aTmp) {
                        $aTmp['time'] = Core::getLib('date')->convertFromGmt($aTmp['time'], Core::getParam('core.default_time_zone_offset'));
                        $aTmp['time'] = date("d/m/Y H:i:s",$aTmp['time']);
                        $aEmotions[$aTmp['id']] =  $aTmp;
                        if (!in_array($aTmp['user_id'], $aUserId)) {
                            $aUserId[] = $aTmp['user_id'];
                        }
                    }
                    
                    //Lấy nội dung emotion
                    $aMappingEmotion = array();
                    if (!empty($aEmotions)) {
                        $aContents = $this->database()->select('emotion_id, content')
                            ->from(Core::getT('emotion_content'))
                            ->where('emotion_id IN ('.implode(',', array_keys($aEmotions)).')')
                            ->execute('getRows');
                        foreach ($aContents as $aContent) {
                            $aMappingEmotion[$aContent['emotion_id']] = $aContent['content'];
                        }
                    }
                    
                    //Lấy thông tin user
                    $aMappingUser = array();
                    if (!empty($aUserId)) {
                        $aMappingUser = Core::getService('user.community')->getUserInfo(array(
                            'user_list' => $aUserId,
                        ));
                    }
                    
                    //mapping data
                    foreach($aTmps as $aTmp) {
                        $aUserTmp = $aMappingUser[$aTmp['user_id']];
                        $aContentTmp = $aMappingEmotion[$aTmp['id']];
                        $aEmotions[$aTmp['id']]['user_info'] = $aUserTmp;
                        $aEmotions[$aTmp['id']]['content'] = $aContentTmp;
                    }
                }
                
            }
            
        }
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aEmotions
        );
    }
    
    public function getLikeProductHistory($aParam = array())
    {
        $iDomainId = Core::getDomainId();
        $iUserId = 0;
        if (isset($aParam['uid']) && $aParam['uid'] > 0) {
            $iUserId = $aParam['uid'];
        }
        else {
            $iUserId = Core::getUserId();
        }
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        //Kiểm tra quyền hạn user trong trường hợp xem thông tin một user khác
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $iCnt = 0;
        $aProductList = array();
        if (Core_Error::isPassed()) {
            //Lấy thông tin những bài viết được like
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('like'))
                ->where('domain_id ='.(int)$iDomainId.' AND type_name = \'bai_viet\' AND status !=2 AND user_id ='.(int)$iUserId)
                ->execute('getField');
            
            if ($iCnt > 0) {
                $aRows = $this->database()->select('id, type_id, type_name, time')
                    ->from(Core::getT('like'))
                    ->where('domain_id ='.(int)$iDomainId.' AND type_name = \'bai_viet\' AND status !=2 AND user_id ='.(int)$iUserId)
                    ->order('time DESC')
                    ->execute('getRows');
                $aProductId = array();
                foreach ($aRows as $aRow) {
                    if (!in_array($aRow['type_id'], $aProductId)) {
                        $aProductId[] = $aRow['type_id'];
                    }
                }
                
                //Lấy thông tin chi tiết bài viết
                if (!empty($aProductId)) {
                    $aProductList = $this->getProducts(array(
                        'list' => $aProductId
                    ));
                }
            }
        }
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aProductList
        );
    }
    
    public function getPurchaseProductHistory($aParam = array())
    {
        $iDomainId = Core::getDomainId();
        $iUserId = 0;
        if (isset($aParam['uid']) && $aParam['uid'] > 0) {
            $iUserId = $aParam['uid'];
        }
        else {
            $iUserId = Core::getUserId();
        }
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        //Kiểm tra quyền hạn user trong trường hợp xem thông tin một user khác
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $iCnt = 0;
        $aProductList = array();
        if (Core_Error::isPassed()) {
            //Lấy thông tin những đơn hàng đã mua, những đơn hàng thỏa điều kiện
            $aRows = $this->database()->select('id, status_deliver, status')
                ->from(Core::getT('shop_order'))
                ->where('domain_id ='.(int)$iDomainId.' AND status != 2 AND user_id ='.(int)$iUserId)
                ->order('create_time DESC')
                ->execute('getRows');
            //Lọc lấy những đơn hàng phù hợp
            $aIdTmp = array();
            foreach ($aRows as $aRow) {
                //Lấy danh sách những đơn hàng đã và sắp thực hiện thành công sử dụng cho phần lấy sản phẩm
                if ($aRow['status_deliver'] != ''
                    && $aRow['status_deliver'] != 'bi-tra-ve'
                    && $aRow['status_deliver'] != 'da-huy'
                    && $aRow['status_deliver'] != 'khong-nhan-hang') {
                    $aIdTmp[] = $aRow['id'];
                }
            }
            
            //Lấy thông tin từ chi tiết đơn hàng
            if (!empty($aIdTmp)) {
                $aRows = $this->database()->select('id, shop_order_id, article_id, sku, quantity, price_discount, note, buy_with')
                    ->from(Core::getT('shop_order_dt'))
                    ->where('shop_order_id IN ('.implode(',', $aIdTmp).')')
                    ->execute('getRows');
                $aArticleId = array();
                foreach ($aRows as $aRow) {
                    if (!in_array($aRow['article_id'], $aArticleId)) {
                        $aArticleId[] = $aRow['article_id'];
                    }
                }
                
                //Dựa vào page và page_size để lấy thông tin những sản phẩm cần lấy và phần trang
                $iCnt = count($aArticleId);
                if (!empty($aArticleId)) {
                    $aArticleId = array_slice($aArticleId, ($iPage - 1)*$iPageSize, $iPageSize);
                    //Lấy thông tin sản phẩm hiện tại
                    $aProductList = $this->getProducts(array(
                        'list' => $aArticleId
                    ));
                }
            }
        }
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aProductList,
        );
    }
    
    /**
    * Lấy thông tin của tất cả các sản phẩm được truyền vào qua id
    * 
    * @param mixed $aParam
    */
    public function getProducts($aParam = array())
    {
        $aId = array();
        if (isset($aParam['list'])) {
            $aId = $aParam['list'];
        }
        
        if (empty($aId)) {
            return array();
        }
        
        $aArticles = array();
        $aMapping = array();
        $aRows = $this->database()->select('title, id, description, image_path, detail_path, expire_time, image_extend')
            ->from(Core::getT('article'))
            ->where('id IN ('.implode(',', $aId).')')
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aTmp = array();
            $aTmp['id'] = $aRow['id'];
            $aTmp['link_id'] = $aRow["link_id"];
            $aTmp['name'] = $aRow['title'];
            $aTmp['name_html'] = htmlspecialchars($aRow['title']);
            $aTmp['description'] = $aRow['description'];
            $aTmp['expire_time'] = $aRow["expire_time"]*1;
            $aTmp['image_path'] = $aRow["image_path"];
            $aTmp['path'] = $aRow["detail_path"];
            $aTmp['image_extend'] = $aRow["image_extend"];
            $aTmp['group'] = $aRow["group_article"];
            
            $aMapping[$aRow['id']] = $aTmp;
        }
        //Foreach lần nữa để giữ nguyên thứ tự của của dữ liệu input vào
        foreach ($aId as $sIdTmp) {
            $aArticles[$sIdTmp] = $aMapping[$sIdTmp];
        }
        
        if (!empty($aArticles)) {
            // tính lại danh sách bài viết
            $aTmps = array();
            $aArticleImageExtendList = array();
            foreach ($aArticles as $Key => $Val) {
                $aTmps[] = $Val['id'];
                    
                if ($Val['image_extend'] > 0) {
                    $aArticleImageExtendList[] = $Val['id'];
                    $aArticles[$Key]['image_extend'] = array();
                }
            }
            // web shop
            if (!empty($aTmps)) {
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('shop_article'))
                    ->where('article_id IN ('.implode(',', $aTmps).')')
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    foreach ($aRow as $Key => $Val) {
                        /*
                        if (is_nan($Key))
                            continue;
                        */
                        if ($Key == 'price_sell'
                            || $Key == 'price_discount'
                            || $Key == 'weight'
                            || $Key == 'buy_quantity'
                            || $Key == 'total_quantity'
                            || $Key == 'necessary_quantity')
                            $Val *= 1;
                        
                        $aRow[$Key] = $Val;
                    }
                    
                    foreach ($aArticles as $Key => $Val) {
                        if ($Val['id'] != $aRow["article_id"])
                            continue;
                        
                        foreach ($aRow as $sKey => $sVal) {
                            if ($sKey == 'id' || $sKey == 'article_id')
                                continue;
                            $aArticles[$Key][$sKey] = $sVal;
                        }
                        $aArticles[$Key]['amount'] = $aRow["price_sell"]*1 - $aRow["price_discount"]*1;
                        
                        $sKey = 'deliver_method';
                        if ($aArticles[$Key][$sKey] == 1)
                            $aArticles[$Key][$sKey] = 'Delivery Receipt';
                        else
                            $aArticles[$Key][$sKey] = 'Delivery Products';
                        //----
                    }
                }
            }
            // Hình mở rộng cho phần Liên kết
            if (!empty($aArticleImageExtendList)) {
                // lấy danh sách hình ảnh
                $aRows = $this->database()->select('id, name_code')
                    ->from(Core::getT('image_extend'))
                    ->where('status = 1 AND domain_id ='.(int)Core::getDomainId())
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    $aImageExtendIdToNameCode[$aRow["id"]] = $aRow["name_code"];
                }
                
                $aRows = $this->database()->select('object_id as article_id, image_extend_id, path')
                    ->from(Core::getT('image_extend_link'))
                    ->where('object_type = 0 AND object_id IN ('.implode(',', $aArticleImageExtendList).')')
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    $sImageExtendNameCode = $aImageExtendIdToNameCode[$aRow["image_extend_id"]];
                    foreach ($aArticles as $Key => $aVal) {
                        if ($aVal['id'] == $aRow["article_id"]) {
                            $aArticles[$Key]['image_extend'][$sImageExtendNameCode] = $aRow["path"];
                        }
                    }
                }
            }
        }
        
        return $aArticles;
    }
    
    public function getNotice($aParam = array())
    {
        return array(
            'total' => 0,
            'page' => 1,
            'page_size' => 10,
            'data' => array(),
        );
    }
    
    public function getViewProduct($aParam = array())
    {
        $iDomainId = Core::getDomainId();
        $iUserId = 0;
        if (isset($aParam['uid']) && $aParam['uid'] > 0) {
            $iUserId = $aParam['uid'];
        }
        else {
            $iUserId = Core::getUserId();
        }
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        //Kiểm tra quyền hạn user trong trường hợp xem thông tin một user khác
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $iCnt = 0;
        $aProductList = array();
        if (Core_Error::isPassed()) {
            //Lấy danh sách id của những sản phẩm đã xem từ log
            $aLogSIDs = $this->database()->select('id')
                ->from(Core::getT('log_access_sid'))
                ->where('domain_id ='.(int)$iDomainId.' AND user_id ='.(int)$iUserId)
                ->order('update_time DESC, create_time DESC')
                ->execute('getRows');
                
            $aIdTmp = array();
            foreach ($aLogSIDs as $aLogSID) {
                $aIdTmp[] = $aLogSID['id'];
            }
            if(!empty($aIdTmp)) {
                //Lấy thông tin từ log truy cập
                $aLogAccess = $this->database()->select('id, object_id')
                    ->from(Core::getT('log_access_dt'))
                    ->where('object_type = \'bai_viet\' AND log_access_sid_id IN ('.implode(',', $aIdTmp).')')
                    ->order('update_time DESC, create_time DESC')
                    ->execute('getRows');
                //Lấy thông tin chi tiết sàn phẩm
                $aArticleId = array();
                foreach ($aLogAccess as $aRow) {
                    if (!in_array($aRow['object_id'], $aArticleId)) {
                        $aArticleId[] = $aRow['object_id'];
                    }
                }
                //Lấy theo trang
                if (!empty($aArticleId)) {
                    $iCnt = count($aArticleId);
                    $aArticleId = array_slice($aArticleId, ($iPage - 1)*$iPageSize, $iPageSize);
                    $aProductList = $this->getProducts(array(
                        'list' => $aArticleId
                    ));
                }
            }
        }
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aProductList,
        );
    }
    
    public function getTop($aParam = array())
    {
        $iDomainId = Core::getDomainId();
        
        //id of category, default in homepage
        $iId = -1;
        
        if (isset($aParam['id']) && $aParam['id']) {
            $iId = $aParam['id'];
        }
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }    
        }
        
        $sConds= '';
        if (isset($aParam['start_time']) && isset($aParam['end_time'])) {
            if ($aParam['start_time'] > 0 && $aParam['end_time']> 0) {
                $sConds .= ' AND (time BETWEEN '.$aParam['start_time'].' AND '.$aParam['end_time'].')';
            }
        }
        
        $iCnt = 0;
        $aUserList = array();
        
        //Lấy thông tin sô lượng sản phẩm đã mua từ shop_order (những đơn hàng đã giao dịch thành công - bổ sung sau)
        $aTmp = $this->database()->select('user_id, sum(total_product) as total_buy')
            ->from(Core::getT('shop_order'))
            ->where('domain_id ='.(int)$iDomainId.$sConds)
            ->group('user_id')
            ->having('total_buy > 0')
            ->execute('getRows');
        $iCnt = count($aTmp);
        if ($iCnt > 0) {
            $aRows = $this->database()->select('user_id, count(id) as cnt, sum(total_product) as total_buy')
                ->from(Core::getT('shop_order'))
                ->where('domain_id ='.(int)$iDomainId.$sConds)
                ->group('user_id')
                ->having('total_buy > 0')
                ->order('total_buy DESC')
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            
            $aUserId = array();
            
            foreach ($aRows as $aRow) {
                if (!in_array($aRow['user_id'], $aUserId)) {
                    $aUserId[] = $aRow['user_id'];
                }
            }
            
            //get info user & get total cart had been created
            $aMappingUser = array();
            $aMappingCart = array();
            if (!empty($aUserId)) {
                $aMappingUser = Core::getService('user.community')->getUserInfo(array(
                    'user_list' => $aUserId,
                ));
                
                $aCarts = $this->database()->select('count(id) as total_cart, user_id')
                    ->from(Core::getT('cart'))
                    ->where('status != 2')
                    ->group('user_id')
                    ->execute('getRows');
                foreach ($aCarts as $aCart) {
                    $aMappingCart[$aCart['user_id']] = $aCart['total_cart'];
                }
            }

            
            foreach ($aRows as $aRow) {
                $aTmp = array();
                $aTmp['total_buy'] = $aRow['total_buy'];
                
                $aTmp['user'] = $aMappingUser[$aRow['user_id']];
                if (!isset($aMappingCart[$aRow['user_id']])) {
                    $aTmp['total_cart'] = 0;
                }
                else {
                    $aTmp['total_cart'] = $aMappingCart[$aRow['user_id']];
                }
                $aUserList[$aRow['user_id']] = $aTmp;
            }
        }
        
        return array(
            'total' => $iCnt,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'data' => $aUserList
        );
    }
    
    public function searchUser($aParam = array())
    {
        $sKey = isset($aParam['key']) ? $aParam['key'] : '';
        if (empty($sKey)) {
            return array();
        }
        
        $sConds = 'tv.status != 2 AND tv.reference_id = 0 AND tv.domain_id ='.Core::getDomainId();
        $sConds .= ' AND (tv.fullname LIKE \'%'. $this->database()->escape($sKey).'%\' OR tv.username LIKE \'%'. $this->database()->escape($sKey).'%\' OR tv.email LIKE \'%'. $this->database()->escape($sKey).'%\')';
        
        $aRows = $this->database()->select('tv.id, tv.code, tv.fullname, tv.username, tv.phone_number, tv.email')
            ->from($this->_sTable, 'tv')
            ->where($sConds)
            ->order('tv.fullname ASC')
            ->limit(10)
            ->execute('getRows');
        return $aRows;
    }
    
}
?>
