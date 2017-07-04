<?php
header('Content-Type: text/html; charset=utf-8');
ob_start();
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);

require(DIR . 'includes' . DS . 'init.inc.php');

// lấy tổng số 
$aData = Core::getService('wallet')->getStatisticsFee();

d('Thống kê phí dịch vụ theo ngày '. date('d/m/Y'). ' tính từ 00:00:00');
$sDebt = (empty($aData['balance'])) ? 'Không lấy được thông tin, vui lòng refresh lại trang.' : $aData['balance']['amount'] .' BTC';
d('Số dư còn lại trên hệ thống (ví 1): '. $sDebt);
d('Tổng số Zen PH: '. $aData['total_zen'] .' ZEN');
d('Tổng số Phí dịch vụ (Zen): '. $aData['total_fee'].' ZEN');

d('Tổng số BTC PH:'. $aData['total_btc'].' BTC');
d('Tổng số Phí dịch vụ (BTC): '. $aData['total_btcfee'].' BTC');
?>
