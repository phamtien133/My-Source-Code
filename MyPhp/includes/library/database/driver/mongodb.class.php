<?php
class Database_Driver_Mongodb
{
    
    private $_oDatabase = null;
    
    private $_oCollection = null;
    
    private $_aField = array();
    
    private $_aConds = array();
    
    private $_iLimit = 0;
    
    private $_iSkip = 0;
    
    private $_sLogFile = '';
    
    public function __construct()
    {
        $this->_sLogFile = DIR_FILE . 'log'. DS. 'database'. DS . 'error.txt'; 
    }
    
    public function connect($aConfig)
    {
        // connect to database
        try{
            $oConnection = new MongoClient($aConfig['server'], array('db' => $aConfig['database'], "username" => $aConfig['user'], "password" => $aConfig['pass']));
        }catch(Exception $e){
            echo 'Cannot connect to the database <br />';
                return false;
        }
        
        // select database
        try{
            $this->_oDatabase = $oConnection->selectDB($aConfig['database']);
        }catch(Exception $e){
            echo $e;
            return false;
        }
        return true;
    }
    
    
    public function getVersion()
    {
        
    }
    
    public function getServerInfo()
    {
        
    }
    
    public function query($sCommand)
    {
        
    }
    
    public function close()
    {
        
    }
    
    public function select($sSelect)
    {
        if(empty($sSelect))
            $this->_aField = array();
        else{
            $aField = explode(',', $sSelect);
            // loại bỏ các khoảng trắng
            foreach($aField as $iKey => $sValue){
                $aField[$iKey] = Core::getService('core.tools')->removeSpace($sValue);
            }
            $this->_aField = $aField;
        }
    }
    
    public function from($sTable, $sAlias = '')
    {
        $this->selectCollection($sTable);
    }
    
    public function where($aConds)
    {
        if (!is_array($aConds)){
            // chuyển thành dạng mảng
            $aConds = strtolower($aConds);
            $aCombine = array('and', 'or', 'in', 'not in');
            $aTemp = Core::getService('core.tools')->multiExplode($aCombine, $aConds);
            foreach($aTemp as $iKey => $sValue){
                $aTemp[$iKey] = Core::getService('core.tools')->removeSpace($sValue);
            }
            $this->_aConds = $aTemp;
        }else{
            $this->_aConds = $aConds;
        }
    }
    
    public function limit($iPage, $sLimit = null, $iCnt = null, $bReturn = false, $bCorrectMax = true)
    {
        if($iPage === null){
            $this->_iLimit = 0;
            return;
        }
        
        if ($sLimit === null && $iCnt === null){
            $this->_iLimit = $iPage;
            return;
        }
        
        if($iCnt === null ){
            $this->_iLimit = $sLimit;
            $this->_iSkip = ($iPage == 0) ? 0 : ($iPage - 1) * $sLimit ;
        }else{
            $iSkip = ($iPage == 0) ? 0 : ($iPage - 1) * $sLimit ;
            if(($iSkip + $sLimit) > $iCnt){
                $sLimit = $iCnt - $iSkip;
            }
            $this->_iSkip = $iSkip;
            $this->_iLimit = $sLimit;
        }
    }
    
    public function insert($sCollection, $aParams, $bEscape = true, $bReturnQuery = false)
    {
        try{
            $this->selectCollection($sCollection);
            $aReturns = $this->_oCollection->insert($aParams);
            return $aReturns;
        }catch(Exception $e){
            $aError = $this->_oDatabase->lastError();
            Core_Error::log($aError['err'], $this->_sLogFile);
            return false;
        }
    }
    
    public function delete($sCollection, $sQuery, $iLimit = null)
    {
        
    }
    
    public function update($sTable, $aValues = array(), $sCond = null, $bEscape = true)
    {
        
    }
    
    public function execute($sType = null, $aParams = array())
    {
        
    }
    
    public function selectCollection($sParam)
    {
        $this->_oCollection = $this->_oDatabase->$sParam; 
    }
    
    
}
?>
