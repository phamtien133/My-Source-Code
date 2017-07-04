<?php
class Url
{
    public $aRewrite = array();
    
    public $aReverseRewrite = array();
    
    private $_aParams = array();
    
    private $_bIsUsed = false;
    
    private static $_isMobile = false;
    
    private $_iWrite = 1;
    
    protected $_aHeaders =  array (
       100 => "HTTP/1.1 100 Continue",
       101 => "HTTP/1.1 101 Switching Protocols",
       200 => "HTTP/1.1 200 OK",
       201 => "HTTP/1.1 201 Created",
       202 => "HTTP/1.1 202 Accepted",
       203 => "HTTP/1.1 203 Non-Authoritative Information",
       204 => "HTTP/1.1 204 No Content",
       205 => "HTTP/1.1 205 Reset Content",
       206 => "HTTP/1.1 206 Partial Content",
       300 => "HTTP/1.1 300 Multiple Choices",
       301 => "HTTP/1.1 301 Moved Permanently",
       302 => "HTTP/1.1 302 Found",
       303 => "HTTP/1.1 303 See Other",
       304 => "HTTP/1.1 304 Not Modified",
       305 => "HTTP/1.1 305 Use Proxy",
       307 => "HTTP/1.1 307 Temporary Redirect",
       400 => "HTTP/1.1 400 Bad Request",
       401 => "HTTP/1.1 401 Unauthorized",
       402 => "HTTP/1.1 402 Payment Required",
       403 => "HTTP/1.1 403 Forbidden",
       404 => "HTTP/1.1 404 Not Found",
       405 => "HTTP/1.1 405 Method Not Allowed",
       406 => "HTTP/1.1 406 Not Acceptable",
       407 => "HTTP/1.1 407 Proxy Authentication Required",
       408 => "HTTP/1.1 408 Request Time-out",
       409 => "HTTP/1.1 409 Conflict",
       410 => "HTTP/1.1 410 Gone",
       411 => "HTTP/1.1 411 Length Required",
       412 => "HTTP/1.1 412 Precondition Failed",
       413 => "HTTP/1.1 413 Request Entity Too Large",
       414 => "HTTP/1.1 414 Request-URI Too Large",
       415 => "HTTP/1.1 415 Unsupported Media Type",
       416 => "HTTP/1.1 416 Requested range not satisfiable",
       417 => "HTTP/1.1 417 Expectation Failed",
       500 => "HTTP/1.1 500 Internal Server Error",
       501 => "HTTP/1.1 501 Not Implemented",
       502 => "HTTP/1.1 502 Bad Gateway",
       503 => "HTTP/1.1 503 Service Unavailable",
       504 => "HTTP/1.1 504 Gateway Time-out"
   );
   
    public function __construct()
    {
        $this->_setParams();
    }
    
    /**
     * Get all the requests.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_aParams;
    }
    
    /**
     * Send a user to a new page using the URL method we use.
     *
     * @param string $sUrl Internal URL.
     * @param array $aParams ARRAY of params to include in the URL.
     * @param string $sMsg Optional message you can pass which will be displayed on the arrival page.
     */
    public function send($sUrl, $aParams = array(), $sMsg = null, $iHeader = null)
    {    
        if ($sMsg !== null){
            Core::addMessage($sMsg);
        }

        $this->_send((preg_match("/(http|https):\/\//i", $sUrl) ? $sUrl : $this->makeUrl($sUrl, $aParams)), $iHeader);
        exit;
    }
    
    /**
     * Get the domain mame of the site.
     *
     * @return string
     */
    public function getDomain()
    {
        if ($this->_iWrite != 3)
        {
            return Core::getParam('core.path');
        }        
        
        return (($this->_aParams['req1'] == 'core') ? Core::getParam('core.path') : preg_replace("/http:\/\/(.*?)\.(.*?)/i", "http://{$this->_aParams['req1']}.$2", Core::getParam('core.path')));
    }
    
    public function getShortDomain()
    {
        $sDomain = $this->getDomain();
        $sDomain = str_replace('http://', '',$sDomain);
        $sDomain = str_replace('https://', '',$sDomain);
        $sDomain = str_replace('/', '',$sDomain);
        return $sDomain;
    }
    
    public function getFullUrl($bNoPath = false)
    {
        if ($bNoPath)
        {
            return Core::getLib('request')->get(CORE_GET_METHOD);
        }

        return $this->makeUrl('current');
    }
    
    /**
     * Make an internal link.
     *
     * @param string $sUrl Internal link.
     * @param array $aParams ARRAY of params to include in the link.
     * @param bool $bFullPath Not using this argument any longer.
     * @return string Full URL.
     */
    public function makeUrl($sUrl, $aParams = array(), $bFullPath = false)
    {       
        if (preg_match('/https?:\/\//i', $sUrl))
        {
            return $sUrl;
        }        
        
        if ($sUrl == 'current')
        {
            $sUrl = '';            
            foreach ($this->_aParams as $sKey => $sValue)
            {
                if (substr($sKey, 0, 3) == 'req')
                {
                    $sUrl .= urlencode($sValue) . '.';
                }
                else 
                {                    
                    $sUrl .= $sKey . '_' . urlencode($sValue) . '.';
                }
            }
        }
        
        // Make it an array if its not an array already (Used as shortcut)
        if (!is_array($aParams))
        {
            $aParams = array($aParams);
        }
        
        $sUrl = trim($sUrl, '.');        
        $sUrls = '';        
        
        switch ($this->_iWrite)
        {
            // www.site.com/foo/bar/
            case 1:
                $aParts = explode('.', $sUrl);                
                if ($bFullPath)
                {
                    $sUrls .= Core::getParam('core.main_path');
                }
                $sUrls .= Core::getParam('core.main_path');                
                $sUrls .= $this->_makeUrl($aParts, $aParams);    

                break;
            // www.site.com/index.php?foo=bar
            case 2:
                $aParts = explode('.', $sUrl);
                if ($bFullPath)
                {
                    $sUrls .= Core::getParam('core.main_path');
                }                
                $sUrls .= Core::getParam('core.main_path') .'index.php?do=/';
                $sUrls .= $this->_makeUrl($aParts, $aParams);        
                break;
            // foo.site.com/bar/
            case 3:                
                if (empty($sUrl))
                {
                    $sUrl = 'www';
                }

                $aParts = explode('.', $sUrl);
                if (isset($this->aRewrite[$aParts[0]]) && !is_array($this->aRewrite[$aParts[0]]))
                {
                    $aParts[0] = $this->aRewrite[$aParts[0]];
                }    
                $sUrls = preg_replace("/http:\/\/(.*?)\.(.*?)/i", "http://{$aParts[0]}.$2", Core::getParam('core.path'));
                $sUrls .= $this->_makeUrl($aParts, $aParams);
                break;
        }
        
        return $sUrls;
    }
    
    /**
     * Get the URL of the current page.
     *
     * @return string URL.
     */
    public function getUrl()
    {
        $sUrl = '';

        foreach ($this->_aParams as $sKey => $sValue)
        {
            if (substr($sKey, 0, 3) == 'req')
            {
                $sUrl .= $sValue . '/';
            }
        }
        $sUrl = rtrim($sUrl, '/');

        return $sUrl;
    }
    
    /**
     * Permalink for items.
     *
     * @return    string    Returns the full URL of the link.
     */
    public function permalink($sLink, $iId, $sTitle = null, $bRedirect = false, $sMessage = null, $aExtraLinks = array())
    {        
        if ($sMessage !== null)
        {
            Core::addMessage($sMessage);
        }        
        
        $aExtra = array();
        $aExtra[] = $iId;
        if (!empty($sTitle))
        {
            $aExtra[] = $this->cleanTitle($sTitle);
        }
        
        if (is_array($sLink))
        {
            $iCnt = 0;
            foreach ($sLink as $mKey => $mValue)
            {
                $iCnt++;
                if ($iCnt === 1)
                {
                    $sActualLink = $mValue;
                    
                    continue;
                }
                
                if (is_numeric($mKey))
                {
                    $aExtra[] = $mValue;
                }
                else 
                {
                    if ($mKey == 'view')
                    {
                        $mValue = urlencode($mValue);
                    }
                    $aExtra[$mKey] = $mValue;    
                }
            }
            $sLink = $sActualLink;
        }
        
        if (is_array($aExtraLinks) && count($aExtraLinks))
        {
            $aExtra = array_merge($aExtra, $aExtraLinks);    
        }
        
        $sUrl = Core::getLib('url')->makeUrl($sLink, $aExtra);
        
        if ($bRedirect === true)
        {
            $this->_send($sUrl);    
        }
        
        return $sUrl;
    }
    
        /**
     * Clean a items title for the sites URL.
     *
     * @param string $sTitle Title we need to parse and clean.
     * @return string New clean title.
     */
    public function cleanTitle($sTitle)
    {
        $sTitle = $this->removeSpecialChar($sTitle);
        $sTitle = html_entity_decode($sTitle, null, 'UTF-8');

        $sTitle = strtr($sTitle, '`!"$%^&*()-+={}[]<>;:@#~,./?|' . "\r\n\t\\", '                             ' . '    ');
        $sTitle = strtr($sTitle, array('"' => '', "'" => ''));
        $sTitle = preg_replace('/[ ]+/', '-', trim($sTitle));        
            
        $sTitle = strtolower($sTitle);
        if (function_exists('mb_strtolower'))
        {
            $sTitle = mb_strtolower($sTitle, 'UTF-8');
        }
        else 
        {
            $sTitle = strtolower($sTitle);    
        }
        
        if (function_exists('mb_substr'))
        {
            $sTitle = mb_substr($sTitle, 0, Core::getParam('core.crop_seo_url'), 'UTF-8');            
        }
        else 
        {
            $sTitle = substr($sTitle, 0, Core::getParam('core.crop_seo_url'));
        }
        
        return $sTitle;
    }
    
    /**
    * Loại bỏ dấu tiếng việt và các ký tự đặc biệt trên url
    * 
    * @param mixed $sText
    */
    public function removeSpecialChar($sText)
    {
        $aMap = array(
           'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
           'd'=>'đ',
           'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
           'i'=>'í|ì|ỉ|ĩ|ị',
           'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
           'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
           'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
           'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
           'D'=>'Đ',
           'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
           'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
           'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
           'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
           'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        
        foreach($aMap as $iKey => $sValue)
        {
           $sText = preg_replace("/($sValue)/i", $iKey, $sText);
        }
        $sText = str_replace("'", '', $sText);
        $sText = htmlentities($sText);
        $sText = str_replace('&quot;', '', $sText);
        $sText = str_replace('&ldquo;', '', $sText);
        $sText = str_replace('&rdquo;', '', $sText);
        $sText = html_entity_decode($sText, ENT_QUOTES, "UTF-8");
        return $sText;
    }
    
    /**
     * Checks to see if a URL exists.
     *
     * @param mixed $mName STRING name of URL or ARRAY of URLs to check.
     * @return bool TRUE if URL exists, FALSE if not.
     */
    public function isUrl($mName)
    {
        $sUrl = $this->getUrl();
        
        if (is_array($mName))
        {
            foreach ($mName as $sName)
            {
                if ($this->_isUrl($sUrl, $sName))
                {
                    return true;
                }
            }
            return false;
        }
        
        return $this->_isUrl($sUrl, $mName);
    }
    
    public function isMobile()
    {        
        if (isset($_REQUEST['js_mobile_version']))
        {
            return true;
        }
        return self::$_isMobile;
    }
    
    private function _setParams()
    {
        if(!empty($_GET['url'])) {
            // reconstruct url
            $sUrl = 'http://'.urldecode($_GET['url']);
            $aTmps = parse_url($sUrl);
            $_SERVER['HTTP_HOST'] = $aTmps['host'];
            $_SERVER['REQUEST_URI'] = $aTmps['path'];
        }
        //else {
//            $sTmp = substr($_SERVER['HTTP_HOST'], 0, 2);
//            if ($sTmp == 's.') {
//                $_SERVER['HTTP_HOST'] = substr($_SERVER['HTTP_HOST'], 2, strlen($_SERVER['HTTP_HOST']) - 2);
//            }
//        }
        if (isset($aTmps['query']) && !empty($aTmps['query'])) {
            $_SERVER['REQUEST_URI'] .= '?'.$aTmps['query'];
            parse_str($aTmps['query'], $_GET);
        }
        // end
        
        $sRequest = $_SERVER['REQUEST_URI'];
        //$sRequest = trim($sRequest, '/');
        $aRequest = explode("/", $sRequest);
        $iCnt = 0;
        
        // check mobile link or not.
        foreach ($aRequest as $sVar) {
            $sVar = trim($sVar);
            if (!empty($sVar)) {
                if ($iCnt == 0 && ($sVar == 'mobile' || $sVar == 'm')) {
                    self::$_isMobile = true;
                    $sRequest = str_replace('mobile/', '', $sRequest);
                    $sRequest = str_replace('m/', '', $sRequest);
                    $sRequest = str_replace('m.', '', $sRequest);
                    break;
                }
            }
        }

        $aRequest = explode("/", $sRequest);
        if (count($aRequest) > 1) {
            unset($aRequest[0]);
        }
        // we assign name "req1, req2,..." for each url cluster (separated by "/")
        foreach ($aRequest as $sReq) {
            $iCnt++;
            $this->_aParams['req' . $iCnt] = $sReq;
        }
        // separate url
        
        $aRequest = explode('/', $sRequest, 3);
        $aTmp = explode('.html', $aRequest[1], 2);
        $sType = $aTmp[0];
        if ($sType == 'search') {
            $this->_aParams['query'] = $aRequest[2];
        }
        $this->_aParams['type'] = $sType;
        
        // get all params in url ((separated by "="))
        $iPos = mb_strpos($sRequest, '?');
        if ($iPos !== false) {
            $iPos++;
            $sParam = substr($sRequest, $iPos);
            // thực hiện tách &
            $aParam = explode('&', $sParam);
            foreach ($aParam as $sValue) {
                $aTmp = explode('=', $sValue);
                if($aTmp[0] == 'code')
                    continue;
                $this->_aParams[$aTmp[0]] = $aTmp[1];
            }
        }
        // save cache for url to avoid the re-analyse again for url.
        if ($sType == '' || $sType = 'not_classify') {

            $iPos = mb_strpos($sRequest, '?');
            if ($iPos === false)
                $iPos = mb_strlen($sRequest);
            
            $iDashPosition = mb_strpos($sRequest, '_');
            if ($iDashPosition > $iPos) {
                $iDashPosition = false;
            }

            if ($iDashPosition === false) {
                $aTmp = explode('?', $sRequest);
                if (isset($aTmp[1])) {
                    $this->_aParams['domain-path'] = $aTmp[0];
                }
                else $this->_aParams['domain-path'] = (empty($sRequest)) ? '/' : $sRequest;
            }
            else {
                $this->_aParams['domain-path'] = (empty($sRequest)) ? '/' : $sRequest;
            }
        }
    }
    
    /**
     * Build a URL based on the apache rewrite rules.
     *
     * @param array $aParts ARRAY of all the URL parts.
     * @param array $aParams ARRAY of all the requests.
     * @return string Converted URL.
     */
    private function _makeUrl(&$aParts, &$aParams)
    {        
        if (isset($this->aRewrite[$aParts[0]]) && !is_array($this->aRewrite[$aParts[0]])) {
            $aParts[0] = $this->aRewrite[$aParts[0]];
        }    

        $sUrls = '';
        foreach ($aParts as $iPartKey => $sPart) {
            if ($this->_iWrite == 3 && $iPartKey == 0) {
                continue;
            }
            
            if (empty($sPart)) {
                continue;
            }
            
            if ($iPartKey === 0 && $sPart == 'admincp') {
                $sPart = 'admincp';
            }
            
            if ($aParts[0] != 'admincp' && isset($this->aRewrite[$sPart]) && !is_array($this->aRewrite[$sPart])) {
                $sPart = $this->aRewrite[$sPart];
            }
            
            $sUrls .= str_replace('.', '', $sPart) . '/';
        }                
                
        if ($aParams && is_array($aParams)) {
            foreach ($aParams as $sKey => $sValue) {                
                if (is_null($sValue)) {
                    continue;
                }
                
                if ($aParts[0] != 'admincp' && is_numeric($sKey) && isset($this->aRewrite[str_replace('.', '', $sValue)]) && !is_array($this->aRewrite[str_replace('.', '', $sValue)])) {
                    $sValue = $this->aRewrite[str_replace('.', '', $sValue)];
                }                
                
                $sUrls .= (is_numeric($sKey) ? str_replace('.', '', $sValue) : $sKey . '_' . str_replace('.', '', $sValue)) . '/';
            }
        }        
        
        if (preg_match('/\#/', $sUrls)) {
            // $sUrls = rtrim($sUrls, '/');
        }
        
        $sSubUrl = rtrim($sUrls, '.');
        if (isset($this->aRewrite[$sSubUrl]) && !is_array($this->aRewrite[$sSubUrl])) {            
            $sUrls = $this->aRewrite[$sSubUrl] . '/';
        }
        
        return $sUrls;    
    }
    
    /**
     * Perform a URL reverse rewrite if it exists.
     *
     * @param string $sUrl URL to check if there is a rewrite and then to reverse it.
     * @return string Rewritten URL.
     */
    public function reverseRewrite($sUrl)
    {
        if (isset($this->aReverseRewrite[$sUrl])) {
            return $this->aReverseRewrite[$sUrl];
        }
        return $sUrl;
    }
    
    /**
     * Get all the custom rewrite rules.
     *
     * @return array
     */
    public function getRewrite()
    {
        return $this->aRewrite;
    }
    
    public function forward($sUrl, $sMsg = '', $iHeader = null)
    {
        if ($sMsg) {
            Core::addMessage($sMsg);
        }

        $this->_send($sUrl, $iHeader);
        exit;        
    }
    
    /**
     * Send the user to a new location.
     *
     * @param string $sUrl Full URL.
     */
    private function _send($sUrl, $iHeader = null)
    {
        // Clean buffer
        ob_clean();        
        
        if (defined('CORE_IS_AJAX_PAGE') && CORE_IS_AJAX_PAGE) {
            echo 'window.location.href = \'' . $sUrl . '\';';
            exit;
        }
        
        if ($iHeader !== null && isset($this->_aHeaders[$iHeader])) {
            header($this->_aHeaders[$iHeader]);
        }
        
        // Send the user to the new location
        header('Location: ' . $sUrl);
    }
    
    /**
     * Checks to see if a URL exists or not within the rewrite rules.
     *
     * @param string $sUrl URL name.
     * @param string $sName ID name of the URL.
     * @return bool TRUE if URL exists, FALSE if not.
     */
    private function _isUrl(&$sUrl, $sName)
    {
        if (($sUrl == $sName) || (isset($this->aRewrite[$sName]) && $this->aRewrite[$sName] == $sUrl)) {
            return true;
        }
        return false;        
    }
}
?>