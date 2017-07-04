<?php
    ob_start();
    define('CORE', true);
    define('DS', DIRECTORY_SEPARATOR);
    define('DIR', dirname(__FILE__) . DS);
    require(DIR . 'includes' . DS . 'init.inc.php');
    Core::run();
    /*
    $sContent = ob_get_clean();
    ob_end_clean();
    ob_end_flush();
    $sContent = str_replace(
        array("\t", '// ]]>'),
        '',
        $sContent
    );
    // remove Simgle line comment
    $sContent = preg_replace("#\n\/\/(.*?)[\n|\r\n]#", "\n", $sContent);

    // remove comment JS
    $sContent = preg_replace('#\/\*(.*?)\*\/#is', '', $sContent);

    $sContent = preg_replace('/\>[^\S ]+/s', '>', $sContent);
    $sContent = preg_replace('/[^\S ]+\</s', '<', $sContent);
    //$sContent = preg_replace('/(\s)+/s', '\\1', $sContent);

    $sContent = Core::getService('core.tools')->removeNewLine($sContent);
    $sContent = Core::getService('core.tools')->removeDuplicate($sContent, ' ');
    $sType = Core::getLib('module')->getModuleName();
    $aReturn = array(
        'type' => $sType,
        'id' => Core::getLib('request')->get('id'),
        'content' => $sContent,
    );
    if($sType == 'article')
    {
        $aReturn['pid'] = Core::getLib('request')->get('parent_id');
    }
    $sContent = serialize($aReturn);
    $sContent = gzencode($sContent, 9);
    header("Content-Encoding: gzip");
    echo $sContent;
*/