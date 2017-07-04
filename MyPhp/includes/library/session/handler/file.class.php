<?php
class Session_Handler_File
{
	/**
	 * Path to save a session.
	 *
	 * @var string
	 */
	private $_sSavePath = '';
	
	/**
	 * Session prefix.
	 *
	 * @var string
	 */
	private $_sPrefix = 'sess_';
	
	/**
	 * Start the session.
	 *
	 * @return mixed NULL if no errors, however FALSE if session cannot start.
	 */
	public function init()
	{		
		session_set_save_handler(
				array($this, 'open'),
				array($this, 'close'),
				array($this, 'read'),
				array($this, 'write'),
				array($this, 'destroy'),
				array($this, 'gc')			
		);		
		
		if (CORE_SAFE_MODE){
			$this->_sSavePath = DIR_CACHE;	
		}else {
			$sSessionSavePath = (CORE_OPEN_BASE_DIR ? DIR_FILE . 'session' . DS : session_save_path());
			if (empty($sSessionSavePath) || (!empty($sSessionSavePath) && !Core::getLib('file')->isWritable($sSessionSavePath))){
				$this->_sSavePath = rtrim(Core::getLib('file')->getTempDir(), DS) . DS;
			}else {
				$this->_sSavePath = rtrim($sSessionSavePath, DS) . DS;
			}
		}
		
		if (!Core::getLib('file')->isWritable($this->_sSavePath)){
            Core_Error::set('session', 'Session path is not wriable: ' . $this->_sSavePath);
			return false;
		}

		if(!isset($_SESSION)){
			session_start();	
		}
	}
	
	/**
	 * Open a session file.
	 *
	 * @return bool Always TRUE.
	 */
	public function open()
	{	  
		return true;
	}
	
	/**
	 * Close a session file.
	 *
	 * @return bool Always TRUE.
	 */
	public function close()
	{
		return true;
	}
	
	/**
	 * Read a session file.
	 *
	 * @param int $iId File ID.
	 * @return mixed FALSE if not exists, STRING if file exists.
	 */
	public function read($iId)
	{
        //echo 11111111111111;
		if (!file_exists($this->_sSavePath . $this->_sPrefix . $iId)){
			return false;
		}
		
		return (string) file_get_contents($this->_sSavePath . $this->_sPrefix . $iId);
	}
	
	/**
	 * Write to session file.
	 *
	 * @param int $iId Session ID.
	 * @param string $mData Session Data.
	 * @return bool TRUE if success, FALSE on failure.
	 */
	public function write($iId, $mData)
	{  	  
		if ($hFp = @fopen($this->_sSavePath . $this->_sPrefix . $iId, "w")) {
	    	$bReturn = fwrite($hFp, $mData);
	    	fclose($hFp);
	    	
	    	return $bReturn;
	  	} else {
	    	return(false);
	  	}	
	}
	
	/**
	 * Remove session file.
	 *
	 * @param int $iId Session ID.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function destroy($iId)
	{
		return(@unlink($this->_sSavePath . $this->_sPrefix . $iId));
	}
	
	/**
	 * Garbage collecting.
	 *
	 * @param int $iMaxLifetime Define how long a session can exist on the server.
	 * @return bool Always TRUE.
	 */
	public function gc($iMaxLifetime)
	{
		foreach (glob($this->_sSavePath . $this->_sPrefix . '*') as $sFilename) {
	    	if (filemtime($sFilename) + $iMaxLifetime < time()) {
	      		@unlink($sFilename);
	    	}
	  	}
	  	return true;
	}	
}

?>