<?php
//header('Access-Control-Allow-Origin: *');
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
$oAjax = Core::getLib('ajax');
$oAjax->process();
echo $oAjax->getData();
if ($oAjax->bIsModeration == true) {
    echo '$(window).trigger("moderation_ended");';
}
ob_end_flush();
?>
