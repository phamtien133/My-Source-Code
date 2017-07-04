<?php
	function core_log($sContent,$sMode = "w+")
	{
		$sPath = dirname(__FILE__). DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR;
		$sFileName = "log.txt";
		$oFile = @fopen($sPath.$sFileName,$sMode);
		$sTime = @date('Y-m-d H:i:s',time());
		$sScriptName = @pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
		if(is_array($sContent))
		{
			ob_start();
			ob_clean();
			var_export($sContent);
			$sReturn = ob_get_contents();
			ob_flush();
			$sContent = $sReturn;
		}
		if(is_object($sContent))
		{
			$sContent = @ucfirst(get_class($sContent));
		}
		$sContentWrite = "\n".$sScriptName.'-----------------------'.$sTime.'-----------------------'."\n".$sContent."\n".'-----------------------------------------------';
		@fwrite($oFile,$sContentWrite);
		@fclose($oFile);
		return true;
	}
?>
