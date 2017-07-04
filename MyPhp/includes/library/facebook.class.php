<?php
class facebook
{
    private $_sCookies = '';
    private $_sDeviceName = 'Home';
    private $_aMap = array();
    private $_sFBEmail = '';
    private $_sFBPass = '';
    // ID của Fanpage, 
    public $wall = '';
    private $linkpost = '';
    /**
    * định nghĩa thời gian cookie tồn tại.
    * đơn vị tính là ngày
    */
    private $_iCookieTime = 15;
    
    public function __construct($arr)
    {
        $this->_sFBEmail = $arr['user'];
        $this->_sFBPass = $arr['pass'];
		$this->wall = $arr['fanpageId'];
		
        $this->linkpost = 'm.facebook.com/composer/?tid='.$this->wall;
		
		$link = Core::getParam('core.dir').'/cache/data/'.Core::getDomainName();
		if(!file_exists($link)) mkdir($link);
		$link = $link.'/cookiesfacebook.txt';
		
        $this->_sCookies = $link;
		
        touch($this->_sCookies);
        $this->_aMap = array(
            'fb_dtsg',
            'charset_test',
            'update',
            'r2a',
            'disable_location_sharing',
            //'linkSummary',
//            'linkThumbnail',
            //'linkTitle',
//            'linkUrl',
            'granularity',
            'timestamp',
            'privacy',
            'albums',
            'users_with',
            'photos',
            'at',
            'target',
            'xhpc_timeline',
            'finch',
            'advcmpsruri',
            'csid'
        );
    }
    
    public function parse_action($html) 
    {
        $dom = new DOMDocument;
        @$dom->loadxml($html);
        $form_action = $dom->getElementsByTagName('form')->item(0)->getAttribute('action');
        if (!strpos($form_action, "//")) {
            $form_action = "https://m.facebook.com".$form_action;
        }
        return($form_action);
    }
    
    public function parseForPage($dom) 
    {
        $form_action = '';
        foreach ($dom->getElementsByTagName('form') as $node)
        {
            if(strpos($node->getAttribute("class"), 'composer') === false && strpos($node->getAttribute("id"), 'composer') === false) continue;
            $form_action = $node->getAttribute('action');
            if (!strpos($form_action, "//")) $form_action = "https://m.facebook.com".$form_action;
            break;
        }
        return($form_action);
    }
    public function parseInputsForPage($dom) 
    {
        $inputs = $dom->getElementsByTagName('input');
        return($inputs);
    }
    
    public function grab_home() 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, 'https://m.facebook.com/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $html = curl_exec($ch);

        curl_close($ch);
        return($html);
    }
    
    public function parse_inputs($html) 
    {
        $dom = new DOMDocument;
        @$dom->loadxml($html);
        $inputs = $dom->getElementsByTagName('input');
        return($inputs);
    }
    
    function checkpoint($html) 
    {
        $form_action = $this->parse_action($html);
        $inputs = $this->parse_inputs($html);
        $post_params = "";
        foreach ($inputs as $input) {
            switch ($input->getAttribute('name')) {
                case "":
                    break;
                case "submit[I don't recognize]":
                    break;
                case "submit[Don't Save]":
                    break;
                case "machine_name":
                    $post_params .= 'machine_name=' . urlencode($this->_sDeviceName) . '&';
                    break;
                default:
                    $post_params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
            }
        }
        //Verify the machine
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $form_action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        $home = curl_exec($ch);
        curl_close($ch);
     
        if (strpos($home, "machine_name") || strpos($home, "/checkpoint/") || strpos($home, "submit[Continue]")) {
            echo "\n[i] Solving another checkpoint...\n";
            $this->checkpoint($home);
        }
    }
    
    public function login($sEmail, $sPass) 
    {
		
		//remove file cookie
		if(!empty($sEmail) && !empty($sPass)) unlink($this->_sCookies);
		
		if(empty($sEmail)) $sEmail = $this->_sFBEmail;
		if(empty($sPass)) $sPass = $this->_sFBPass;
		
        /*
         * Grab login page and parameters
         */
        $sLoginpage = $this->grab_home();
        $form_action = $this->parse_action($sLoginpage);
        $inputs = $this->parse_inputs($sLoginpage);
        $post_params = "";
        foreach ($inputs as $input) {
            switch ($input->getAttribute('name')) {
                case 'email':
                    $post_params .= 'email=' . urlencode($sEmail) . '&';
                    break;
                case 'pass':
                    $post_params .= 'pass=' . urlencode($sPass) . '&';
                    break;
                default:
                    $post_params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
            }
        }
        //echo "[i] Using these login parameters: ".$post_params;
        /*
         * Login using previously collected form parameters
         */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $form_action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        $loggedin = curl_exec($ch);
        curl_close($ch);
        /*
         * Check if location checking is turned on or you have to verify location
         */
        if (strpos($loggedin, "machine_name") || strpos($loggedin, "/checkpoint/") || strpos($loggedin, "submit[Continue]")) {
            echo "\n[i] Found a checkpoint...\n";
            $this->checkpoint($loggedin);
            echo "\n[i] Checkpoints passed...\n";
        }
		if (strpos($loggedin, "login_form") === false) {
			return 'success';
        }
		unset($this->_sCookies);
		return $loggedin;
    }
    
	private function convertUrlQuery($query) {
		$queryParts = explode('&', $query);
		$params = array();
		foreach ($queryParts as $param) {
		$item = explode('=', $param);
		$params[$item[0]] = $item[1];
		}
		
		return $params;
	}
	
    public function get_fanpage($id) 
    {
		if(empty($id)) $url = 'https://m.facebook.com/friends/?q&f=10&mp&refid=5';
		else $url = 'https://graph.facebook.com/'.$id;
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
		
		if(empty($id))
		{
			$listFanpage = array();
			// replace html
			$data = preg_replace('#(.*?)role="main"#is', '', $data, 1);
			// clear footer
			$data = preg_replace('#pages\/create(.*)"#is', '', $data, 1);
			$tmps = explode('<a ', $data);
			foreach($tmps as $tmp)
			{
				if(strpos($tmp, 'refid=') === false) continue;
				
				preg_match('#href="(.*?)" .*><span>(.*?)</#is', $tmp, $val);
				$v = $val;
				
				preg_match('#</a>(.*?)</#is', $tmp, $val);
				$tmp = $val[1];
				if(!empty($tmp))
				{
					$arr = explode(' ', $tmp);
					foreach($arr as $tmp)
					{
						if(!is_numeric($tmp)) continue;
						
						$tmp = str_replace(',', '', $tmp);
						$tmp = str_replace('.', '', $tmp);
						$tmp = $tmp*1;
						
						if($tmp < 1) continue;
						
						break;
					}
					if($tmp < 1) $tmp = 0;
				}
				else $tmp = 0;
				
				if(mb_strpos($v[1], '?') !== false)
				{
					$tam = mb_substr($v[1], mb_strpos($v[1], '?')+1);
					$tam = $this->convertUrlQuery($tam);
					if(!empty($tam['id'])) $v[1] = $tam['id'];
					else
					{
						// remove from / to ?
						$v[1] = mb_substr($v[1], 1, mb_strpos($v[1], '?') - 1);
					}
				}
				
				$listFanpage[] = array(
					'id' => $v[1],
					'title' => $v[2],
					'likes' => $tmp,
				);
			}
		}
		else
		{
			$data = json_decode($data, 1);
			$v[1] = $data['id'];
			$v[0] = $data['username'];
			$v[2] = $data['name'];
			$tmp = $data['likes'];
			$listFanpage[] = array(
					'id' => $v[1],
					'name' => $v[0],
					'title' => $v[2],
					'likes' => $tmp,
				);
		}
        return($listFanpage);
    }
    
    public function grab_home_page() 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $this->linkpost);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $html = curl_exec($ch);
        curl_close($ch);
        return($html);
    }
    
    public function postToFanPage($aVals)
    {
        touch($this->_sCookies);
        $sRefe = $this->linkpost;
        $html = $this->grab_home_page();
        $dom = new DOMDocument;
        @$dom->loadxml($html);
        $form_action = $this->parseForPage($dom);
        $inputs = $this->parseInputsForPage($dom);
        //$post_params = array();
//        foreach ($inputs as $input) {
//            if(in_array($input->getAttribute('name'), $this->_aMap)){
//                $post_params[$input->getAttribute('name')] = urlencode($input->getAttribute('value'));
//            }
//        }
//        $arr = array(
//            'status' => urlencode($aVals['status']),
//            'linkTitle' => urlencode($aVals['tieu_de']),
//            'linkUrl' => urlencode($aVals['url']),
//        );
//        $post_params = $this->array_insert_after('charset_test', $post_params, $arr);

        $post_params = "status=".urlencode($aVals['status']). "&linkTitle=".urlencode($aVals['tieu_de']). "&linkUrl=".urlencode($aVals['url']). "&";
		//if(!empty($aVals['picture'])) $post_params .= urlencode('attachment[params][title]').'='.urlencode($aVals['tieu_de']).'&'.urlencode('attachment[params][summary]').'='.urlencode($aVals['status']).'&'.urlencode('attachment[params][images][0]').'='.urlencode($aVals['picture']).'&';
		
        foreach ($inputs as $input) {
            if(in_array($input->getAttribute('name'), $this->_aMap)){
                $post_params .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
            }
        }
        $post_params = rtrim($post_params, '&');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $form_action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_REFERER, $sRefe);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        
        $updated = curl_exec($ch);
        $iStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $iStatus;
    }

    function logout() 
    {
        $dom = new DOMDocument;
        @$dom->loadxml($this->grab_home());
        $links = $dom->getElementsByTagName('a');
        $logout = '/logout.php';
        foreach ($links as $link) {
            if (strpos($link->getAttribute('href'), 'logout.php')) {
                $logout = $link->getAttribute('href');
                break;
            }
        }

        $url = 'https://m.facebook.com' . $logout;
        /*
         * just logout lol
         */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $loggedout = curl_exec($ch);
        
        curl_close($ch);
    }
    
    private function array_insert_after($key, array $array, $arr_after) {
        if(!array_key_exists($key, $array)) return $array;
        $new = array();
        foreach ($array as $k => $value) {
            $new[$k] = $value;
            if ($k === $key) {
                foreach ($arr_after as $k_after => $value_after) {
                $new[$k_after] = $value_after;
                }
            }
        }
        return $new;
    }
    
    /**
    * kiểm tra thời gian gần nhất cập nhật cookie có quá thời quan quy định trước ko.
    * 
    */
    public function checkTime()
    {
        $sFilePath = BONG_DIR_FILE . 'static'. BONG_DS . 'cookies.txt';
        $iModifyTime = filemtime($sFilePath);
        $iTime = BONG_TIME - $iModifyTime;
        $iDay = floor($iTime/86400);
        return ($iDay > $this->_iCookieTime) ? true : false;
    }
    
    public function clearCookieFile()
    {
        $sFilePath = BONG_DIR_FILE . 'static'. BONG_DS . 'cookies.txt';
        $oFile = fopen($sFilePath, 'w');
        fwrite($oFile, '');
        fclose($oFile);
    }
}
?>
