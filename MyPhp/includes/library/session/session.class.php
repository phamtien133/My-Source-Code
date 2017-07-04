<?php
class Session
{
	/**
	 * Session object.
	 *
	 * @var object
	 */	
	private $_oObject = null;

	/**
	 * Class constructor which loads the session hanlder we should use.
	 *
	 * @return object
	 */	
	public function __construct()
	{
		if (!$this->_oObject){
			$sStorage = 'session.storage.session';
					
			$this->_oObject = Core::getLib($sStorage);
		}
		return $this->_oObject;
	}	
	
	/**
	 * Get session object.
	 *
	 * @return Returns the session object we loaded with the class constructor.
	 */	
	public function &getInstance()
	{
		return $this->_oObject;
	}	
}

?>