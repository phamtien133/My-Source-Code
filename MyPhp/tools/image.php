<?php
ob_start();
define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)) . DS);
require(DIR . 'includes' . DS . 'init.inc.php');
$aCaptchaNums = array('2','2','2','3','4','5','2','7','8','9');
$aCaptchaChars = array('A','B','C','D','E','F','H','H','H','J','K','H','M','N','H','P','Q','R','S','Y','U','V','W','X','Y','Z');
$oSession = Core::getLib('session');
$sCaptchaString = "";

$sId = Core::getLib('request')->get('id');
$sId = Core::getLib('input')->removeXSS($sId);
$sFontName = "arial.ttf";
$oImage = null;
if ($sId == 'register' || $sId == 'login' || $sId == 'forgot') {
    $sActiveCode = $oSession->getArray('session-'.$sId, 'active_code');
    if(empty($sActiveCode)) {
        $sActiveCode = Core::getService('core.tools')->getRandomCode(6);
        $oSession->setArray('session-'.$sId, 'active_code', $sActiveCode);
    }

    $oImage = imagecreatetruecolor(260, 60);
    $iWhite = imagecolorallocate ($oImage, 255, 255, 255);
    $iRandom = imagecolorallocate ($oImage, rand(190,192),  rand(190,192),  rand(64,192));
    imagefill ($oImage, 0, 0, $iWhite);
    $iMyX = 15;
    $iMyY = 30;
    $iAngle = 0;
    for ($iCnt = 0; $iCnt <=1000; $iCnt++) {
        $iMyX = rand(1,248);
        $iMyY = rand(1,58);
        imageline($oImage, $iMyX, $iMyY, $iMyX + rand(-5,5), $iMyY + rand(-5,5), $iRandom);
    }
    
    $aCaptchaNums = strtoupper($sActiveCode);
    for ($iCnt = 0; $iCnt <= strlen($aCaptchaNums); $iCnt++) {
        $iDark = imagecolorallocate ($oImage, rand(5,128), rand(5,128), rand(5,128));
        $sCapChar = $aCaptchaNums[$iCnt];
        $sCaptchaString .= $sCapChar;
        $iFs = rand (20, 26);
        $iMyX = 15 + ($iCnt * 28+ rand(-5,5));
        $iMyY = rand($iFs + 2,55);
        $iAngle = rand(-30, 30);
        imagettftext ($oImage, $iFs, $iAngle, $iMyX, $iMyY, $iDark, $sFontName, $sCapChar);
    }
}
else {
    $oImage = imagecreatetruecolor(157, 60);
    $iWhite = imagecolorallocate ($oImage, 255, 255, 255);
    $iRandom = imagecolorallocate ($oImage, rand(190,192),  rand(190,192),  rand(64,192));
    imagefill ($oImage, 0, 0, $iWhite);
    $iMyX = 15;
    $iMyY = 30;
    $iAngle = 0;
    for ($iCnt = 0; $iCnt <=1000; $iCnt++) {
        $iMyX = rand(1,148);
        $iMyY = rand(1,58);
        imageline($oImage, $iMyX, $iMyY, $iMyX + rand(-5,5), $iMyY + rand(-5,5), $iRandom);
    }

    $sActiveCode = $oSession->get('session-verify_code-'.$sId);
    if(!empty($sActiveCode)) {
        $aCaptchaNums = $sActiveCode;
        for ($iCnt = 0; $iCnt <= strlen($aCaptchaNums); $iCnt++) {
            $iDark = imagecolorallocate ($oImage, rand(5,128),rand(5,128),rand(5,128));
            $sCapChar = $aCaptchaNums[$iCnt];
            $sCaptchaString .= $sCapChar;
            $iFs = rand (20, 26);
            $iMyX = 15 + ($iCnt * 28+ rand(-5,5));
            $iMyY = rand($iFs + 2,55);
            $iAngle = rand(-30, 30);
            imagettftext ($oImage,$iFs, $iAngle, $iMyX, $iMyY, $iDark, $sFontName, $sCapChar);
        }
    }
    else {
        // number
        for ($iCnt = 0; $iCnt <= 1; $iCnt++) {
        $iDark = imagecolorallocate ($oImage, rand(5,128), rand(5,128), rand(5,128));
        $sCapChar = $aCaptchaNums[rand(0, count($aCaptchaNums)-1)];
        $sCaptchaString .= $sCapChar;
        $iFs = rand (20, 26);
        $iMyX = 15 + ($iCnt * 28+ rand(-5,5));
        $iMyY = rand($iFs + 2,55);
        $iAngle = rand(-30, 30);
        imagettftext ($oImage,$iFs, $iAngle, $iMyX, $iMyY, $iDark, $sFontName, $sCapChar);
        }
        // chars
        for ($iCnt = 2; $iCnt <= 4; $iCnt++) {
        $iDark = imagecolorallocate ($oImage, rand(5,128), rand(5,128), rand(5,128));
        $sCapChar = $aCaptchaChars[rand(0, count($aCaptchaNums)-1)];
        $sCaptchaString .= $sCapChar;
        $iFs = rand (20, 26);
        $iMyX = 15 + ($iCnt * 28+ rand(-5,5));
        $iMyY = rand($iFs + 2,55);
        $iAngle = rand(-30, 30);
        imagettftext ($oImage, $iFs, $iAngle, $iMyX, $iMyY, $iDark, $sFontName, $sCapChar);
        }
        $sCaptchaString = strtolower($sCaptchaString);

        if($sId != '') {
            $oSession->set('session-verify_code-'.$sId, $sCaptchaString);
        }
        else {
            $oSession->set('session-verify_code', $sCaptchaString);
        }
    }
}
header ("Content-type: image/jpeg");
imagejpeg($oImage, NULL, 85);
imagedestroy($oImage);exit;
?>
