<?php
ob_start();
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);

require(DIR . 'includes' . DS . 'init.inc.php');
set_time_limit(300);
$aRequest = Core::getLib('request')->getRequests();
$bIsGetAll = (isset($aRequest['get_all'])) ?  $aRequest['get_all'] : 0;
$bInvisible = (isset($aRequest['invi'])) ?  $aRequest['invi'] : 0;
Core::getService('core.minify')->minify(array(
    'get_all' => $bIsGetAll,
    'invisible' => $bInvisible
));
?>
