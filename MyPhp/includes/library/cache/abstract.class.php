<?php
Core::getLibClass('cache.interface');
abstract class Cache_Abstract implements Cache_Interface
{
	/**
	 * Array of any special params we pass in case we need to do something.
	 *
	 * @var array
	 */
	protected $_aParams = array();	
	
	/**
	 * Array of stats holding the information about the cache files
	 *
	 * @var unknown_type
	 */
	protected $_aStats = array();
	
	/**
	 * Load all the custom params in a protected variable array.
	 *
	 * @param array $aParams Optional array of custom params
	 */
	public function __construct($aParams = array())
	{	
		$this->_aParams = $aParams;		
	}
	
	/**
	 * Returns all the static javascript and css files we have saved using the cache system.
	 *
	 * @return array List of cached files within the "file/static/" folder.
	 */
	public function getStatic()
	{
		$aFiles = array();
		if ($hDir = @opendir(DIR_FILE . 'static' . DS)){
			while ($sFile = readdir($hDir)){
				if (!preg_match("/(.*)\.(js|css)/i", $sFile)){
					continue;
				}
				
				$aFiles[] = array(
					'id' => md5($sFile),
					'name' => $sFile,
					'size' => filesize(DIR_FILE . 'static' . DS . $sFile),
					'date' => filemtime((DIR_FILE . 'static' . DS . $sFile)),
					'type' => 'Static'
				);	
			}
		}
		
		return $aFiles;
	}	
	
	/**
	 * Get all the stats about the cache storage system.
	 *
	 * @return array
	 */
	public function getStats()
	{
		return $this->_aStats;
	}
	
	/**
	 * List of all the cache files found within the "file/cache/" folder.
	 *
	 * @staticvar array Hold all the files from the cache folder.
	 * @return array Array of all the files found within the "file/cache/" folder.
	 */
	public function getAll()
	{
		static $aFiles = array();
		
		if ($aFiles){
			return $aFiles;
		}
		
		if ($hDir = @opendir(DIR_CACHE)){			
			while ($sFile = readdir($hDir)){
				if ($sFile == '.' 
					|| $sFile == '..' 
					|| $sFile == '.svn'
					|| $sFile == '.htaccess'
					|| $sFile == 'index.html'
					|| $sFile == 'debug.php'					
				){
					continue;
				}	
				
				$aFiles[] = array(
					'id' => md5($sFile),
					'name' => $sFile,
					'size' => filesize(DIR_CACHE . $sFile),
					'date' => filemtime((DIR_CACHE . $sFile)),
					'type' => 'File'
				);				
			}
			closedir($hDir);
			
			return $aFiles;
		}		
		
		return array();
	}	
	
	/**
	 * Removes a static javascript or css file.
	 *
	 * @param string $sFile Name of the static file. If nothing is passed then we delete all the files found within the "file/static/" folder.
	 * @return bool Returns true if we were able to delete the file.
	 */
	public function removeStatic($sFile = null)
	{		
		if ($sFile !== null){
			if (file_exists(DIR_FILE . 'static' . DS . $sFile)){
				unlink(DIR_FILE . 'static' . DS . $sFile);
			}
			
			return true;
		}
		
		$aFiles = Core::getLib('file')->getFiles(DIR_FILE . 'static' . DS);
		foreach ($aFiles as $sFile){
			if (!preg_match("/(.*)\.(js|css)/i", $sFile)){
				continue;
			}

			@unlink(DIR_FILE . 'static' . DS . $sFile);
		}
		
		$aFiles = Core::getLib('file')->getFiles(DIR_FILE . 'gzip' . DS);
		foreach ($aFiles as $sFile){
			if (!preg_match("/(.*)\.php/i", $sFile)){
				continue;
			}

			@unlink(DIR_FILE . 'gzip' . DS . $sFile);
		}		

		return true;
	}

	/**
	 * Stores all the cache information in the database for stats.
	 * NOTICE: We are not using this method anymore.
	 * 
	 * @deprecated Deprecated since version 2.0.1
	 * @param string $sFile Name of the file
	 * @param string $sType Type of cache file
	 * @param string $sContent serialized data to store in the database
	 * @param int $iSize Size of the serialized data
	 * @return null Exit the function we need need to skip it
	 */
	public function saveInfo($sFile, $sType, $sContent, $iSize)
	{
		return;
		
		if ($sFile == 'sql_reserved_list'){
			return;
		}	
		
		$this->removeInfo($sFile);
		$oDb = Core::getLib('database');
		$oDb->insert(Core::getT('cache'), array(
				'file_name' => $sFile,
				'cache_data' => $sContent,
				'data_size' => (int) $iSize,
				'type_id' => $sType,
				'time_stamp' => CORE_TIME
			)
		);
	}	
	
	/**
	 * Removes information from the database that the cache file is not cached any longer.
	 * NOTICE: We are not using this method anymore.
	 *
	 * @deprecated Deprecated since version 2.0.1
	 * @param bool Nothing to do, we don't use this method anymore.
	 */
	public function removeInfo($sFile)
	{
		return;
	
		$oDb = Core::getLib('database');
		$oDb->delete(Core::getT('cache'), 'file_name = \'' . $oDb->escape($sFile) . '\'');				
	}
	
	/**
	 * Get all the information of the cache file from the database to check if the file is cached.
	 *	
	 * NOTICE: We are not using this method anymore.
	 *
	 * @deprecated Deprecated since version 2.0.1	 
	 * @param int $iId ID of the cache file.
	 * @return string Data of the cache file.
	 */
	public function getData($iId)
	{
		$oDb = Core::getLib('database');
		$aCache = $oDb->select('cache_id, cache_data')
			->from(Core::getT('cache'))
			->where('cache_id = ' . (int) $iId)
			->execute('getSlaveRow');
			
		if (!isset($aCache['cache_id'])){
			return Core::setError('Unable to find this cache file.');
		}

		$sData = preg_replace("/<\?php \/\* Cached:(.*?) \*\/ \?>/i", "", $sData);
		$sData = trim($sData);
		
		return $sData;
	}
	
	/**
	 * Lock the cache system. When the cache system is locked you will not be able to cache anything new until it is unlocked.
	 *
	 * @see self::unlock()
	 */
	public function lock()
	{
		touch(DIR_CACHE . 'cache.lock');
	}
	
	/**
	 * Unlock the cache system.
	 *
	 */
	public function unlock()
	{
		if (file_exists(DIR_CACHE . 'cache.lock')){
			unlink(DIR_CACHE . 'cache.lock');
		}
	}
	
	/**
	 * Get a params value in case we pass any information to this class when it was first constructed.
	 *
	 * @param string $sParam Name of the param
	 * @return mixed Returns the params value if it exists, if not we null it.
	 */
	protected function _getParam($sParam)
	{
		return (isset($this->_aParams[$sParam]) ? $this->_aParams[$sParam] : null);
	}	
}

?>