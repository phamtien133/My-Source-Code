<?php
class Language
{
    /**
     * ARRAY of all the phrases
     *
     * @var array
     */
    private $_aPhrases = array();
    
    /**
     * ARRAY of the current language package being used
     *
     * @var array
     */
    private $_aLanguage = array();
    
    /**
     * ARRAY of all the language packages
     *
     * @var array
     */
    private $_aLanguages = array();
    
    /**
     * ASCII conversion for URL strings (non-latin character support)
     *
     * @var array
     */
    private $_aAscii = array(
        // Svenska
        '246' => 'o',
        '228' => 'a',
        '229' => 'a',
        '214' => 'O',
        '196' => 'A',
        '197' => 'A'
    );    
    
    /**
     * ARRAY of phrases to be cached on a specific page
     *
     * @var array
     */
    private $_aCache = array();
    
    /**
     * Cache ID associated with the caching of phrases for a specific page
     *
     * @var string
     */
    private $_sCacheId;
    
    /**
     * Cache object
     *
     * @see Cache
     * @var object
     */
    private $_oCache = null;
    
    /**
     * Check to see if we already cached this page
     *
     * @var bool
     */
    private $_bIsCached = false;
    
    /**
     * Regex rules to manipulate phrases
     *
     * @var array
     */
    private $_aRules = array();
    
    /**
     * Use this variable to override the default language ID
     *
     * @var unknown_type
     */
    private $_sOverride = '';
    
    private $_aPhraseHistory = array();
    
    /**
     * Class constructor used to load the default language package and all the phrases that are part
     * of that language package. Also loads languag rules for that specific language package. All this information
     * is cached and database queries are only executed the first time the site is loaded after a hard
     * re-cache.
     *
     */
    public function __construct()
    {
        $oCache = Core::getLib('cache');
        $oDb = Core::getLib('database');
        $oSession = Core::getLib('session');
        $iLanguageListId = 0;
        $sType = '';
        if(!Core::isAdminPanel()) {
            $iLanguageListId = $oSession->get('session-language');
            $sType = 'site';
        } 
        else {
            $iLanguageListId = $oSession->get('session-acp_language');
            $sType = 'admin';
        }
        
        if($iLanguageId < 1) 
            $iLanguageListId = 1; // language default (vietnamese)
            
        $sLangId = $oCache->set('lang|'. $iLanguageListId.'|'.$sType);

        $aLanguage = $oCache->get($sLangId);
        $aLangId = array();
        if (!($aLanguage)) {
            $aRows = $oDb->select('id, name')
                ->from(Core::getT('language'))
                ->where('type =\''. $oDb->escape($sType). '\'')
                ->execute('getRows');
                
            foreach ($aRows as $aRow) {
                $aLangId[$aRow['id']] = $aRow['name'];
            }
            // get language value
            $aRows = $oDb->select('language_id, value')
                ->from(Core::getT('language_value'))
                ->where('language_list_id = '. $iLanguageListId)
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                if (!isset($aLangId[$aRow['language_id']]) || empty($aLangId[$aRow['language_id']]))
                    continue;
                $aLanguage[$aLangId[$aRow['language_id']]] = $aRow['value'];
            }
            $oCache->save($sLangId, $aLanguage);
        }
        
        $sCustomLangsId = 'customlang|'. $iLanguageListId. '|'. $sType. '|'. Core::getDomainId();
        
        // load custom language only used for this domain .
        $sCustomLangsId = $oCache->set($sCustomLangsId);
        $aCustomLangs = $oCache->get($sCustomLangsId);
        if (!$aCustomLangs) {
            $aLangId = array();
            $aRows = $oDb->select('language_id, value')
                ->from(Core::getT('language_custom'))
                ->where('language_list_id = '. $iLanguageListId . ' AND domain_id = '. Core::getDomainId())
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aLangId[$aRow['language_id']] = $aRow['value'];
            }
            if (!empty($aLangId)) {
                $aRows = $oDb->select('id, name')
                    ->from(Core::getT('language'))
                    ->where('id IN ('. implode(',', array_keys($aLangId)) . ')')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aCustomLangs[$aRow['name']] = $aLangId[$aRow['id']];
                }
            }
            $oCache->save($sCustomLangsId, $aCustomLangs);
        }
        unset($aLangId);
        foreach ($aCustomLangs as $sKey => $sValue) {
            $aLanguage[$sKey] = $sValue;
        }
        foreach($aLanguage as $iKey => $sValue) {
            $this->_aLanguage['language_'.$iKey] = $sValue;
        }
        unset($aLanguage);
    }
    
    /**
     * Get all the information provided on the current language package being used.
     *
     * @return array
     */
    public function getLang()
    {        
        $this->_aLanguage['image'] = (file_exists(Core::getParam('core.dir_pic') . 'flag' . DS . $this->_aLanguage['language_id'] . '.' . $this->_aLanguage['flag_id']) ? Core::getParam('core.url_pic') . 'flag/' . $this->_aLanguage['language_id'] . '.' . $this->_aLanguage['flag_id'] : '');                            
        
        return $this->_aLanguage;
    }
    
    /**
     * Get all the information for a specific language package
     *
     * @param string $sVar Language ID to look for
     * @return mixed ARRAY if we found the language package, emptry STRING if we didnt.
     */
    public function getLangBy($sVar)
    {
        return (isset($this->_aLanguage[$sVar]) ? $this->_aLanguage[$sVar] : '');
    }
    
    /**
     * Return the language ID for the current language package in use. This value is based on several
     * variables as specific users can select a language package they want to browse the site in
     * and admins can also select the default language package for the site.
     *
     * @return string Language ID for the language package in use.
     */
    public function getLangId()
    {
        if ($this->_sOverride != '')
        {
            return $this->_sOverride;
        }
        if (Core::isUser())
        {
            $sLanguageId = Core::getUserBy('language_id');
            if (empty($sLanguageId))
            {
                $sLanguageId = $this->autoLoadLanguage();    
            }
        }
        else 
        {
            if (($sLanguageId = Core::getLib('session')->get('language_id')))
            {
                
            }
            else 
            {
                $sLanguageId = $this->autoLoadLanguage();
            }
        }
        
        if (!isset($this->_aLanguages[$sLanguageId]))
        {
            $sLanguageId = 'en';
        }        
                
        return $sLanguageId;
    }
    
    /**
     * Checks if a phrase exists in the language package or not
     *
     * @param string $sParam Phrase to check if it exists
     * @return bool TRUE if it exists, FALSE if it does not
     */
    public function isPhrase($sParam)
    {
        if (strpos($sParam, '.') === false)
        {
            return '';
        }        
        if (strpos($sParam, ' '))
        {
            return false;
        }
        list($sModule, $sVar) = explode('.', $sParam);        
        
        if (!isset($this->_aPhrases[$sModule]))
        {
            $this->_getModuleLanguage($sModule);
        }        
        
        return (isset($this->_aPhrases[$sModule][$sVar]) ? true : false);
    }
    
    /**
     * Gets a phrase from a language.
     * 
     * Example Usage (PHP)
     * <code>
     * Core::getPhrase('foo.bar');
     * </code>
     * 
     * Example Usage (HTML)
     * <code>
     * {phrase var='for.bar'}
     * </code>
     *
     * @param string $sParam Phrase param that is unique for that specific phrase.
     * @param array $aParams (Optional) ARRAY of data we need to replace in the phrase
     * @param bool $bNoDebug (Optional) FALSE allows debug mode to be executed, while TRUE forces that there is no debug output.
     * @param string $sDefault (Optional) If the phrase is not found you can pass a default string in its place and we will return that instead.
     * @param string $sLang (Optional) By default we use the default language ID, however you can specifiy to load a phrase for a specific language package here.
     * @return string Phrase value associated with the 1st argument passed.
     */
    public function getPhrase($sParam)
    {
        return (isset($this->_aLanguage[$sParam]) ? $this->_aLanguage[$sParam] : '');
    }  
    
    /**
     * Sets the cache ID when caching phrases for a specific page.
     *
     */
    public function setCache()
    {
        $this->_oCache = Core::getLib('cache');
        $this->_sCacheId = $this->_oCache->set(array('locale', 'language-' . $this->getLangId() . '-page-' . str_replace('/', '-', Core::getLib('url')->getUrl()) . '-' . Core::getLib('module')->getModuleName() . '-' . str_replace('_', '-', Core::getLib('module')->getControllerName())));
        if (($this->_aPhrases = $this->_oCache->get($this->_sCacheId)))
        {        
            $this->_bIsCached = true;
        }
    }
    
    /**
     * Caches all the phrases being used on a specific page.
     *
     */
    public function cache()
    {
        if ($this->_oCache == null) {
            $this->setCache();
        }
        if (!$this->_bIsCached) {
            $this->_oCache->save($this->_sCacheId, $this->_aCache);
            $this->_bIsCached = false;
            $this->_aCache = array();
        }
    }
    
    /**
     * Translates a phrase from one language to another, if the translation exists; otherwise we return the default phrase.
     *
     * @param string $sStr Full string of the phrase.
     * @param mixed $sPrefix (Optional) Unique ID of a group of phrases.
     * @return string If a phrase is found we return the translated phrase or we simply return the default phrase string.
     */
    public function translate($sStr, $sPrefix = null)
    {
        $sPhrase = 'language.translate_' . ($sPrefix ? $sPrefix . '_' : '') . strtolower(preg_replace("/\W/i", "_", $sStr));        

        if ($this->isPhrase($sPhrase)) {
            return $this->getPhrase($sPhrase);
        }
        
        // In case this is a module ID# lets change the modules to have at least the first letter uppercase
        if ($sPrefix == 'module') {
            $sStr = ucwords($sStr);
        }        
        
        return (Core::getParam('language.lang_pack_helper') ? '{' . $sStr . '}' : $sStr);    
    }
    
    /**
     * Parses a phrase to convert ASCII rules.
     *
     * @see self::_parse()
     * @param string $sTxt Phrase to parse.
     * @return string Returns the newly parsed string.
     */
    public function parse($sTxt)
    {        
        $sTxt = preg_replace("/&\#(.*?)\;/ise", "'' . \$this->_parse('$1') . ''", $sTxt);

        return $sTxt;
    }
    
    /**
     * Converts HTML template code in phrases into actual phrases.
     *
     * @see self::_convert()
     * @param string $sPhrase Phrase to convert.
     * @return string Fully converted phrase.
     */
    public function convert($sPhrase)
    {        
        if (preg_match('/\{phrase var=(.*)\}/i', $sPhrase, $aMatches)) {
            $sPhrase = ' ' . $sPhrase . ' ';
            
            $sPhrase = preg_replace_callback('/ {phrase var=(.*?)} /is', array($this, '_convert'), $sPhrase);
            
            return trim($sPhrase);
        }
        
        return $sPhrase;
    }
    
    /**
     * Parses a phrase to convert ASCII rules.
     *
     * @see self::parse()
     * @param string $sTxt Phrase to parse.
     * @return string Returns the newly parsed string.
     */
    private function _parse($mParam)
    {
        return (isset($this->_aAscii[$mParam]) ? $this->_aAscii[$mParam] : '&#' . $mParam . ';');
    }
    
    /**
     * Loads and caches phrases for a specific module.
     *
     * @param string $sModule Module ID
     * @param bool $bForce TRUE to force loading phrases or FALSE to not.
     * @return mixed ARRAY of phrases if it already has been cached, otherwise NULL.
     */
    private function _getModuleLanguage($sModule, $bForce = false)
    {                
        if (!$bForce && isset($this->_aPhrases[$sModule])) {            
            return $this->_aPhrases[$sModule];
        }        
        
        $oCache = Core::getLib('cache');
        
        $sId = $oCache->set(array('locale', 'language_' . $this->getLangId() . '_phrase_' . $sModule));

        if (!is_array($this->_aPhrases)) {
            $this->_aPhrases = array();
        } // saw this error showing up on >2 sites, think its related to caching 

        if (!$bForce && ($this->_aPhrases[$sModule] = $oCache->get($sId))) {
            // $this->_aPhrases[$sModule] = $oCache->get($sId);            
        }
        else {            
            if (!($this->_aPhrases[$sModule] = $oCache->get($sId))) {            
                $aRows = Core::getLib('database')->select('p.var_name, p.text')
                    ->from(Core::getT('language_phrase'), 'p')
                    ->join(Core::getT('product'), 'product', 'product.product_id = p.product_id AND product.is_active = 1')
                    ->join(Core::getT('module'), 'm', "m.module_id = '" . $sModule . "' AND p.module_id = m.module_id AND m.is_active = 1")
                    ->where("p.language_id = '" . Core::getLib('database')->escape($this->getLangId()) . "'")
                    ->execute('getRows');
    
                foreach ($aRows as $aRow) {
                    $this->_aPhrases[$sModule][$aRow['var_name']] = $aRow['text'];    
                }
    
                $oCache->save($sId, $this->_aPhrases[$sModule]);    
            }
        }
    }    
    
    /**
     * Get all the phrases for a specific language package.
     *
     * @param string $sId Language ID.
     * @return array ARRAY of phrases.
     */
    private function _getPhrases($sId)
    {
        $aRows = Core::getLib('database')->select('p.var_name, p.text, m.module_id')
            ->from(Core::getT('language_phrase'), 'p')
            ->leftJoin(Core::getT('module'), 'm', 'p.module_id = m.module_id AND m.is_active = 1')
            ->join(Core::getT('product'), 'product', 'p.product_id = product.product_id AND product.is_active = 1')
            ->where("p.language_id = '" . Core::getLib('database')->escape($sId) . "'")
            ->execute('getRows');        
                
        $_aPhrasess = array();
        foreach ($aRows as $aRow) {
            $_aPhrasess[$aRow['module_id']][$aRow['var_name']] = $aRow['text'];    
        }
            
        return $_aPhrasess;
    }
    
    /**
     * Converts HTML template code in phrases into actual phrases.
     *
     * @see self::convert()
     * @param string $sPhrase Phrase to convert.
     * @return string Fully converted phrase.
     */
    private function _convert($aMatches)
    {
        $sPhrase = trim(trim($aMatches[1], "&#039;"), "'");
        $aParts = explode('.', $sPhrase);
        if (!Core::isModule($aParts[0])) {
            return '';
        }
        
        return Core::getPhrase($sPhrase);    
    }
}

?>