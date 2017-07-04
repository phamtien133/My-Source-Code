<?php
ob_start();
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(dirname(__FILE__))) . DS);

require(DIR . 'includes' . DS . 'init.inc.php');
set_time_limit(500);
$sTime = time();
$sTmp = 'http://cms.gomart.vn/tools/cron/app_transport.php';
$sTmp = 'wget "'.$sTmp.'" -O '.DIR.'/cache/system/'.md5($sTime);
exec($sTmp.' > /dev/null &');
sleep(1);
unlink(DIR.'cache/system/'.md5($sTime));
?>
