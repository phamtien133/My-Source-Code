<?php
class Service 
{
    /**
     * Holds the default database table we are working with in this service.
     *
     * @var string
     */
    protected $_sTable;
    
    public function __construct()
    {
        
    }

    /**
     * Extends the database object.
     *
     * @see Phpfox_Database
     * @return object
     */
    protected function database()
    {
        return Core::getLib('database');
    }
    
    /**
     * Extends the cache object
     *
     * @see Cache
     * @return object
     */
    protected function cache()
    {
        return Core::getLib('cache');
    }
    
    /**
     * Extends the pre-parsing object.
     *
     * @see Phpfox_Parse_Input
     * @return object
     */
    protected function preParse()
    {
        return Core::getLib('input');
    }
    
    /**
     * Extends the request class and returns its class object.
     *
     * @see Phpfox_Request
     * @return object
     */
    protected function request()
    {
        return Core::getLib('request');    
    }
}
?>
