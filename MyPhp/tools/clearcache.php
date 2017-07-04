<?php
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);
require(DIR . 'includes' . DS . 'init.inc.php');
$oCache = Core::getLib('cache');
$oCache->remove();
?>
