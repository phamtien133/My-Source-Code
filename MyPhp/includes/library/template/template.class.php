<?php
class Template
{
    /**
     * Default template name.
     *
     * @var string
     */
    public $sDisplayLayout = 'template';

    /**
     * Folder of the theme being used.
     *
     * @var string
     */
    private $_sThemeFolder;

    /**
     * Theme layout to load.
     *
     * @var string
     */
    private $_sThemeLayout;

    /**
     * Folder of the style being used.
     *
     * @var string
     */
    private $_sStyleFolder;

    /**
     * List of meta data.
     *
     * @var array
     */
    private $_aMeta = array();

    /**
     * URL of the current page we are on.
     *
     * @var string
     */
    private $_sUrl = null;

    /**
     * Information about the current theme we are using.
     *
     * @var array
     */
    private $_aTheme = array(
        'theme_parent_id' => 0
    );

    /**
     * Mobile headers.
     *
     * @var array
     */
    private $_aMobileHeaders = array();

    /**
     * Cache of all the <head></head> content being loaded.
     *
     * @var array
     */
    private $_aCacheHeaders = array();

    /**
     * Holds section menu information
     *
     * @var array
     */
    private $_aSectionMenu = array();

    /**
     * Static variable of the current theme folder.
     *
     * @static
     * @var string
     */
    protected static $_sStaticThemeFolder = null;

    /**
     * List of titles assigned to a page.
     *
     * @var array
     */
    private $_aTitles = array();

    /**
     * Class constructor we use to build the current theme and style
     * we are using.
     *
     */

     /**
     * List of all the variables assigned to templates.
     *
     * @var array
     */
    private $_aVars = array();

    /**
     * List of data to add within the templates HTML <head></head>.
     *
     * @var array
     */
    private $_aHeaders = array();

    private $_sFooter = '';

    private $_sComponent = '';

    public function __construct()
    {
        //$this->_sThemeLayout = (Core::isMobile() ? 'mobile' : 'web');
        $this->_sThemeLayout = 'web';
        $this->_sThemeFolder = 'default';
        $this->_sStyleFolder = 'default';
        self::$_sStaticThemeFolder = $this->_sThemeFolder;
        // tạm thời disable để fix lỗi website
        Core::getLib('setting')->setParam('setting_template', 'cms');
    }

    /**
     * Loads the current template.
     *
     * @param string $sName Layout name.
     * @param bool $bReturn TRUE to return the template code, FALSE will echo it.
     * @return mixed STRING if 2nd argument is TRUE. Otherwise NULL.
     */
    public function getLayout($sName, $bReturn = false)
    {
        $bIsCache = Core::getParam('core.cache_template_file');
        if ($bIsCache) {
            $this->_getFromCache($this->getLayoutFile($sName));
            if ($bReturn) {
                return $this->_returnLayout();
            }
        }
        else {
           // core_log($sName, 'a+');
            require($this->getLayoutFile($sName));
            if ($bReturn) {
                return $this->_returnLayout();
            }
        }
    }

    /**
     * Gets a template file from cache. If the file does not exist we re-cache the template.
     *
     * @param string $sFile Full path of the template we are loading.
     */
    private function _getFromCache($sFile)
    {
        if (!$this->_isCached($sFile)) {
            $oTplCache = Core::getLib('template.cache');
            $sContent = (file_exists($sFile) ? file_get_contents($sFile) : null);
            $mData = $oTplCache->compile($this->_getCachedName($sFile), $sContent);
        }
        $this->_requireFile($sFile);
    }

    /**
     * Get the full path of the current layout file.
     *
     * @param string $sName Name of the layout file.
     * @return string Full path to the layout file.
     */
    public function getLayoutFile($sName)
    {
        // get directory template for domain
        if (Core::isParam('setting_template')) {
            $this->_sThemeFolder = Core::getParam('setting_template');
        }

        $sFile = DIR_THEME . $this->_sThemeLayout . DS . $this->_sThemeFolder . DS .'template'. DS. $sName . CORE_TPL_SUFFIX;

        if (!file_exists($sFile)) {
            $sFile = DIR_THEME . $this->_sThemeLayout . DS . 'default' . DS .'template'. DS. $sName . CORE_TPL_SUFFIX;
        }

        if (!file_exists($sFile)) {
            $sFile = DIR_THEME . 'web' . DS . 'default' . DS . 'template' . DS . $sName . CORE_TPL_SUFFIX;
        }

        return $sFile;
    }

    public function setHeader($mHeaders, $mValue = null)
    {
        if ($mValue !== null) {
            if ($mHeaders == 'head') {
                $mHeaders = array($mValue);
            }
            else {
                $mHeaders = array($mHeaders => $mValue);
            }
        }
        $this->_aHeaders[] = $mHeaders;
    }

    public function setTemplate($sLayout)
    {
        $this->sDisplayLayout = $sLayout;

        return $this;
    }

    public function setControllerTemplate($sComponent)
    {
        $this->_sComponent = $sComponent;
    }

    public function getControllerTemplate()
    {
        return $this->_sComponent;
    }

    /**
     * Set all the meta tags to be used on the site.
     *
     * @param array $mMeta ARRAY of meta tags.
     * @param string $sValue Value of meta tags in case the 1st argument is a string.
     * @return object Returns self.
     */
    public function setMeta($mMeta, $sValue = null)
    {
        if (!is_array($mMeta)) {
            $mMeta = array($mMeta => $sValue);
        }

        foreach ($mMeta as $sKey => $sValue) {
            if ($sKey == 'og:url') {
                $this->_aMeta[$sKey] = $sValue;

                return $this;
            }

            if (isset($this->_aMeta[$sKey])) {
                $this->_aMeta[$sKey] .= ($sKey == 'keywords' ? ', ' : ' ') . $sValue;
            }
            else {
                $this->_aMeta[$sKey] = $sValue;
            }
            $this->_aMeta[$sKey] = ltrim($this->_aMeta[$sKey], ', ');
        }

        return $this;
    }

    /**
     * Assign a variable so we can use it within an HTML template.
     *
     * PHP assign:
     * <code>
     * Core::getLib('template')->assign('foo', 'bar');
     * </code>
     *
     * HTML usage:
     * <code>
     * {$foo}
     * // Above will output: bar
     * </code>
     *
     * @param mixed $mVars STRING variable name or ARRAY of variables to assign with both keys and values.
     * @param string $sValue Variable value, only if the 1st argument is a STRING.
     * @return object Returns self.
     */
    public function assign($mVars, $sValue = '')
    {
        if (!is_array($mVars)) {
            $mVars = array($mVars => $sValue);
        }

        foreach ($mVars as $sVar => $sValue) {
            $this->_aVars[$sVar] = $sValue;
        }

        return $this;
    }

    public function clearBreadCrumb()
    {
        $this->_aBreadCrumbs = array();
        $this->_aBreadCrumbTitle = array();
    }

    /**
     * Override the layout of the site.
     *
     * @param string $sName Layout we should load.
     */
    public function setLayout($sName)
    {
        $this->_sSetLayout = $sName;
    }

    public function setTitle($sTitle)
    {
        $this->_aTitles[] = $sTitle;

        $this->setMeta('og:site_name', Core::getParam('core.site_title'));
        $this->setMeta('og:title', $sTitle);

        return $this;
    }

    /**
     * All data placed between the HTML tags <head></head> can be added with this method for mobile devices.
     * Since we rely on custom templates we need the header data to be custom as well. Current
     * support is for: css & JavaScript
     * All HTML added here is coded under XHTML standards.
     *
     * @access public
     * @param unknown_type $mHeaders
     * @return object Returns self.
     */
    public function setMobileHeader($mHeaders, $mValue = null)
    {
        if ($mValue !== null)
        {
            $mHeaders = array($mHeaders => $mValue);
        }

        $this->_aMobileHeaders[] = $mHeaders;

        return $this;
    }

    /**
     * Get the title for the current page beind displayed.
     * All titles are added earlier in the script using self::setTitle().
     * Each title is split with a delimiter specificed from the Admin CP.
     *
     * @see setTitle()
     * @return string $sData Full page title including delimiter
     */
    public function getTitle()
    {
        $oFilterOutput = Core::getLib('output');
        $sData = '';

        foreach ($this->_aTitles as $sTitle) {
            $sData .= Core::getParam('core.title_delim') . ' '. $oFilterOutput->clean($sTitle) . ' ';
        }
        // get default title of domain
        if (Core::getParam('core.main_server') == 'sup.') {
            $sData = 'Hệ quản trị nội dung '.Core::getParam('setting_title').' dành cho Nhà cung cấp' . ' ' . $sData;
        }
        else {
            $sData = 'Hệ quản trị nội dung ' .Core::getParam('setting_title'). ' ' . $sData;
        }

        return $sData;
    }

    public function getStyle($sType = 'css', $sValue = null, $sModule = null)
    {
        $sTemplatePath = Core::getParam('core.image_path');

        if ($sModule !== null) {
            if ($sType == 'static_script') {
                $sType = 'jscript';
            }

            $sUrl = Core::getParam('core.path'). 'module/' . $sModule . '/static/' . $sType . '/';
            $sDir = DIR_MODULE . $sModule . DS . 'static' . DS . $sType . DS;

            if ($sType == 'jscript') {
                $sPath = $sUrl . $sValue;
            }
            else {
                $bPass = false;
                if (file_exists($sDir . $this->_sThemeFolder . DS . $this->_sStyleFolder . DS . $sValue)) {
                    $bPass = true;
                    $sPath = $sUrl . $this->_sThemeFolder . '/' . $this->_sStyleFolder . '/' . $sValue;
                }

                if ($bPass === false) {
                    $sPath = $sUrl . 'default/' . $sValue;
                }
            }

            return $sPath;
        }

        if ($sType == 'global_script') {
            $sPath = $sTemplatePath. 'global/js/' . $sValue;
        }
        elseif ($sType == 'crm_script') {
            $sPath = $sTemplatePath. 'crm/js/' . $sValue;
        }
        else if($sType == 'site_script'){
            $sPath = $sTemplatePath. 'js/'. $sValue;
        }
        else if($sType == 'global_css'){
            $sPath = $sTemplatePath. 'global/css/' . $sValue;
        }
        else if($sType == 'site_css'){
            $sPath = $sTemplatePath.  'css/'. $sValue;
        }

        return $sPath;
    }

    public function setBreadCrumb($sPhrase, $sLink = '', $bIsTitle = false)
    {
        if (is_array($sPhrase))
        {
            foreach ($sPhrase as $aPhrase)
            {
                ((isset($aPhrase[2]) && $aPhrase[2]) ? $this->_aBreadCrumbTitle = array($aPhrase[0], $aPhrase[1]) : $this->_aBreadCrumbs[$aPhrase[1]] = $aPhrase[0]);
            }
            return $this;
        }
        if ($bIsTitle === true)
        {
            $this->_aBreadCrumbTitle = array(Core::getLib('locale')->convert($sPhrase), $sLink);
            if (!empty($sLink))
            {
                $this->setMeta('og:url', $sLink);
            }
        }

        $this->_aBreadCrumbs[$sLink] = Core::getLib('locale')->convert($sPhrase);
        return $this;
    }

    public function getHeader($bReturnArray = false)
    {
        if (Core::isParam('setting_template')) {
            $this->_sThemeFolder = Core::getParam('setting_template');
        }

        if (Core::isMobile()) {
            $this->setHeader(array(
                    'mobile.css' => 'style_css'
                )
            );
        }
        //$this->setHeader(array('custom.css' => 'style_css'));
        $aArrayData = array();
        $sData = '';
        $sCss = '';
        $sJs = '';
        $iVersion = '0.9.5';
        $oUrl = Core::getLib('url');
        $aUrl = $oUrl->getParams();

        if (!CORE_IS_AJAX_PAGE) {
            $sJs .= "\t\t\tvar oCore = {'core.is_admincp': " . (Core::isAdminPanel() ? 'true' : 'false') . ", 'core.section_module': '" . Core::getLib('module')->getModuleName() . "', 'log.security_token': '" . Core::getService('log.session')->getToken() . "'};\n";

            $aJsVars = array(
                'sJsHome' => Core::getParam('core.path'),
                'sJsMain' => Core::getParam('core.main_path'),
                'sJsImage' => Core::getParam('core.image_path'),
                'sJsHostname' => $_SERVER['HTTP_HOST'],
                'sSiteName' => $this->getTitle(),
                'sImagePath' => Core::getParam('core.image_path'),
                'sGlobalImage' => Core::getParam('core.image_path') . 'global/images/',
                'sStylePath' => Core::getParam('core.image_path'),
                'sGlobalStyle' => Core::getParam('core.image_path') . 'global/',
                'iUserId' => Core::getUserId(),
                'sJsAjax' => Core::getParam('core.main_path') .'includes/'. 'ajax.php',
                'sStaticVersion' => $iVersion,
                'sDateFormat' => 'a:2:{s:7:"default";s:3:"MDY";s:6:"values";a:3:{i:0;s:3:"MDY";i:1;s:3:"DMY";i:2;s:3:"YMD";}}',
                'sGlobalTokenName' => Core::getTokenName(),
                'sController' => Core::getLib('module')->getFullControllerName(),
                'bJsIsMobile' => Core::isMobile(),
                'sHostedVersionId' => (defined('CORE_IS_HOSTED_VERSION') ? CORE_IS_HOSTED_VERSION : '')
            );

//            if (Core::isAdmin()) {
//                $aJsVars['sAdminCPLocation'] = 'admincp';
//            }
//            else {
//                $aJsVars['sAdminCPLocation'] = '';
//            }

            if (Core::isModule('input') && false) {
                $this->setHeader('cache', array('browse.css' => 'style_css'));
            }
            $sJs .= "\t\t\tvar oParams = {";
            $iCnt = 0;
            foreach ($aJsVars as $sVar => $sValue) {
                $iCnt++;
                if ($iCnt != 1) {
                    $sJs .= ",";
                }

                if (is_bool($sValue)) {
                    $sJs .= "'{$sVar}': " . ($sValue ? 'true' : 'false');
                }
                elseif (is_numeric($sValue)) {
                    $sJs .= "'{$sVar}': " . $sValue;
                }
                else {
                    $sJs .= "'{$sVar}': '" . str_replace("'", "\'", $sValue) . "'";
                }
            }
            $sJs .= "};\n";

            if (count($this->_aImages)) {
                $sJs .= "\t\t\tvar oJsImages = {";
                foreach ($this->_aImages as $sKey => $sImage) {
                    $sJs .= $sKey . ': \'' . $sImage. '\',';
                }
                $sJs = rtrim($sJs, ',');
                $sJs .= "};\n";
            }
        }

        if (CORE_IS_AJAX_PAGE) {
            $this->_aCacheHeaders = array();
        }

        $bIsHttpsPage = false;
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
            $bIsHttpsPage = true;
        }

        $aSubCache = array();
        $sStyleCacheData = '';
        $sJsCacheData = '';
        $aUrl = Core::getLib('url')->getParams();
        // These two arrays hold the files to be combined+minified and put in CDN (if available)
        $aCacheJs = array();
        $aCacheCSS = array();

        $this->_sFooter = '';
        $sJs .= "\t\t\t" . 'var $Behavior = {};' . "\n";
        $sJs .= "\t\t\t" .'var $Core = {};' . "\n";

        $aCustomCssFile = array();
        foreach ($this->_aHeaders as $aHeaders) {
            if (!is_array($aHeaders)) {
                $aHeaders = array($aHeaders);
            }
            foreach ($aHeaders as $mKey => $mValue) {
                $sQmark = (strpos($mKey, '?') ? '&amp;' : '?');

                if (is_numeric($mKey)) {
                    if ($mValue === null) {
                        continue;
                    }
                    if ($bReturnArray) {
                        $aArrayData[] = $mValue;
                    }
                    else {
                        if (is_string($mValue) && (strpos($mValue, '.js') !== false || strpos($mValue, 'javascript') !== false)) {
                            if (strpos($mValue, 'RecaptchaOptions')){
                                $sData .= "\t\t" . $mValue . "\n";
                            }
                            else {
                                $sData .= "\t\t". $mValue;
                            }
                        }
                        else if (is_string($mValue)) {
                            $sData .= "\t\t" . $mValue . "\n";
                        }
                        else {
                            $sData .= "\t\t" . implode($mValue) . "\n";
                        }
                    }
                }
                else {
                    $bToHead = false;
                    if ($bReturnArray) {
                        $aArrayData[] = $this->getStyle($mValue, $mKey);
                    }
                    else {
                        if ($mValue == 'site_css' || $mValue == 'global_css') {
                            $aCacheCSS[] = $this->getStyle($mValue, $mKey);
                        }
                        else {
                            $aCacheJs[] = $this->getStyle($mValue, $mKey);
                        }
                    }

                    $aSubCache[$mKey][$mValue] = true;
                }
            }
        }

        $sCacheData = '';
        $sCacheData .= "\n\t\t<script type=\"text/javascript\">\n";
        $sCacheData .= $sJs;
        $sCacheData .= "\t\t</script>";

        if (!empty($sStyleCacheData)) {
            $sCacheData .= "\n\t\t" . '<link rel="stylesheet" type="text/css" href="' . URL_STATIC . 'gzip.php?t=css&amp;s=' . $sStylePath . '&amp;f=' . rtrim($sStyleCacheData, ',') . '&amp;v=' . $iVersion . '" />';
        }

        if (!empty($sJsCacheData)) {
            $sCacheData .= "\n\t\t" . '<script type="text/javascript" src="' . URL_STATIC . 'gzip.php?t=js&amp;f=' . rtrim($sJsCacheData, ',') . '&amp;v=' . $iVersion . '"></script>';
        }

        if (!empty($sCacheData)) {
            $sData = preg_replace('/<link rel="shortcut icon" type="image\/x-icon" href="(.*?)" \/>/i', '<link rel="shortcut icon" type="image/x-icon" href="\\1" />' . $sCacheData, $sData);
        }

        if ($bReturnArray) {
            $sData = '';
        }
        $aCacheJs = array_unique($aCacheJs);
        $aSubCacheCheck = array();
        foreach ($aCacheCSS as $sFile) {
           if (isset($aSubCacheCheck[$sFile])) {
                continue;
            }
            $aSubCacheCheck[$sFile] = true;
            $sData .= "\t\t" . '<link rel="stylesheet" type="text/css" href="'. $sFile . $sQmark .'v=' . $iVersion . '" />' . "\n";
        }

        if (!empty($aCustomCssFile)) {
            foreach ($aCustomCssFile as $sCustomCssFile) {
                $sData .= "\n\t\t".'<!-- Custom --> <link rel="stylesheet" type="text/css" href="' . $sCustomCssFile . '" />' . "";
            }
        }
        foreach ($aCacheJs as $sFile) {
            $this->_sFooter .= "\t\t" . '<script type="text/javascript" src="' . $sFile . $sQmark .'v=' . $iVersion . '"></script>' . "\n";
        }

       // $this->_sFooter .= "\t\t" . '<script type="text/javascript"> $Core.init(); </script>' . "\n";

        if ($bReturnArray) {
            $aArrayData[] = $sData;
            return $aArrayData;
        }

        // Convert meta data
        $bHasNoDescription = false;
        if (count($this->_aMeta) && !CORE_IS_AJAX_PAGE) {
            //$oParseOutput = Core::getLib('output');
            $aFind = array(
                '&lt;',
                '&gt;',
                '$'
            );
            $aReplace = array(
                '<',
                '>',
                '&36;'
            );

            foreach ($this->_aMeta as $sMeta => $sMetaValue) {
                $sMetaValue = str_replace($aFind, $aReplace, $sMetaValue);
                $sMetaValue = strip_tags($sMetaValue);
                $sMetaValue = str_replace(array("\n", "\r"), "", $sMetaValue);
                $bIsCustomMeta = false;

                switch ($sMeta) {
                    case 'keywords':
                        $sKeywordSearch = 'and, i, in';
                        if (!empty($sKeywordSearch)) {
                            $aKeywordsSearch = array_map('trim', explode(',', $sKeywordSearch));
                        }
                        //$sMetaValue = $oParseOutput->shorten($oParseOutput->clean($sMetaValue), 900);

                        $sMetaValue = trim(rtrim(trim($sMetaValue), ','));
                        $aParts = explode(',', $sMetaValue);
                        $sMetaValue = '';
                        $aKeywordCache = array();
                        foreach ($aParts as $sPart) {
                            $sPart = trim($sPart);
                            if (isset($aKeywordCache[$sPart])) {
                                continue;
                            }

                            if (isset($aKeywordsSearch) && in_array(strtolower($sPart), array_map('strtolower', $aKeywordsSearch))) {
                                continue;
                            }

                            $sMetaValue .= $sPart . ', ';

                            $aKeywordCache[$sPart] = true;
                        }
                        $sMetaValue = rtrim(trim($sMetaValue), ',');
                        break;
                    case 'description':
                        $bHasNoDescription = true;
                        //$sMetaValue = $oParseOutput->shorten($oParseOutput->clean($sMetaValue), Core::getParam('core.meta_description_limit'));
                        break;
                    case 'robots':
                        $bIsCustomMeta = false;
                        break;
                    default:
                        $bIsCustomMeta = true;
                        break;
                }
                $sMetaValue = str_replace('"', '\"', $sMetaValue);
                $sMetaValue = html_entity_decode($sMetaValue, null, 'UTF-8');
                $sMetaValue = str_replace(array('<', '>'), '', $sMetaValue);

                if ($bIsCustomMeta) {
                    if ($sMeta == 'og:description') {
                        //$sMetaValue = $oParseOutput->shorten($oParseOutput->clean($sMetaValue),500);
                    }

                    switch ($sMeta) {
                        case 'canonical':
                            $sCanonical = $sMetaValue;
                            $sCanonical = preg_replace('/\/when\_([a-zA-Z0-9\-]+)\//i', '/', $sCanonical);
                            $sCanonical = preg_replace('/\/show\_([a-zA-Z0-9\-]+)\//i', '/', $sCanonical);
                            $sCanonical = preg_replace('/\/view\_\//i', '/', $sCanonical);

                            if (Core::isMobile()) {
                                if (URL_REWRITE == '1') {
                                    $sCanonical = str_replace(Core::getParam('core.path') . 'mobile/', Core::getParam('core.path'), $sMetaValue);
                                }
                                elseif (URL_REWRITE == '2') {
                                    $sCanonical = str_replace('?' . 'do' . '=/mobile/', '?' . 'do' . '=/', $sMetaValue);
                                }
                            }

                            $sData .= "\t\t<link rel=\"canonical\" href=\"{$sCanonical}\" />\n";

                            if (!Core::isMobile()) {
                                $sMobileReplace = '';
                                if (Core::getParam('core.url_rewrite') == '1') {
                                    $sMobileReplace = str_replace(Core::getParam('core.path'), Core::getParam('core.path') . 'mobile/', $sCanonical);
                                }
                                elseif (Core::getParam('core.url_rewrite') == '2') {
                                    $sMobileReplace = str_replace('?' . 'do' . '=/', '?' . 'do' . '=/mobile/', $sCanonical);
                                }

                                $sData .= "\t\t<link rel=\"alternate\" media=\"only screen and (max-width: 640px)\" href=\"{$sMobileReplace}\" />\n";
                            }
                            break;
                        default:
                            $sData .= "\t\t<meta property=\"{$sMeta}\" content=\"{$sMetaValue}\" />\n";
                            break;
                    }

                }
                else {
                    if (strpos($sData, 'meta name="'. $sMeta . '"') !== false) {
                        $sData = preg_replace("/<meta name=\"{$sMeta}\" content=\"(.*?)\" \/>\n\t/i", "<meta" . ($sMeta == 'description' ? ' property="og:description" ' : '') . " name=\"{$sMeta}\" content=\"" . $sMetaValue . "\" />\n\t", $sData);

                    }
                    else {
                        $sData = preg_replace('/<meta/', '<meta name="'. $sMeta . '" content="' . $sMetaValue . '" />' . "\n\t\t" . '<meta', $sData, 1);
                    }
                }

            }

            if (!$bHasNoDescription) {
                $sData .= "\t\t" . '<meta name="description" content="ERP-FI" />' . "\n";
            }
        }

        // Clear from memory
        $this->_aHeaders = array();
        $this->_aMeta = array();
        return $sData;
    }

    public function getBreadCrumb()
    {
        if (count($this->_aBreadCrumbTitle))
        {
            foreach ($this->_aBreadCrumbs as $sKey => $mValue)
            {
                if ($sKey === $this->_aBreadCrumbTitle[1])
                {
                    unset($this->_aBreadCrumbs[$sKey]);
                }
            }

            if (isset($this->_aBreadCrumbTitle[1]))
            {
                $this->setMeta('canonical', $this->_aBreadCrumbTitle[1]);
            }
        }

        if (count($this->_aBreadCrumbs) === 1 && !count($this->_aBreadCrumbTitle))
        {
            $sKey = array_keys($this->_aBreadCrumbs);
            $this->setMeta('canonical', $sKey[0]);
        }

        return array($this->_aBreadCrumbs, $this->_aBreadCrumbTitle);
    }

    /**
     * Get the full path to the modular template file we are loading.
     *
     * @param string $sTemplate Name of the file.
     * @param bool $bCheckDb TRUE to check the database if the file exists there.
     * @return string Full path to the file we are loading.
     */
    public function getTemplateFile($sTemplate, $bCheckDb = false)
    {
        // get directory template for domain
        if (Core::isParam('setting_template')) {
            $this->_sThemeFolder = Core::getParam('setting_template');
        }

        $aParts = explode('.', $sTemplate);

        $sModule = $aParts[0];

        unset($aParts[0]);

        $sName = implode('.', $aParts);

        $sName = str_replace('.', DS, $sName);
        $bPass = false;
        $sFile = DIR_MODULE . $sModule . DIR_MODULE_TPL . DS . $this->_sThemeFolder . DS. $sName . CORE_TPL_SUFFIX;
        if (file_exists($sFile)) {
            $bPass = true;
        }

        if ($bPass === false && file_exists(DIR_MODULE . $sModule . DIR_MODULE_TPL . DS . 'default' . DS. $sName . CORE_TPL_SUFFIX)) {
            $sFile = DIR_MODULE . $sModule . DIR_MODULE_TPL . DS . 'default' . DS. $sName . CORE_TPL_SUFFIX;
            $bPass = true;
        }

        if ($bPass === false && isset($aParts[2]) && file_exists(DIR_MODULE . $sModule . DIR_MODULE_TPL . DS . $this->_sThemeFolder . DS. $sName . DS . $aParts[2] . CORE_TPL_SUFFIX)) {
            $sFile =  DIR_MODULE . $sModule . DIR_MODULE_TPL . DS . $this->_sThemeFolder . DS. $sName . DS . $aParts[2] . CORE_TPL_SUFFIX;
            $bPass = true;
        }

        if (!isset($sFile)) {
            $sFile = DIR_MODULE . $sModule . DIR_MODULE_TPL . DS . 'default' . DS . $sName . CORE_TPL_SUFFIX;
        }

        if (!file_exists($sFile)) {
            echo 'Unable to load module template: ' . $sModule .'->' . $sName;
            return false;
        }
        return $sFile;
    }

    public function getBuiltFile($sFile)
    {
        $sCacheName = 'block_' . $sFile;

        if (!$this->_isCached($sCacheName))
        {
            $mContent = $this->getTemplateFile($sFile);
            if (is_array($mContent))
            {
                $mContent = $mContent[0];
            }
            else
            {
                $mContent = file_get_contents($mContent);
            }
            $oTplCache = Core::getLib('template.cache');
            $oTplCache->compile($this->_getCachedName($sCacheName), $mContent, true);
        }

        require($this->_getCachedName($sCacheName));

    }

    public function getVariable($sVar)
    {
        return (isset($this->_aVars[$sVar])) ? $this->_aVars[$sVar] : '';
    }

    /**
     * Get the current template data.
     *
     * @param string $sTemplate Template name.
     * @param bool $bReturn TRUE to return its content or FALSE to just echo it.
     * @return mixed STRING content only if the 2nd argument is TRUE.
     */
    public function getTemplate($sTemplate, $bReturn = false)
    {
        $sFile = $this->getTemplateFile($sTemplate);

        if(!Core::getParam('core.cache_template_file')) {
            // do not save to new file, only include template to view
            require($sFile);
        }
        else {
            if ($bReturn) {
                $sOriginalContent = ob_get_contents();
                ob_clean();
            }

            if ($this->_sSetLayout) {
                if (!$this->_isCached($sFile)) {
                    $sLayoutContent = file_get_contents($this->getLayoutFile($this->_sSetLayout));
                    $sName = $this->_getCachedName($sFile);

                    $aSubTemplate = array();
                    $sLayoutContent = str_replace('{layout_content}', (isset($aSubTemplate['html_data']) ? $aSubTemplate['html_data'] : file_get_contents($sFile)), $sLayoutContent);
                    $oTplCache = Core::getLib('template.cache');
                    $oTplCache->compile($this->_getCachedName($sFile), $sLayoutContent, true, (isset($aSubTemplate['html_data']) ? true : false));
                }
                $this->_sSetLayout = '';
                $this->_requireFile($sFile);
                $this->_sSetLayout = '';
            }
            else {
                $this->_getFromCache($sFile);
            }

            if ($bReturn) {
                $sReturn = $this->_returnLayout();
                echo $sOriginalContent;
                return $sReturn;
            }
        }
    }

    /**
     * Returns the content of a template that has already been echoed.
     *
     * @return unknown
     */
    private function _returnLayout()
    {
        $sContent = ob_get_contents();

        ob_clean();

        return $sContent;
    }

    /**
     * Checks to see if a template has already been cached or not.
     *
     * @param string $sName Full path to the template file.
     * @return bool TRUE is cached, FALSE is not cached.
     */
    private function _isCached($sName)
    {
        if (defined('NO_TEMPLATE_CACHE') && NO_TEMPLATE_CACHE)
        {
            return false;
        }
        if (!file_exists($this->_getCachedName($sName)))
        {
            return false;
        }

        if (file_exists($sName))
        {
            $iTime = filemtime($sName);

            // Check if a file has been updated recently, if it has make sure we return false to we recache it.
            if (($iTime + $this->_iCacheTime) > time())
            {
                return false;
            }
        }

        return true;
    }

    private function _requireFile($sFile)
    {
        if (defined('CORE_IS_HOSTED_SCRIPT')) {
            $oCache = Core::getLib('cache');
            $sId = $oCache->set(md5($this->_getCachedName($sFile)));
            eval('?>' . $oCache->get($sId) . '<?php ');
        }
        else {
            require($this->_getCachedName($sFile));
        }
    }

    /**
     * Gets the full path of the cached template file
     *
     * @param string $sName Name of the template
     * @return string Full path to cached template
     */
    private function _getCachedName($sName)
    {
        if (!defined('CORE_TMP_DIR')) {
            if (!is_dir(DIR_CACHE . 'template' . DS)) {
                mkdir(DIR_CACHE . 'template' . DS);
                chmod(DIR_CACHE . 'template' . DS, 0777);
            }
        }
        return (defined('CORE_TMP_DIR') ? CORE_TMP_DIR : DIR_CACHE) . ((defined('CORE_TMP_DIR') || CORE_SAFE_MODE) ? 'template_' : 'template/') . str_replace(array(DIR_THEME, DIR_MODULE, DS), array('', '', '_'), $sName) . (Core::isAdminPanel() ? '_admincp' : '') . (CORE_IS_AJAX ? '_ajax' : '') . '.php';
    }
}
