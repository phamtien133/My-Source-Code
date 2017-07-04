<?php
defined('CORE') or exit('NO DICE!');
ini_set('memory_limit', '64M');
ini_set('display_errors', 1);
set_time_limit(15);
error_reporting(E_ALL);

mb_internal_encoding("UTF-8");

header_remove("X-Powered-By");

if (!isset($_SERVER['HTTP_USER_AGENT'])){
    $_SERVER['HTTP_USER_AGENT'] = '';
}

define('CORE_MEM_START', memory_get_usage());
define('CORE_TIME_START', array_sum(explode(' ', microtime())));

// load define
if (file_exists(DIR. 'includes'. DS . 'setting' . DS . 'constant.sett.php')){
    require_once(DIR. 'includes'. DS . 'setting' . DS . 'constant.sett.php');
}
require(DIR . 'log.php');
// load core file
require(DIR_LIB . 'core.class.php');
require(DIR_LIB . 'error.class.php');
require(DIR_LIB . 'service.class.php');
require(DIR_LIB . 'component.class.php');

// define time to gmt.
if (function_exists('date_default_timezone_set')){
    date_default_timezone_set('GMT');
    define('CORE_TIME', time());
}else {
    define('CORE_TIME', strtotime(gmdate("M d Y H:i:s", time())));
}

Core::getLib('setting')->set();

// initial session
$sSid = '';
$aParams = Core::getLib('request')->getRequests();
if (isset($aParams['sid'])) {
    $sSid = $aParams['sid'];
    $sSid = htmlspecialchars(Core::getLib('input')->removeXSS($sSid));    
}
if(!empty($sSid) && preg_match('/^[a-z0-9-.A-Z]+$/', $sSid) != 0)
{
    session_id($sSid);
    //define('CORE_NO_SESSION', 0);
}
// set cookie. Time out for cookie is 15 minute. If in this time do not have any request from browser, cookie will be destroy. For the chat case, when a message have been send, cookie will be adjourn (temporary method)
$sHost = $_SERVER['HTTP_HOST'];
$iPos = strpos($sHost, Core::getParam('core.main_server'));
if ($iPos !== false) {
    $sTmp = substr($sHost, $iPos, strlen(Core::getParam('core.main_server')));
    $sHost = str_replace($sTmp, '', $sHost);
}
if ($_SERVER['SERVER_PORT'] != 80) {
    $sHost = str_replace(':'.$_SERVER['SERVER_PORT'], '', $sHost);
}
$_SERVER['HTTP_HOST'] = $sHost;
//d($sHost);die;
$secret ='!@#?>*&';
$pc2id = md5(md5(date('m')).hash('sha256', date('d')).$secret.$sHost);
if(!isset($_COOKIE['pc2id']) || $pc2id != $_COOKIE['pc2id'])
    Core::setCookie('pc2id', $pc2id, CORE_TIME + 15*60, '/', $_SERVER['HTTP_HOST']);
// initial session
if (defined('APPS') && APPS) {
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
    if (isset($aParmas['sid'])) {
        $sSid = $aParmas['sid'];
    }
    if (!empty($sSid) && preg_match('/^[a-z0-9-.A-Z]+$/', $sSid) != 0 && $sSid != 'undefined') {
        session_id($sSid);
    }
}

Core::getService('domain')->init();

//if (!defined('CORE_NO_SESSION')){
//    Core::getLib('session.handler')->init();
//}
//if (defined('CORE_IS_AJAX') && CORE_IS_AJAX) {
//    $aSession = Core::getLib('session')->get(Core::getParam('core.session_prefix'));
//    if(!isset($aSession['session-domain']) || !isset($aSession['session-domain']['id']) || $aSession['session-domain']['id'] < 1) {
//        Core::getService('domain')->loadSession();
//    }
//}
