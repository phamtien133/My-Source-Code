<?php
class Core_Service_Minify extends Service
{
    private $_iInvisible = false;
    public function __construct()
    {
        
    }
    
    /**
    * minify css and javascript
    * 
    * @param mixed $aParam
    */
    public function minify($aParam = array())
    {
        $oTools = Core::getService('core.tools');
        $oFile = Core::getLib('file');
        if (isset($aParam['invisible']) && $aParam['invisible']) {
            $this->_iInvisible = true;
        }
        $aDomains = array();
        $aLists = array();
        // get list of module.
        $aTmp = scandir(DIR_MODULE);
        foreach ($aTmp as $sTmp) {
            if ($sTmp === '.' or $sTmp === '..') 
                continue;
            if (is_dir(DIR_MODULE . '/' . $sTmp)) {
            //code to use if directory
                $aLists[] = $sTmp;
            }
        }
        
        if ($aParam['get_all']) {
            // get all domain in system to re-build.
            
        }
        else {
            //d(Core::getDomainId());
            //d(Core::getLib('session')->getArray('session-domain', 'id'));die;
            $iDomainId = Core::getDomainId();
            if($iDomainId < 0){
                $aRow = Core::getService('domain')->getByHost($_SERVER['HTTP_HOST']);
                if(!isset($aRow['id']) || $aRow['id'] < 1) {
                    die('Domain not found');
                }
                else {
                    Core::getLib('session')->setArray('session-domain', 'id', $aRow['id']);
                    Core::getLib('session')->setArray('session-domain', 'domain', $aRow['domain']);
                }
            }
            $aRow = $this->database()->select('domain_id, template')
                ->from(Core::getT('domain_setting'))
                ->where('domain_id = '. Core::getDomainId())
                ->execute('getRow');
                
            $aDomains[] = array(
                'domain' => Core::getDomainName(),
                'id' => Core::getDomainId(),
                'template' => $aRow['template']
            );
        }
        
        foreach($aDomains as $aDomaim) {
            $sTmp = '';
            // find and minify in default theme folder.
            if(!Core::getParam('core.local'))
                $sTmp = 'decode'.DS;
            $sDir = DIR_THEME. $sTmp. 'web'. DS. 'default'.DS;
            // get all file in the folder.
            $aFile = $oFile->getAllFiles($sDir);
            foreach ($aFile as $sFile) {
                if(strpos($sFile, '.php') === false) 
                    continue;
                //$sFile = $sDir.$sFile;
                $this->_createMinify(array(
                    'domain' => $aDomaim['domain'],
                    'file' => $sFile
                ));
            }
            
            // find and minify in site theme folder.
            $sTmp = '';
            if(!Core::getParam('core.local')) 
                $sTmp = 'decode'.DS;
            $sDir = DIR_THEME. $sTmp. 'web'. DS. $aDomaim['template']. DS;
            $aFile = $oFile->getAllFiles($sDir);
            foreach($aFile as $sFile) {
                if(strpos($sFile, '.php') === false) 
                    continue;
                //$sFile = $sDir.$sFile;
                $this->_createMinify(array(
                    'domain' => $aDomaim['domain'],
                    'file' => $sFile
                ));
            }
            
            foreach ($aLists as $sValue) {
                // find and minify in module default theme.
                $sTmp = '';
                if(!Core::getParam('core.local')) 
                    $sTmp = 'decode'.DS;
                $sDir = DIR_MODULE. $sTmp. $sValue. DS. 'template'. DS. 'default'. DS;
                if(is_dir($sDir) !== false) {
                    $aFile = $oFile->getAllFiles($sDir);
                    foreach($aFile as $sFile) {
                        if(strpos($sFile, '.php') === false) 
                            continue;
                        //$sFile = $sDir.$sFile;
                        $this->_createMinify(array(
                            'domain' => $aDomaim['domain'],
                            'file' => $sFile
                        ));
                    }
                    
                }

                // find and minify in module site theme.
                $sTmp = '';
                if(!Core::getParam('core.local')) 
                    $sTmp = 'decode'.DS;
                $sDir = DIR_MODULE. $sTmp. $sValue. DS. 'template'. DS. $aDomaim['template']. DS;
                if(is_dir($sDir) !== false) {
                    $aFile = $oFile->getAllFiles($sDir);
                    foreach($aFile as $sFile) {
                        if(strpos($sFile, '.php') === false) 
                            continue;
                        //$sFile = $sDir.$sFile;
                        $this->_createMinify(array(
                            'domain' => $aDomaim['domain'],
                            'file' => $sFile
                        ));
                    }
                }
            }
        }
    }
    
    private function _createMinify($aParam = array())
    {
        if(!isset($aParam['domain']) || !isset($aParam['file']) || empty($aParam['domain']) || empty($aParam['file']))
            return false;
        
        $sSource = file_get_contents($aParam['file']);
        $aTokens = token_get_all($sSource);
        $iSave = 0;
        $bChekId = $bIsOld = -1;
        $iCnt = 0;
        $aTmp[$iCnt] = '';
        
        foreach ($aTokens as $sKey => $aToken) {
            if (substr($aToken[1],0, 2) == '<?') { // open 
                $iSave = 1;
            }
            if ($iSave == 1) {
                if(!is_array($aToken)) 
                    $aToken[1] = $aToken;
                $aTmp[$iCnt] .= $aToken[1];
            }
            if (substr($aToken[1], 0, 2) == '?>') {// close cua host linux
                $iSave = 0;
                $iCnt++;
                $aTmp[$iCnt] = '';
            }
        }
        // find minify function 
        foreach($aTmp as $sKey => $sValue) {
            if(strpos($sValue, 'getMinifyName(') !== false) 
                continue;    
            unset($aTmp[$sKey]);
        }
        // Tách dữ liệu trong hàm
        foreach($aTmp as $sKey => $sValue) {
            $iFirst = mb_strpos($sValue, 'getMinifyName(') + 1;
            $iLast = mb_strrpos($sValue, ')');
            $sValue = mb_substr($sValue, $iFirst + 13, $iLast - $iFirst - 13);
            $aTmp[$sKey] = $sValue;
        }
        
        if(empty($aTmp)) 
            return ;
        // gọi hàm API
        $sFileName = 'http://img.'.$aParam['domain'].':8080/tools/api.php?type=minify';
        foreach ($aTmp as $sKey => $sValue) {
            $sValue = str_replace("img.'.\$this->_aVars['sDomainName'].'", 'img.'.$aParam['domain'], $sValue);
            $sValue = str_replace("'.\$this->_aVars['sDomainName'].'", 'img.'.$aParam['domain'], $sValue);
            $sValue = str_replace("'.\$this->_aVars['versionExFile'].'", Core::getParam('core.versionExFile'), $sValue);
            
            $sResult = Core::getService('core.tools')->openProcess(array(
                'url' => $sFileName
            ), $sValue);
            
            flush();
            ob_flush();
            //echo $tmp.'<br>'.PHP_EOL;
            $this->_minifyStatus($sResult);
            
            // tạm ngừng 1 giây
            sleep(0.8);
        }
    }
    /**
    * show the minify result to browser.
    * 
    * @param mixed $sResult
    */
    private function _minifyStatus($sResult = '')
    {
        if($this->_iInvisible)
            return false;
        print('<script>if (document.getElementById("progress-bar")) {document.getElementById("pb_warning").innerHTML = "'.addslashes($sResult).'";}</script>'."\n");
        ob_flush(); flush();
    }
}
?>
