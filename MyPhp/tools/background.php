<?php
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);


//debug 
$_GET['debug'] = 0;
/* */
if (@$_GET['debug'] == 1) {
    //đang debug, lấy thông số như chuỗi được gán
    $iDebug = 1;
    $argv[] = '--filepostD:/server/he_thong/xu_ly/cache/data/vdg.vn/1329_1435183330.7658.sys';
}
error_reporting(3);
if (!empty($argv)) {
    //Tái tạo lại $_POST và $_GET để giả lập thực hiện
    $_GET = $_POST = array(); //Reset
    foreach ($argv as $Key => $Value) {
        if (substr($Value,0,5) == '--get') {
            $sIn = substr($Value, 5);
            $aTmps = unserialize(base64_decode($sIn));
            $_GET = $aTmps;
        }
        elseif (substr($Value,0,6) == '--post') {
            $sIn = substr($Value, 6);
            if (substr($sIn, -1, 1) == '"')
                $sIn = substr($sIn, 0, -1);
            $aTmps = unserialize(base64_decode($sIn));
            $_POST = $aTmps;
        }
        elseif (substr($Value,0,10) == '--filepost') {
            $sIn = substr($Value, 10);
            if (substr($sIn, -1, 1) == '"')
                $sIn = substr($sIn, 0, -1);
            // mơ tập tin tạm
            $aTmps = unserialize(file_get_contents($sIn));
            $_POST = $aTmps;    
            //if($iDebug == 0) unlink($sIn);
        }
    }
}
// end debug
$sFilePost = $_GET['filepost'];
if (empty($sFilePost)) {
    echo 'error'; exit();
}
$sParam = file_get_contents($sFilePost);
$aParam = unserialize($sParam);

$sSid = '';
if(isset($aParam['session']['id'])) 
    $sSid = $aParam['session']['id'];

if (empty($sSid) && isset($aParam['sid'])) {
    $sSid = $aParam['sid'];
}
if (!empty($sSid) && preg_match('/^[a-z0-9-.A-Z]+$/', $sSid) != 0) {
    session_id($sSid);
}


if (isset($aParam['session']['data'])) {
    define('CORE_NO_SESSION', true);
    $_SESSION = $aParam['session']['data'];
    unset($aParam['session']);
}
else {
    // tạm thời bỏ để cho phép chạy cả trường hợp mới và cũ.
    //die ('access deny!');
}

require(DIR . 'includes' . DS . 'init.inc.php');

$sProcess = '';
if (isset($aParam['process'])) {
    $sProcess = $aParam['process'];
}
if (!empty($sProcess)) {
    //d($aParam);die;
    if ($sProcess == 'create_edit_article') {
        Core::getService('article.background')->createEditArticle($aParam);
    }
    elseif ($sProcess == 'emotion') {
        Core::getService('user.activity')->createEditEmotion($aParam);
    }
    elseif ($sProcess == 'create_edit_category') {
        Core::getService('category.process')->createEditCategory($aParam);
    }
    elseif ($sProcess == 'send_mail') {
        Core::getService('core')->sendEmail($aParam);
    }
    elseif ($sProcess == 'send_sms') {
        Core::getService('marketing.sms')->sendProcess($aParam);
    }
    elseif ($sProcess == 'send_email_marketing') {
        Core::getService('marketing.email')->sendProcess($aParam);
    }
    elseif ($sProcess == 'apply_marketing') {
        Core::getService('marketing')->applyCampaign($aParam);
    }
    elseif ($sProcess == 'move_category') {
        Core::getService('category.background')->updateDisplayMoveCategory($aParam);
    }
    elseif ($sProcess == 'push_notification') {
        Core::getService('app.notification.process')->pushNotification($aParam);
    }
    elseif ($sProcess == 'canceled_order_transport') {
        Core::getService('app.admin.order')->processCancelOrder($aParam);
    }
    elseif ($sProcess == 'return_order_transport') {
        Core::getService('app.admin.order')->processReturnOrder($aParam);
    }
    elseif ($sProcess == 'push_order_route') {
        Core::getService('app.route.process')->pushOrder($aParam);
    }
}
d('ss'); die();
?>
