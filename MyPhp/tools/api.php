<?php
    header('Content-type: application/json');

    ob_start();
    define('CORE', true);
    define('DS', DIRECTORY_SEPARATOR);
    define('DIR', dirname(dirname(__FILE__)) . DS);
    if (isset($_GET['ajax_page_display'])) {
        define('CORE_IS_AJAX_PAGE', true);
    }
    else {
        define('CORE_IS_AJAX', true);
    }
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
    
    $aReturn = Core::getService('api')->call($aParmas);
    if (isset($aParmas['return']) && $aParmas['return'] == 1) {
        echo json_encode($aReturn);exit;
    }
?>
