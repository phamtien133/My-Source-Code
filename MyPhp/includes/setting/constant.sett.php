<?php
define('CORE_DEBUG', 1);
define('DIR_FILE' , DIR . 'file' . DS);
define('DIR_INCLUDE' , DIR . 'includes' . DS);
define('DIR_CACHE' , DIR . 'cache' . DS);
define('DIR_MEMCACHE' , DIR . 'cache' . DS. 'memcache'. DS);
define('DIR_MODULE' , DIR . 'modules' . DS);
define('DIR_LIB' , DIR_INCLUDE . 'library' . DS);
define('DIR_THEME' , DIR . 'theme' . DS);
define('DIR_MODULE_TPL', DS . 'template');
define('CORE_TPL_SUFFIX', '.html.php');
define('SURCHARGE', 10000);
if (!defined('CORE_IS_AJAX')){
    define('CORE_IS_AJAX', false);
}
if (!defined('CORE_IS_AJAX_PAGE')){
    define('CORE_IS_AJAX_PAGE', false);
}

define('CORE_SAFE_MODE', ((@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? true : false));
define('CORE_OPEN_BASE_DIR', ((($sBd = @ini_get('open_basedir')) && $sBd != '/') ? true : false));
define('CORE_USE_DATE_TIME', class_exists('DateTime') && class_exists('DateTimeZone') && method_exists('DateTime','settimestamp'));