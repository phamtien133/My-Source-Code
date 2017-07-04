<?php
ob_start();

define('CORE', true);
define('DS', DIRECTORY_SEPARATOR);
define('DIR', dirname(dirname(__FILE__)). DS);


if (isset($_GET['ajax_page_display']))
{
	define('CORE_IS_AJAX_PAGE', true);
}
else 
{
	define('CORE_IS_AJAX', true);
}
require(DIR . 'include' . DS . 'init.inc.php');
if (isset($_GET['ajax_page_display']))
{
	$oCache = Core::getLib('cache');
	$oAjax = Core::getLib('ajax');

	Core::run();

	$aHeaderFiles = Core::getLib('template')->getHeader(true);	
	
	if (Core::getLib('template')->sDisplayLayout)
	{			
		Core::getLib('template')->getLayout(Core::getLib('template')->sDisplayLayout);
	}	
	
	$sJs = '';
	$sCustomCss = '';
	$sLoadFiles = '';		
	$sEchoData = '';
	
	
	foreach ($aHeaderFiles as $sHeaderFile)
	{
		if (preg_match('/js_user_profile_css/i', $sHeaderFile))
		{
			$sJs .= 'profilecss: \'' . $sHeaderFile . '\', ';
			
			continue;
		}
		
		if (preg_match('/<style(.*)>(.*)<\/style>/i', $sHeaderFile))
		{
			$sCustomCss .= '\'' . strip_tags($sHeaderFile) . '\',';
			
			continue;
		}
		
		if (preg_match('/href=(["\']?([^"\'>]+)["\']?)/', $sHeaderFile, $aMatches) > 0 && strpos($aMatches[1], '.css') !== false)
		{
			$sHeaderFile = str_replace(array('"', "'"), '', $aMatches[1]);
			$sHeaderFile = substr($sHeaderFile, 0, strpos($sHeaderFile, '?') ); 
		}
		$sHeaderFile = strip_tags($sHeaderFile);
		
		$sNew = preg_replace('/\s+/','',$sHeaderFile);
		if (empty($sNew))
		{
			continue;
		}
			
		$sLoadFiles .= '\'' . str_replace("'", "\'", $sHeaderFile) . '\',';
	}		
	$sLoadFiles = rtrim($sLoadFiles, ',');
	$sCustomCss = rtrim($sCustomCss, ',');

	$sContent = Core::getLib('ajax')->getContent();
	
	$sJs .= 'content: \'' . $sContent . '\', ';
	$sJs .= 'files: [' . $sLoadFiles . '], ';
	$sJs .= 'title: \'' . str_replace("'", "\'", html_entity_decode(Core::getLib('template')->getTitle(), null, 'UTF-8')) . '\'';
	
	if (!empty($sCustomCss))
	{
		$sJs .= ', customcss: [' . $sCustomCss . ']';
	}

	/*
	$aAds = array();
	for ($i = 1; $i <= 11; $i++)
	{
		$aAds[] = Phpfox::getService('ad')->getForBlock($i);
	}
	
	if (count($aAds))
	{
		$sJs .= ', ads: {';
		foreach ($aAds as $aAd)
		{
			if (!isset($aAd['ad_id']))
			{
				continue;
			}
			$sJs .= '\'' . $aAd['location'] . '\': \'' . str_replace("'", "\'", $aAd['html_code']) . '\',';
		}
		$sJs = rtrim($sJs, ',');
		$sJs .= '}';
	}
	 * 
	 */
	
	if (isset($_GET['js_mobile_version']) && $_GET['js_mobile_version'])
	{
		if (isset($_GET['req1']) && empty($_GET['req2']))
		{
			Core::getLib('ajax')->call('$(\'#mobile_search\').show();');
		}
		else
		{
			Core::getLib('ajax')->call('$(\'#mobile_search\').hide();');
		}
	}
	
	Core::getLib('ajax')->call('$Core.page({' . $sJs . '});');
	
	echo Core::getLib('ajax')->getData();	
}
else 
{
	$oAjax = Core::getLib('ajax');
	$oAjax->process();
	echo $oAjax->getData();
	if ($oAjax->bIsModeration == true)
	{
		echo '$(window).trigger("moderation_ended");';
	}
	
	if (!isset($_REQUEST['height']) && !isset($_REQUEST['width']) && !isset($_REQUEST['no_page_update']))
	{
		// echo '$Core.updatePageHistory();';	
	}
}

ob_end_flush();
?>