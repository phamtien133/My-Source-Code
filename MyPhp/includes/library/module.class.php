<?php
class Module 
{
    /**
     * List of all the active modules.
     *
     * @var array
     */
    private $_aModules = array();    
    
    /**
     * List of all the active services part of active modules.
     *
     * @var array
     */
    private $_aServices = array();
    
    /**
     * List of all the active blocks part of active modules.
     *
     * @var unknown_type
     */
    private $_aModuleBlocks = array();    
    
    /**
     * Holds the value of the default controller to execute.
     *
     * @var string
     */
    private $_sController = 'index';
    
    /**
     * Holds the value of the default module to execute.
     *
     * @var string
     */
    private $_sModule = 'core';
    
    /**
     * List of all the active components part of active modules.
     *
     * @var array
     */
    private static $_aComponent = array();
    
    /**
     * Object of the class loaded by the current controller being used.
     *
     * @var object
     */
    private $_oController = null;
    
    /**
     * List of controllers that are within iframes and require special rules to be loaded.
     *
     * @var array
     */
    private $_aFrames = array(
        'attachment-frame',
        'photo-frame'
    );
    
    private $_aMapUrl = array();
    
    /**
     * Defines if a template should not be loaded when calling a controller.
     *
     * @var bool
     */
    private $_bNoTemplate = false;
    
    /**
     * Cache and store all the return values from components being loaded.
     *
     * @var array
     */
    private $_aReturn = array();    
    
    /**
     * Holds all the HTML output of a controller so it can later be displayed in a specific position on the site.
     * This allows the ability to drag/drop blocks.
     *
     * @var array
     */
    private $_aCachedData = array();
    
    /**
     * Cached array of all the components. Only stored during debug mode.
     *
     * @var array
     */
    private $_aComponents = array();    
    
    /**
     * Cache ARRAY of all the custom pages the site has, which are created by admins.
     *
     * @var unknown_type
     */
    private $_aPages = array();
    
    /**
     * List of all the block locations on the site.
     *
     * @var array
     */
    private $_aBlockLocations = array();    
    
    /**
     * ARRAY can add extra blocks that are not loaded by normal means (via AdminCP).
     *
     * @var array
     */
    private $_aCallbackBlock = array();
    
    /**
     * If a user has dragged/dropped blocks this variable will store such information in an ARRAY.
     *
     * @var mixed
     */
    private $_aCacheBlockData = null;
    
    /**
     * If a user has dragged/dropped blocks this variable will store such information of the position ID (INT).
     *
     * @var mixed
     */
    private $_aCachedItemData = null;

    /**
     * If a user has dragged/dropped blocks this variable will store such information of the block ID (INT).
     *
     * @var mixed
     */    
    private $_aCachedItemDataBlock = null;    
    
    /**
     * If a user has dragged/dropped blocks this variable will store the blocks information in an ARRAY.
     *
     * @var array
     */        
    private $_aItemDataCache = array();
    
    /**
     * Holds an ARRAY of all the blocks that were moved by the end user.
     *
     * @var array
     */
    private $_aMoveBlocks = array();
    
    /**
     * ARRAY of blocks that has source code in the database, instead of PHP files.
     *
     * @var array
     */
    private $_aBlockWithSource = array();
    
    /**
     * List of cached block IDs.
     *
     * @var array
     */
    private $_aCacheBlockId = array();
    
    public $sFinalModuleCallback = '';
    
    public function __construct()
    {
        $this->initModuleList();
    }
    
    public function getModuleName()
    {
        return $this->_sModule;
    }
    
    public function getFullControllerName()
    {
        $sFullControllerName = str_replace('\\', '.', $this->_sController);
        $sFullControllerName = str_replace('/', '.', $sFullControllerName);
        return $this->_sModule. '.' . $sFullControllerName;
    }
    
    public function setModule($sVar)
    {
        $this->_sModule = $sVar;
    }
    
    public function initModuleList()
    {
        $this->_aModules = array(
            'core' => 'core',
            'crm' => 'crm',
            'log' => 'log',
            'user' => 'user',
            'category' => 'category',
            'article' => 'article',
            'comment' => 'comment',
            'vendor' => 'vendor',
            'shop' => 'shop',
            'domain' => 'domain',
            'menu' => 'menu',
            'support' => 'support',
            'slide' => 'slide',
            'ads' => 'ads',
            'report' => 'report',
            'newsletter' => 'newsletter',
            'filter' => 'filter',
            'combo' => 'combo',
            'tab' => 'tab',
            'system' => 'system',
            'imageextend' => 'imageextend',
            'producer' => 'producer',
            'discount' => 'discount',
            'filter' => 'filter',
            'response' => 'response',
            'unit' => 'unit',
            'cart' => 'cart',
            'area' => 'area',
            'print' => 'print',
            'marketing' => 'marketing',
            'store' => 'store',
            'manage' => 'manage',
            'app' => 'app',
            'display' => 'display',
            'community' => 'community',
            'advertise' => 'advertise',
            'package' => 'package',
            'api' => 'api',
            'wallet' => 'wallet',
            'card' => 'card',
            'surcharge' => 'surcharge',
            'project' => 'project',
            'contact' => 'contact',
        );
        $this->_mapUrl();
    }
    
    /**
     * Checks if a module is valid or not (IF EXISTS OR IF EXISTS AND IS VALUE)
     *
     * @param string $sModule Module name.
     * @return bool TRUE if it exists, FALSE if it does not.
     */
    public function isModule($sModule)
    {            
        $sModule = strtolower($sModule);
        if (isset($this->_aModules[$sModule])){
            return true;
        }
        return false;
    }
    
    /**
     * Get all the active modules.
     *
     * @return array
     */
    public function getModules()
    {   
        return $this->_aModules;
    }
    
        /**
     * Gets all the blocks for a specific location on a specific page.
     * 
     * @param int Position on the template.
     * @param bool (Optional) If blocks are already loaded set this to TRUE to reload them anyway.
     * @return array Returns a list of blocks for that page and in a specific order.
     */
    public function getModuleBlocks($iId, $bForce = false)
    {
        // edited the tempalte.cache instead easier for DnD
        static $aBlocks = array();    
        static $bIsOrdered = false;
        
        if (isset($aBlocks[$iId]) && $bForce === false){
            return $aBlocks[$iId];
        }        
    
        $aBlocks[$iId] = array();

        $sController = strtolower($this->_sModule . '.' . str_replace(array('\\', '/'), '.' , $this->_sController));
        
        if (isset($this->_aModuleBlocks[$sController][$iId]) || isset($this->_aModuleBlocks[str_replace('.index','',$sController)][$iId]) || isset($this->_aModuleBlocks[$this->_sModule][$iId]) || isset($this->_aModuleBlocks[''][$iId])){
            $aCachedBlocks = array();            
            if (isset($this->_aModuleBlocks[$sController][$iId])){
                foreach ($this->_aModuleBlocks[$sController][$iId] as $mKey => $mData){
                    $aCachedBlocks[$mKey] = $mData;    
                }                    
            }

            if (isset($this->_aModuleBlocks[str_replace('.index','',$sController)][$iId])){
                foreach ($this->_aModuleBlocks[str_replace('.index','',$sController)][$iId] as $mKey => $mData){
                    $aCachedBlocks[$mKey] = $mData;
                }
            }
            
            if (isset($this->_aModuleBlocks[$this->_sModule][$iId])){
                foreach ($this->_aModuleBlocks[$this->_sModule][$iId] as $mKey => $mData){
                    $aCachedBlocks[$mKey] = $mData;    
                }                
            }
            
            if (isset($this->_aModuleBlocks[''][$iId])){
                foreach ($this->_aModuleBlocks[''][$iId] as $mKey => $mData){
                    $aCachedBlocks[$mKey] = $mData;    
                }
            }

            foreach ($aCachedBlocks as $sKey => $sValue){
                if ($sKey == 'user.login-block'){
                    $aDeny = array(
                        'profile'
                    );
                    
                    // If we are logged in lets not display the login block
                    if (Core::isUser()){
                        continue;
                    }
                    
                    if (in_array(self::getModuleName(), $aDeny)){
                        continue;
                    }
                    
                    if (Core::getLib('url')->isUrl(array('user/login', 'user/register', 'profile', 'user/password/request'))){
                        continue;
                    }                    
                }
                
                $aControllerParts = array();
                if (preg_match('/\./', $sController)){
                    $aControllerParts = explode('.', $sController);                    
                }                

                if (isset($this->_aBlockWithSource[$sController][$iId][$sKey]) || isset($this->_aBlockWithSource[str_replace('.index','',$sController)][$iId][$sKey]) || isset($this->_aBlockWithSource[''][$iId][$sKey]) || (count($aControllerParts) && isset($this->_aBlockWithSource[$aControllerParts[0]][$iId][$sKey]))){
                    $oCache = Core::getLib('cache');
                    $sCacheId = $oCache->set(array('block', 'file_' . $sKey));
                    
                    if (($aCacheData = $oCache->get($sCacheId)) && (isset($aCacheData['block_id']))){
                        $this->_aCacheBlockId[md5($aCacheData['source_parsed'])] = array(
                            'block_id' => $aCacheData['block_id'],
                            'location' => $aCacheData['location']
                        );

                        $aBlocks[$iId][] = array(
                            $aCacheData['source_parsed']
                        );
                    }
                }else {                
                    $aBlocks[$iId][] = $sKey;
                }            
            }    
        }        
        
        if (isset($this->_aCallbackBlock[$iId])){
            $aBlocks[$iId] = array_merge($aBlocks[$iId], array($this->_aCallbackBlock[$iId]));
        }

        return $aBlocks[$iId];
    }
    
    /**
     * Sets the controller for the page we are on. This method controlls what component to load, which 
     * will be used to display the content on that page.
     *
     * @param string $sController (Optional) We find the controller by default, however you can override our default findings by passing the name of the controller with this argument.
     */
    public function setController($sController = '')
    {    
        $oUrl = Core::getLib('url');
        $oReq = Core::getLib('request');        
        //$oPage = Core::getService('page');
        $sDomainPath = $oReq->get('domain-path');
        $sType = $oReq->get('type');
        $sReq1 = $oReq->get('req1');
        $sReq2 = $oReq->get('req2');
        $oSession = Core::getLib('session');
        if ($sReq1 == 'user' && ($sReq2 == 'logout' || $sReq2 == 'login') ) {
            $this->_sModule = 'user';
            $this->_sController = $sReq2;
            return;
        }
        elseif ($oSession->getArray('session-user', 'id') < 1) {
            $this->_sModule = 'user';
            $this->_sController = 'login';
            return;
        }
        if (Core::getParam('core.main_server') == 'cms.') {
            if($oSession->getArray('session-user', 'priority_group') == -1) {
                $this->_sModule = 'core';
                $this->_sController = 'deny';
                return;
            }
        }
        elseif(Core::getParam('core.main_server') == 'sup.') {
            $iCountTime = $oSession->get('session-scount');
            if($oSession->getArray('session-user', 'priority_group') == -1) {
                if ($iCountTime == 1) {
                    $this->_sModule = 'core';
                    $this->_sController = 'deny';
                    $oSession->set('session-scount', -1); // tránh bị redirect liên tục khi không có phân quyền.
                    return;
                }
                else {
                    $this->_sModule = 'core';
                    $this->_sController = 'select';
                    $oSession->set('session-scount', 1); // tránh bị redirect liên tục khi không có phân quyền.
                    return;
                }
            }
        }

        $iVendorSession = $oSession->get('session-vendor');
        if (($iVendorSession > 0 && Core::getParam('core.main_server') == 'cms.')) {
            $this->_sModule = 'core';
            $this->_sController = 'confirm';
            return;
        }
        if (($iVendorSession == -1 && Core::getParam('core.main_server') == 'sup.') || (Core::getParam('core.main_server') == 'sup.' && $iVendorSession < 1) ) {
            // chuyển từ admin qua sup thì chuyển thẳng vào trang chọn siêu thị quản lý, và hiển thị thông báo.
            $this->_sModule = 'core';
            $this->_sController = 'select';
            return;
        }
        if (!empty($sController)){
            $aParts = explode('.', $sController);            
            $this->_sModule = $aParts[0];
            $this->_sController = substr_replace($sController, '', 0, strlen($this->_sModule . '_'));           
            
            $this->getController();
            return;
        }
        
        $this->_sModule = 'core';
        $this->_sController ='index';
        $bIsPass = false;
        if (!empty($sType) || !empty($sReq1)) {
            if (isset($this->_aMapUrl[$sType])) {
                $aTmp = explode('.', $this->_aMapUrl[$sType]);
                // return module and controller
                $this->_sModule = $aTmp[0];
                $this->_sController = $aTmp[1];
                $oReq->set('type', $aTmp[0]);
               
                $bIsPass = true;
            }
            if (!$bIsPass && isset($this->_aMapUrl[$sReq1])) {
                $aTmp = explode('.', $this->_aMapUrl[$sReq1]);
                // return module and controller
                $this->_sModule = $aTmp[0];
                $this->_sController = $aTmp[1]; 
                $oReq->set('type', $aTmp[0]);
                $bIsPass = true;
            }
            else {
                $this->_sModule = (!empty($sReq1) ? strtolower($sReq1) : 'core');
                if ($oReq->get('req2') && file_exists(DIR_MODULE. $this->_sModule. DS . 'component'. DS . 'controller' . DS . strtolower($oReq->get('req2')) . '.class.php')) {
                    $this->_sController = strtolower($oReq->get('req2'));
                }
                elseif ($oReq->get('req3') && file_exists(DIR_MODULE. $this->_sModule. DS . 'component'. DS . 'controller' . DS . strtolower($oReq->get('req2')) . DS . strtolower($oReq->get('req3')). '.class.php')) {
                    
                    $this->_sController = strtolower($oReq->get('req2')) . '.'. strtolower($oReq->get('req3'));
                }
                elseif ($oReq->get('req2') && file_exists(DIR_MODULE. $this->_sModule. DS . 'component'. DS . 'controller' . DS . strtolower($oReq->get('req2')) . DS . 'index.class.php')) {
                    
                    $this->_sController = strtolower($oReq->get('req2')) . '.index';
                }
            }
        }
        // check file exist or not. if not exist, redirect to build page.
        $sDir = DIR_MODULE . $this->_sModule . DS;
        if ($bIsPass && $this->_sModule != 'core' 
            && $this->_sController != 'build'
            && !file_exists($sDir . 'component'. DS . 'controller' . DS . strtolower($this->_sController) . '.class.php')){
                $this->_sModule = 'core';
                $this->_sController = 'build';
        }
    }
    
    /**
     * Loads and outputs the current page based on the controller we loaded with the method setController().
     * 
     * @see self::setController()
     */
    public function getController()
    {        
        // Get the component
        $this->_oController = $this->getComponent($this->_sModule . '.' . $this->_sController, array('bNoTemplate' => true), 'controller');
    }
    
    /**
     * Gets the controllers template. We do this automatically since each controller has a specific template that it loads to
     * output data to the site.
     *
     * @return mixed NULL if we were able to load a template and FALSE if such a template does not exist.
     */
    public function getControllerTemplate()
    {        
        $sClass = $this->_sModule . '.controller.' . $this->_sController;
        if (isset($this->_aReturn[$sClass]) && $this->_aReturn[$sClass] === false){
            return false;
        }
        
        $sControllerTemplate = Core::getLib('template')->getControllerTemplate();
        if (!empty($sControllerTemplate)) {
            Core::getLib('template')->getTemplate($sControllerTemplate);
        }
        else {
            // Get the template and display its content for the specific controller component
            Core::getLib('template')->getTemplate($sClass);
        }

        // Check if the component we have loaded has the clean() method
        if (is_object($this->_oController) && method_exists($this->_oController, 'clean')){
            // This method is used to clean out any garbage we don't need later on in the script. In most cases Template assigns.
            $this->_oController->clean();
        }
    }
    
    /**
     * Loads a service class. Service classes are module based class that interact with the database
     * and runs general PHP logic that is not needed to be found with components.
     *
     * @param string $sClass Name of the service class to load.
     * @param array $aParams (Optional) ARRAY of params to pass to that class.
     * @return mixed On success we return the class object, on failure we return FALSE.
     */
    public function getService($sClass, $aParams = array())
    {
        if (isset($this->_aServices[$sClass])){
            return $this->_aServices[$sClass];    
        }        

        if (preg_match('/\./', $sClass) && ($aParts = explode('.', $sClass)) && isset($aParts[1])){
            $sModule = $aParts[0];
            $sService = $aParts[1];            
        }else {
            $sModule = $sClass;
            $sService = $sClass;
        }
        
        if ( !isset($this->_aModules[$sModule])){
            echo 'Calling a Service from an invalid Module. Make sure the module is valid or set to active.';
            return false;
        }            
        
        if ($sClass == 'core.currency.process'){
            $sFile = DIR_MODULE . 'core' . DS . 'service' . DS . 'currency' . DS . 'process.class.php';        
            $sModule = 'Core';
            $sService = 'Currency_Process';    
        }else {
            $sFile = DIR_MODULE . $sModule . DS . 'service' . DS . $sService . '.class.php';
        }        
        
        if (!file_exists($sFile)){
            if (isset($aParts[2])){
                $sFile = DIR_MODULE . $sModule . DS . 'service' . DS . $sService . DS . $aParts[2] . '.class.php';
                
                if (!file_exists($sFile)){
                    if (isset($aParts[3]) && file_exists(DIR_MODULE . $sModule . DS . 'service' . DS . $sService . DS . $aParts[2] . DS . $aParts[3] . '.class.php')){
                        $sFile = DIR_MODULE . $sModule . DS . 'service' . DS . $sService . DS . $aParts[2] . DS . $aParts[3] . '.class.php';                
                        $sService .= '_' . $aParts[2] . '_' . $aParts[3];
                    }else {                    
                        $sFile = DIR_MODULE . $sModule . DS . 'service' . DS . $sService . DS . $aParts[2] . DS . $aParts[2] . '.class.php';                
                        $sService .= '_' . $aParts[2] . '_' . $aParts[2];
                    }
                }else {
                    $sService .= '_' . $aParts[2];
                }
            }else {
                $sFile = DIR_MODULE . $sModule . DS . 'service' . DS . $sService . DS . $sService . '.class.php';    
                $sService .= '_' . $sService;
            }
        }        
        
        if (!file_exists($sFile)){
            echo 'Unable to load service class ' .$sService;
            return false;
        }

        require($sFile);
        
        $this->_aServices[$sClass] = Core::getObject($sModule . '_service_' . $sService);        
    
        return $this->_aServices[$sClass];
    }
    /**
     * Loads a module component. Components are the building blocks of the site and
     * include controllers which build up the pages we see and blocks that build up the controllers.
     *
     * @param string $sClass Name of the component to load.
     * @param array $aParams (Optional) Custom params you can pass to the component.
     * @param string $sType (Optional) Identify if this component is a block or a controller.
     * @param boolean $bTemplateParams Assign $aParams to the template
     * @return mixed Return the component object if it exists, otherwise FALSE.
     */
    public function getComponent($sClass, $aParams = array(), $sType = 'block', $bTemplateParams = false)
    {

        // permission - END
        if ($sType == 'ajax' && strpos($sClass, '.') === false){
            $sClass = $sClass . '.ajax';
        }

        $aParts = explode('.', $sClass);
        
        $sModule = $aParts[0];        
        $sComponent = $sType . DS . substr_replace(str_replace('.', DS, $sClass), '', 0, strlen($sModule . DS));        
        
        if ($sType == 'controller') {
            $this->_sModule = $sModule;
            $this->_sController = substr_replace(str_replace('.', DS, $sClass), '', 0, strlen($sModule . DS));
            
        }
        
        static $sBlockName = '';
        
        if ($sModule == 'custom'){
            if (preg_match('/block\\' . DS . 'cf_(.*)/i', $sComponent, $aCustomMatch)){
                $aParams = array(
                    'type_id' => 'user_main',
                    'template' => 'content',
                    'custom_field_id' => $aCustomMatch[1]
                );
                $sBlockName = 'custom_cf_' . $aCustomMatch[1];
                $sComponent = 'block' . DS . 'display';
                $sClass = 'custom.display';                
            }
        }        
        
        $sMethod = $sModule . '_component_' . str_replace(DS, '_', $sComponent) . '_process';            
        
        $sHash = md5($sClass . $sType);            

        if (!isset($this->_aModules[$sModule])){            
            return false;
        }

        if (isset($this->_aComponent[$sHash])) {
            $this->_aComponent[$sHash]->__construct(array('sModule' => $sModule, 'sComponent' => $sComponent, 'aParams' => $aParams));
        }else {
            $sClassFile = DIR_MODULE . $sModule . DS . 'component'. DS . $sComponent . '.class.php'; 
            if (!file_exists($sClassFile) && isset($aParts[1])){
                $sClassFile = DIR_MODULE . $sModule . DS . 'component'. DS . $sComponent . DS . $aParts[1] . '.class.php';
            }
            // Lets check if there is such a component
            if (!file_exists($sClassFile)){
                // Opps, for some reason we have loaded an invalid component. Lets send back info to the dev.
                //Faile to load component
                return false;
            }
            
            // Require the component
            require($sClassFile);
            // Get the object     
       
            $this->_aComponent[$sHash] = Core::getObject($sModule . '_component_' . str_replace(DS, '_', $sComponent), array('sModule' => $sModule, 'sComponent' => $sComponent, 'aParams' => $aParams));
        }
        
        $mReturn = 'blank';
        if ($sType != 'ajax'){
            $mReturn = $this->_aComponent[$sHash]->process();
        }
        
        $this->_aReturn[$sClass] = $mReturn;
        
        // If we return the component as 'false' then there is no need to display it.
        if ((is_bool($mReturn) && !$mReturn) || $this->_bNoTemplate) {
            if ($this->_bNoTemplate) {
                $this->_bNoTemplate = false;
            }
            
            return $this->_aComponent[$sHash];    
        }
        
        /* Should we pass the params to the template? */
        if ($bTemplateParams){
            Core::getLib('template')->assign($aParams);
        }
        // Check if we don't want to display a template
        if (!isset($aParams['bNoTemplate']) && $mReturn != 'blank') {
            if ($mReturn && is_string($mReturn)) {
                $sBlockShowName = ($sModule == 'custom' && !empty($sBlockName)) ? $sBlockName : ucwords(str_replace('.', ' ', $sClass));
                $sBlockBorderJsId = ($sModule == 'custom' && !empty($sBlockName)) ? $sBlockName : str_replace('.', '_', $sClass);
                
                $sBlockPath = $sModule . '.' . str_replace( 'block' . DS, '', $sComponent);
                
                $bCanMove = (!isset($this->_aMoveBlocks[ $this->_sModule . '.' . $this->_sController][$sBlockPath] )) || (isset($this->_aMoveBlocks[ $this->_sModule . '.' . $this->_sController][$sBlockPath] ) && $this->_aMoveBlocks[ $this->_sModule . '.' . $this->_sController][$sBlockPath] == true);
                
                Core::getLib('template')->assign(array(
                        'sBlockShowName' => $sBlockShowName,
                        'sBlockBorderJsId' => $sBlockBorderJsId,
                        'bCanMove' => $bCanMove,
                        'sClass' => $sClass
                    )
                )->setLayout($mReturn);
            }    
            
            if (!is_array($mReturn)) {
                $sComponentTemplate = $sModule . '.' . str_replace(DS, '.', $sComponent);
                Core::getLib('template')->getTemplate($sComponentTemplate);
            }
            
            // Check if the component we have loaded has the clean() method
            if (is_object($this->_aComponent[$sHash]) && method_exists($this->_aComponent[$sHash], 'clean')) {
                // This method is used to clean out any garbage we don't need later on in the script. In most cases Template assigns.
                $this->_aComponent[$sHash]->clean();
            }
        }
        return $this->_aComponent[$sHash];
    }
    
    private function _mapUrl()
    {
        $this->_aMapUrl = array(
            "trang_chu" => "core.index",
            "de_tai" => "category.index",
            "bai_viet" => "article.view",
            "so_sanh_san_pham" => "artile.compare",
            "dang_nhap" => "user.login",
            "dang_xuat" => "user.logout",
            "doi_mat_khau" => "user.changepwd",
            "quen_mat_khau" => "user.forgot",
            "bao_loi" => "core.error",
            "tim_kiem" => "search.index",
            "search" => "search.index",
            "san_pham_ban_chay" => "artile.topsale",
            "san_pham_xem_nhieu" => "artile.topview",
            "shop_chi_tiet" => "shop.view",
            "shop_thanh_toan" => "shop.checkout",
            "shop_order" => "shop.order",
            "tags" => "search.tags",
            "dang_ky" => "user.register",
            "doi_thong_tin" => "user.edit",
            "trang_ca_nhan" => "user.info",
            'thanh_vien'    => 'user.profile',
            'cong-dong' => 'user.community',
            'vendor'        => 'vendor.index',
            'area'          => 'area.index',
            'project' => 'project.index',
            'contact' => 'contact.index',
        );
    }
    
}
?>