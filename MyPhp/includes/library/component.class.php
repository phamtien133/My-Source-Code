<?php
class Component
{
    /**
     * Params that can be passed to a component other then request methods.
     *
     * @static 
     * @var array
     */
    private static $_aParams = array();    

    /**
     * Holds the current module that component belongs to.
     *
     * @var string
     */
    private $_sModule;
    
    /**
     * Holds the current component being loaded.
     *
     * @var string
     */
    private $_sComponent;    
    
    /**
     * Groups params based on unique cache ID for the component.
     *
     * @var string
     */
    private $_sCacheVar = null;
    
    /**
     * Holds the current menu for the page we are on.
     *
     * @var string
     */
    private static $_sMenuName = null;
    
    /**
     * Class constructor that sets all the variables to identify what component we are loading,
     * module it is a part of and any custom params we are passing.
     *
     * @param array $aParams ARRAY of params we are passing to the component.
     */
    public function __construct($aParams)
    {
        $this->_sModule = $aParams['sModule'];
        $this->_sComponent = $aParams['sComponent'];
        $this->setParam($aParams['aParams']);
    }
    
    /**
     * Set a param that can be used in other component so we don't pass information via get/post requests.
     *
     * @param mixed $mParams ARRAY or string of param with values.
     * @param string $sValue Value of param if argument 1 is not an ARRAY.
     */
    protected function setParam($mParams, $sValue = '')
    {
        if (!is_array($mParams)){
            $mParams = array($mParams => $sValue);
        }
        
        foreach ($mParams as $sVar => $sValue){
            self::$_aParams[$sVar] = $sValue;
        }
    }
    
    /**
     * Gets a param that is part of this component group.
     *
     * @param string $sData Param name.
     * @return mixed If param exists we return the param value otherwise we return NULL.
     */
    public function __get($sData)
    {
        if (isset(self::$_aParams[$this->_sCacheVar][$sData])) {
            return self::$_aParams[$this->_sCacheVar][$sData];
        }
        
        Core_Error::trigger('Undefined property: ' . $sData, E_USER_ERROR);
    }
    
    /**
     * Get a param for any component loaded after it was set using the method setParam()
     *
     * @see self::setParam()
     * @param string $sVar Var name of the param.
     * @param string $mDef If we cannot find the param you can provide a default value.
     * @return mixed If the param exists we return the value or NULL if nothing was found and a default value was not provided.
     */
    protected function getParam($sVar, $mDef = null)
    {
        return (isset(self::$_aParams[$sVar]) ? self::$_aParams[$sVar] : $mDef);
    }    
    
    /**
     * Clears a param that was set earlier.
     *
     * @param string $sVar
     */
    protected function clearParam($sVar)
    {
        if (isset(self::$_aParams[$sVar])){
            unset(self::$_aParams[$sVar]);    
        }
    }

    /**
     * Extends the template class and returns its class object.
     *
     * @see Phpfox_Template
     * @return object
     */
    protected function template()
    {
        return Core::getLib('template');    
    }    
    
    /**
     * Extends the url class and returns its class object.
     *
     * @see Phpfox_Url
     * @return object
     */
    protected function url()
    {
        return Core::getLib('url');    
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
    
    /**
     * Creates a groupig for a param.
     *
     * @param string $sVar The name of the group.
     * @return object This this class.
     */
    protected function param($sVar)
    {
        if (isset(self::$_aParams[$sVar])){        
            $this->_sCacheVar = $sVar;            
        }
        
        return $this;
    }
}
?>
