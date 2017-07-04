<?php 
ob_start();
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(dirname(__FILE__))) . DS);

require(DIR . 'includes' . DS . 'init.inc.php');
set_time_limit(500);

Core::getService('app.transport')->runCronJobApp();
?>