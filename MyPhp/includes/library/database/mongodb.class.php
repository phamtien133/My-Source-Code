<?php
interface Database_Interface
{
    /**
     * Makes a connection to the SQL database
     *
     * @param string $sHost Hostname or IP
     * @param string $sUser User used to log into MySQL server
     * @param string $sPass Password used to log into MySQL server. This can be blank.
     * @param string $sName Name of the database.
     * @param mixed $sPort Port number (int) or false by default since we do not need to define a port.
     * @param bool $sPersistent False by default but if you need a persistent connection set this to true.
     * @return bool If we were able to connect we return true, however if it failed we return false and a error message why.
     */    
    public function connect($aParams);
    
    /**
     * Returns the SQL version
     *
     * @return string
     */    
    public function getVersion();
    
    /**
     * Returns SQL server information.
     *
     * @return string
     */
    public function getServerInfo();
    
    /**
     * Performs sql query with error reporting and logging.
     * 
     * @see mysql_query()
     * @param  string $sSql MySQL query to perform
     * @param resource $hLink MySQL resource. If nothing is passed we load the default master server.
     * @return resource Returns the MYSQL resource from the function mysql_query()
     */    
    public function query($sCommand);
}

abstract class Database_Mongodb implements Database_Interface
{
    
    private $_aData = array();
    
    public function __construct()
    {
        
    }
    
    
    public function select()
    {
        return true;
    }
    
    public function insert($sTable, $aValues = array(), $bEscape = true, $bReturnQuery = false)
    {
        if (!$aValues){
            $aValues = $this->_aData;
        }
        return $this->insert();
    }
}
?>
