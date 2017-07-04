<?php
class Locale
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
	 * @see Phpfox_Cache
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
		
		$this->_aLanguages['en'] = true;
		
		$sLangId = $oCache->set(array('locale', 'language_' . $this->getLangId()));	
		
		define('CORE_LOCALE_LOADED', true);
	}
	
	/**
	 * Get all the information provided on the current language package being used.
	 *
	 * @return array
	 */
	public function getLang()
	{		
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
		//if (Core::isUser())
//		{
//			$sLanguageId = Core::getUserBy('language_id');
//			if (empty($sLanguageId))
//			{
//				$sLanguageId = $this->autoLoadLanguage();	
//			}
//		}
//		else 
//		{
//			if (($sLanguageId = Core::getLib('session')->get('language_id')))
//			{
//				
//			}
//			else 
//			{
//				$sLanguageId = $this->autoLoadLanguage();
//			}
//		}
		
		//if (!isset($this->_aLanguages[$sLanguageId]))
//		{
			$sLanguageId = 'en';
//		}		
				
		return $sLanguageId;
	}
	
	public function autoLoadLanguage()
	{
		static $sNewLanguage = null;
		
		if ($sNewLanguage !== null)
		{
			return $sNewLanguage;
		}
		
		if (Core::getParam('language.auto_detect_language_on_ip') && Core::getParam('core.ip_infodb_api_key') != '')
		{		
			if (($sLanguageId = Core::getLib('session')->get('language_id')))
			{
				$sNewLanguage = $sLanguageId;

				return $sNewLanguage;
			}			
			else
			{
				//$sUrl = 'http://api.ipinfodb.com/v2/ip_query.php?ip=' . Phpfox::getLib('request')->getIp() . '&key=' . Phpfox::getParam('core.ip_infodb_api_key');
				$sUrl = 'http://api.ipinfodb.com/v3/ip-city/?key='.Core::getParam('core.ip_infodb_api_key').'&ip='.Core::getLib('request')->getIp().'&format=xml';
				if (function_exists('file_get_contents') && ini_get('allow_url_fopen'))
				{
					$sXML = file_get_contents($sUrl);
				}
				else
				{
					$sXML = Core::getLib('request')->send($sUrl, array(), 'GET');
				}
				$aCallback = Core::getLib('xml.parser')->parse($sXML, 'UTF-8');			

				if (!empty($aCallback['countryCode']))
				{
					foreach ($this->_aLanguages as $sLangId => $bLang)
					{
						if (strtolower($sLangId) == strtolower($aCallback['countryCode']))
						{
							Core::getLib('session')->set('language_id', $sLangId);

							$sNewLanguage = $sLangId;

							return $sNewLanguage;
						}
					}
				}
			}
			$sLangId = Core::getParam('core.default_lang_id');
			Core::getLib('session')->set('language_id', $sLangId);
		}
		
		$sNewLanguage = Core::getParam('core.default_lang_id');
		
		return $sNewLanguage;
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
	 * Phpfox::getPhrase('foo.bar');
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
	public function getPhrase($sParam, $aParams = array(), $bNoDebug = false, $sDefault = null, $sLang = '')
	{
		if (strpos($sParam, '.') === false)
		{
			if ((Core::getParam('language.lang_pack_helper') && !$bNoDebug))
			{
				return "#{$sParam}#";
			}
			return '';
		}
		
		list($sModule, $sVar) = explode('.', $sParam);		

		
		if ($sLang != null && $sLang != '' && ($sLang != $this->getLangId()) && isset($this->_aLanguages[$sLang]))
		{
			$this->_sOverride = $sLang;
			$this->_aPhrases = array();
			$this->_getModuleLanguage($sModule);
			$sPhrase = $this->getPhrase($sParam, $aParams);
			$this->_sOverride = '';
			$this->_aPhrases = array();
			
			return $sPhrase;
		}
		
		if (!isset($this->_aPhrases[$sModule]))
		{
			$this->_getModuleLanguage($sModule);			
		}
		
		$bPassed = true;
		if (!isset($this->_aPhrases[$sModule][$sVar]))
		{
			$bPassed = false;		
			
			if (!$bPassed)
			{				
				if ($sDefault !== null)
				{
					return $sDefault;
				}
				
				if (CORE_DEBUG)
				{
					Core_Core_Error::trigger('Unable to find the phrase: ' . strip_tags($sParam));
				}
				return '';
			}
		}		
		
		$sPhrase = $this->_aPhrases[$sModule][$sVar] ;		
	
		if (isset($aParams['user']))
		{
			if (!is_array($aParams['user']))
			{
				Core_Core_Error::trigger('The key "user" needs to be an array of the users details.');
			}
			
			$sUserPrefix = (isset($aParams['user_prefix']) ? $aParams['user_prefix'] : '');	
			
			$aUser = $aParams['user'];
			$aUser['user_link'] = '<a href="' . Core::getLib('url')->makeUrl($aUser[$sUserPrefix . 'user_name']) . '">' . Core::getLib('output')->clean($aUser[$sUserPrefix . 'full_name']) . '</a>';
			unset($aParams['user']);
			$aParams = array_merge($aParams, $aUser);
		}
		
		if ($aParams)
		{
			$aFind = array();
			$aReplace = array();
			foreach ($aParams as $sKey => $sValue)
			{
				if (is_array($sValue))
				{
					continue;
				}
				$aFind[] = '{' . $sKey . '}';				
				$aReplace[] = '' . $sValue . '';		
			}		
			
			$sPhrase = str_replace($aFind, $aReplace, $sPhrase);
		}			
			
		if (isset($this->_aRules[$sModule . '.' . $sVar]))
		{
			$sEval = '';
			$iCnt = 0;
			foreach ($this->_aRules[$sModule . '.' . $sVar] as $aRule)
			{
				$iCnt++;
				
				$aFind = array();
				$aReplace = array();
				foreach ($aParams as $sKey => $sValue)
				{
					$aFind[] = '/{' . $sKey . '}/i';					
					$aReplace[] = '' . $sValue . '';		
				}			
				
				$aRule['rule'] = preg_replace($aFind, $aReplace, $aRule['rule']);
				$aRule['rule_value'] = preg_replace($aFind, $aReplace, $aRule['rule_value']);				
				
				$sEval .= ($iCnt === 1 ? 'if' : 'elseif') . ' (' . $aRule['rule'] . ') { $sPhrase = \'' . str_replace("'", "\'", $aRule['rule_value']) . '\'; } ';
			}			
			
			eval($sEval);
		}
		
		$sPhrase = ((Core::getParam('language.lang_pack_helper') && !$bNoDebug) ? '{' . $sPhrase . '}' : $sPhrase);
		
		if (isset($aParams['phpfox_squote']))
		{
			$sPhrase = str_replace("'", "\\'", $sPhrase);
		}
		
		if ($sParam == 'user.full_name' && Core::getParam('user.display_or_full_name') == 'display_name')
		{
			return Core::getPhrase('user.display_name');
		}
		
		$this->_aPhraseHistory[md5($sPhrase)] = array('var_name' => $sParam, 'params' => $aParams);
		
		return $sPhrase;
	}
	
	public function getPhraseHistory($sPhraseValue, $sLanguageId = null)
	{
        
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
		if ($this->_oCache == null)
		{
			$this->setCache();
		}
		if (!$this->_bIsCached)
		{
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

		if ($this->isPhrase($sPhrase))
		{
			return $this->getPhrase($sPhrase);
		}
		
		// In case this is a module ID# lets change the modules to have at least the first letter uppercase
		if ($sPrefix == 'module')
		{
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
		if (preg_match('/\{phrase var=(.*)\}/i', $sPhrase, $aMatches))
		{
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
		
	}	
	
	/**
	 * Get all the phrases for a specific language package.
	 *
	 * @param string $sId Language ID.
	 * @return array ARRAY of phrases.
	 */
	private function _getPhrases($sId)
	{
		
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
		if (!Core::isModule($aParts[0]))
		{
			return '';
		}
		
		return Core::getPhrase($sPhrase);	
	}
}

?>
