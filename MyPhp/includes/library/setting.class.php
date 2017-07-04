<?php
class Setting
{
    /**
     * List of all the settings.
     *
     * @var array
     */
    private $_aParams = array();
    
    /**
     * Default settings we load and their values. We only 
     * use this when installing the script the first time
     * since the database hasn't been installed yet.
     *
     * @var array
     */
    private $_aDefaults = array();
        
    
    /**
     * Class constructor. We run checks here to make sure the server setting file
     * is in place and this is where we can judge if the script has been installed
     * or not.
     *
     */
    public function __construct()
    {
        $_CONF = array();
            
        if (file_exists(DIR_INCLUDE . 'setting'. DS . 'config.sett.php')) {
            require(DIR_INCLUDE . 'setting'. DS . 'config.sett.php');
        }
        
        if (file_exists(DIR_INCLUDE . 'setting'. DS . 'common.sett.php')) {
            require(DIR_INCLUDE . 'setting'. DS . 'common.sett.php');
        }
        
        if ((!isset($_CONF['core.host'])) || (isset($_CONF['core.host']) && $_CONF['core.host'] == 'HOST_NAME')) {
            $_CONF['core.host'] = $_SERVER['HTTP_HOST'];
        }

        if ((!isset($_CONF['core.folder'])) || (isset($_CONF['core.folder']) && $_CONF['core.folder'] == 'SUB_FOLDER')) {
            $_CONF['core.folder'] = '/';                
        }
        
        $this->_aParams =$_CONF;
    }
    
    /**
     * Creates a new setting.
     *
     * @param array $mParam ARRAY of settings and values.
     * @param string $mValue Value of setting if the 1st argument is a string.
     */
    public function setParam($mParam, $mValue = null)
    {
        if (is_string($mParam)) {
            $this->_aParams[$mParam] = $mValue;
        }
        else {
            foreach ($mParam as $mKey => $sValue) {
                $this->_aParams[$mValue.'_'.$mKey] = $sValue;
            }
        }
    }
    
    public function set()
    {
        
    }
    
    /**
     * Get a setting and its value.
     *
     * @param mixed $mVar STRING name of the setting or ARRAY name of the setting.
     * @param string $sDef Default value in case the setting cannot be found.
     * @return nixed Returns the value of the setting, which can be a STRING, ARRAY, BOOL or INT.
     */
    public function getParam($mVar, $sDef = '')
    {
        if (is_array($mVar)){
            $sParam = (isset($this->_aParams[$mVar[0]][$mVar[1]]) ? $this->_aParams[$mVar[0]][$mVar[1]] : 'Missing Param: ' . $mVar[0] . '][' . $mVar[1]);
        }
        else {
            $sParam = (isset($this->_aParams[$mVar]) ? $this->_aParams[$mVar] : 'Missing Param: ' . $mVar);
        }
        
        return $sParam;
    }    
    
    /**
     * Checks to see if a setting exists or not.
     *
     * @param string $mVar Name of the setting.
     * @return bool TRUE it exists, FALSE if it does not.
     */
    public function isParam($mVar)
    {
        return (isset($this->_aParams[$mVar]) ? true : false);
    }
}
?>