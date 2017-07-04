<?php
class File_Minimize
{
	public function __construct()
	{		
	}
	
	public function js($sContent)
	{		
		if (file_exists(DIR_LIB . 'jsmin/jsmin.class.php'))
		{
			require_once(DIR_LIB . 'jsmin/jsmin.class.php');		
			
			return JSMin::minify($sContent);
		}
		
		return $sContent;
	}
	
	public function css($sContent)
	{
		$sContent = preg_replace_callback('/url\([\'"](.*?)[\'"]\)/is', array($this, '_replaceImages'), $sContent);		
		$sContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $sContent);
		$sContent = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $sContent);				
		
		return $sContent;
	}
	
	public function _replaceImages($aMatches)
	{
		$sMatch = trim($aMatches[1]);
		$sMatch = str_replace('../image/', '', $sMatch);
		
		return 'url(\'' . Core::getLib('template')->getStyle('image', $sMatch) . '\')';
	}

	/*
	 * @param array|string $aFiles What to minify, may be a string or an array of strings
	 * @param string $sVersion To add to the url to output
	 * @param boolean $bIsJS
	 * @param boolean $bDoInit In some cases we may not want to add the Core.init() to the JS file
	 * @param boolean $bReturn Sometimes we just need to minify and not to store the file anywhere specific
	 * @param boolean $bReplaceUrl When creating the master files it is not needed to replace the url in CSS: url(/../../
	 */ 
    public function minify($aFiles, $sVersion, $bIsJS = true, $bDoInit = false, $bReturn = false, $bReplaceUrl = true)
    {
        static $oFormat = null;
        
        if (!isset($oFormat))
        {
            $oFormat = Core::getLib('format');
        }
        if (!is_array($aFiles))
        {
			$aFiles = array($aFiles);
		}
        $sHash = md5(implode($aFiles) . $sVersion);
        $sNameMd5 = md5(implode($aFiles) . $sVersion) . ($bIsJS ? '.js' : '.css');
        $sFilePath = DIR_FILE . 'static' . DS . $sNameMd5;
        $sUrl = Core::getParam('core.path') .'file/static/'. $sNameMd5;
        
        $bExists = false;
        if (Core::getParam('core.allow_cdn') && Core::getParam('core.push_jscss_to_cdn'))
        {
        	$sCacheId = Core::getLib('cache')->set(array('jscss', $sHash));
        	if ((Core::getLib('cache')->get($sCacheId)))
        	{
        		$bExists = true;
        	}
        }
        else
        {
        	$bExists = file_exists($sFilePath);
        }

        if ($bExists)
        {
            if (Core::getParam('core.allow_cdn') && Core::getParam('core.push_jscss_to_cdn'))
            {
                $sUrl = Core::getLib('cdn')->getUrl($sNameMd5);
            }
        }
        else
        {
            $sMinified = '';
            if ($bIsJS)
            {
                foreach ($aFiles as $sFile)
                {
					$sOriginal = file_get_contents(DIR . $sFile);    
					$oJsMin = new JSMin($sOriginal);
					$sCompressed = $oJsMin->min();                    
					// $sCompressed = $oFormat->helpJS($sCompressed);                    
					$sMinified .= "\n /* {$sFile} */" . $sCompressed;					
                }
            }
            else
            {
				$sHomeThemePath = (Core::getParam('core.force_https_secure_pages') ? 'https://' : 'http://') . Core::getParam('core.host') . Core::getParam('core.folder') . 'theme';
				
                foreach ($aFiles as $sFile)
                {
					$sOriginal = file_get_contents(DIR . $sFile);
					$sCompressed = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), ' ', $sOriginal);
					$sPathTo = substr($sFile, 0, strrpos($sFile, '/'));
					$sPathTo = substr($sPathTo, 0, strrpos($sPathTo, '/'));

					//if (Core::getParam('core.allow_cdn') && Core::getParam('core.push_jscss_to_cdn') && defined('PHPFOX_IS_HOSTED_SCRIPT'))
//					{
//						$sCompressed = str_replace('url(\'..', 'url(\'' . Core::getCdnPath() . '' . $sPathTo, $sCompressed, $iCount);
//						$sCompressed = str_replace('url("..', 'url("' . Core::getCdnPath() . '' . $sPathTo, $sCompressed, $iCount);
//						$sCompressed = str_replace('url(..', 'url(' . Core::getCdnPath() . '' . $sPathTo, $sCompressed, $iCount);
//					}
//					else 
                    if ($bReplaceUrl == true)
					{                    
						$sCompressed = str_replace('url(\'..', 'url(\'../../' . $sPathTo, $sCompressed, $iCount);
						$sCompressed = str_replace('url("..', 'url("../../' . $sPathTo, $sCompressed, $iCount);
						$sCompressed = str_replace('url(..', 'url(../../' . $sPathTo, $sCompressed, $iCount);
						$sCompressed = str_replace('../../theme', ''.$sHomeThemePath, $sCompressed, $iCount);
					}
											
					if ($bReplaceUrl == true)
					{
						$sCompressed = str_replace('css/', 'image/', $sCompressed);
					}					
                    $sMinified .= $sCompressed;
                }
            }
            if ($bReturn == true) return $sMinified;
            
            if ($bIsJS && $bDoInit)
            {
                 $sMinified .= "\n".'$Core.init();';
            }
            file_put_contents($sFilePath, $sMinified);

            // if cdn enabled put it in cdn as well here
            if (Core::getParam('core.allow_cdn') && Core::getParam('core.push_jscss_to_cdn'))
            {
            	Core::getLib('cache')->save($sCacheId, '1');
                Core::getLib('cdn')->put($sFilePath, $sNameMd5);      
                $sUrl = Core::getLib('cdn')->getUrl($sNameMd5);
            }
        }
        
        return $sUrl . $sVersion;
    }
    
}
