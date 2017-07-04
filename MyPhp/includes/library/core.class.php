<?php
class Core
{
    const VERSION = '1.0';
    const CODE_NAME = 'core';

    private static $_aParams = array();

    private static $_aLibs = array();

    private static $_bIsAdminCp = true;

    /**
     * ARRAY of objects initiated. Used to keep a static history
     * so we don't call the same class more then once.
     *
     * @var unknown_type
     */
    private static $_aObject = array();

    public static function getT($sTable)
    {
        return Core::getParam(array('db', 'prefix')) . $sTable;
    }

    public static function getCodeName()
    {
        return self::CODE_NAME;
    }

    public static function getParam($mVar, $sDef = '')
    {
        return Core::getLib('setting')->getParam($mVar);
    }

    public static function getVersion()
    {
        return self::VERSION;
    }

    public static function getTimeZone($bDst = true)
    {
        return Core::getLib('date')->getTimeZone($bDst);
    }

    public static function addMessage($sMsg)
    {
        Core::getLib('session')->set('message', $sMsg);
    }

    public static function getMessage()
    {
        $sMessage = Core::getLib('session')->get('message');
        Core::clearMessage();
        return $sMessage;
    }

    public static function clearMessage()
    {
        Core::getLib('session')->remove('message');
    }

    public static function &getObject($sClass, $aParams = array())
    {
        $sHash = md5($sClass . serialize($aParams));

        if (isset(self::$_aObject[$sHash])){
            return self::$_aObject[$sHash];
        }

        $sClass = str_replace(array('.', '-'), '_', $sClass);

        if (!class_exists($sClass)){
            echo 'Unable to call class '. $sClass;
            return false;
        }

        if ($aParams)
        {
            self::$_aObject[$sHash] = new $sClass($aParams);
        }
        else
        {
            self::$_aObject[$sHash] = new $sClass();
        }

        if (method_exists(self::$_aObject[$sHash], 'getInstance'))
        {
            return self::$_aObject[$sHash]->getInstance();
        }

        return self::$_aObject[$sHash];
    }

    public static function getLibClass($sClass)
    {
        if (isset(self::$_aLibs[$sClass])){
            return true;
        }

        self::$_aLibs[$sClass] = md5($sClass);

        $sClass = str_replace('.', DS, $sClass);
        $sFile = DIR_LIB . $sClass . '.class.php';

        if (file_exists($sFile))
        {
            require($sFile);
            return true;
        }

        $aParts = explode(DS, $sClass);
        if(count($aParts) == 1 && !file_exists($sFile))
        {
            $sSubClassFile = DIR_LIB . $sClass . DS . $sClass . '.class.php';
            if (file_exists($sSubClassFile))
            {
                require($sSubClassFile);
                return true;
            }
        }
        if (isset($aParts[1]))
        {
            $sSubClassFile = DIR_LIB . $sClass . DS . $aParts[1] . '.class.php';
            if (file_exists($sSubClassFile))
            {
                require($sSubClassFile);
                return true;
            }
        }

        return false;
    }

    public static function &getLib($sClass, $aParams = array())
    {
        $sHash = md5($sClass . serialize($aParams));

        if (isset(self::$_aObject[$sHash])){
            return self::$_aObject[$sHash];
        }

        Core::getLibClass($sClass);
        self::$_aObject[$sHash] = Core::getObject($sClass, $aParams);

        return self::$_aObject[$sHash];
    }

    public static function getService($sClass, $aParams = array())
    {
        return Core::getLib('module')->getService($sClass, $aParams);
    }

    public static function getComponent($sClass, $aParams = array(), $sType = 'block', $bTemplateParams = false)
    {
        return Core::getLib('module')->getComponent($sClass, $aParams, $sType, $bTemplateParams);
    }

    public static function getBlock($sClass, $aParams = array(), $bTemplateParams = false)
    {
        return Core::getLib('module')->getComponent($sClass, $aParams, 'block', $bTemplateParams);
    }

    public static function isAdminPanel()
    {
        return (self::$_bIsAdminCp ? true : false);
    }

    /**
     * get language give by phrase.
     * @param string $sParam
     * @param array $aParams
     * @param bool $bNoDebug
     * @param string $sDefault
     * @param string $sLang
     * @return string
     */
    public static function getPhrase($sParam)
    {
        return Core::getLib('language')->getPhrase($sParam);
    }


    public static function getVariable($sVar)
    {
        return Core::getLib('template')->getVariable($sVar);
    }

    public static function isUser($bRedirect = false)
    {
        $iId = Core::getLib('session')->getArray('session-user', 'id');
        $bIsUser = false;
        if($iId > 0)
            $bIsUser = true;
        if ($bRedirect && !$bIsUser) {
            if (CORE_IS_AJAX || CORE_IS_AJAX_PAGE) {
                //return Core::getLib('ajax')->isUser();
            }
            else {
                // Create a session so we know where we plan to redirect the user after they login
                Core::getLib('session')->set('redirect', Core::getLib('url')->getFullUrl(true));

                if(Core::isMobile())
                    Core::getLib('url')->send('mobile.user.login');
                else
                    Core::getLib('url')->send('user.login');
            }
        }

        return $bIsUser;
    }

    public static function isMobile($bRedirect = true)
    {
        return Core::getLib('request')->isMobile($bRedirect);
    }

    public static function getTokenName()
    {
        return 'core';
    }

    public static function getCookie($sName)
    {
        $sName = Core::getParam('core.session_prefix') . $sName;

        return (isset($_COOKIE[$sName]) ? $_COOKIE[$sName] : '');
    }

    public static function getUserId()
    {
        //static $bChecked = false;
        return Core::getLib('session')->getArray('session-user', 'id');
    }

    public static function getUserName()
    {
        //static $bChecked = false;
        return Core::getLib('session')->getArray('session-user', 'name');
    }

    public static function isModule($sModule)
    {
        return Core::getLib('module')->isModule($sModule);
    }

    public static function isParam($sParam)
    {
        return Core::getLib('setting')->isParam($sParam);
    }

    public static function getDomainId()
    {
        return Core::getService('domain')->getDomainId();
    }

    public static function getDomainName()
    {
        return Core::getService('domain')->getDomainName();
    }

    public static function getPermission($sPermission)
    {
        return Core::getLib('session')->getArray('session-permission', $sPermission);
    }

    public static function run()
    {
        $oModule = Core::getLib('module');
        $oTpl = Core::getLib('template');
        // get domain
        $oModule->setController();
        $aSetting = Core::getParam('core.setting');
        if (!is_array($aSetting))
            $aSetting = array();
        $oTpl->assign(array(
            'iId' => Core::getLib('request')->get('id'),
            //'aSession' => $_SESSION[Core::getParam('core.session_prefix')],
            'aSettings' => $aSetting,
            'sType' => Core::getLib('request')->get('type')
        ));
        // set header
        if ($oModule->getModuleName() == 'print') {

        }
        else {
            $oTpl->setHeader(array(
                'reset.css' => 'site_css',
                'font.css' => 'site_css',
                'angular-material.css' => 'site_css',
                'font-awesome.min.css' => 'site_css',
                'jquery.jscrollpane.css' => 'site_css',
                'bootstrap.min.css' => 'site_css',
                'jquery.mCustomScrollbar.min.css' => 'site_css',
                'angular-chart.css' => 'site_css',
                'jquery.datetimepicker.css' => 'site_css',
                'main.css' => 'site_css',
                'general.css' => 'site_css',
                'donhang.css' => 'site_css',
                'snap.css' => 'site_css',
                'khachhang.css' => 'site_css',
                'baiviet.css' => 'site_css',
                'detai.css' => 'site_css',
                'menu.css' => 'site_css',
                'slide.css' => 'site_css',
                'trichloc.css' => 'site_css',
                'phanquyen.css' => 'site_css',
                'quangcao.css' => 'site_css',
                'chuyentrang.css' => 'site_css',
                'crm.css' => 'site_css',
                '<link rel="shortcut icon" type="image/x-icon" href="' . $aSetting['avatar']['favicon'].'" />',
                //'jquery.min.js' => 'site_script',
                'hammer.min.js' => 'site_script',
                'angular.min.js' => 'site_script',
                'blue-skin/js/simpla.jquery.configuration.js' => 'site_script',
                'ui/jquery.ui.widget.js' => 'global_script',
                'ui/jquery.ui.tabs.js' => 'global_script',
                'angular-aria.min.js' => 'site_script',
                'angular-animate.min.js' => 'site_script',
                'angular-material.js' => 'site_script',
                'messages.js' => 'site_script',
                'angular-route.min.js' => 'site_script',
                'angular-touch.min.js' => 'site_script',
                'jquery.ellipsis.min.js' => 'site_script',
                'jquery.textarea_autosize.js' => 'site_script',
                'jquery.jscrollpane.js' => 'site_script',
                'ui-bootstrap-tpls-0.11.0.js' => 'site_script',
                'jquery.mCustomScrollbar.js' => 'site_script',
                'chart.js' => 'site_script',
                'angular-chart.min.js' => 'site_script',
                /*'compile.js' => 'site_script',*/
                'jquery.datetimepicker.js' => 'site_script',
                'snap.min.js' => 'site_script',
                'tools.js' => 'site_script',
                'main.js' => 'site_script',
                'js.js' => 'site_script',
                'don-hang.js' => 'site_script',
                'support.js' => 'site_script',
                'select2/select2.js' => 'global_script',
            ));
        }

        // get crm user
        if (Core::isUser() && Core::getParam('core.main_server') == 'cms.') {
            $aCrm = Core::getService('core.crm')->getCurrentUser();
            if (isset($aCrm['user_id'])) {
                $oTpl->setHeader(array(
                    '<script type="text/javascript">crmuser = '. json_encode($aCrm).';</script>'
                ));
            }
            $oTpl->setHeader(array(
                'crm.js' => 'crm_script',
                'SIPml-api.js?svn=230' => 'crm_script',
                'assets/js/bootstrap-transition.js' => 'crm_script',
                'assets/js/bootstrap-alert.js' => 'crm_script',
                'assets/js/bootstrap-modal.js' => 'crm_script',
                'assets/js/bootstrap-dropdown.js' => 'crm_script',
                'assets/js/bootstrap-scrollspy.js' => 'crm_script',
                'assets/js/bootstrap-tab.js' => 'crm_script',
                'assets/js/bootstrap-tooltip.js' => 'crm_script',
                'assets/js/bootstrap-popover.js' => 'crm_script',
                'assets/js/bootstrap-button.js' => 'crm_script',
                'assets/js/bootstrap-collapse.js' => 'crm_script',
                'assets/js/bootstrap-carousel.js' => 'crm_script',
                'assets/js/bootstrap-typeahead.js' => 'crm_script',
            ));

        }
        $oModule->getController();
        //d($oModule->getFullControllerName());

        //d($aSetting);
        // assign setting to template.

        // call template
        if (!CORE_IS_AJAX_PAGE && $oTpl->sDisplayLayout)
        {
            $oTpl->getLayout($oTpl->sDisplayLayout);
        }
    }

    public static function setCookie($sName, $sValue, $iExpire = 0, $bSecure = false, $bHttpOnly = false)
    {
		if(!is_bool($bSecure)) $bSecure = false;
		if(!is_bool($bHttpOnly)) $bHttpOnly = false;

		$domain = Core::getParam('core.cookie_domain');
		if(empty($domain)) $domain = $_SERVER['HTTP_HOST'];

        //$sName = Core::getParam('core.session_prefix') . $sName;
        if (version_compare(PHP_VERSION, '5.2.0', '>=')){
            setcookie($sName, $sValue, (($iExpire != 0 || $iExpire != -1) ? $iExpire : (CORE_TIME + (3600*24*1))), Core::getParam('core.cookie_path'), $domain, $bSecure, $bHttpOnly);
        } else {
            setcookie($sName, $sValue, (($iExpire != 0 || $iExpire != -1) ? $iExpire : (CORE_TIME + (3600*24*1))), Core::getParam('core.cookie_path'), $domain, $bSecure);
        }
    }
}
