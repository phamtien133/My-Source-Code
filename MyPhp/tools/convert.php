<?php
ob_start();
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);

require(DIR . 'includes' . DS . 'init.inc.php');
set_time_limit(500);

Core::getService('package')->getChartData();
die;

Core::getService('package')->getStatistics();
die;
Core::getService('core.convert')->addVendorToOnSale();
die;
Core::getService('core.tools')->updateSitemap(array(
    'domain' => array(
        'stt' => 1,
        'ten' => 'vdg.vn'
    )
));
?>
