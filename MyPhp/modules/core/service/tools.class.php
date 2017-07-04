<?php
class Core_Service_Tools extends Service
{
    public function __construct()
    {
        
    }
    
    
    /**
    * get all file in folder.
    * 
    * @param string folder path $sPath 
    * @return array
    */
    public function getFileInFolder($sPath = '')
    {
        if(empty($sPath))
            return array();
        
        if(substr($sPath, -1, 1) != '/') 
            $sFolder .= '/';
        $oDirHandle = @opendir($sPath) or $bError = true;
        $aFile = array();
        if(!$bError) {
            $iCnt= 0;
            while (false !== ($sFile = readdir($oDirHandle))) {
                if($sFile != "." && $sFile != ".." && $sFile != ".htaccess" && strpos($sFile, "-del-") === false) {
                    if(filetype($sPath.$sFile) != 'dir') {
                        $aFile[] = $sFile;
                    }
                    $iCnt++;
                }
            }
            closedir($oDirHandle);
        }
        return $aFile;
    }
    
    private $_aMultiMenu = array();
    public function showMultiMenu($aParam)
    {
        if (empty($this->_aMultiMenu)) {
            $this->_aMultiMenu = $aParam['menu'];
            unset($aParam['menu']);
        }
       // d($this->_aMultiMenu);die;
        foreach ($this->_aMultiMenu as $aMenu) {
            if ($aMenu[2] == $aParam['parent_id']) {

                $bIsExit = false;
                if($aMenu[3] > 0) 
                    $bIsExit = true;
                if(empty($aMenu[6]))
                    $aMenu[6] = str_replace('"', '&quot;', $aMenu[1]);
                
                $ncol = '';
                $ncol_close = '';
                if(!isset($aParam['param'][$aParam['degree']])) 
                    return '';
                // kiểm tra, nếu ko chia cột, cộng dồn đoạn opencol vào opendad
                if (!empty($aParam['param'][$aParam['degree']]['opencol']) && !empty($aParam['param'][$aParam['degree']]['opendad']) && $bIsExit) {
                    if($aMenu[8] < 2) {
                        $ncol = $aParam['param'][$aParam['degree']]['opencol'];
                        $ncol = str_replace(
                            array('{ncol}'),
                            array('1'),
                            $ncol
                        );
                        $ncol_close = $aParam['param'][$aParam['degree']]['closecol'];
                    }
                }
                $aParent = array();
                $aParent['column_count'] = 0;
                $aParent['child_count'] = 0;
                if (isset($aParam['param'][$aParam['degree'] - 1]) && !empty($aParam['param'][$aParam['degree'] - 1]['opencol'])) {
                    // lấy thông tin của cha
                    foreach ($aParam['menu'] as $aValue) {
                        if ($aValue[0] == $aParam['parent_id']) {
                            $aParent['column_count'] = $aValue[8];
                            $aParent['child_count'] = $aValue[3];
                            break;
                        }
                    }
                }
                
                $iLimitRows = 0;
                if ($aParent['column_count'] > 1) {
                    $iLimitRows = ceil($aParent['child_count'] / $aParent['column_count']);
    
                    if(!isset($aLine[$aParam['degree']]) || $aLine[$aParam['degree']] < 1)
                        $aLine[$aParam['degree']] = 1;
                    
                    // xử lý thêm open col
                    if(($aLine[$aParam['degree']] - 1) % $iLimitRows === 0) {
                        $ncol = $aParam['param'][$aParam['degree'] - 1]['opencol'];
                        $ncol = str_replace(
                            array('{ncol}'),
                            array((($aLine[$aParam['degree']] - 1) / $iLimitRows) + 1),
                            $ncol
                        );
                    }
                }

                if($bIsExit) {
                    if(empty($aParam['param'][$aParam['degree']]['opendad']))
                        $aParam['param'][$aParam['degree']]['opendad'] = $aParam['param'][$aParam['degree']]['open'];
                    $sContent = $aParam['param'][$aParam['degree']]['opendad'];
                }
                else
                    $sContent = $aParam['param'][$aParam['degree']]['open'];
               // d($sContent);
                            
                $aMap = array(
                    '{v[0]}' => $aMenu[0],
                    '{level}' => 0,
                    '{sep}' => $aParam['sep'],
                    '{v[4]}' => $aMenu[4],
                    '{v[6]}' => $aMenu[6],
                    '{targetWindows}' => $aMenu[5],
                    '{v[1]}' => $aMenu[1],
                    '{cut(v[1])}' => $this->cutString($aMenu[1], 20),
                    '{v[3]}' => $aMenu[3],
                    '{v[7]}' => $aMenu[7],
                    '{v[8]}' => $aMenu[8],
                    '{v[note]}' => $aMenu['note']
                );
                
                $iEndPoint = true;
                $iStartPoint = true;
                while(1) {
                    $iStartPoint = mb_strpos($sContent, '{if ');
                    if($iStartPoint !== false)
                        $iEndPoint = mb_strpos($sContent, '{endif}', $iStartPoint);
                    // if don't exit if statement, break loop.
                    if($iStartPoint === false || $iEndPoint === false)
                        break;
                    
                    $sTmp = mb_substr($sContent, $iStartPoint, $iEndPoint - $iStartPoint + 7);
                
                    // split if statement.
                    $iStartStatement = mb_strpos($sTmp, '{if ');
                    $iEndStatement = mb_strpos($sTmp, '}', $iStartStatement);
                    
                    $iStartStatement += 4;
                    
                    $sFirst = '{'.mb_substr($sTmp, $iStartStatement, $iEndStatement - $iStartStatement).'}';
                    
                    if (!empty($aMap[$sFirst])) {
                        // replace
                        $sFirst = mb_substr($sTmp, $iEndStatement + 1, -7);
                    }
                    else $sFirst = '';
                    
                    // replace
                    $sContent = str_replace($sTmp, $doan_dau, $sContent);
                    unset($sFirst);
                    unset($iStartPoint);
                    unset($iEndPoint);
                }
                
                foreach($aMap as $sKey => $sValue) {
                    $sContent = str_replace($sKey, $sValue, $sContent);
                }

                if($aParent['column_count'] > 1)
                    $sTmp = $aParam['sep'].$ncol.$sContent;
                else
                    $sTmp = $aParam['sep'].$sContent.$ncol;
                
                
                $aParam['result'] .= $this->showMultiMenu(array(
                    'parent_id' => $aMenu[0],
                    'result' => $sTmp,
                    'sep' => $aParam['sep']. '    ',
                    'degree' => $aParam['degree']+1,
                    'param' => $aParam['param']
                ));
                
                // xử lý thêm open col
                $ncol = '';
                if ($aParent['column_count'] > 1) {
                    if($aLine[$aParam['degree']] % $iLimitRows === 0 || $aLine[$aParam['degree']] == $aParent['child_count']) {
                        $ncol = $aParam['param'][$aParam['degree']-1]['closecol'];
                    }
                    // nếu tổng chia ko dư, thêm clsecol để kết thúc
                    $aLine[$aParam['degree']]++;
                }
                
                
                if ($bIsExit) {
                    if(empty($aParam['param'][$aParam['degree']]['closedad']))
                        $aParam['param'][$aParam['degree']]['closedad'] = $aParam['param'][$aParam['degree']]['close'];
                    $sContent = $aParam['param'][$aParam['degree']]['closedad'];
                }
                else 
                    $sContent = $aParam['param'][$aParam['degree']]['close'];
                
                
                foreach ($aMap as $sKey => $sValue) {
                    $sContent = str_replace($sKey, $sValue, $sContent);
                }
                
                if($aParent['column_count'] > 1)
                    $aParam['result'] .= $sContent.$ncol;
                else
                    $aParam['result'] .= $ncol_close.$sContent;
            }
        }

        return $aParam['result'];
    }
    
    public function getMinifyName($sType, $aData, $sStr ='')
    {
        $sName = implode(',', $aData);
        
        if($sType != 'css' && $sType != 'js') 
            return ;
        if(empty($sName) && empty($sStr)) 
            return ;
        
        if(empty($sName)) 
            $sName = microtime();
        $sName = md5($sName);
        $sName = '//img.'.Core::getDomainName().'/'.$sName.'.'.$sType.'?v='.Core::getParam('core.versionExFile');
        
        if($sType == 'css') {
            $sName = '<link href="'.$sName.'" rel="stylesheet" type="text/css" />';
        }
        else {
            $sName = '<script type="text/javascript" language="javascript" src="'.$sName.'"></script>';
        }
        return $sName;
    }
    
    public function getUniqueCode($aParam = array())
    {
        $sCode = '';
        if (!empty($aParam['type'])) {
            $sCode = Core::getDomainId().'|'.$aParam['type'];
            
            $sCode = $this->database()->insert(Core::getT('get_number'), array(
                'type' => $this->database()->escape($sCode)
            ));
            
            if (!empty($aParam['length']) && $aParam['length'] > 0) {
                $sCode = str_pad($sCode, $aParam['length'], 0, STR_PAD_LEFT);
            }
            
            // prefix
            if (!empty($aParam['prefix'])) {
                // random
                if (isset($aParam['prefix']['type']) && $aParam['prefix']['type'] == 'random') {
                    $aParam['prefix']['value'] -= 1;
                    $iNumber = pow(10, $aParam['prefix']['value']); // 10 ^ x
                    $iExponente = $s * 10 - 1; // giả sử s là 100, thì e sẽ là 100 * 10 - 1 = 999
                    
                    $iExponente = rand($iNumber, $iExponente);
                    $aParam['prefix']['value'] = $iExponente;
                }
                else if(!isset($aParam['prefix']['value']) && !is_array($aParam['prefix'])) {
                    $aParam['prefix'] = array(
                        'type' => 'define',
                        'value' => $aParam['prefix'],
                    );
                }
                
                $sCode = $aParam['prefix']['value'].$sCode;
            }
            // suffix
            if (!empty($aParam['suffix'])) {
                // random
                if (isset($aParam['suffix']['type']) && $aParam['suffix']['type'] == 'random') {
                    $aParam['suffix']['value'] -= 1;
                
                    $iNumber = pow(10, $aParam['suffix']['value']); // 10 ^ x
                    $iExponente = $iNumber * 10 - 1; // giả sử s là 100, thì e sẽ là 100 * 10 - 1 = 999
                    
                    $iExponente = rand($iNumber, $iExponente);
                    $aParam['suffix']['value'] = $iExponente;
                }
                else if(!isset($aParam['suffix']['value']) && !is_array($aParam['suffix'])) {
                    $aParam['suffix'] = array(
                        'type' => 'define',
                        'value' => $aParam['suffix'],
                    );
                }
                
                $sCode .= $aParam['suffix']['value'];
            }
        }
        else {
            list($usec, $sec) = explode(" ", microtime());
            $usec = substr($usec, 2);
            $usec *= 1;
            $t = $sec.$usec;
            if($aParam['complex']) $t = mt_rand().$t;
            $sCode = $this->alphaID($t, false);
        }
        return $sCode;
    }
    
    public function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
    {
        $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n<strlen($index); $n++) {
                $i[] = substr( $index,$n ,1);
            }

            $passhash = hash('sha256',$passKey);
            $passhash = (strlen($passhash) < strlen($index))
                ? hash('sha512',$passKey)
                : $passhash;

            for ($n=0; $n < strlen($index); $n++) {
                $p[] =  substr($passhash, $n ,1);
            }

            array_multisort($p,  SORT_DESC, $i);
            $index = implode($i);
        }

        $base  = strlen($index);

        if ($to_num) {
            // Digital number  <<--  alphabet letter code
            $in  = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a   = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in  = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }
    
    public function getRandomCode($iTotalCharacter)
    {
        $aCaptchaChars = array('2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','J','K','M','N','P','Q','R','S','Y','U','V','W','X','Y','Z');
        $sCode = '';
        for ($iCnt = 0; $iCnt < $iTotalCharacter; $iCnt++) {
            $sTmp = $aCaptchaChars[rand(0,30)];
            $sCode .= $sTmp;
        }
        return $sCode;
    }
    
    public function openProcess($aParam, $sContent, $aSession = array())
    {
        $bIsSyn = false;
        $sUploadLink = '';
        $sUploadFile = '';
        if (!empty($sContent)) {
            $bIsSyn = (isset($sContent['dong_bo']) && is_bool($sContent['dong_bo'])) ? $sContent['dong_bo'] : false;
            
            // ghi giá trị session đến file, để đảm bảo ko bị mất session khi đăng xuất
            // nếu gọi minify thì không thêm phần session vào
            if (isset($aParam['minify']) && $aParam['minify'] == 1) {
                unset($aParam['minify']);
            }
            else {
                if(!is_array($sContent)) 
                    $sContent = array('data' => $sContent);

                if (isset($aSession['id']) && !empty($aSession['id'])) {
                    $sContent['session'] = $aSession;
                }
                else {
                    $sContent['session'] = array(
                        'id' => session_id(),
                        'data' => $_SESSION
                    );
                    $aSession = $_SESSION['core_'];
                }
                $sContent = serialize($sContent);
            }
            
            $aSession = $aSession['data']['core_'];
            if (isset($aSession['session-domain']['domain']) || empty($aSession['session-domain']['domain'])) {
                $sDomain = $aSession['session-domain']['domain'];
                $iDomainId = $aSession['session-domain']['id'];
                $iUserId = isset($aSession['session-user']['id']) ? $aSession['session-user']['id'] : 0;
            }
            
            if(empty($sDomain)) {
                $sDomain = Core::getDomainName();
                $iDomainId = Core::getDomainId();
                $iUserId = Core::getUserId() ;
            }
            if(empty($sDomain)) {
                return false;
            }
            
            if(empty($iUserId)) 
                $iUserId = 'r-'.rand(11111,99999);
                
            $sUploadFile = $iUserId.'_'.microtime(true).'.sys';
            $sUploadLink = 'http://'.Core::getParam('core.main_server').$sDomain.'/data/'.$sUploadFile;
            $sDir = Core::getParam('core.dir').'/cache/data/'.$sDomain;
            
            if(!file_exists($sDir)) 
                mkdir($sDir);
            
            $sUploadFile = $sDir.'/'.$sUploadFile;
            $oFp = fopen($sUploadFile, 'w');
            fwrite($oFp, $sContent);
            fclose($oFp);
        }
        
        $sTmp = '';
        //$sFileName = 'http://cms.vdg.vn/'.'tools/background.php';
        $sFileName = Core::getParam('core.main_path'). 'tools/background.php';
        if (isset($aParam['url']) && !empty($aParam['url'])) {
            $sFileName = $aParam['url'];
            unset($aParam['url']);
        }

        if (strpos($sFileName, '?') === false)
            $sFileName .= '?';

        if(!is_array($aParam))
            $aParam = (array) $aParam;
        $bIsParam = false;
        if (count($aParam)) {
            $bIsParam = true;
            foreach ($aParam as $sKey => $sValue) {
                $sFileName .= $sKey.'='.$sValue.'&';
            }
            $sFileName = trim($sFileName, '&');
        }
        if ($bIsParam)
            $sTmp = $sFileName.'&filepost='.urlencode($sUploadLink);
        else if(strpos($sFileName, '?') === strlen($sFileName))
            $sTmp = $sFileName.'filepost='.urlencode($sUploadLink);
        else
            $sTmp = $sFileName.'&filepost='.urlencode($sUploadLink);
        //core_log(Core::getParam('core.local'), 'a+');    
        //core_log(Core::getParam('core.dir'), 'a+');    
        if (!Core::getParam('core.local')) {
            //if (empty($sTmp)) {
//                if(strpos($sFileName, '.php') !== false) 
//                    $sTmp = 'php "'.Core::getParam('core.dir'). $sFileName.'" "--filepost'. $sUploadFile.'"';
//                else 
//                    $sTmp = $sFileName;
//            }
//            else
                $sTmp = 'wget "'.$sTmp.'" -O '.Core::getParam('core.dir').'/cache/system/'.md5($sFileName);
                //core_log($sTmp, 'a+');
            if(!$bIsSyn) 
                exec($sTmp.' > /dev/null &');
            else 
                exec($sTmp);
        }
        else {
            /* *
            echo $sUploadFile;
            exit;
            return ;
            /* */
            if(!$bIsSyn) {
                if(empty($sTmp))
                     $sTmp = '"' . Core::getParam('core.phpexe') . '" "'.Core::getParam('core.dir'). $sFileName.'" "--filepost'.$sUploadFile.'"';
                else {
                    $sFile = 'C:\Program Files (x86)\Mozilla Firefox\firefox.exe';
                    if(!file_exists($sFile))
                        $sFile = 'C:\Program Files (x86)\Internet Explorer\iexplore.exe';
                    
                    $sTmp = '"'.$sFile.'" "'.$sTmp.'"';
                }
                // pclose(popen("start /B ". $tmp, "r"));
                if(!$bIsSyn) 
                    pclose(popen('start "bla" '.$sTmp, "r"));
                else if(!empty($sTmp)) 
                    $this->getDataWithCurl($sTmp, 1000, null, false);
            }
            else {
                if(!empty($sTmp)) 
                    $this->getDataWithCurl($sTmp, 1000, null, false);
                else {
                    unlink($sUploadFile);
                    $aContent = unserialize($sContent);
                    if ($oProc = popen(Core::getParam('core.dir').$sFileName.' "'.$aContent['du_lieu'].'"',"r")){
                        while (!feof($oProc)) 
                            $sResult .= fgets($oProc, 1000);
                        pclose($oProc);
                        $sTmp = $sResult;
                    }
                }
            }
        }
        return $sTmp;
    }
    
    public function getDataWithCurl($sUrl, $iTimeout = 20, $aFields = null, $bIsLoop = true, $bIsGetHeader = 0)
    {
        $pc2id = $_COOKIE['pc2id'];
        $sUrl = str_replace(' ', '%20', $sUrl);
        $aHeader[] = "Accept: text/xml,application/xml,application/xhtml+xml, text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $aHeader[] = "Cache-Control: max-age=0";
        $aHeader[] = "Connection: keep-alive";
        if($iTimeout > 1) $aHeader[] = "Keep-Alive: ".$iTimeout;
        $aHeader[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $aHeader[] = "Accept-Language: en-us,en;q=0.5";
        $aHeader[] = "Pragma: no-cache";
        $aHeader[] = "Cookie: pc2id=".$pc2id.';localauth=1';
        $oCurl = curl_init();
        
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5");// 'Googlebot/2.1 (+http://www.google.com/bot.html)');
        curl_setopt($oCurl, CURLOPT_HEADER, 1);
        // curl_setopt($oCurl, CURLOPT_REFERER, 'http://www.google.com');
        curl_setopt($oCurl, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($oCurl, CURLOPT_AUTOREFERER, true);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($oCurl, CURLOPT_PUT, 0);
        
        if ($iTimeout < 1) {
            curl_setopt($oCurl, CURLOPT_TIMEOUT_MS, $iTimeout * 1000);
            curl_setopt($oCurl, CURLOPT_NOSIGNAL, 1);
            curl_setopt($oCurl, CURLOPT_DNS_CACHE_TIMEOUT, 10000);
        }
        else curl_setopt($oCurl, CURLOPT_TIMEOUT, $iTimeout);
        
        if (is_array(@$aFields)) {
            $aFields = (is_array($aFields)) ? http_build_query($aFields) : $aFields; 
            
            $aHeader[] = 'Content-Length: ' . strlen($aFields);
            curl_setopt($oCurl,CURLOPT_POST, 1);
            curl_setopt($oCurl,CURLOPT_POSTFIELDS, $aFields);
        }
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $aHeader);
        
        $sData = curl_exec($oCurl);
        if($sData === false) {
            $sError =  'Curl error: ' . curl_error($oCurl) .'<br>' .$iTimeout.'<br>'.curl_errno($oCurl).'<br>'.$sUrl.'<hr>';
            // 
            $aInsertLog = array(
                'item_id' => 0,
                'item_type' => 'error',
                'header_send' => serialize($aHeader),
                'param_send' => serialize($aFields),
                'response_header' => '',
                'response_body' => $sError,
                'create_time' => CORE_TIME,
                'status' => 3
            );
            $this->database()->insert(Core::getT('log_curl'), $aInsertLog);
            flush();
            ob_flush();
            return '';
        }
        // Then, after your curl_exec call:
        $sHeaderSize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
        $aResponseHeader = substr($sData, 0, $sHeaderSize);
        $sBody = substr($sData, $sHeaderSize);
        $aInsertLog = array(
            'item_id' => 0,
            'item_type' => 'success',
            'header_send' => serialize($aHeader),
            'param_send' => serialize($aFields),
            'response_header' => $aResponseHeader,
            'response_body' => $sBody,
            'create_time' => CORE_TIME,
            'status' => 1
        );
        $this->database()->insert(Core::getT('log_curl'), $aInsertLog);
        curl_close($oCurl);
        
        // check header if there have Redirect link.
        if (strpos($aResponseHeader, 'ocation: ') !== false) {
            preg_match("#location: (.*?)\n#is", $aResponseHeader, $tmps);
            $tmps[1] = trim($tmps[1]);
            $sData = ' moved <a href="'.$tmps[1].'">';
        }
        if($bIsLoop && strpos($sData, ' moved <a href="') !== false) {
            // trường hợp lỗi do redirect page thì tạm thời trả về rổng.
            return '';
            preg_match('/href="(.*?)">/is', $sData, $sData);
            $sNewUrl = urldecode($sData[1]);
            if (strpos($sNewUrl, '://') === false) {
                $aTmp = parse_url($sUrl);
                $host = $aTmp['scheme'].'://'.$aTmp['host'];
                if (substr($sNewUrl,0, 1) == '/') {
                    $sNewUrl = $host.$sNewUrl;
                }
                else {
                    $path = $aTmp['path'];
                    $vi_tri = mb_strrpos($path, '/',0, 'utf8');
                    if ($vi_tri !== false) {
                        $path = mb_substr($path, 0, $vi_tri, 'utf8');
                    }
                    $sNewUrl = $host.$path.$sUrl;
                }
            }
            
            return $this->getDataWithCurl($sNewUrl, $iTimeout, $aFields, false, $bIsGetHeader);
        }
        if($bIsGetHeader == 1) {
            return array(
                'header' => $aResponseHeader,
                'body' => $sBody
            );
        }
        return $sBody;
    }
    
    public function cutString($sStr, $iLength) 
    {
        if(mb_strlen($sStr,'utf-8') > $iLength) {
            for ($iCnt = $iLength; $iCnt > 0; $iCnt--) {
                if (mb_substr($sStr, $iCnt, 1, 'utf-8') == ' ') {
                    break;
                }
            }
            if($iCnt == 0)
                $iCnt = $iLength;
            $sStr = mb_substr($sStr, 0, $iCnt, 'utf-8').'...';
        }
        return $sStr;
    }
    
    public function convertStringToInt($sStr, $iMax = 5)
    {
        if (empty($sStr)) {
            $iReturn = pow(10, $iMax - 1);
            return $iReturn;
        }
        $aAlphabet = array('', 'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $aAlphaFlip = array_flip($aAlphabet);
        $sStr = mb_strtolower($sStr);
        $sStr = $this->removeMark($sStr);
        // convert to array
        $aTmps = str_split($sStr);
        $iReturn = 0;
        for ($i = 0; $i < $iMax; $i++) {
            $iReturn += $aAlphaFlip[$aTmps[$i]] * pow(10, $iMax - $i - 1);
        }
        return $iReturn;
    }
    
    public function removeMark($sStr)
    {
        $aTrans = array(
        "đ"=>"d","ă"=>"a","â"=>"a","á"=>"a","à"=>"a","ả"=>"a","ã"=>"a","ạ"=>"a",
        "ấ"=>"a","ầ"=>"a","ẩ"=>"a","ẫ"=>"a","ậ"=>"a",
        "ắ"=>"a","ằ"=>"a","ẳ"=>"a","ẵ"=>"a","ặ"=>"a",
        "é"=>"e","è"=>"e","ẻ"=>"e","ẽ"=>"e","ẹ"=>"e",
        "ế"=>"e","ề"=>"e","ể"=>"e","ễ"=>"e","ệ"=>"e",
        "í"=>"i","ì"=>"i","ỉ"=>"i","ĩ"=>"i","ị"=>"i",
        "ư"=>"u","ô"=>"o","ơ"=>"o","ê"=>"e",
        "Ư"=>"u","Ô"=>"o","Ơ"=>"o","Ê"=>"e",
        "ú"=>"u","ù"=>"u","ủ"=>"u","ũ"=>"u","ụ"=>"u",
        "ứ"=>"u","ừ"=>"u","ử"=>"u","ữ"=>"u","ự"=>"u",
        "ó"=>"o","ò"=>"o","ỏ"=>"o","õ"=>"o","ọ"=>"o",
        "ớ"=>"o","ờ"=>"o","ở"=>"o","ỡ"=>"o","ợ"=>"o",
        "ố"=>"o","ồ"=>"o","ổ"=>"o","ỗ"=>"o","ộ"=>"o",
        "ú"=>"u","ù"=>"u","ủ"=>"u","ũ"=>"u","ụ"=>"u",
        "ứ"=>"u","ừ"=>"u","ử"=>"u","ữ"=>"u","ự"=>"u",'ý'=>'y','ỳ'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y', 'Ý'=>'Y','Ỳ'=>'Y','Ỷ'=>'Y','Ỹ'=>'Y','Ỵ'=>'Y',
        "Đ"=>"D","Ă"=>"A","Â"=>"A","Á"=>"A","À"=>"A","Ả"=>"A","Ã"=>"A","Ạ"=>"A",
        "Ấ"=>"A","Ầ"=>"A","Ẩ"=>"A","Ẫ"=>"A","Ậ"=>"A",
        "Ắ"=>"A","Ằ"=>"A","Ẳ"=>"A","Ẵ"=>"A","Ặ"=>"A",
        "É"=>"E","È"=>"E","Ẻ"=>"E","Ẽ"=>"E","Ẹ"=>"E",
        "Ế"=>"E","Ề"=>"E","Ể"=>"E","Ễ"=>"E","Ệ"=>"E",
        "Í"=>"I","Ì"=>"I","Ỉ"=>"I","Ĩ"=>"I","Ị"=>"I",
        "Ư"=>"U","Ô"=>"O","Ơ"=>"O","Ê"=>"E",
        "Ư"=>"U","Ô"=>"O","Ơ"=>"O","Ê"=>"E",
        "Ú"=>"U","Ù"=>"U","Ủ"=>"U","Ũ"=>"U","Ụ"=>"U",
        "Ứ"=>"U","Ừ"=>"U","Ử"=>"U","Ữ"=>"U","Ự"=>"U",
        "Ó"=>"O","Ò"=>"O","Ỏ"=>"O","Õ"=>"O","Ọ"=>"O",
        "Ớ"=>"O","Ờ"=>"O","Ở"=>"O","Ỡ"=>"O","Ợ"=>"O",
        "Ố"=>"O","Ồ"=>"O","Ổ"=>"O","Ỗ"=>"O","Ộ"=>"O",
        "Ú"=>"U","Ù"=>"U","Ủ"=>"U","Ũ"=>"U","Ụ"=>"U",
        "Ứ"=>"U","Ừ"=>"U","Ử"=>"U","Ữ"=>"U","Ự"=>"U",);
        
        //remove any '-' from the string they will be used as concatonater
        $sStr = strtr($sStr, $aTrans);
        return $sStr;
    }
    
    public function encodeUrl($sUlr)
    {
        $sUlr = $this->removeMark($sUlr);
        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $sUlr = preg_replace(array('/\s+/','/[^A-Za-z0-9\-\_\.]/'), array('-','-'), $sUlr);
        // thêm dấu . nhằm để đa dạng hóa đề tại. Ví dụ đè tài tên là abcd.htm, abcd.html, abcd.php,...
        // $str = preg_replace(array('/\s+/', '/\./'), array('-', '_'), $str);
        //$str = utf8_accents_to_ascii($str);
        // lowercase and trim
        $sUlr = trim(strtolower($sUlr));
        $sUlr = $this->removeDash($sUlr);
        return $sUlr;
    }
    
    public function removeDash($sStr)
    {
        while(1) {
            $iPos = strpos($sStr, "--");
            if($iPos !== false) {
                $sStr = str_replace("--", "-", $sStr);
            }
            else break;
        }
        $iPos = substr($sStr, 0, 1);
        if ($iPos == "-") {
            $sStr = substr($sStr.'-', 1, -1);
        }
        $iPos = substr($sStr, -1, 1);
        if ($iPos == "-") {
            $sStr = substr($sStr, 0, -1);
        }
        return $sStr;
    }
    
    public function checkTime($sTime, $sType = 1, $sTypeReturn = 1)
    {
        /*
        $sType = 1
            'dd-mm-yy hh:ii:ss'
        $sType = 2
            'mm-dd-yy hh:ii:ss'
        $sType = 3
            'yy-m-dd hh:ii:ss'
        
        $sTypeReturn = 1
            time()
        $sTypeReturn = 2
            Y-m-d H:i:s
        */
        if ($sTypeReturn == 2)
            $sOutput = '';
        else
            $sOutput = 0;
        // check thoi gian bat dau
        if(empty($sTime))
            return $sOutput;
        // tách năm và giờ phút
        $aTmp = explode(" ", $sTime);
        // tách năm
        $aDayTime = explode("-", $aTmp[0]);
        $aDayTime[0] *= 1;
        $aDayTime[1] *= 1;
        $aDayTime[2] *= 1;
        
        if ($sType == 3) {
            $sTmp = $aDayTime[0];
            
            $aDayTime[0] = $aDayTime[1];
            $aDayTime[1] = $aDayTime[2];
            $aDayTime[2] = $sTmp;
            $sType = 2;
        }
        
        if ($sType == 2) {
            $mm = $aDayTime[0];
            $dd = $aDayTime[1];
        }
        else {
            $mm = $aDayTime[1];
            $dd = $aDayTime[0];
        }
        if (!@checkdate($mm, $dd, $aDayTime[2]))
            return $sOutput;
        // tách giờ phút
        if (!empty($aTmp[1]) && strpos($aTmp[1], ':')) {
            $aTime = explode(":", $aTmp[1]);
            $aTime[0] *= 1;
            $aTime[1] *= 1;
            $aTime[2] *= 1;
            if ($aTime[0] < 0 || $aTime[0] > 23)
                $aTime[0] = 0;
            if ($aTime[1] < 0 || $aTime[1] > 59)
                $aTime[1] = 0;
            if ($aTime[2] < 0 || $aTime[2] > 59)
                $aTime[2] = 0;
        }
        
        if ($sTypeReturn == 2) {
            if ($sType == 2)
                $sOutput = $aDayTime[2].'-'.$aDayTime[1].'-'.$aDayTime[0];
            else
                $sOutput = $aDayTime[0].'-'.$aDayTime[1].'-'.$aDayTime[2];
            $sTmp = $aTime[0].':'.$aTime[1].':'.$aTime[2];
            if ($sTmp != '::')
                $sOutput = $sOutput.' '.$sTmp;
        }
        else {
            $sOutput = mktime($aTime[0], $aTime[1], $aTime[2], $mm, $dd, $aDayTime[2]);
        }
        return $sOutput;
    }
    
    public function splitKeywordFromString($sStr)
    {
        $iTmp = 0;
        $aPosition = array();
        $aWord = array();
        if($sStr != '') {
            while(1) {
                $iTmp = mb_strpos($sStr, '-', $iTmp, 'utf8');
                if($iTmp !== false) {
                    $iTmp += 1;
                    $aPosition[] = $iTmp;
                }
                else
                    break;
            }
            $iTmp = 0;
            while(1) {
                $iTmp = mb_strpos($sStr, '.', $iTmp, 'utf8');
                if($iTmp !==false) {
                    $iTmp += 1;
                    $aPosition[] = $iTmp;
                }
                else
                    break;
            }
            
            $iTmp = 0;
            while(1) {
                $iTmp = mb_strpos($sStr, ',', $iTmp, 'utf8');
                if($iTmp !==false) {
                    $iTmp += 1;
                    $aPosition[] = $iTmp;
                }
                else
                    break;
            }

            $iTmp = 0;
            while(1) {
                $iTmp = mb_strpos($sStr, '|', $iTmp, 'utf8');
                if ($iTmp !==false) {
                    $iTmp += 1;
                    $aPosition[] = $iTmp;
                }
                else 
                    break;
            }
            
            if($aPosition) {
                asort($aPosition);
                $aNewPosition[] = 0;
                foreach ($aPosition as $iValue) {
                    $aNewPosition[] = $iValue;
                }
                unset($aPosition);
                $aNewPosition[] = mb_strlen($sStr, 'utf8')+1;
                $iTotal = count($aNewPosition);
                for ($iCnt = 0; $iCnt < $iTotal; $iCnt++) {
                    $aWord[] = trim(mb_substr($sStr, $aNewPosition[$iCnt], ($aNewPosition[$iCnt + 1] - $aNewPosition[$iCnt] - 1), 'utf8'));
                }
            }
            else
                $aWord[] = trim($sStr);
        }
        return $aWord;
    }
    
    public function sortArrayByColumn(&$aArray, $sColumn, $sDir = SORT_ASC) {
        $aSortCol = array();
        foreach ($aArray as $iKey=> $aRow) {
            $aSortCol[$iKey] = $aRow[$sColumn];
        }
        array_multisort($aSortCol, $sDir, $aArray);
    }

    // function order by value of key in array
    public function aasort (&$aArray, $sKey) {
        $this->sortArrayByColumn($aArray, $sKey);
    }
    
    /**
    * create new url from param.
    * 
    * @param array $aParam
    * @return string url
    */
    public function buildUrl($aParam = array())
    {
        $aStructure = array(
            'lang',
            'area',
            'vendor',
            'production',
            'cart',
            'q',
            'page',
            'sort'
        );
        
        $sCurrentUrl = $aParam['url'];
        $sModule = $aParam['module'];
        $aKeys = $aParam['param'];
        $sUrl = '';
        // split current url to array param. 
        $aRequests = array();
        $sTmpUrl = '';
        $sParam = '';
        $iPos = mb_strpos($sCurrentUrl, '?');
        if ($iPos !== false) {
            $sTmpUrl = substr($sCurrentUrl, 0, $iPos);
            $iPos++;
            $sParam = substr($sCurrentUrl, $iPos);
            // thực hiện tách &
            $aTmps = explode('&', $sParam);
            foreach ($aTmps as $sValue) {
                $aTmp = explode('=', $sValue);
                $aRequests[$aTmp[0]] = $aTmp[1];
            }
        }
        else 
            $sTmpUrl = $sCurrentUrl;
        
        if ($sModule == $aParam['type']) {
            return $aParam['detail_path'] .$sParam;
        }
        if($aParam['type'] == 'category') {
            return $aParam['detail_path'] .$sParam;
        }
        foreach ($aKeys as $sKey => $sValue) {
            $aRequests[$sKey] = $sValue; // replace old value and add new value.
        }
        
        $sParam = '';
        foreach ($aStructure as $sValue) {
            if (isset($aRequests[$sValue])) {
                $sParam .= $sValue.'='.$aRequests[$sValue].'&';
            }
        }
        // build param
        if(!empty($sParam)) {
            $sParam = rtrim($sParam,'&');
        }
        if(!empty($sParam)) {
            $sParam = '?'.$sParam;
        }
        
        if (($sModule == 'vendor' && $aParam['type'] == 'area') || ($sModule == 'area' && $aParam['type'] == 'vendor')) {
             $sUrl = Core::getParam('core.path').'areavendor/'. $sParam;
             return $sUrl;
        }
        
        // if url has param, we split all param and replace old param with new value, add new param if not exist.
        if($sModule == 'category' || $sModule = 'core') {
            $sUrl = $sTmpUrl.$sParam;
            return $sUrl;
        }
        $sUrl = $aParam['detail_path'];
        // remove 
        if (isset($aRequests[$aParam['type']])) {
            unset($aRequests[$aParam['type']]);
        }
        $sParam = '';
        foreach ($aStructure as $sValue) {
            if (isset($aRequests[$sValue])) {
                $sParam .= $sValue.'='.$aRequests[$sValue].'&';
            }
        }
        // build param
        if(!empty($sParam)) {
            $sParam = rtrim($sParam,'&');
        }
        if(!empty($sParam)) {
            $sParam = '?'.$sParam;
        }
        $sUrl .= $sParam;
        return $sUrl;
    }
    
    public function checkTags($sContent)
    {
        if (empty($sContent))
            return '';
        $iPositionThird = 0;
        $iPositionFirst = -1;
        $sError = '';
        while (1) {
            
            $iPositionFirst = mb_strpos($sContent, '<', $iPositionThird , 'UTF8');
            if ($iPositionFirst===false)
                break;
            else
                $iPositionFirst += 1;
            if ($iPositionFirst)
                $iPositionSecond = mb_strpos($sContent, '<', $iPositionFirst , 'UTF8');
            if ($iPositionFirst)
                $iPositionThird = mb_strpos($sContent, '>', $iPositionFirst , 'UTF8');
            if( ($iPositionFirst && $iPositionSecond !== false && $iPositionSecond <= $iPositionThird) || $iPositionThird === false){
                $sError = 'Thẻ kết thúc không đúng';
                break;
            }
        }
        
        if(!empty($sError)) return $sError;
        // loc tag
        preg_match_all('#<(.*?)>#is', $sContent, $tmp);
        
        $iCount = 0;
        for($i=0;$i<count($tmp[1]);$i++)
        {
            $iTabOpen = 0;
            $sVarTmp = explode(' ', $tmp[1][$i]);
            $sVarTmp = $sVarTmp[0];
            if(substr($sVarTmp, 0, 1)=="/")
            {
                $sVarTmp = substr($sVarTmp .'-', 1, -1);
            }
            else
            {
                $iTabOpen = 1;
            }
            if($sVarTmp != "" && $sVarTmp != "!--" && $sVarTmp != 'hr' && $sVarTmp != 'br' && $sVarTmp != 'img' && $sVarTmp != 'param' && $sVarTmp != 'col')
            {
                $bIsRelate = false;
                $aVar[$iCount] = $sVarTmp;
                $aPosition[$iCount] = mb_strpos( $sContent, $aVar[$iCount], $aPosition[$iCount-1]+1, "UTF8");
                if($iTabOpen == 1) $aOpen[$iCount] = 1;
                else
                {
                    $aOpen[$iCount] = 0;
                    $t = $iCount;
                    $bIsContinue = true;
                    while(1)
                    {
                        for($j=$t-1;$j>=0;$j--)
                        {
                            if($aVar[$iCount] == $aVar[$j] && $aOpen[$j]==1 )
                            {
                                $bIsRelate = true;
                                $bIsContinue = false;
                                break;
                            }
                        }
                        if($t == $iCount && $bIsContinue == true) break;
                        if(!$bIsContinue)
                        {
                            for($z=$iCount-1;$z>=0;$z--)
                            {
                                if($aRelate[$z] == $j)
                                {
                                    $t = $j;
                                    $bIsContinue = true;
                                }
                            }
                        }
                        if(!$bIsContinue) break;
                    }
                }
                if(!$bIsRelate) $aRelate[$iCount] = -1;
                else
                {
                    $aRelate[$iCount]=$j;
                }
                $iCount++;
            }
            if($sError) break;
        }
        if (!$sError) {
            // kiem tra bien
            $iCnt = count($aVar);
            for ($i = $iCnt;$i >= 0; $i--) {
                if ($aOpen[$i] == 1) {
                    $bIsExist = 0;
                    for ($j = $i + 1;$j < $iCnt; $j++) {
                        if( $aRelate[$j] == $i && $aRelate[$j] > -1) {
                            for ($iCount = $iCnt - 1;$iCount >= 0; $iCount--) {
                                if ($aPosition[$iCount] > $aPosition[$i] && $aPosition[$iCount] < $aPosition[$j] ) {
                                    $sError = 'Lỗi 3 '.$aVar[$i];
                                    break;
                                }
                            }
                            if (!$sError) {
                                $aPosition[$i] = null;
                                $aPosition[$j] = null;
                            }
                            $bIsExist = 1;
                            break;
                        }
                    }
                    if ($bIsExist == 0) {
                        $sError = 'không tồn tại kết thúc thẻ '.$i.'='.$aVar[$i].'='.$aOpen[$i];
                        break;
                    }
                }
                if ($sError)
                    break;
            }
        }
        for ($i = 0;$i < count($aVar); $i++) {
            if (!$sError) {
                $aTmps = explode('"', $aVar[$i]);
                $iTmps = count($aTmps)-1;
                if ($iTmps % 2 != 0)
                    $sError = 'Thẻ kết thúc không đúng(4)';
            }
            
            if (preg_match("/='(.*)'/i", $aVar[$i])) {
                $sError = "Nội dung chứa thẻ cấm";
                break;
            }
            if ($aVar[$i] != 'img' && $aVar[$i] != 'span' && $aVar[$i] != 'div' && $aVar[$i] != 'table' && $aVar[$i] != 'thead' && $aVar[$i] != 'tbody' && $aVar[$i] != 'th' && $aVar[$i] != 'tr' && $aVar[$i] != 'td' && $aVar[$i] != 'dl' && $aVar[$i] != 'dt' && $aVar[$i] != 'font' && $aVar[$i] != 'a' && $aVar[$i] != 'br' && $aVar[$i] != 'h1' && $aVar[$i] != 'h2' && $aVar[$i] != 'h3' && $aVar[$i] != 'h4' && $aVar[$i] != 'h5' && $aVar[$i] != 'h6' && $aVar[$i] != 'b' && $aVar[$i] != 'i' && $aVar[$i] != 'u' && $aVar[$i] != 'p' && $aVar[$i] != 'a' && $aVar[$i] != 'span' && $aVar[$i] != 'tbody' && $aVar[$i] != 'colgroup' && $aVar[$i] != 'font' && $aVar[$i] != 'br'  && $aVar[$i] != 'blockquote' && $aVar[$i] != 'strong' && $aVar[$i] != 'object' && $aVar[$i] != 'em' && $aVar[$i] != 'pre'  && $aVar[$i] != 'code' && $aVar[$i] != 'sub' && $aVar[$i] != 'sup' && $aVar[$i] != 'ul' && $aVar[$i] != 'ol' && $aVar[$i] != 'li'  && $aVar[$i] != 'label' && $aVar[$i] != 'hr' && $aVar[$i] != 'center')
            {
                $sError = " thẻ cấm: ".$aTmps[0].'-'.$aVar[$i];
                break;
            }
        }
        if (!empty($sError))
            return $sError;
        else
            return '';
    }
    
    /**
    * function tải hình trong nội dung
    */
    public function autoLoadImage($aParam = array())
    {
        $sContent = '';
        if (isset($aParam['content']) && !empty($aParam['content'])) {
            $sContent = $aParam['content'];
        }
        if (empty($sContent)) {
            return array();
        }
        $iUpdateAlt = 0;
        if (isset($aParam['update_alt'])) {
            $sContent = $aParam['update_alt'];
        }
        $sSessionDomain = '';
        if (isset($aParam['session_domain'])) {
            $sContent = $aParam['session_domain'];
        }
        if ($sSessionDomain < 0)
            $sSessionDomain = Core::getDomainName();
        $sContentToLower = mb_strtolower($sContent, 'UTF8');
        //$n = 1;
        //$link = '';
        $aOutput = array();
        if (preg_match('#<input #is', $sContentToLower)) {
            $iPositionLast = 0;
            while (1) {
                $sKeyStart = '<input ';
                $sKeyEnd = '>';
                $iLengthKeyStart = strlen($sKeyStart);
                $iLengthKeyEnd = strlen($sKeyEnd);
                $iPositionFirst = mb_strpos($sContentToLower, $sKeyStart, $iPositionLast, 'UTF8');
                if ( $iPositionLast > $iPositionFirst)
                    break;
                if ($iPositionFirst !== false) {
                    $iPositionLast = mb_strpos($sContentToLower, $sKeyEnd, $iPositionFirst, 'UTF8');
                    if ($iPositionLast !== false) {
                        $sVarChild = mb_substr($sContent, $iPositionFirst, $iPositionLast-$iPositionFirst, 'UTF8');
                        $iPositionFirst += mb_strlen($sKeyStart, 'UTF8');
                        $sVarChild_tolower = mb_strtolower($sVarChild, 'UTF8');
                        if (preg_match('#(type="image")|(type=\'image\')#is', $sVarChild_tolower))  {
                            $iPositionFirstChild = mb_strpos($sVarChild_tolower, 'src="', 0, 'UTF8');
                            if ($iPositionFirstChild !== false) {
                                $iPositionLastChild = mb_strpos($sVarChild_tolower, '"', $iPositionFirstChild + 5, 'UTF8');
                            }
                            if ($iPositionFirstChild === false && $iPositionLastChild === false) {
                                $iPositionFirstChild = mb_strpos($sVarChild_tolower, 'src=\'', 0, 'UTF8');
                                if ($iPositionFirstChild !== false) {
                                    $iPositionLastChild = mb_strpos($sVarChild_tolower, '\'', $iPositionFirstChild + 5, 'UTF8');
                                }
                            }
                            if ($iPositionFirstChild === false && $iPositionLastChild === false) {
                                $iPositionFirstChild = mb_strpos($sVarChild_tolower, 'src=', 0, 'UTF8');
                                if ($iPositionFirstChild !== false) {
                                    $iPositionLastChild = mb_strpos($sVarChild_tolower, ' ', $iPositionFirstChild + 5, 'UTF8');
                                }
                            }
                            if ($iPositionFirstChild === false && $iPositionLastChild === false) {
                                $sVarMain = mb_substr($sContent, $iPositionFirst,$iPositionLast-$iPositionFirst, 'UTF8');
                                $sContent = str_replace($sVarChild, '', $sContent);
                                $sContentToLower = mb_strtolower($sContent,'UTF8');
                            }
                            else {
                                $sVarChild_url = mb_substr($sVarChild, $iPositionFirstChild + 5, $iPositionLastChild - $iPositionFirstChild - 5, 'UTF8');
                                $sContent = str_replace($sVarChild, '<img src="'.$sVarChild_url.'"', $sContent);
                                $sContentToLower = mb_strtolower($sContent,'UTF8');
                            }
                        }
                    }
                    else
                        break;
                }
                else
                    break;
            }
        }
        $iPositionLast = 0;
        while (1) {
            if ($sError)
                break;
            $iPositionFirst = mb_strpos($sContentToLower, '<img ', $iPositionLast, 'UTF8');
            if ( $iPositionLast > $iPositionFirst)
                break;
            if ($iPositionFirst !== false) {
                $iPositionLast = mb_strpos($sContentToLower, '>', $iPositionFirst, 'UTF8');
                if ($iPositionLast !== false) {
                    $iPositionLast += 1;
                    $sHtml = mb_substr($sContent, $iPositionFirst, $iPositionLast-$iPositionFirst, 'UTF8');
                    /*
                    $sHtml_tolower = mb_strtolower($sHtml, 'UTF8');
                    $iPositionFirstChild = mb_strpos($sHtml_tolower, ' src=', 0, 'UTF8');
                    if($iPositionFirstChild!==false)
                    {
                        $iPositionFirstChild += 5;
                        $iPositionLastChild = mb_strpos($sHtml_tolower, ' ', $iPositionFirstChild, 'UTF8');
                        if($iPositionLastChild===false) $iPositionLastChild = mb_strpos($sHtml_tolower, '>', $iPositionFirstChild, 'UTF8');
                        
                    }
                    if($iPositionFirstChild===false || $iPositionLastChild===false ) 
                    {
                        $sError = 'Deny(no position end)';
                    }
                    else
                    {
                        $sVarChild = mb_substr($sHtml, $iPositionFirstChild, $iPositionLastChild-$iPositionFirstChild, 'UTF8');
                    }
                    */
                    
                    preg_match('/src=(.*?)["|\'](.*?)["|\']/is', $sHtml, $aTmp);
                    $sVarChild = $aTmp[2];
                    
                    $sVarChild = str_replace('\\', '', $sVarChild);
                    $sVarChild = str_replace('"', '', $sVarChild);
                    $sVarChild = str_replace('\'', '', $sVarChild);
                    //$sVarChild = preg_replace('#\ .*? #is', '', $sVarChild.' ');
                    $sVarChild = trim($sVarChild);
                    
                    $bIsContinue = false;
                    
                    // xác đinh xem url co nam tren host ko
                    $sDomain = $this->getDomain(array(
                        'url' => $sVarChild,
                        'filter' => false
                    ));
                    // bỏ qua photobucket
                    $bTmp = true;
                    if (strpos($sDomain, 'photobucket.com') !== false) {
                        $bTmp = false;
                    }
                    if ($bTmp) {
                        /*
                            xoa bo phan img. va s.
                                lay chuoi ky tu dau tien tu dau tinh vao toi dau .
                        */
                        if (!empty($sDomain)) {
                            $iPositionExtra = strpos($sDomain, '.');
                            $sDomainExtra = substr($sDomain, 0, $iPositionExtra);
                            if ($sDomainExtra == 'img' || $sDomainExtra == 's') {
                                $sDomain = substr($sDomain, $iPositionExtra+1);
                            }
                        }
                        
                        /*
                        danh cho CMS cu, khi ma tinymcs chua format URL theo kieu giu nguyen domain, ma thay the domain bang ../
                        $sVarChild = str_replace('../../', $chuoi_url, $sVarChild);
                        */
                        if ($sDomain != $sSessionDomain) {
                            $bIsContinue = true;
                        }
                        else {
                            // check xem có nằm ở thư mục hình tạm hay không, nếu có thì chuyển
                            if (!empty($sDomain) && strpos($sVarChild, '/files/'.$config['thu_muc_tam'].'/') !== false){
                                $bIsContinue = true;
                            }
                            else
                                $bIsContinue = false;
                            // end
                        }
                    }
                    if (!empty($sError))
                        $bIsContinue = false;
                    if ($bIsContinue) {
                        // lấy thẻ alt và cập nhật
                        if ($iUpdateAlt) {
                            $aOutput['part'][] = $sHtml;
                        }
                        $aOutput[] = $sVarChild;
                    }
                }
                else
                {
                    $sError = 'No value in content';
                }
            }
            else
                break;
        }
        return $aOutput;
    }
    
    public function getDomain($aParam = array())
    {
        $sUrl = '';
        if (isset($aParam['url'])) {
            $sUrl = $aParam['url'];
        }
        $bIsFilter = false;
        if (isset($aParam['filter'])) {
            $bIsFilter = $aParam['filter'];
        }
        if (!empty($sUrl) && strpos($sUrl, '://') !== false ) {
            $iPosition = mb_strpos($sUrl, '/', 7, 'utf8');
            if ($iPosition !== false) {
                $sDomain = mb_substr($sUrl, 7, ($iPosition-7), 'utf8');
            }
            else
                $sDomain = mb_substr($sUrl.'.', 7, -1, 'utf8');
            // remove port
            $aTmps = explode(':', $sDomain);
            $sDomain = $aTmps[0];
            
            // filter dung de lay ten mmien chinh, bao gom phan ten mien dung truoc va duoi cua ten mien
            if ($bIsFilter) {
                $sDomain = explode('.', $sDomain);
                $iPosition = count($sDomain)-1;
                $sDomain = $sDomain[$iPosition-1].'.'.$sDomain[$iPosition];
            }
            return
                $sDomain;
        }
        else
            return '';
    }
    
    public function addCronJob($aParam = array())
    {
        $aInsert = array();
        $sConds = '';
        foreach ($aParam as $sKey => $sVal) {
            $sConds .= $sKey.'= "'.addslashes($sVal).'",';
            $aInsert[$sKey] = addslashes($sVal);
        }
        if ($aParam['domain_id'] < 1) {
            $aParam['domain'] = Core::getDomainName();
            $aParam['domain_id'] = Core::getDomainId();
        }
        $aInsert['domain_id'] = $aParam['domain_id'];
        $aInsert['domain'] = addslashes($aParam['domain']);
        $this->database()->insert(Core::getT('cronjob'), $aInsert);
        
        return true;
    }
    
    public function saveLogSystem($aParam = array())
    {
        $sAction = '';
        if (isset($aParam['action'])) {
            $sAction = $aParam['action'];
        }
        $sContent = 'phpinfo';
        if (isset($aParam['content'])) {
            $sContent = $aParam['content'];
        }
        $sPath = '';
        if (isset($aParam['path'])) {
            $sPath = $aParam['path'];
        }
        if ($sContent == 'phpinfo') {
            $sContent = array(
                //'get' => $_GET,
                //'post' => $_POST,
                'request' => Core::getLib('request')->getRequests(),
            );
        }
        
        if (empty($sPath))
            $sPath = '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $aInsert = array(
            'time' => CORE_TIME,
            'path' => addslashes($sPath),
            'user_id' => Core::getUserId(),
            'domain_id' => (int)Core::getDomainId(),
            'action' => addslashes($sAction),
            'content' => addslashes(serialize($sContent))
        );
        
        $iId = $this->database()->insert(Core::getT('log_system'), $aInsert);
        return $iId;
    }
    
    public function searchEngineQueryString($sUrl = false) 
    {
        if(!$sUrl && !$sUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false) {
            return '';
        }
        $aPart = parse_url($sUrl);
        $sQuery = isset($aPart['query']) ? $aPart['query'] : (isset($aPart['fragment']) ? $aPart['fragment'] : '');
        if(!$sQuery) {
            return '';
        }
        parse_str($query, $aQuery);
        return isset($aQuery['q']) ? $aQuery['q'] : (isset($aQuery['p']) ? $aQuery['p'] : '');
    }
    
    public function removeDuplicate($sStr, $sKey = '  ', $sReplace = ' ')
    {
        if($sKey == $sReplace) 
            return $sStr;
        while(1) {
            if (strpos($sStr, $sKey) !==false) {
                $sStr = str_replace($sKey, $sReplace, $sStr);
            }
            else break;
        }
        return $sStr;
    }
    
    public function removeNewLine($sContent, $sReplace = '')
    {
        $sContent = str_replace(array("\t", "\r\n", "\n\r", "\n", "\r"), $sReplace, $sContent);
        return $sContent;
    }
    
    public function paginate($nPageCnt, $nCurrPage, $sUrl, $fPageUrl, $fPageTitle, $title, $nMaxPage = 10, $lang = null, $setting = array('mo_rong'=>1))
    {
        if($nPageCnt == 0 || $nPageCnt == 1)
            return '';

        //set default lang
        if($lang == null) {
            $lang = array(
            'first' => '&lt;&lt;',
            'previous' => '&lt;',
            'next' => '&gt;',
            'last' => '&gt;&gt;',
            'currPage' => 'Trang hiện tại'
            );
        }
        // check setting xử lý ffashion
        /*
        Cấu trúc quy định

        */
        $sOut    =   '';

        $bDrewDots = true;

        if($bDrewDots)
            $dotsString = '<span class="paginate_dot">...</span>';
        else 
            $dotsString = '';


        if ($nPageCnt > $nMaxPage)  {
            $tmp = floor($nMaxPage /2);
            if (1 > ($nCurrPage - $tmp))  {
                $nStart = 1;
                $nEnd  = $nMaxPage;
            } 
            elseif ($nPageCnt < ($nCurrPage + $tmp))  {
                $nStart = $nPageCnt - $nMaxPage;
                $nEnd  = $nPageCnt;
            } 
            else {
                $nStart = $nCurrPage - $tmp;
                $nEnd  = $nCurrPage + $tmp;
            }//if
        } 
        else  {
            $nStart = 1;
            $nEnd  = $nPageCnt;
        }//if
        $a = 1;
        if($nStart == 1) {
            if($nCurrPage == 1)
                $sOut .= '<a class="n atv" href="' . $fPageUrl . '" title="' . $fPageTitle . '">'.$a.'</a>';
            else
                $sOut .= '<a class="n" href="' . $fPageUrl . '" title="' . $fPageTitle . '">'.$a.'</a>';
                $nStart = 2;
        }
        for ($a = $nStart; $a <= $nEnd; $a++)  {
            if ($a == $nCurrPage)
                $sOut .= '<a class="n atv" title="'.$lang['currPage'].'">'.$a.'</a>';
            else
            $sOut .= '<a class="n" href="' . str_replace('::PAGE::', $a, $sUrl) . '" title="'.sprintf($title, $a).'">'.$a.'</a>';

        }//for
        if($setting['mo_rong'] != 1)
            $setting['mo_rong'] = 0;
            
        if($setting['mo_rong'] == 1) {
            if ($nStart > 3)  {
                $sOut = '
                <a class="n" href="' . $fPageUrl . '" title="' . $fPageTitle . '">1</a>
                <a class="n" href="' . str_replace('::PAGE::', 2, $sUrl) . '" title="'.sprintf($title, 2).'">2</a>
                '.$dotsString.'
                ' . $sOut;
            }//if

            if ($nEnd < ($nPageCnt - 3))  {
                $sOut .= $dotsString .'
                <a class="n" href="' . str_replace('::PAGE::', $nPageCnt - 1, $sUrl) . '" title="'.sprintf($title, ($nPageCnt - 1)) . '">' . ($nPageCnt-1) . '</a>
                <a class="n" href="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'.sprintf($title, $nPageCnt) . '">' .$nPageCnt . '</a>
                ' ;
                // die($sOut);
            }//if
        }
        //insert previous/next button
        $class = '';
        if ($nCurrPage <= 1 || ($nCurrPage > 1 && $nCurrPage == 2)) $class = ' none';


        $class = '';
        if ($nCurrPage <= 1 || ($nCurrPage > 1 && $nCurrPage != 2)) $class = ' none';

        $tmp = '';
        if ($nCurrPage > 1 && $nCurrPage == 2) {
            $tmp = ' href="' . $fPageUrl . '" title="' . $fPageTitle . '"';
        }
        elseif ($nCurrPage > 1) {
            $tmp = $nCurrPage - 1;
            $tmp = ' href="' . str_replace('::PAGE::', $tmp, $sUrl) . '" title="'.sprintf($title, $tmp).'"';
        }
        $sOut = '<a class="n'.$class.'"'.$tmp.'>'.$lang['previous'].'</a>' . $sOut;

        $class = '';
        if ($nCurrPage >= $nPageCnt) 
            $class = ' none';

        $tmp = $nCurrPage + 1;
        $sOut .= '<a href="' . str_replace('::PAGE::', $tmp, $sUrl) . '" class="n'.$class.'" title="'.sprintf($title, $tmp).'">'.$lang['next'].'</a>';

        if($setting['mo_rong'] == 1) {
            //insert first/last button
            $class = '';
            if ($nCurrPage <= 2) $class = ' none';
                $sOut = '<a class="n'.$class.'" href="' . $fPageUrl . '" title="' . $fPageTitle . '">'.$lang['first'].'</a>' . $sOut;

            $class = '';
            if($nCurrPage <= ($nPageCnt-1)) $class = ' none';
                $sOut .= '<a class="n'.$class.'" href="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'.sprintf($title, $nPageCnt).'">'.$lang['last'].'</a>';
        }
        $sOut = '<div class="pagination"><div class="p">' . $sOut . '</div></div>';  

        //not include /page-1 in url
        $sOut = preg_replace('/\/page-1([^\d])/', '\1', $sOut);

        return $sOut;

    }
    
    // cập nhật Menu
    public function updateMenu()
    {
        // xóa menu
        $this->cache()->remove('tm|'.Core::getDomainId().'|menu');
        
        //clear cache
        Core::getService('core')->removeCache();
    }
    
    public function processKeyword($sStr)
    {
        $sStr = $this->strip_symbols($sStr);
        $sStr = $this->strip_punctuation($sStr);
        $sStr = $this->strip_numbers($sStr);
        $sStr = mb_strtolower($sStr,"UTF8");
        $sStr = str_replace("
", '', $sStr);
        $sStr = str_replace("\n", '', $sStr);
        $sStr = str_replace(array('?', '`', '~', '!', '@', '#', '$', '%', '^', '&', '*', "{","}","(",")", ":", ";", '"', "'", '"', '/', '\\', '|', '+', '=', '-', '*'), " ", $sStr);
    //    $sStr = preg_replace("(\d+)","", $sStr); 
        $sStr = mb_strtolower(trim($sStr),"UTF8");

        $sStr = Core::getLib('input')->removeDuplicate(array('text' => $sStr));
        
        $tu_loai = array("ốm", "vài", "ấy", "bi", "bạn", "bởi", "cách", "chắc", "cho", "chơi", "có", "các", "chăng", "cũng", "chúng", "dù", "đang", "đã", "để", "đi", "đó", "được", "đúng","em", "gì", "hay", "hơn", "họ", "khi", "kia", "kìa", "kỉa", "kịa", "phải", "mày", "mà", "nếu", "nào", "này", "nọ", "nó", "những", "nên", "như", "là", "làm", "lại", "rồi", "rùi", "ơi", "ôi", "ta", "tôi", "tao", "tại", "thì", "trên", "trong", "xa", "với", "vừa", "vào", "vì", "về", ".", ",", "\"", "'", "của", "mình", "thấy","nhiều", "ít", "cũ", "mới", "dễ", "rất", "tất", "ý", "tới", "&quot;", "ở", "chạy", "một", "do", "và", "luôn", "muốn", "chỉnh", "sửa", "thể", "khởi", "thậm", "hết", "tạo", "khác", "nhanh", "chậm", "ngắn", "dài", "chung", "khá", "tốt", "sẽ",  "hoặc", "giữa", 'dưới', 'trên', '"', 'gần', 'xa', 'vẫn', 'theo', 'lần', 'mỗi', 'lớn', 'rằng', 'so', 'tăng', 'thêm', 'gặp', 'quá', 'nhưng', 'kém');
            $cum_tu = array(
            "ào ào", 
            'ảnh hưởng',
            'ấn tượng',
            "bây giờ",
            'bao giờ',
            'bao gồm',
            'ban hành',
            'ban đầu',
            'bản thân',
            'bắt đầu',
            'bất cứ',
            'bất kể',
            'bên cạnh',
            'biện pháp',
            'bình tâm',
            'bình tĩnh',
            'bình thường',
            'bỏ qua',
            'cảm giác',
            'cạnh tranh',
            'cải thiện',
            'cải tạo',
            'cải vã',
            'cấp cho',
            "chăm chắm",
            'chỉ đạo',
            "chia sẻ", 
            'cho dù',
            'cho các',
            'chủ yếu',
            'chưa kịp',
            'chưa kiệp',
            'chuyên gia',
            'chuẩn bị',
            'cùng ngày',
            'cung cấp',
            "co ro", 
            'cơ sở',
            'cơ chế',
            'con người',
            'công bố',
            'công việc',
            'đáp ứng',
            'đáng kể',
            'đầu tiên',
            'đảm bảo',
            'dạo đầu',
            "dồn dập", 
            'di chuyển',
            'diễn ra',
            'dự kiến',
            'dự định',
            'dự lễ',
            'đa số',
            'đa phần',
            "góp ý", 
            'giảm mức',
            'giải pháp',
            'giúp đỡ',
            'giúp ích',
            "háo hức",
            'hậu quả',
            'hậu thuẫn',
            'hàng loạt',
            'hiệu quả',
            'hiện nay',
            'hoàn toàn',
            'hoàn thành',
            'hoàn tất',
            'hỗ trợ',
            'hôm nay',
            'hôm qua',
            'hôm kia',
            'hôm sau',
            'hướng dẫn',
            'mạnh khỏe',
            'mau chóng',
            'mau thuẫn',
            'mâu thuẫn',
            'mọi chiện',
            'mọi chuyện',
            'mọi người',
            'mọi thứ',
            'mọi việc',
            'mọi vật',
            'mục tiêu',
            'mục đích',
            "mời gọi", 
            "nơi trao", 
            'nhẹ nhàng',
            'nhỏ gọn',
            'nóng bỏng',
            'nhiệm vụ',
            'niêm yết',
            'nhu cầu',
            'nguy hiểm',
            'người dùng',
            "phát triển",  
            'phiên bản',
            'phương pháp',
            'lần đầu',
            "làm sao",
            'lao vào', 
            'lĩnh vực',
            'lưu ý',
            'kềnh càng',
            'kích thước',
            'kiềm chế',
            'kiềm hãm', 
            'khả năng',
            'khó khăn',
            'khó quên',
            'khôn lường',
            "khe khẽ", 
            "ra sao", 
            'quan trọng',
            'ổn định',
            "tâm huyết",
            'tâm trạng', 
            'tập trung',
            'thay đổi',
            'tham gia',
            'thắt chặt',
            'thắt lưng',
            'theo bà',
            'theo ông',
            'thị trường',
            "thúc giục", 
            "thực hiện", 
            "thực tế", 
            'thường xuyên',
            'tích hợp',
            'tiểu số',
            'tối đa',
            'tối thiểu',
            'tất cả',
            'tránh né',
            'trang bị',
            "trở thành", 
            "trở nên", 
            'trốn tránh',
            'trước đó',
            'trung bình',
            "từ phía",
            'tuy vậy',
            'tuy nhiên',
            'sẵn sàng',
            'số ít',
            'số nhiều',
            'số liệu',
            'sử dụng',
            "vấn đề",
            "ví dụ",
            'ưu tiên',
            'ủng hộ',
            'yếu tố',
            'tình trạng');
        $cum_tu_3_chu = array(
            'biết sao giờ',
            'cuộc cải vã',
            'chỉ ảnh hưởng',
            'chính bản thân',
            'để thực hiện',
            'ngay lập tức',
            'ngay sau khi',
            'không ai khác',
            'không biết sao',
            'không hiểu sao',
            'ảnh minh họa',
            "làm thế nào"
            );
        $sStr = preg_replace('/<.*?>/', '', $sStr);
        for($i=0;$i<count($cum_tu_3_chu);$i++)
        {
            $sStr = str_replace($cum_tu_3_chu[$i], "", $sStr);
        }
        for($i=0;$i<count($cum_tu);$i++)
        {
            $sStr = str_replace($cum_tu[$i], "", $sStr);
        }
        $data = explode(" ", $sStr);
        $sStr = "";
        for($i=0;$i<count($data);$i++)
        {
            $error = false;
            for($j=0;$j<count($tu_loai);$j++)
            {
                if($tu_loai[$j]==$data[$i])
                {
                    $data[$i] = "";
                    $error = true;
                    break;
                }
            }
            if(!$error) $sStr .= $data[$i]." ";
        }
        
        $sStr = Core::getLib('input')->removeDuplicate(array('text' => $sStr));
        $sStr = trim($sStr);
        return $sStr;
    }
    
    public function strip_punctuation( $text )
    {
        $urlbrackets    = '\[\]\(\)';
        $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
        $urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
        $urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
     
        $specialquotes  = '\'"\*<>';
     
        $fullstop       = '\x{002E}\x{FE52}\x{FF0E}';
        $comma          = '\x{002C}\x{FE50}\x{FF0C}';
        $arabsep        = '\x{066B}\x{066C}';
        $numseparators  = $fullstop . $comma . $arabsep;
     
        $numbersign     = '\x{0023}\x{FE5F}\x{FF03}';
        $percent        = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
        $prime          = '\x{2032}\x{2033}\x{2034}\x{2057}';
        $nummodifiers   = $numbersign . $percent . $prime;
     
        return preg_replace(
            array(
            // Remove separator, control, formatting, surrogate,
            // open/close quotes.
                '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
            // Remove other punctuation except special cases
                '/\p{Po}(?<![' . $specialquotes .
        $numseparators . $urlall . $nummodifiers . '])/u',
            // Remove non-URL open/close brackets, except URL brackets.
                '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
            // Remove special quotes, dashes, connectors, number
            // separators, and URL characters followed by a space
                '/[' . $specialquotes . $numseparators . $urlspaceafter .
        '\p{Pd}\p{Pc}]+((?= )|$)/u',
            // Remove special quotes, connectors, and URL characters
            // preceded by a space
                '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
            // Remove dashes preceded by a space, but not followed by a number
                '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
            // Remove consecutive spaces
                '/ +/',
            ),
            ' ',
            $text );
    }
    public function strip_numbers( $text )
    {
        $urlchars      = '\.,:;\'=+\-_\*%@&\/\\\\?!#~\[\]\(\)';
        $notdelim      = '\p{L}\p{M}\p{N}\p{Pc}\p{Pd}' . $urlchars;
        $predelim      = '((?<=[^' . $notdelim . '])|^)';
        $postdelim     = '((?=[^'  . $notdelim . '])|$)';
     
        $fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
        $comma         = '\x{002C}\x{FE50}\x{FF0C}';
        $arabsep       = '\x{066B}\x{066C}';
        $numseparators = $fullstop . $comma . $arabsep;
        $plus          = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
        $minus         = '\x{2212}\x{208B}\x{207B}\p{Pd}';
        $slash         = '[\/\x{2044}]';
        $colon         = ':\x{FE55}\x{FF1A}\x{2236}';
        $units         = '%\x{FF05}\x{FE64}\x{2030}\x{2031}';
        $units        .= '\x{00B0}\x{2103}\x{2109}\x{23CD}';
        $units        .= '\x{32CC}-\x{32CE}';
        $units        .= '\x{3300}-\x{3357}';
        $units        .= '\x{3371}-\x{33DF}';
        $units        .= '\x{33FF}';
        $percents      = '%\x{FE64}\x{FF05}\x{2030}\x{2031}';
        $ampm          = '([aApP][mM])';
     
        $digits        = '[\p{N}' . $numseparators . ']+';
        $sign          = '[' . $plus . $minus . ']?';
        $exponent      = '([eE]' . $sign . $digits . ')?';
        $prenum        = $sign . '[\p{Sc}#]?' . $sign;
        $postnum       = '([\p{Sc}' . $units . $percents . ']|' . $ampm . ')?';
        $number        = $prenum . $digits . $exponent . $postnum;
        $fraction      = $number . '(' . $slash . $number . ')?';
        $numpair       = $fraction . '([' . $minus . $colon . $fullstop . ']' .
            $fraction . ')*';
     
        return preg_replace(
            array(
            // Match delimited numbers
                '/' . $predelim . $numpair . $postdelim . '/u',
            // Match consecutive white space
                '/ +/u',
            ),
            ' ',
            $text );
    }
    public function strip_symbols( $text )
    {
        $plus   = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
        $minus  = '\x{2012}\x{208B}\x{207B}';
     
        $units  = '\\x{00B0}\x{2103}\x{2109}\\x{23CD}';
        $units .= '\\x{32CC}-\\x{32CE}';
        $units .= '\\x{3300}-\\x{3357}';
        $units .= '\\x{3371}-\\x{33DF}';
        $units .= '\\x{33FF}';
     
        $ideo   = '\\x{2E80}-\\x{2EF3}';
        $ideo  .= '\\x{2F00}-\\x{2FD5}';
        $ideo  .= '\\x{2FF0}-\\x{2FFB}';
        $ideo  .= '\\x{3037}-\\x{303F}';
        $ideo  .= '\\x{3190}-\\x{319F}';
        $ideo  .= '\\x{31C0}-\\x{31CF}';
        $ideo  .= '\\x{32C0}-\\x{32CB}';
        $ideo  .= '\\x{3358}-\\x{3370}';
        $ideo  .= '\\x{33E0}-\\x{33FE}';
        $ideo  .= '\\x{A490}-\\x{A4C6}';
     
        return preg_replace(
            array(
            // Remove modifier and private use symbols.
                '/[\p{Sk}\p{Co}]/u',
            // Remove mathematics symbols except + - = ~ and fraction slash
                '/\p{Sm}(?<![' . $plus . $minus . '=~\x{2044}])/u',
            // Remove + - if space before, no number or currency after
                '/((?<= )|^)[' . $plus . $minus . ']+((?![\p{N}\p{Sc}])|$)/u',
            // Remove = if space before
                '/((?<= )|^)=+/u',
            // Remove + - = ~ if space after
                '/[' . $plus . $minus . '=~]+((?= )|$)/u',
            // Remove other symbols except units and ideograph parts
                '/\p{So}(?<![' . $units . $ideo . '])/u',
            // Remove consecutive white space
                '/ +/',
            ),
            ' ',
            $text );
    }
    
    public function separatedKeyword($str)
    {
        if(empty($str)) return array(array(), array());
        // ghi t
        $sLink = Core::getParam('core.dir').'/cache/data/'.Core::getDomainName();
        if(!file_exists($sLink)) mkdir($sLink);
        $sLink = $sLink.'/'.Core::getUserId().'-'.microtime(true).'.kw';
        
        $fp = fopen($sLink, 'wb');
        fwrite($fp, $str);
        fclose($fp);
        
        if(!Core::getParam('core.local'))
        {
            //$tmp = shell_exec(Core::getParam('core.dir').'/includes/vnTokenizer/exc.sh -i '.$sLink.' -o '.$sLink.'.xml -xo');
            chdir(Core::getParam('core.dir').'/includes/library/vnTagger/');
            $tmp = shell_exec('sh exc.sh -i '.$sLink.' -o '.$sLink.'.xml');
        }
        else
        {
            $tmp = shell_exec(Core::getParam('core.dir').'/includes/library/vnTagger/exc.bat -i '.$sLink.' -o '.$sLink.'.xml');
        }
        /*
            1.  Np - Proper noun
            2.  Nc - Classifier
            3.  Nu - Unit noun
            4.  N - Common noun
            5.  V - Verb
            6.  A - Adjective
            7.  P - Pronoun
            8.  R - Adverb
            9.  L - Determiner
            10. M - Numeral
            11. E - Preposition
            12. C - Subordinating conjunction
            13. CC - Coordinating conjunction
            14. I - Interjection
            15. T - Auxiliary, modal words
            16. Y - Abbreviation
            17. Z - Bound morphemes
            18. X - Unknown
        */
        // đọc tập tin, để tách hệ thống.
        $arrs = array();
        $xml = simplexml_load_file($sLink.'.xml');
        foreach($xml->s as $line)
        {
            foreach($line->w as $key => $value)
            {
                $arrs[] = array(
                    'type' => trim($value['pos']),
                    'value' => trim($value),
                );
            }
        }
        /*
        foreach($arrs as $i => $arr)
        {
            echo $arr['type'].': '.$arr['value'].'<br>';
        }
        */
        /* 
        unset($arrs);
        $arrs = array(
            array(
                'type' => 'V',
                'value' => 'Cung cp',
            ),
            array(
                'type' => 'Np',
                'value' => 'Quoc gi',
            ),
            array(
                'type' => 'Np',
                'value' => 'Đông',
            ),
            array(
                'type' => 'Np',
                'value' => 'U',
            ),
        );
        /* */
        
        $key = array();
        for($i=0; $i < count($arrs); $i++)
        {
            $arr = $arrs[$i];
            $cum_tu = strpos($arr['value'], ' ');
            if($cum_tu  === false) $cum_tu = false;
            else $cum_tu = true;
            
            if(!$cum_tu && mb_strlen($arr['value']) != strlen($arr['value']))// || (($arr['type'] != 'N' && $arr['type'] != 'Np' && $arr['type'] != 'Nc' && $arr['type'] != 'Nu') && !$cum_tu))
            {
                $arrs[$i]['value'] = '';
                continue;
            }
            if($arr['type'] == $arrs[$i+1]['type'])
            {
                $arrs[$i+1]['value'] = $arr['value'].' '.$arrs[$i+1]['value'];
                
                $key[] = $arr['value'];
            
                $arrs[$i]['value'] = '';
                continue;
            }
        }
        foreach($arrs as $i => $arr)
        {
            if(empty($arr['value'])) continue;
            $key[] = $arr['value'];
        }
        return array($key, array());
    }
    
    public function selfURL()
    {
        $sHttps = $_SERVER["HTTPS"];
        $s = empty($sHttps) ? '' : ($sHttps == "on") ? "s" : "";
        $protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        return $protocol."://".$_SERVER['HTTP_HOST'].$port.$_SERVER['REQUEST_URI'];
    }
    
    function strleft($s1, $s2) {
        return substr($s1, 0, strpos($s1, $s2)); 
    }
    
    public function splitUrl($sUrl, $sKey)
    {
        if (empty($sUrl) || empty($sKey)) {
            return $sUrl;
        }
        
        $iPosition = strpos($sUrl, $sKey.'=');
        if ($iPosition === false) {
            return $sUrl;
        }
        else {
            $sUrlFirst = substr($sUrl, 0,$iPosition-1);
            $sTmp = substr($sUrl, $iPosition);
            $iPositionLast = strpos($sTmp, '&');
            if ($iPositionLast === false) {
                $sUrl = $sUrlFirst;
            }
            else {
                $sUrlLast = substr($sTmp, $iPositionLast);
                $sUrl = $sUrlFirst.$sUrlLast;
            }
        }
        return $sUrl;
    }
    
    public function hasString($aNeedle = array(), $sString = '', $bEqual = false)
    {
        if(empty($sString) || empty($aNeedle)) return false;
        
        foreach($aNeedle as $v)
        {
            if( (!$bEqual && mb_strpos($sString, $v) === false) || ($bEqual && $sString != $v) ) continue;
            return true;
        }
        return false;
    }
    
    // hàm xóa từ phải qua 
    public function str_mreplace($search, $rep, $str, $margin = 'right')
    {
        $pos = mb_strlen($search);
        
        if($margin == 'right')
        {
            $pos *= -1;
            $str = mb_substr($str, 0, $pos).$rep;
        }
        else
        {
            $str = $rep.mb_substr($str, $pos);
        }
        return $str;
    }
    
    public function sendEmail($sEmail, $sUser, $sTitle, $sContent)
    {
        $sEmail =  explode(',', $sEmail);
        $user =  explode(',', $user);
        foreach($sEmail as $i => $v) {
            // check structure email
            if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $sEmail[$i])==0)
            {
                unset($sEmail[$i]);
                unset($user[$i]);
            }
        }
        if(empty($sEmail) || empty($sContent)) return false;
        
        $oSession = Core::getLib('session');
        $aSessionEmailSetting = $oSession->get('session-setting_gmail');
        
        if(empty($aSessionEmailSetting)) {
            $aRow = $this->database()->select('email')
                ->from(Core::getT('domain_setting'))
                ->where('domain_id ='.Core::getDomainId())
                ->execute('getRow');
            $data = $aRow['email'];
            if (!empty($data)) {
                $endecode = Core::getLib('endecode');
                $endecode->content = $data;
                $data = $endecode->giai_ma();
                $data = json_decode($data, 1);
                $oSession->set('session-setting_gmail', $data);
            }
        }
        else {
            $data = $aSessionEmailSetting;
        }
        
        if (!empty($data)) {
            $host = $data[0];
            $port = $data[1];
            $smtpsecure = $data[2];
            $username = $data[3];
            $password = $data[4];
            $from = $data[5];
        }
        if ($data != '') {
            $mail             = Core::getLib('phpmailer');
            $mail->IsSMTP(); // telling the class to use SMTP
            //$mail->Host       = "mail.vohoangtuan.com"; // SMTP server
            //$mail->SMTPDebug     = 2; //debug
            
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = $smtpsecure;                 // sets the prefix to the servier
            $mail->Host       = $host;      // sets GMAIL as the SMTP server
            $mail->Port       = $port;                   // set the SMTP port for the GMAIL server
            $mail->Username   = $username;  // GMAIL username
            $mail->Password   = $password;            // GMAIL password
            $mail->CharSet    = 'utf-8';
            $mail->ContentType= 'text/html';
            $mail->SetFrom($username, $from);
            //core_log($mail, 'a+');
            //$mail->AddReplyTo("no-reply@vohoangtuan.com","Thư tự động, vui lòng không gửi lại");
            
            $mail->Subject    = $sTitle;
            
            $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            
            $mail->MsgHTML($sContent);
            
            foreach($sEmail as $i => $v)
            {
                $mail->AddAddress($v, $user[$i]);
            }
            
            $value = $mail->Send();
            if(!$value) return $value;
            else return 'success';
        }
        else
        {
            $header = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $header .= 'From: '.Core::getDomainName().' <info@'.Core::getDomainName().'>' . "\r\n";
            
            $tmp = '';
            foreach($sEmail as $i => $v)
            {
                $tmp .= $user[$i].' <'.$v.'>,';
            }
            $tmp = mb_substr($tmp, 0, -1);
            $value = mail($tmp, '=?UTF-8?B?'.base64_encode($sTitle).'?=', $sContent, $header);
            if($value) return 'success';
            else return '111'.$value.'-'.$sEmail;
        }
    }

    public function getUniqueNumber($iTotalNumber)
    {
        $array = range(0, 9);
        //Initialize the random generator
        srand ((double)microtime()*1000000);
        $numberOfRandoms = $iTotalNumber;
        
        //A for-loop which selects every run a different random number
        for($x = 0; $x < $numberOfRandoms; $x++)
        {
             //Generate a random number
             $i = rand(1, count($array))-1;
         
             //Take the random number as index for the array
             $result[] = $array[$i];
         
             //The chosen number will be removed from the array
             //so it can't be taken another time
             array_splice($array, $i, 1);
        }
        return implode('', $result);
    }
    
    public function newPaginate($nPageCnt, $nCurrPage, $sUrl, $fPageUrl, $fPageTitle, $title,  $iPageSize = 10, $nMaxPage = 10, $lang = null, $setting = array('mo_rong'=>1))
    {
        //tổng số cần lấy (page_size)
        $aPageSize = array(
            10 => 'Xem 10',
            20 => 'Xem 20',
            30 => 'Xem 30',
        );
        
        if($nPageCnt == 0 || $nPageCnt == 1) {
            $sOut = '<div class="trp-pagi">'; 
            $sOut .= '<select name="page_size" id="page_size" class="pagi-select">';
            foreach ($aPageSize as $sKey => $sVal) {
                $sSelect = '';
                if ($sKey == $iPageSize) {
                    $sSelect = 'selected';
                }
                $sOut .= '<option value='.$sKey.' '.$sSelect.'>'.$sVal.'</option>';
            }
            $sOut.= '</select>'.'</div>';
            return $sOut;
        }
            

        //set default lang
        if($lang == null) {
            $lang = array(
            'first' => '&lt;&lt;',
            'previous' => '&lt;',
            'next' => '&gt;',
            'last' => '&gt;&gt;',
            'currPage' => 'Trang hiện tại'
            );
        }
        // check setting xử lý ffashion
        /*
        Cấu trúc quy định

        */
        $sOut    =   '';

        $bDrewDots = true;

        if($bDrewDots)
            $dotsString = '<span class="paginate_dot">...</span>';
        else 
            $dotsString = '';


        if ($nPageCnt > $nMaxPage)  {
            $tmp = floor($nMaxPage /2);
            if (1 > ($nCurrPage - $tmp))  {
                $nStart = 1;
                $nEnd  = $nMaxPage;
            } 
            elseif ($nPageCnt < ($nCurrPage + $tmp))  {
                $nStart = $nPageCnt - $nMaxPage;
                $nEnd  = $nPageCnt;
            } 
            else {
                $nStart = $nCurrPage - $tmp;
                $nEnd  = $nCurrPage + $tmp;
            }//if
        } 
        else  {
            $nStart = 1;
            $nEnd  = $nPageCnt;
        }//if
        $a = 1;
        if($nStart == 1) {
            if($nCurrPage == 1)
                $sOut .= '<a class="item-trp-pagi atv" href="' . $fPageUrl . '" title="' . $fPageTitle . '">'.$a.'</a>';
            else
                $sOut .= '<a class="item-trp-pagi" href="' . $fPageUrl . '" title="' . $fPageTitle . '">'.$a.'</a>';
                $nStart = 2;
        }
        for ($a = $nStart; $a <= $nEnd; $a++)  {
            if ($a == $nCurrPage)
                $sOut .= '<a class="item-trp-pagi atv" title="'.$lang['currPage'].'">'.$a.'</a>';
            else
            $sOut .= '<a class="item-trp-pagi" href="' . str_replace('::PAGE::', $a, $sUrl) . '" title="'.sprintf($title, $a).'">'.$a.'</a>';

        }//for
        if($setting['mo_rong'] != 1)
            $setting['mo_rong'] = 0;
            
        if($setting['mo_rong'] == 1) {
            if ($nStart > 3)  {
                $sOut = '
                <a class="item-trp-pagi" href="' . $fPageUrl . '" title="' . $fPageTitle . '">1</a>
                <a class="item-trp-pagi" href="' . str_replace('::PAGE::', 2, $sUrl) . '" title="'.sprintf($title, 2).'">2</a>
                '.$dotsString.'
                ' . $sOut;
            }//if

            if ($nEnd < ($nPageCnt - 3))  {
                $sOut .= $dotsString .'
                <a class="item-trp-pagi" href="' . str_replace('::PAGE::', $nPageCnt - 1, $sUrl) . '" title="'.sprintf($title, ($nPageCnt - 1)) . '">' . ($nPageCnt-1) . '</a>
                <a class="item-trp-pagi" href="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'.sprintf($title, $nPageCnt) . '">' .$nPageCnt . '</a>
                ' ;
                // die($sOut);
            }//if
        }
        //insert previous/next button
        $class = '';
        if ($nCurrPage <= 1 || ($nCurrPage > 1 && $nCurrPage == 2)) $class = ' none';


        $class = '';
        if ($nCurrPage <= 1 || ($nCurrPage > 1 && $nCurrPage != 2)) $class = ' none';

        $tmp = '';
        if ($nCurrPage > 1 && $nCurrPage == 2) {
            $tmp = ' href="' . $fPageUrl . '" title="' . $fPageTitle . '"';
        }
        elseif ($nCurrPage > 1) {
            $tmp = $nCurrPage - 1;
            $tmp = ' href="' . str_replace('::PAGE::', $tmp, $sUrl) . '" title="'.sprintf($title, $tmp).'"';
        }
        $sOut = '<a class="item-trp-pagi'.$class.'"'.$tmp.'>'.$lang['previous'].'</a>' . $sOut;

        $class = '';
        if ($nCurrPage >= $nPageCnt) 
            $class = ' none';

        $tmp = $nCurrPage + 1;
        $sOut .= '<a href="' . str_replace('::PAGE::', $tmp, $sUrl) . '" class="item-trp-pagi'.$class.'" title="'.sprintf($title, $tmp).'">'.$lang['next'].'</a>';

        if($setting['mo_rong'] == 1) {
            //insert first/last button
            $class = '';
            if ($nCurrPage <= 2) $class = ' none';
                $sOut = '<a class="item-trp-pagi'.$class.'" href="' . $fPageUrl . '" title="' . $fPageTitle . '">'.$lang['first'].'</a>' . $sOut;

            $class = '';
            if($nCurrPage <= ($nPageCnt-1)) $class = ' none';
                $sOut .= '<a class="item-trp-pagi'.$class.'" href="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'.sprintf($title, $nPageCnt).'">'.$lang['last'].'</a>';
        }
        
        $sOut = '<div class="trp-pagi">'. $sOut; 
        $sOut .= '<select name="page_size" id="page_size" class="pagi-select">';
        foreach ($aPageSize as $sKey => $sVal) {
            $sSelect = '';
            if ($sKey == $iPageSize) {
                $sSelect = 'selected';
            }
            $sOut .= '<option value='.$sKey.' '.$sSelect.'>'.$sVal.'</option>';
        }
        $sOut.= '</select>'.'</div>';
        
        //not include /page-1 in url
        $sOut = preg_replace('/\/page-1([^\d])/', '\1', $sOut);

        return $sOut;

    }
    
    public function isPhoneNumber($sStr = '')
    {
        if (empty($sStr))
            return false;
        
        $iLength = strlen($sStr);
        $bHasPlusSymbol = false;
        if (substr($sStr, 0, 1) == '+')
             $bHasPlusSymbol = true;
        $sStr = preg_replace( '/(0|\+?\d{2})(\d{7,8})/', '0$2', $sStr);
        $iCount = strlen($sStr);
        if ($iCount != 10 && $iCount != 11) {
            return false;
        }
        return $sStr;
        // will check with country code after.
        if ($bHasPlusSymbol) {
            
        }
        else {
            
        }
        return true;
        
    }
    
    public function updateSitemap($aParam = array())
    {
        $aDomain = isset($aParam['domain']) ? $aParam['domain'] : array();
        // tách các sitemap theo cấu trúc 5.000 link 1 file
        if (empty($aDomain)) {
            $aDomain['stt'] = Core::getDomainId();
            $aDomain['ten'] = Core::getDomainName();
        }
        if($aDomain['stt'] < 1 || empty($aDomain['ten'])) 
            return false;
            
        $aData = array();
        $aData['category']['tong'][0] = '';
        $aTotal = array();
        $aTotalVendor = array();
        // lấy bài
        $aRows = $this->database()->select('detail_path, time, update_time, category_id, category_child_5')
            ->from(Core::getT('article'))
            ->where('domain_id = '. $aDomain['stt']. ' AND status = 1')
            ->order('time DESC')
            ->execute('getRows');

        foreach ($aRows as $aRow) {
            $sPath =  'https://'.$aDomain['ten'].$aRow["detail_path"] . '?vendor='. $aRow["category_child_5"];
            if($aRow['update_time'] == 0) 
                $aRow['update_time'] = CORE_TIME;
            $sTime = date("Y-m-d H:i:s", $aRow['update_time']);
            $sYear = date("Y", $aRow['update_time']);
            if(empty($sYear)) 
                $sYear = 'none';
            $sTime = str_replace(' ', 'T', $sTime).substr(date("O"), 0, -2).':00';
            if($iIndexTime == '') 
                $iIndexTime = $sTime;
            $aData['articles'][$sYear][] = "<url>\n<loc>".$sPath."</loc>\n<lastmod>".$sTime."</lastmod>\n<changefreq>daily</changefreq>\n</url>\n";
            if(!isset($aTotal[$aRow['category_id']])) 
                $aTotal[$aRow['category_id']] = 0;
            $aTotal[$aRow['category_id']]++;
            if(!isset($aTotalVendor[$aRow['category_child_5']])) 
                $aTotalVendor[$aRow['category_child_5']] = 0;
            $aTotalVendor[$aRow['category_child_5']]++;
        }
        
        // trường hợp này xảy ra khi không có bài viết
        if ($iIndexTime == '') {
            $sTime = date("Y-m-d H:i:s");
            $iIndexTime = str_replace(' ', 'T', $sTime).substr(date("O"), 0, -2).':00';
        }
        $aData['category']['total'][0] = '
        <url>
            <loc>http://'.$aDomain['ten'].'</loc>
            <lastmod>'.$iIndexTime.'</lastmod>
            <changefreq>daily</changefreq>
        </url>';
        
        $aRows = $this->database()->select('id, display_article_count, detail_path')
            ->from(Core::getT('category'))
            ->where('domain_id = '. $aDomain['stt'] . ' AND status = 1')
            ->execute('getRows');
        
        foreach ($aRows as $aRow) {
            $iDisplayArticle = $aRow["display_article_count"] * 1;
            if($iDisplayArticle < 1) 
                $iDisplayArticle = 1;
            $sPath =  'https://'.$aDomain['ten'].$aRow["detail_path"];
            $iTotalRow = $aTotal[$aRow['id']] * 1;
            if($iTotalRow == 0) 
                $iTotalRow = 1;
            
            // quét tất cả các trang
            for($iCnt = 1; $iCnt <= ceil($iTotalRow/$iDisplayArticle); $iCnt++) {
                $sTmpPath = $sPath;
                if($iCnt > 1)
                    $sTmpPath .= '?page='.$iCnt;
                $aData['category']['total'][] = "<url>\n<loc>".$sTmpPath."</loc>\n<lastmod>".$iIndexTime."</lastmod>\n<changefreq>weekly</changefreq>\n</url>\n";
            }
        }
 
        $dir = Core::getParam('core.dir').'/cache/sitemap/'.$aDomain['ten'];
        if(!file_exists($dir)) mkdir($dir, 0777);
        $dir .= '/';
 
        $sitemap = 'sitemap';
        
        // link để chứa tất cả các link sitemap
        $link = array();
        // tách 5.000 link cho 1 file của Bài viết
        $tong_dong_quy_dinh = 20000;
        print_r($aData);die;
        foreach($aData as $data_type => $data_tmp) {
            foreach($data_tmp as $data_time => $data_val) {
                // chèn vào link sitema[
                $sitemap_tmp = $sitemap.'-'.$data_type;
                if($data_type != 'category') $sitemap_tmp .= '-'.$data_time;
                
                // tách $tong_dong_quy_dinh link cho 1 file
                $tong_dong = count($data_val);
                $tong_cot = ceil($tong_dong  / $tong_dong_quy_dinh);
                
                for($i = 0; $i < $tong_cot; $i++) {
                    $content = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
                    
                    $tong_cuoi = ($i + 1)  * $tong_dong_quy_dinh;
                    if  ($i == $tong_cot - 1 && $tong_cuoi > $tong_cot) $tong_cuoi = $tong_dong;
                    
                    for ($j = ($i * $tong_dong_quy_dinh); $j < $tong_cuoi; $j++) {
                        $content .= $data_val[$j];
                    }
                    $content .= '</urlset>';
                    
                    $filename = $sitemap_tmp;
                    if($i > 0) $filename .= '-'.$i;
                    $filename .= '.xml';
                    
                    $fp = fopen($dir.$filename, 'w');
                    fwrite($fp, $content);
                    fclose($fp);
                    
                    $link[] = $filename;
                }
            }
        }
        
        // cập nhật cho link sitemap đầu tien
        $content = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        ';
        for($i = 0; $i < count($link); $i++) {
          $content .= '<sitemap>
              <loc>http://'.$aDomain['ten'].'/data/'.$link[$i].'</loc>
           </sitemap>';
        }
        $content .= '</sitemapindex>';
        
        $filename = $dir.$sitemap.'.xml';
        
        $fp = fopen($filename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
}
?>
