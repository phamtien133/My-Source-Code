<?php
class User_Component_Ajax_Ajax extends Ajax
{
    public function addUser()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $iResult = Core::getService('user.process')->addUser($aParam);
        $aReturn = array();
        if (Core_Error::isPassed()) {
            $aReturn = array(
                'status' => 'success',
                'data' => array(
                    'status' => $aResult
                )
            );
        }
        else {
            $aReturn = array(
                'status' => 'error',
                'data' => array(
                    'error' => Core_Error::get(),
                ),
            );
        }
        echo json_encode($aReturn);
    }

    public function deleteUser()
    {
        $aVals = $this->get('val');
        $iUserId = isset($aVals['id']) ? $aVals['id'] : 0 ;
        if (!$iUserId) {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu'
            );
            echo json_encode($aReturn);exit;
        }

        $aReturn = Core::getService('user.process')->deleteUser(array(
            'id' => $iUserId
        ));
        echo json_encode($aReturn);exit;
    }

    public function forgetPassword()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aResult = Core::getService('user.process')->forgotPassword($aParam);

        if (Core_Error::isPassed()) {
            $aReturn = array(
                'status' => 'success',
                'data' => array(
                    'status' => $aResult
                )
            );
        }
        else {
            $aReturn = array(
                'status' => 'error',
                'data' => Core_Error::get()
            );
        }
        echo json_encode($aReturn);
    }

    public function callLoginPopup()
    {
        Core::getBlock('user.login', array());
    }

    public function login()
    {
        $aVals = $this->get('val');

        if (!isset($aVals['email']) || empty($aVals['email'])) {
            if ((!isset($aVals['username']) || empty($aVals['username']))) {
                $aReturn = array(
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ.'
                );
                echo json_encode($aReturn);exit;
            }
        }
        if (!isset($aVals['passwd']) || empty($aVals['passwd'])) {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.'
            );
            echo json_encode($aReturn);exit;
        }
        $aReturn = Core::getService('user.auth')->login(array(
            'email' => isset($aVals['email']) ? $aVals['email'] : '',
            'username' => isset($aVals['username']) ? $aVals['username'] : '',
            'passwd' => $aVals['passwd'],
        ));

        echo json_encode($aReturn); exit;
    }

    public function verify()
    {

    }

    public function getUserStatus()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.auth')->handleStatus($aParam);
        echo json_encode($aReturn);exit;
    }

    public function statisticsAccess()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.auth')->statisticsAccess($aParam);
        echo json_encode($aReturn);exit;
    }

    /**
    * Lấy thông tin bài viết theo từng tiêu chí trong công đồng
    *
    */
    public function getCommunity()
    {
        $aParam = Core::getLib('request')->getArray('val');
        /*
        Giả lập dữ liệu input:
            order: time (bài viết mới nhất), like (bài viết được tư vấn tốt nhất), comment (được bình luận nhiều nhất)
            Chưa có tiêu chí cho bài viết được vote nhiếu nhất
        */
        $aParam = array (
            'id' => 1, //category id
            'order' => 'time', //Tiêu chí lấy bài, có thể bị thay đổi khi thay đổi các tiêu chí lấy dữ liệu ra
            'page' => 1,
            'page_size' => 10,
        );


        $aResult = array();
        if (!empty($aParam)) {
            $aParam['category_id'] = $aParam['id'];
            $aResult = Core::getService('user.community')->getArticles($aParam);
        }
        $aReturn = array();
        if (empty($aResult)) {
            $aReturn = array(
                'status' => 'error',
            );
        }
        else {
            $aReturn = array(
                'status' => 'success',
                'data' => $aResult
            );
        }
        echo json_encode($aReturn);
    }

    public function checkExistEmail()
    {
        $aReturn = array();
        $aVals = $this->get('val');
        if (empty($aVals) || empty($aVals['email'])) {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.'
            );
        }
        else {
            $bIsReturn = Core::getService('user.auth')->checkEmailExist($aVals);
            if ($bIsReturn) {
                $aReturn['status'] = 'success';
            }
            else {
                $aReturn['status'] = 'error';
                $aReturn['message'] = 'Hộp thư đã được sử dụng.';
            }
        }
        echo json_encode($aReturn); exit;
    }

    public function checkLoginStatus()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user')->checkLoginStatus($aParam);
        echo json_encode($aReturn);exit;
    }

    public function logout()
    {
        $aReturn = Core::getService('user.auth')->logout();
        if($aReturn['status'] == 'success') {
            echo 'Thao tác thành công.';
            $this->call('<script type="text/javascript">logoutCallback();</script>');
        }
        else
            echo $aReturn['message'];

        return false;
    }

    /**
    * Function ajax gọi khi tạo sửa bài viết
    *
    */
    public function createEditArticle()
    {
        $aResult = array();
        $aRequest = Core::getLib('request')->getRequests();
        /*
        Dữ liệu giả lập được gọi lên:
            Các service được sử dụng:
                + ('article.process')->createEditArticle(): Tạo sửa bài viết
                + ('article.process')->createEditArticle(): Chạy ngầm của tạo sửa bài viết
            File background.php trong xuly/includes/background.php: Chạy ngầm sẽ gọi qua đây và được thực thi tùy vào
            giá trị process được gửi theo
        */
        $aVals = array(
            'id' => 0,
            'type' => 'create_edit_article',
            'code_article' => '1234',
            'category_id' => 136,
            'name' => 'tên bài viết',
            'content' => 'Nội dung bài viết',
            'image_path' => '//img.vdg.vn/avacat/0.png',
            'path' => 'ten-bai-viet',
            'allow_comment' => 1,
            'status' => 1,
            'user_id' => 1,
            'image_extend' => array(),
            'filter_price' => array(),
            'link' => array(),
            'arr' => 'category_id,name,content,image_path,path,allow_comment,status'
        );
        if (!empty($aVals)) {
            $aVals['type_status'] = 4;
            $aReturn = Core::getService('article.process')->createEditArticle(array('val' => $aVals));
            if (Core_Error::isPassed()) {
                $aResult['status'] = 'success';
            }
            else{
                $aResult['status'] = 'error';
                $aResult['message'] = Core_Error::get();
            }
        }
        else {
            $aResult['status'] = 'error';
            $aResult['message'][]  = 'Empty input data!';
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Thực thi thao tác với comment
    *
    */
    public function actionComment()
    {
        $aResult = array();
        $aRequest = Core::getLib('request')->getRequests();
        /* Giả lập dữ liệu input gửi lên


        $aVals = array(
            'id' => 7573, //id đối tượng được comment
            'content' => 'Đây là đoạn cmt 123!', //Nội dung comment
            'action' => 'add', //hành được thực thi: add, edit, del
            'type_act' => 'atc',//Là đối tượng thực thì hành động vd: cmt cho bài viết => type_act = atc, cmt cho cmt type_act = cmt,  chỉnh sửa cmt của bài viết type_act = 'atc', chỉnh sủa cmt của cmt type_act = cmt
        );
        */
        //comment của comment
        $aVals = array(
            'id' => 7573, //id đối tượng chính được comment (không phải id cmt trong trường hợp cmt của cmt)
            'content' => 'Đây cũng là đoạn cmt của cmt 1!', //Nội dung comment
            'action' => 'add', //hành được thực thi: add, edit, del
            'type_act' => 'atc',//Là đối tượng thực thì hành động vd: cmt cho bài viết => type_act = atc, cmt cho cmt type_act = cmt,  chỉnh sửa cmt của bài viết type_act = 'atc', chỉnh sủa cmt của cmt type_act = cmt
            'pid' => 1, //parent id của cmt (là id của cmt 1)
        );

        if (!empty($aVals)) {
            $aResult = Core::getService('article.comment')->actionComment(array('val' => $aVals));
        }
        else {
            $aResult['status'] = 'error';
            $aResult['message'][]  = 'Empty input data!';
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Lấy thông tin cá nhân của user
    *
    */
    public function getUserInfo()
    {
        $aResult = array();
        $aParam = Core::getLib('request')->getArray('val');
        if (empty($aParam)) {
            $aParam = array();
        }
        $aReturn = Core::getService('user')->getFullUserInfo($aParam);
        if (empty($aReturn)) {
            $aResult['status'] = 'error';
            $aResult['message'] = Core_Error::get();
        }
        else {
            $aResult['status'] = 'success';
            $aResult['data'] = $aReturn;
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Lấy thông tin lịch sử mua hàng
    *
    */
    public function getPurchaseHistory()
    {
        //Lấy thông tin các đơn hàng đã mua
        $aResult = array();
        $aParam = Core::getLib('request')->getArray('val');
        if (empty($aParam)) {
            $aParam = array();
        }
        $aReturn = Core::getService('user')->getPurchaseHistory($aParam);
        if (Core_Error::isPassed()) {
            $aResult['status'] = 'success';
            $aResult['data'] = $aReturn;
        }
        else {
            $aResult['status'] = 'error';
            $aResult['message'] = Core_Error::get();
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Lấy thông tin các hoạt động gần đây của những thành viên đã được follow
    *
    */
    public function getActivityFriend()
    {
        $aResult = array();
        $aParam = Core::getLib('request')->getArray('val');
        if (empty($aParam)) {
            $aParam = array();
        }
        $aReturn = Core::getService('user')->getActivityFriends($aParam);
        if (empty($aReturn)) {
            $aResult['status'] = 'error';
            $aResult['message'] = Core_Error::get();
        }
        else {
            $aResult['status'] = 'success';
            $aResult['data'] = $aReturn;
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Hành động liên quan đến Follow: add, del
    *
    */
    public function actionFollow()
    {
        $aResult = array();
        $aRequest = Core::getLib('request')->getRequests();
        /* Giả lập dữ liệu input gửi lên action add*
        $aParam = array();
        $aParam['action'] = 'add';
        $aParam['uid'] = 2488;
        $aParam['uname'] = 'my friend';
        /* *
        /* Giả lập dữ liệu input gửi lên action delete *
        $aParam = array();
        $aParam['action'] = 'delete';
        $aParam['uid'] = 2488; //Id của follow
        /* */
        if (!empty($aRequest)) {
            $aResult = Core::getService('user.process')->actionFollow($aRequest);
        }
        else {
            $aResult['status'] = 'error';
            $aResult['message']  = 'Empty input data!';
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Lấy thông tin sản phẩm đã mua
    *
    */
    public function getPurchaseProduct()
    {
        $aResult = array();
        $aParam = Core::getLib('request')->getArray('val');

        if (empty($aParam)) {
            $aParam = array();
        }
        $aReturn = Core::getService('user')->getPurchaseProductHistory($aParam);
        if (Core_Error::isPassed()) {
            $aResult['status'] = 'success';
            $aResult['data'] = $aReturn;
        }
        else {
            $aResult['status'] = 'error';
            $aResult['message'] = Core_Error::get();
        }
        echo json_encode($aResult);exit;
    }

    /**
    * Lấy thông tin sản phẩm đã mua
    *
    */
    public function getLikeProductHistory()
    {
        $aResult = array();
        $aParam = Core::getLib('request')->getArray('val');

        if (empty($aParam)) {
            $aParam = array();
        }
        $aReturn = Core::getService('user')->getPurchaseProductHistory($aParam);
        if (Core_Error::isPassed()) {
            $aResult['status'] = 'success';
            $aResult['data'] = $aReturn;
        }
        else {
            $aResult['status'] = 'error';
            $aResult['message'] = Core_Error::get();
        }
        echo json_encode($aResult);exit;
    }

    public function checkCaptchaCode()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aResult = Core::getService('user.auth')->verify($aParam);
        echo json_encode($aResult);exit;
    }

    public function getProfile()
    {
        if (!Core::isUser()) {
            $this->call('callAjaxLogin("'. base64_encode('trang_ca_nhan.html').'");');
            return false;
        }
        //get information
        Core::getBlock('user.info.personal', array());

        $this->call('showProfileBlock("'. $this->getContent().'");');
        //ob_end_clean();
//        ob_end_flush();
//        ob_start();

        return true;

    }

    public function getInfoPersonal()
    {
        if (!Core::isUser()) {

            //Xử lý trường hợp chưa đăng nhập
           // return true;
        }

        //get imformation product
        Core::getBlock('user.info.product', array());

        $this->call('showProductBlock("'. $this->getContent().'");');
        ob_end_clean();
        ob_end_flush();
        ob_start();

        //get information
        Core::getBlock('user.order', array());

        $this->call('showListOrderBlock("'. $this->getContent().'");');
        ob_end_clean();
        ob_end_flush();
        ob_start();
        //get information user
        Core::getBlock('user.info.profile', array());

        $this->call('showInfoPersonalBlock("'. $this->getContent().'");');

        return true;
    }

    public function updateStatusUser()
    {
        $aVals = Core::getLib('request')->getArray('val');
        Core::getService('user.process')->updateStatusUser($aVals);
        exit;
    }

    public function deleteUserPermission()
    {
        $aVals = Core::getLib('request')->getArray('val');
        Core::getService('user.permission')->deleteUserPermission($aVals);
        exit;
    }

    public function setPermission()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aVals['api'] = true;
        /* Giả lập *
        $aVals['vendor_id'] = -1;
        /* */
        $aReturn = Core::getService('user.permission')->updatePermission($aVals);
        echo json_encode($aReturn); exit;
    }

    public function updateVerifyStatus()
    {
        $iUserId = $this->get('uid');
        $iStatus = $this->get('status');
        $iOrderId = $this->get('oid');
        $aReturn = Core::getService('user.process')->verifyUser(array(
            'uid' => $iUserId,
            'status' => $iStatus,
            'oid' => $iOrderId
        ));
        echo json_encode($aReturn); exit;
    }

    public function loginApi()
    {
        $aParam = $this->get('val');
        d($aParam);die;
        $aReturn = Core::getService('user.auth')->loginWithAPpi($aParam);

        echo json_encode($aReturn); exit;
    }

    public function getBlockUser()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aParam['uid'] = 0;
        if (isset($aParam['id'])) {
            $aParam['uid'] = $aParam['id'];
            unset($aParam['id']);
        }
        Core::getBlock('user.add', $aParam);
    }

    public function getBlockRecharge()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aParam['uid'] = 0;
        if (isset($aParam['id'])) {
            $aParam['uid'] = $aParam['id'];
            unset($aParam['id']);
        }
        Core::getBlock('user.recharge', $aParam);
    }

    public function recharge()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.account')->recharge($aParam);

        echo json_encode($aReturn); exit;
    }

    public function approvalRecharge()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.account')->approvalRecharge($aParam);
        echo json_encode($aReturn); exit;
    }

    public function searchUser()
    {
        $sKeyWord = $this->get('key');
        if (!empty($sKeyWord)) {
            $aUser = Core::getService('user')->searchUser(array(
                'key' => $sKeyWord,
            ));

            $aResult['data'] = $aUser;
        }
        else {
            $aResult['data'] = array();
        }
        $aResult['status'] = 'success';
        echo json_encode($aResult);
    }

    public function deleteGroup()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.group')->delete($aParam);
        echo json_encode($aReturn); exit;
    }

    public function updateFieldStatus()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.field')->updateStatus($aParam);
        echo json_encode($aReturn); exit;
    }

    public function setPermissionGroup()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.permission')->settingPermissionGroup($aParam);
        echo json_encode($aReturn); exit;
    }

    public function setPermissionModify()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.permission')->settingPermissionModify($aParam);
        echo json_encode($aReturn); exit;
    }

    public function setHighLightMember()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.group')->setHighLight($aParam);
        echo json_encode($aReturn); exit;
    }

    public function removeMemberGroup()
    {
        $aParam = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('user.group')->removeMember($aParam);
        echo json_encode($aReturn); exit;
    }
}
?>
