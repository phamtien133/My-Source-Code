<?php
if(Core::getParam(array('db', 'driver')) == 'mongodb')
{
    //Core::getLibClass('database.mongodb');
}
else
{
    Core::getLibClass('database.dba');
}
class Database
{
    /**
     * Holds the drivers object
     *
     * @var object
     */
    private $_oObject = null;

    /**
     * Loads and initiates the SQL driver that we need to use.
     *
     */
    public function __construct()
    {
        if (!$this->_oObject){
            $sDriver = '';
            switch(Core::getParam(array('db', 'driver'))){
                case 'mysqli':
                    $sDriver = 'database.driver.mysqli';
                    break;
                case 'mongodb':
                    $sDriver = 'database.driver.mongodb';
                    break;
                default:
                    $sDriver = 'database.driver.mysql';
                    break;
            }
            
            $this->_oObject = Core::getLib($sDriver);
            
            if(Core::getParam(array('db', 'driver')) == 'mongodb'){
                $this->_oObject->connect(Core::getParam(array('mongodb', 'config')));
            }else{
                $this->_oObject->connect(Core::getParam(array('db', 'host')), Core::getParam(array('db', 'user')), Core::getParam(array('db', 'pass')), Core::getParam(array('db', 'name')));
                $this->_oObject->query('SET NAMES UTF8');
            }
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
?>