<?php
    //header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
    //header('Access-Control-Allow-Credentials: true');
    header('Content-type: application/json');
    ob_start();
    define('CORE', true);
    define('DS', DIRECTORY_SEPARATOR);
    define('DIR', dirname(dirname(__FILE__)) . DS);
    define('APPS', 1);
    require(DIR . 'includes' . DS . 'init.inc.php');

    $aRequest = Core::getLib('request')->getRequests();

    if (isset($aRequest['filepost'])) {
        $sFilePost = $_GET['filepost'];
        if(empty($sFilePost)) {
            Core_Error::log('Dữ liệu rỗng.', Core::getService('core.log')->getApiFile());
            return false;
        }
        $sParams = file_get_contents($sFilePost);
        $aParmas = unserialize($sParams);
    }
    else {
        $aParmas = $aRequest;
    }
    $aParmas['api'] = 1;
    $aParmas['return'] = 1;
    $aParmas['job_type'] = 0;
    
    $aReturn = Core::getService('core.apps')->call($aParmas);
    if (isset($aParmas['return']) && $aParmas['return'] == 1) {
        echo json_encode($aReturn);exit;
    }
?>
