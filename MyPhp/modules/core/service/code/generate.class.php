<?php
class Core_Service_Code_Generate extends Service
{
    public function __construct()
    {
        
    }
    
    public function TestgetCode($sType)
    {
        if(!$sType)
            return false;
        //build file path
        $aListTypes = Core::getParam(array('server', 'code_generate'));
        
    }
    
    public function getCode($sType)
    {
        if(!$sType)  //import
            return false;
        //get config item and build file path
        $aListTypes = Core::getParam(array('server', 'code_generate'));
        //echo '<pre>';
       //print_r($aListTypes);echo'</pre>';exit;
        $sDomain = Core::getLib('url')->getShortDomain();
                           //echo $sDomain;exit;
                          // print_r($aListTypes[$sDomain][$sType]);exit;
                         // print_r($aListTypes[$sDomain][$sType]); exit;
        if(!isset($aListTypes[$sDomain][$sType]))
            return false;
        // build file path
       $sFilePath = Core::getParam('core.dir_code') . $sDomain . DS . $aListTypes[$sDomain][$sType]['file'];
        $aData = array(
            'file_path' => $sFilePath,
            'domain' => $sDomain,
            'type' => $sType,
        );
        $sCode = $this->_getCode($aData);
        // add một session để kiểm tra xem mã đã dc add vào db hay chưa
        $_SESSION[$sDomain][$sType] = $sCode;
        //format code follow the define
        $sCode = $this->_formatNumber($sCode, $aListTypes[$sDomain][$sType]['length']);
        $sCode = $aListTypes[$sDomain][$sType]['prefix'] . $sCode . $aListTypes[$sDomain][$sType]['suffix'] .'.' .date('m');
               //echo $sCode;exit;
        return $sCode;
    }
    
    public function getSampleCode($sType)
    {
        if(!$sType)
            return false;
        //get config item and build file path
        $aListTypes = Core::getParam(array('server', 'code_generate'));
        $sDomain = Core::getLib('url')->getShortDomain();     
        if(!isset($aListTypes[$sDomain][$sType]))
            return false;
    
        $sCode = $this->_formatSampleNumber('', $aListTypes[$sDomain][$sType]['length']);

        $sCode = $aListTypes[$sDomain][$sType]['prefix'] . $sCode . $aListTypes[$sDomain][$sType]['suffix'] .'.' .date('m');
        
        return $sCode;
    }
    
    /**
    * initialize function to create file and default value.
    * this function only run once when run config. 
    * 
    */
    public function setup()
    {
        $aListTypes = Core::getParam(array('server', 'code_generate'));
        $sDomain = Core::getLib('url')->getShortDomain();
        if(!isset($aListTypes[$sDomain]))
            return false;
        $sFilePath = Core::getParam('core.dir_code') . $sDomain . DS ;
        if(!is_dir($sFilePath))
            mkdir($sFilePath,777,true);
        foreach($aListTypes[$sDomain] as $aListType)
        {
            $sFilePath = Core::getParam('core.dir_code') . $sDomain . DS . $aListType['file'];
            $oFile = fopen($sFilePath, 'w+');
            fwrite($oFile, 0);
            fclose($oFile);
        }
    }
    
    private function _formatNumber($iNumber, $iLength)
    {                       // echo $iNumber.' '.$iLength;exit;
        $iCountAdd = $iLength - strlen($iNumber);
        while($iCountAdd)
        {
            $iNumber = 0 . $iNumber;
            $iCountAdd--;
        }       //echo $iNumber;exit;
        return $iNumber;
    }
    
    private function _formatSampleNumber($iNumber, $iLength)
    {
        $iCountAdd = $iLength - strlen($iNumber);
        while($iCountAdd)
        {
            $iNumber = 'x' . $iNumber;
            $iCountAdd--;
        }
        return $iNumber;
    }
    
    private function _getCode($aData, $bIsLoop = true) 
    {                 
        //kiểm tra xem đã tồn tại session code hay chưa nếu đã có rồi thì không tăng biến đếm
        if(isset($_SESSION[$aData['domain']][$aData['type']]) && !empty($_SESSION[$aData['domain']][$aData['type']]))
            return $_SESSION[$aData['domain']][$aData['type']];
            
        $oFile = fopen($aData['file_path'],"r+");
        if(flock($oFile, LOCK_EX))
        {                                       
            $iCnt = fread($oFile, filesize($aData['file_path']));    //Get Current Hit Count
            $iCnt = $iCnt + 1;    //Increment Hit Count by 1
            ftruncate($oFile, 0);    //Truncate the file to 0
            rewind($oFile);           //Set write pointer to beginning of file
            fwrite($oFile, $iCnt);    //Write the new Hit Count
            fflush($oFile);            // flush output before releasing the lock
            flock($oFile, LOCK_UN);    //Unlock File
            
            return $iCnt;
        }
        
        if($bIsLoop)
        {
            $iLimitTime = time() + 5; // nếu đợi 5s mà file vẫn ko dc unlock thì ngừng vòng lặp và lưu log.
            while(time() < $iLimitTime)
            {
                usleep(500000);
                $iCnt = $this->_getCode($sFilePath, false);
                if($iCnt)
                    break;
            }
            if(!isset($iCnt) || !$iCnt)
                $this->_saveLog('File đang bị lock');
            return $iCnt;
        }
    }
    
    private function _getSampleCode($aData, $bIsLoop = true)
    {
        
    }
    
    private function _saveLog($sText)
    {
        $sFilePath = Core::getParam('core.dir_file'). 'log' . DS. 'code.txt';
        $oFile = fopen($sFilePath, 'w+');
        $sTime = @date('Y-m-d H:i:s',time());
        $sScriptName = @pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME);
        $sContentWrite = "\n".$sScriptName.'-----------------------'.$sTime.'-----------------------'."\n".$sText."\n".'-----------------------------------------------';
        @fwrite($oFile,$sContentWrite);
        @fclose($oFile);
    }
}
?>
