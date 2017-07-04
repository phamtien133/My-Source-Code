<?php
Core::getLibClass('cache.abstract');
class Cache
{
	/**
	 * Object of the storage class.
	 *
	 * @var object
	 */
	private $_oObject = null;	
	
	/**
	 * Based on what storage system is set within the global settings this is where we load the file.
	 * You can also pass any params to the storge object.
	 *
	 * @param array $aParams Any extra params you may want to pass to the storage object.
	 */
	public function __construct($aParams = array())
	{		
		if (!$this->_oObject){
			$sStorage = (isset($aParams['storage']) ? $aParams['storage'] : Core::getParam('core.cache_storage'));
			
			switch($sStorage){
				case 'memcache':
					$sStorage = 'cache.storage.memcache';
					break;
				default:		
					$sStorage = 'cache.storage.file';
			}						
			
			$this->_oObject = Core::getLib($sStorage, $aParams);
		}
	}

	/**
	 * Return the object of the storage object.
	 *
	 * @return object Object provided by the storage class we loaded earlier.
	 */	
	public function &getInstance()
	{
		return $this->_oObject;
	}	
}