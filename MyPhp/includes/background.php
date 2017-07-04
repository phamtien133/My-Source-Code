<?php
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);
require(DIR . 'includes' . DS . 'init.inc.php');
echo 111; exit;
//$sFilePost = $_GET['filepost'];
//if(empty($sFilePost))
//{
//    echo 'error'; exit();
//}
//$sParams = file_get_contents($sFilePost);
//$aParams = unserialize($sParams);

//debug 
$_GET['debug'] = 1;
/* */
if (@$_GET['debug'] == 1) {
    //đang debug, lấy thông số như chuỗi được gán
    $iDebug = 1;
    $argv[] = '--filepostD:/server/he_thong/xu_ly/cache/data/vdg.vn/r-48054_1434252152.1883.sys';
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

d($_POST); die();

$sProcess = '';
if (isset($aParams['process'])) {
    $sProcess = $aParams['process'];
}

if (!empty($sProcess)) {
    if ($sProcess == 'create_edit_article') {
        Core::getService('article.background')->createEditArticle($aParams);
    }
}
d('ss'); die();
?>
