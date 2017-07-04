<?php
class User_Service_Activity extends Service
{
    public function __construct()
    {
        $this->_sTable = Core::getT('user');
    }
    
    public function getActivityBuy($aParam)
    {
        $oSession = Core::getLib('session');
        
        $iPage = 1;
        $iPageSize = 20;
        if(isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if(isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 20;
            }    
        }
        $sOrder = 'time DESC';
        if(isset($aParam['order'])) {
            $sOrder = $aParam['order'];
        }
        //get sum of activity
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('emotion'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND type = 1 AND status != 2')
            ->execute('getField');
        if ($iCnt < 1) {
            return array();
        }
        
        $aRows = $this->database()->select('id, user_id, time')
            ->from(Core::getT('emotion'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND type = 1 AND status != 2')
            ->order($sOrder)
            ->limit($iPage, $iPageSize, $iCnt)
            ->execute('getRows');
        if (count($aRows) > 0) {
            $aEmotionIdList = array();
            $aUserIdList = array();
            
            foreach ($aRows as $aRow) {
                $aEmotionIdList[] = $aRow['id'];
                $aUserIdList[] = $aRow['user_id'];
            }
            //get content
            $aMappingEmotion  = array();
            $aTmps = $this->database()->select('emotion_id, content')
                ->from(Core::getT('emotion_content'))
                ->where('emotion_id IN ('.implode(',', $aEmotionIdList).')')
                ->execute('getRows');
            foreach ($aTmps as $aTmp) {
                $aMappingEmotion[$aTmp['emotion_id']] = $aTmp;
            }
            //get info user
            $aMappingUser = array();
            $aTmps = $this->database()->select('id, code, fullname, profile_image, sex')
                ->from(Core::getT('user'))
                ->where('id IN ('.implode(',', $aUserIdList).')')
                ->execute('getRows');
            foreach ($aTmps as $sKey => $aTmp) {
                if (empty($aTmp['profile_image'])) {
                    //set default profile image
                    if($aTmp['sex'] == 2)
                        $sFileName = 'female.png';
                    else
                        $sFileName = 'male.png';
                    $aTmp['profile_image'] =  Core::getParam('core.image_path'). 'styles/web/global/images/noimage/'.$sFileName;
                }
                $aMappingUser[$aTmp['id']] = $aTmp;
            }
            $aData = array();
            foreach ($aRows as $aRow) {
                $aTmp = array();
                $aTmp['user_id'] = $aRow['user_id'];
                $aTmp['user_code'] = $aMappingUser[$aRow['user_id']]['code'];
                $aTmp['user_fullname'] = $aMappingUser[$aRow['user_id']]['fullname'];
                $aTmp['user_profile_image'] = $aMappingUser[$aRow['user_id']]['profile_image'];
                $aTmp['emotion_id'] = $aRow['id'];
                $aRow['time'] = Core::getLib('date')->convertFromGmt($aRow['time'], Core::getParam('core.default_time_zone_offset'));
                $aTmp['emotion_time'] = date("j M, Y",$aRow['time']);
                $aTmp['emotion_comtent'] = $aMappingEmotion[$aRow['id']]['content'];
                $aData[] = $aTmp;
            }
            unset($aRows);
            unset($aMappingUser);
            return $aData;
        }
        
    }
    
    public function actionEmotion($aParam = array())
    {
        
    }
    
    public function createEditEmotion($aParam = array())
    {
        /*
            Đăng trạng thái sẽ có các lựa chọn
                Add        http://vodichgia.com/tools/api.php?type=cam_xuc&action=add
                Edit    http://vodichgia.com/tools/api.php?type=cam_xuc&action=edit&id=3
                Delete
            +++++++++++++++++++
            Phần đăng status
            
                User soạn nội dung và gửi ajax đến server
                Server lưu vào db
                    Bảng cam_xuc: lưu nội dung status
                    Bảng tuong_thanh_vien_tam: lưu các thành viên đang follow user để khi user follow đăng nhập thì xử lý, xử lý gọi ngầm, và thực hiện từ mới đến cũ để có thể show các bài mới nhất. Bảng này sẽ xóa tự động sau 30 ngày. Như vậy với các thông tin quá 30 ngày mà thành viên ko truy cập, sẽ ko đc hiển thị.
                Khi user follow đăng nhập:
                    Hệ thống đọc bảng tạm từ thời gian đăng nhập lần cuối và bắt đầu chạy thuật toán để lấy danh sách bài viết user mong muốn. Sau đó, lưu vào bang tuong_thanh_vien, ko cần xóa tuong_thanh_vien_tam vì user đăng nhập thì chỉ lấy từ thời gian đăng nhập cuối.
                    Tiếp theo đọc từ tuong_thanh_vien để lấy danh sách cam_xuc STT, sau đó hiển thị.
                    => Như vậy, khi user đăng, like 1 bài viết, hay comment thì cũng xem như là user đăng 1 status. Vấn đề khi đó nếu user chỉnh sửa bài viết
                        Nếu ko làm cách trên, thì khi user đăng nhập phải đọc hết toàn bộ user đang follow.
                        
                    => Làm cách lưu vào bảng tuong_thanh_vien_tam, vậy cần đổi tên bảng thành theo_doi_thanh_vien_tam. Và trong đây sẽ có loại theo dõi để biết là trang_thai, bv,...
                Như vậy thông tin đã đc phát tán đến tường user
                
            Trường hợp User đăng status lên tường user khác, vẫn tính là đăng status của mình với tính năng tags.
            
            Khi user đăng 1 bài viết, sẽ đăng 1 status => thông báo đến tất cả user
            
            Còn trên trang cá nhân, sẽ đọc tất cả trường  bao gồm bài viết, cảm xúc, bình luận, like, share,... Cần 1 bảng để lưu tất cả thông tin trên, bảng này sẽ câp nhật ngay khi user có thao tác
            
            
            Cấu trúc hiện tại bị ngược, đúng ra tuong_thanh_vien phải là trang cá nhân, vì trang này show các bài viết của user khác và ở đây cho dổi pass
            Thêm trang ca nhan là khi user khac vào trang mình sẽ thấy thông tin bao gồm bài viết, cảm xúc, bình luận, like, share,...
            
            Khi tác giả đăng 1 bài viết, sẽ có 2 bước đc gọi liên quan đến Social
                1. Đăng lên tường của mình - Dùng 1 bảng như bảng tuong_thanh_vien, để lưu tất cả tương tác bao gồm bài viết, cảm xúc, bình luận, like, share,...Trong đó có loại và loại stt. Khi có truy vấn chỉ cần tải dữ liệu ra lại, dành cho trường hợp @tên.
                    Khi đăng bài viết thành công, lưu vào db. Các comment bên trong sẽ là dạng comment con trong bài viết
                    Khi comment (bai viet OR de_tai, OR comment), lưu vào db. Vói comment phải móc thêm bài viết. Các comment bên trong sẽ là dạng comment con trong bài viết
                    Khi đăng status, lưu vào db, loại comment sẽ là comment status.
                    Khi like, share bài viết, like comment, 
                    Như vậy tren trang cua thành viên  sẽ hiên thị
                        1.    Bài viết đc viết
                        2.    Bài viết đc like
                        3.    Bc dc comment
                        4.    Bv dc đọc gôm thành top theo giờ
                        5.    Top 20 bv của các follow
                        6.    Status,cho like status
                        7.    Comment trong status,nhu vay comment se co phan loai là đè tai,bv,status..loại này tương ứng vói loại trong bảng like
                        8.    Khi đổi avaya,cover se up 1 statua

                2. THông báo đến các user follow - hoàn tất, chuyển cam_xuc_stt thành loại tên và loại stt, ko cần thiết vì chỉ đẩy thông tin status. Xem như là 1 thao tác chia sẻ link
        */
        
        /* gia lap *
        $aParam = array(
            'type_status' => 5,//Tạo 1 bài viết thảo luân
            'content' => 'Vừa đăng bài viết...<br>[article]'.'123456'.'[/article]',
            'action' => 'add', //edit
            'id' => 0, //id của cảm xúc cần chỉnh sửa
            
        );
        /* *///-----------------
        
        
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $iUserId = Core::getUserId();
        $iDomainId = Core::getDomainId();
        
        if ($iUserId < 1) {
            Core_Error::set('error', 'Session time out');
            $bIsReturn = true;
        }

        $sAction = $aParam['action'];
        
        $iTypeStatus = 0;
        if (isset($aParam['type_status'])) {
            $iTypeStatus = $aParam['type_status'];
        }
        
        if ($iTypeStatus < 1) {
            Core_Error::set('error', 'Empty type of action');
            $bIsReturn = true;
        }
        
        $aData = array();
        if ( !$bIsReturn && ($sAction == 'add' || $sAction == 'edit')) {
            // check stt
            if ($sAction == 'edit') {
                $aData['id'] = $id;
                if ($aData['id'] < 1) {
                    Core_Error::set('error', 'Empty ID');
                    $bIsReturn = true;
                }
            }
            
            $aData['content'] = $aParam['content'];
            
            if (empty($aData['content'])) {
                Core_Error::set('error', 'Empty data');
                $bIsReturn = true;
            }
            if (!$bIsReturn) {
                // nếu nôi dung chỉ có 1 dòng, loại bỏ html thẻ p
                
                $aData['content'] = Core::getLib('input')->filterTagsPHeadLine($aData['content']);
                // end
                $aData['content'] = Core::getLib('input')->optimalBbcode($aData['content']);
                
                // xử lý html
                $aConfigTidy = array(
                    'indent' => true,
                    'output-xhtml' => true,
                    'show-body-only' => true);
                $oTidy = new tidy();
                $oTidy->parseString($aData['content'], $aConfigTidy, 'utf8');
                $oTidy->cleanRepair();
                $aData['content'] = $oTidy;
                // end
                $sTmp = Core::getService('core.tools')->checkTags($aData['content']);
                
                if (!empty($sTmp)) {
                    Core_Error::set('error', $sTmp);
                    $bIsReturn = true;
                }
            }
            if (!$bIsReturn) {
                $aData['content'] = Core::getLib('input')->modifyUrl($aData['content']);
                
                if ($aData['id'] < 1) {
                    $aInsert = array(
                        'user_id' => $iUserId,
                        'time' => CORE_TIME,
                        'type' => $iTypeStatus,
                        'domain_id' => $iDomainId
                    );
                    $aData['id'] = $this->database()->insert(Core::getT('emotion'), $aInsert);
                    //Insert content
                    $aInsert = array (
                        'emotion_id' => $aData['id'],
                        'content' => addslashes($aData['content'])
                    );
                    $this->database()->insert(Core::getT('emotion_content'), $aInsert);
                }
                else {
                    //update emotion content
                    $this->database()->update(
                        Core::getT('emotion_content'),
                        array('content' => addslashes($aData['content'])),
                        'domain_id ='.$iDomainId.' AND user_id='.$iUserId.' AND emotion_id='.$aData['id']
                    );
                }
                
                $aOutput['id'] = $aData['id'];
            }
            
            // lưu đến TƯờng
            if ($sAction == 'add') {
                // lấy danh sách user đang follow user đăng
                $aData['ufollow'] = array();
                $aRows = $this->database()->select('user_id_from')
                    ->from(Core::getT('follow'))
                    ->where('domain_id = '.$iDomainId.' AND status = 0 AND user_id_to = '.$iUserId)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aData['ufollow'][] = $aRow['user_id_from'];
                }
                foreach ($aData['ufollow'] as $iUserIdTo) {
                    //Thêm vào bảng tạm tường thành viên
                    $aInsert = array (
                        'emotion_id' => $aData['id'],
                        'user_id_from' => $iUserId,
                        'user_id_to' => $iUserIdTo,
                        'time' => CORE_TIME,
                        'domain_id' => $iDomainId,
                    );
                    $this->database()->insert(Core::getT('user_wall_temp'), $aInsert);
                }
            }
        }

        if ($bIsReturn){
            $aOutput['status'] = 'error';
            $aOutput['error'] = Core_Error::get('error');
        }
        else {
            $aOutput['status'] = 'success';
        }
        return $aOutput;
    }
    
    public function getEmotionUser($aParam = array())
    {
        /*
            vodichgia.com/tools/api.php?type=cam_xuc_thanh_vien&action=get
            
            Cảm xúc của 1 thành viên khác, thì vào file cam_xuc_thanh_vien.php. , còn để lấy Tường thành viên là để lấy newsfeed
            Thuật toán trong api/cam_xuc.php

        */
        
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $iUserId = Core::getUserId();
        $iDomainId = Core::getDomainId();
        
        if ($iUserId < 1) {
            Core_Error::set('error', 'Session time out');
            $bIsReturn = true;
        }
        
        $sAction = $aParam['action'];
        $iUserId = $aParam['uid'];
        /* gia lap */

        ////-----------------
        if ($iUserId < 1) {
            Core_Error::set('error', 'User not found');
            $bIsReturn = true;
        }
        
        $aData = array();
        if (!$bIsReturn && $sAction == 'get') {
            // lấy danh sách tuong_thanh_vien_tam
            $aRows = $this->database()->select('id, emotion_id, time')
                ->from(Core::getT('emotion'))
                ->where('domain_id = '.$iDomainId.' AND status != 2 AND user_id = '.$iUserId)
                ->execute('getRows');
            $sCond = '';
            foreach ($aRows as $aRow) {
                $aData[] = array(
                    'emotion_id' => $aRow['emotion_id'],
                    'time' => $aRow['time'],
                );
                $sCond .= $aRow['emotion_id'].',';
            }
            
            // lấy thông tin bài viết
            $aRows = array();
            if (!empty($sCond)) {
                $sCond = rtrim($sCond, ',');
                $aRows = $this->database()->select('emotion_id, content')
                    ->from(Core::getT('emotion'))
                    ->where('emotion_id IN ('.$sCond.')')
                    ->execute('getRows');
            }
            $sCond = '';
            foreach ($aData as $Key => $Val) {
                foreach ($aRows as $aRow) {
                    if ($Val['emotion_id'] != $aRow['emotion_id'])
                        continue;
                    
                    $aData[$Key]['content'] = $aRow['content'];
                    break;
                }
            }
            // lấy thông tin user
            // thông tin user là đã có ở tường, tuy nhiên lấy mối quan hệ với user hiện tại, vì trang cảm xúc sẽ đc cache lại
            $aTmps['user_id_from'] = array();
            $aTmps['user_id_from'][] = $iUserId;
            // thong tin tên gợi ý
            $aRows = $this->database()->select('user_id_to, user_fullname_to_suggest')
                ->from(Core::getT('follow'))
                ->where('domain_id = '.$iDomainId.' AND status = 0 AND user_id_from = '.$iUserId.' AND user_id_from IN ('.implode(',', $aTmps['user_id_from']).')')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aData['ufollow'][$aRow['user_id_to']] = $aRow['user_fullname_to_suggest'];
            }
            // lấy thêm thông tin số Like
            
            
            $aOutput['data'] = $aData;
        }

        if ($bIsReturn){
            $aOutput['status'] = 'error';
        }
        else {
            $aOutput['status'] = 'success';
        }
        return $aOutput;
    }
    
    public function actionLike($aParam = array())
    {
        /*
        input:
        - list: dữ liệu được jsonencodebao gồm id và status
        - act: action thực thi: save, del, get, save
        - n: Số lượng bài cần lấy
        - type: Loại đối tượng (bài viết, đề tài, comment)
        */
        
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $iDomainId = Core::getDomainId();
        $iUserId = Core::getUserId();
        
        $aList = array();
        /// data gửi lên là jsonencode, bao gồm id và status
        $aTmps = $aParam["list"];
        if (!empty($aTmps)) {
            $aTmps = json_decode($aTmps, 1);
            // quét từng dòng, mỗi dòng sẽ gồm id và status
            foreach ($aTmps as $aVal) {
                foreach ($aVal as $iKey => $Val) {
                    $Val *= 1;
                    $aVal[$i] = $Val;
                }
                if ($aVal['id'] > 0) {
                    $aList['id'][] = $aVal['id'];
                    $aList['status'][] = $aVal['status'];
                }
            }
        }
        /*  giả lập */
        if (empty($aList['id'])) {
            $aList['id'][] = 111706;
        }
        
        /* */
        $iNumOfGetArticle = $aParam['n'];
        $iNumOfGetArticle *= 1;
        if ($iNumOfGetArticle < -1 || $iNumOfGetArticle == 0 || $iNumOfGetArticle > 100)
            $iNumOfGetArticle = 10;
        $aNews = array();
        // quy định là bài viết (top), đề tài (cat), trạng thái (status)
        $sType = $aParam["type"];
        if ($sType == 'cat')
            $sTypeTable = 'category';
        elseif ($sType == 'status')
            $sTypeTable = 'status';
        elseif ($sType == 'comment')
            $sTypeTable = 'comment';
        elseif ($sType == 'fav')
            $sTypeTable = 'article';
        else {
            $sType = 'artc';
            $sTypeTable = 'article';
        }
        $sTypeName = $sTypeTable;
        if ($sType == 'fav')
            $sTypeName = 'favorite';  
        $sAct = $aParam["act"];
        
        $sPageType = $oSession->get('session-page_type');
        
        if ((empty($aList) && empty($iNumOfGetArticle)) || empty($sAct) || empty($sType)) {
            Core_Error::set('error', 'Deny-no value1');
            $bIsReturn = true;
        }
        elseif ($sPageType != 'shopping' && $sPageType != 'marketplace') {
            Core_Error::set('error', 'Deny-no type');
            $bIsReturn = true;
        }
        
        $iLikeTime = $oSession->get('session-like_time');
        if (!$bIsReturn && $sAct != 'del') {
            if(CORE_TIME - 1 < $iLikeTime) {
                Core_Error::set('error', 'Deny-time('.CORE_TIME.' - '.$iLikeTime);
                $bIsReturn = true;
            }
        }
        if (!$bIsReturn) {
            $oSession->set('session-like_time', CORE_TIME);
            if ($sAct == 'save') {
                // check if exists
                $aRows = $this->database()->select('id')
                    ->from(Core::getT($sTypeTable))
                    ->where('domain_id ='.$iDomainId.' AND id IN ('.implode(',', $aList['id']).')')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aListExists[] = $aRow['id'];
                }
                foreach ($aList['id'] as $Key => $iVal) {
                    $iStatus = $aList['status'][$Key];
                    if ($iVal < 0 || !in_array($iVal, $aListExists))
                        continue;
                    $sCond = 'domain_id ='.$iDomainId.' AND user_id = '.$iUserId.' AND status != 2 AND type_name = "'.addslashes($sTypeName).'" AND type_id = "'.addslashes($iVal).'"';
                    if ($iStatus == 1) {
                        $this->database()->update(Core::getT('like'), array('status' => 2), $sCond);
                        $iTmp = $oSession->getArray('session-user', 'total_like');
                        $iTmp--;
                        $oSession->set('session-user', 'total_like', $iTmp);
                    }
                    else {
                        // check xem đã tồn tại chưa
                        $iCnt = $this->database()->select('count(*)')
                            ->from(Core::getT('like'))
                            ->where($sCond)
                            ->execute('getField');
                        if ($iCnt > 0)
                            continue;
                        $aInsert = array(
                            'domain_id' => $iDomainId,
                            'user_id' => $iUserId,
                            'type_name' => addslashes($sTypeName),
                            'type_id' => addslashes($iVal),
                            'time' => CORE_TIME,
                        );
                        $iId = $this->database()->insert(Core::getT('like'), $aInsert);
                        if (!$iId) {
                            Core_Error::set('error', 'System error1');
                            return array();
                        }
                        $iTmp = $oSession->getArray('session-user', 'total_like');
                        $iTmp++;
                        $oSession->set('session-user', 'total_like', $iTmp);
                        $sContent = 'Vừa thích ';
                        if ($sType == 'cat') {
                            $sTypeTmp = 'category';
                        }
                        elseif ($sType == 'artc') {
                            $sTypeTmp = 'article';
                        }
                        elseif ($sType == 'comment') {
                            $sTypeTmp = 'comment';
                        }
                        elseif ($sType == 'status') {
                            $sTypeTmp = 'status';
                        }
                        elseif ($sType == 'fav') {
                            $sTypeTmp = 'fav';
                            $sContent = 'Vừa yêu thích ';
                        }
                        $sContent .= '['.$sTypeTmp.']'.$iVal.'[/'.$sTypeTmp.']';
                        // gọi hàm đăng status
                        Core::getService('core.tools')->openProcess(array(), array(
                            'sid' => session_id(),
                            'content' => $sContent,
                            'action' => 'add',
                            'process' => 'emotion',
                            'type_status' => 6,
                        ));
                    }
                }
                $aOutput = array(
                    'status' => 'success'
                );
            }
            elseif ($sAct == 'get') {
                if ($iNumOfGetArticle != 0) {
                    //Lấy tổng số
                    $iCnt = $this->database()->select('count(type_id)')
                        ->from(Core::getT('like'))
                        ->where('domain_id='.$iDomainId.' AND user_id = '.$iUserId.' AND status != 2 AND type_name = "'.addslashes($sTypeName).'"')
                        ->execute('getField');
                    $aOutput['total'] = $iCnt*1;
                    if ($aOutput['total'] > 0) {
                        $aTmps = array();
                        // lấy chi tiết sản phẩm
                        $aRows = $this->database()->select('type_id')
                            ->from(Core::getT($sTypeTable))
                            ->where('domain_id='.$iDomainId.' AND user_id = '.$iUserId.' AND status != 2 AND type_name = "'.addslashes($sTypeName).'"')
                            ->order('BINARY id DESC')
                            ->limit($iNumOfGetArticle)
                            ->execute('getRows');
                        foreach ($aRows as $aRow) {
                            $aArticleIdList[] = $aRow['type_id'];
                        }
                        if (( $sTypeName == 'article' || $sTypeName == 'favorite' ) && !empty($aArticleIdList)) {
                            $sQuery = implode(',', $aArticleIdList);
                            $aRows = $this->database()->select('id, title, description, category_id, detail_path, image_path, article_extend_id')
                                ->from(Core::getT('article'))
                                ->where('domain_id='.$iDomainId.' AND status = 1 AND id IN ('.$sQuery.')')
                                ->execute('getRows');
                            $iCount = 0;
                            foreach ($aRows as $aRow) {
                                $aNews['id'][$iCount] = $aRow["id"];
                                $aNews['name'][$iCount] = $aRow["title"];
                                $aNews['article_path'][$iCount] = $aRow["detail_path"];
                                $aNews['image_path'][$iCount] = $aRow["image_path"];
                                $aNews['path'][$iCount] = $aRow["detail_path"];
                                $aNews['description'][$iCount] = $aRow["description"];
                                $aNews['extends'][$iCount] = $aRow["article_extend_id"];
                                $iCount++;
                            }
                            // bổ sung thông tin cho bài viết shop
                            $aRows = $this->database()->select('*')
                                ->from(Core::getT('shop_article'))
                                ->where('article_id IN ('.$sQuery.')')
                                ->execute('getRows');
                            foreach ($aRows as $aRow) {
                                foreach ($aRow as $Key => $Val) {
                                    if (is_nan($Key))
                                        continue;
                                    if ($Key == 'price_sell'
                                        || $Key == 'price_discount'
                                        || $Key == 'weight'
                                        || $Key == 'buy_quantity'
                                        || $Key == 'total_quantity'
                                        || $Key == 'necessary_quantity')
                                        $Val *= 1;
                                    
                                    $aRow[$Key] = $Val;
                                }
                                foreach ($aNews['id'] as $iKey => $iIdTmp) {
                                    if ($iIdTmp != $aRow["article_id"])
                                        continue;
                                    foreach ($aRow as $Key => $Val) {
                                        if ($Key == 'id' || $Key == 'article_id')
                                            continue;
                                        $aNews[$Key][$iKey] = $Val;
                                    }
                                    $aNews['amount'][$iKey] = $aRow["price_sell"]*1 - $aRow["price_discount"]*1;
                                    $Key = 'deliver_method';
                                    if ($aNews[$Key][$iKey] == 1)
                                        $aNews[$Key][$iKey] = 'Giao phiếu';
                                    else
                                        $aNews[$Key][$iKey] = 'Giao sản phẩm';
                                }
                            }
                        }
                        $aOutput['product'] = $aNews;
                    }
                }
                else {
                    $aRows = $this->database()->select('type_id')
                        ->from(Core::getT('like'))
                        ->where('domain_id='.$iDomainId.' AND user_id = '.$iUserId.' AND status != 2 AND type_name = "'.addslashes($sTypeName).'" AND type_id IN ('.implode(',', $aList['id']).')')
                        ->execute('getRows');
                    foreach ($aRows as $aRow) {
                        $aOutput[] = $aRow['type_id'];
                    }
                }
            }
        }
        if ($bIsReturn) {
            return array();
        }
        return $aOutput;
    }
}
?>
