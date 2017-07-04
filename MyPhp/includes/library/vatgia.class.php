<?php
class vatgia
{
    private $_sCookies = '';
    private $_sCookies_vatgia = '';
    private $_sDeviceName = 'Home';
    private $_aMap = array();
    private $username = '';
    private $password = '';
    // ID của Fanpage, 
    public $wall = '';
    private $linkpost = '';
	
	private $post_params = array();
    /**
    * định nghĩa thời gian cookie tồn tại.
    * đơn vị tính là ngày
    */
    private $_iCookieTime = 15;
    
    public function __construct($arr)
    {
		global $config;
        $this->username = $arr['user'];
        $this->password = $arr['pass'];
		if(!isset($arr['_sCookies']) && !isset($arr['_sCookies_vatgia']))
		{
			
			$link = $config['dir'].'/cache/data/'.$_SESSION['session-ten_mien']['ten'];
			if(!file_exists($link)) mkdir($link);
			
			$this->_sCookies = $link.'/cookies_ID_vatgia.txt';
			$this->_sCookies_vatgia = $link.'/cookies_vatgia.txt';
		}
		else
		{
			$this->_sCookies = $arr['_sCookies'];
			$this->_sCookies_vatgia = $arr['_sCookies_vatgia'];
		}
		
    }
	
    public function login($sEmail, $sPass) 
    {
		if(empty($sEmail)) $sEmail = $this->username;
		if(empty($sPass)) $sPass = $this->password;
		
        $form_action = 'https://id.vatgia.com/dang-nhap/?another=1';
		$fields = array(
			'SignInForm' => array(
				'username' => $sEmail,
				'password' => $sPass,
				'remember' => '1',
			),
			'btnSignIn' => '',
		);
		
        //echo "[i] Using these login parameters: ".$post_params;
		
		$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		if($timeout > 1) $header[] = "Keep-Alive: ".$timeout;
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: no-cache";
		//$fields= '';
		// đăng nhập đén id vat gia
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $form_action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if(!empty($fields))
		{
			$fields = (is_array($fields)) ? http_build_query($fields) : $fields; 
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
			
			curl_setopt($ch,CURLOPT_POST, 1);
			$header[] = 'Content-Length: ' . strlen($fields);
		}
		curl_setopt($ch, CURLOPT_REFERER, $form_action);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$data = curl_exec($ch);
        curl_close($ch);
		
		if(strpos($data, 'sso/signIn') === false) return ;
		
		// tiếp theo Chuyển từ phiên id vat gia qua slave vatgia.
		
		// gọi http://id.vatgia.com/sso/signIn/?_cont=%2Fthiet-lap%2F&service= để tìm phiên gán cho vatgia
		//http://slave.vatgia.com/authorize/sso/setsid.php?token=AQACK1qHD06ArMsArXcSW4c8f%2F9R8wfnA1Vf9e0Me0d...
		
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_HEADER, 1);
       // curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, 'http://id.vatgia.com/sso/signIn/?_cont=%2Fthiet-lap%2F&service=');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_REFERER, $form_action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
		
		// xóa code đến phần body
		$data = preg_replace('#(.*?)<body#is', '', $data);
		$data = preg_replace('#<\/body(.*?)#is', '', $data);

		preg_match('#token=(.*?)"#is', $data, $data);
		
		$data = 'http://slave.vatgia.com/authorize/sso/setsid.php?token='.$data[1];
		//echo $data;
		flush();
		ob_flush();
		
		// quét dữ liệu để lấy cookie cho trang vatgia
		$ch = curl_init();
		 curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies_vatgia);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_REFERER, $form_action);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
	
		return true;
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
	
	public function searchProduct($arr)
	{
		$tmps = array(
			'keyword',
			'page'
		);
		/*
			Tìm sản phẩm để mốc link
				http://www.vatgia.com/home/quicksearch.php?keyword=KENDO
				
				Trang 2
				http://www.vatgia.com/home/quicksearch.php?&keyword=KENDO&page=2
			
			Sau đó lọc HTML để trả về ARRAY
		*/
		if(empty($arr['keyword'])) return false;
		$data = '';
		if($arr['page'] > 1)
		{
			$data = '&page='.$arr['page'];
		}
		// su dung proxy
		$data = lay_du_lieu_curl('http://112.78.9.40:8080/tools/getURL.php?url='.urlencode('http://www.vatgia.com/home/quicksearch.php?keyword='.$arr['keyword'].$data));
		//$data = lay_du_lieu_curl('http://www.vatgia.com/home/quicksearch.php?keyword='.$arr['keyword'].$data);
		
		// replace html
		$data = preg_replace('#(.*?)<body#is', '', $data, 1);
		$data = preg_replace('#<\/body.*#is', '', $data, 1);
		
		$tmps = explode('<div class="block', $data);
		
		$tmp = $tmps[count($tmps)-1];
		$tmp = preg_replace('#class="break_content.*#is', '', $tmp, 1);
		// lay phan trang
		$tmp = explode('page_div', $tmp);
		$tmps[count($tmps)-1] = $tmp[0];
		$tmp = $tmp[1];
		$tmp = preg_replace('#<\/div.*#is', '', $tmp, 1);
		
		$tmp = preg_replace('#(.*?)page_current#is', '', $tmp, 1);
		preg_match_all('#href="(.*?)".*?>(\d)</#is', $tmp, $val);
		$v = $val[2][count($val[2])-1];
		if(empty($v)) $v = $arr['page'];
		$output['tong_trang'] = $v;
		
		// lay tong bai
		
		$tmp = $tmps[0];
		unset($tmps[0]);
		$tmp = preg_replace('#(.*?)class="break_module#is', '', $tmp, 1);
		
		preg_match('#class="price.*?>(.*?)</#is', $tmp, $val);
		$output['tong_san_pham'] = $val[1];
		
		foreach($tmps as $tmp)
		{
			if(strpos($tmp, 'product_teaser') === false) continue;
			
			$arr = array();
			// lấy hình
			preg_match('#class="picture.*? src="(.*?)"#is', $tmp, $val);
			$v = $val[1];
			$arr['duong_dan_hinh'] = $v;
			// lấy liên kết
			preg_match('#class="name.*? href="(.*?)">(.*?)</a#is', $tmp, $val);
			$v = $val[1];
			if(strpos($v, '//') === false) $v = 'http://www.vatgia.com'.$v;
			$arr['duong_dan'] = $v;
			$v = $val[2];
			$arr['ten'] = $v;
			// lấy giá bán
			preg_match('#class="estore_price.*? class="price.*?>(.*?)</#is', $tmp, $val);
			$v = $val[1];
			$v = explode(' ', $v);
			$v = $v[0];
			$v = str_replace('.', '', $v);
			$v *= 1;
			$arr['gia_ban'] = $v;
			// lấy thông tin
			preg_match('#id="product_teaser_(.*?)".*?>(.*?)<\/div#is', $tmp, $val);
			$v = $val[1];
			if(empty($v))
			{
				preg_match('#id="product_teaser_(.*?)"#is', $tmp, $val);
				$v = $val[1];
			}
			$arr['stt'] = $v;
			$v = $val[2];
			if(!empty($v))
			{
				$arr['thong_tin'] = $v;
			}
			$output['san_pham'][] = $arr;
		}
		return $output;
	}
	public function updateProduct($arr)
	{
		/*
			Xem tất cả sản phẩm
				http://slave.vatgia.com/quantrigianhang/products/danhsach.php
				
			Quét để lấy danh sách sản phẩm hết hạn
				http://slave.vatgia.com/quantrigianhang/products/danhsach_expire_date.php
			Sau đó gọi hàm dưới, gửi kèm id để update lại hết hạn.
			
			Link update Tất cả sản phẩm:
				http://slave.vatgia.com/quantrigianhang/products/last_update.php?list_id=1461014,2093583,3601878,452652,1920425,3820059,3905635,2881657,4069562,&redirect=ZGFuaHNhY2hfZXhwaXJlX2RhdGUucGhwPw==
			http://slave.vatgia.com/quantrigianhang/products/danhsach_expire_date.php
		*/
	}
	
	private function addProduct($arr)
	{
		
		/*
			lấy tất cả các field để gửi dữ liệu lên
			Sau đó nhúng data vào các phần quan trọng
			
		*/
		
		$ch = curl_init();
       // curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies_vatgia);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, 'http://www.vatgia.com/ajax_v2/load_add_product.php?v=3&iData=' . $arr['lien_ket']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $data = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($data, 0, $header_size);
		$html = substr($data, $header_size);
        curl_close($ch);
		
		if(strpos($html, '<form') === false)
		{
			return false;
		}
		
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $form = $dom->getElementsByTagName('form')->item(0);
		
		$form_action = $form->getAttribute('action');
        if (!strpos($form_action, "//")) {
            $form_action = "http://www.vatgia.com/ajax_v2/".$form_action;
        }
		$this->danh_sach_field($form);
		
		$post_params = $this->post_params;
		unset($this->post_params);
		
		unset($post_params['reset']);
		unset($post_params['shipCos_other']);
		unset($post_params['up_hot']);
		unset($post_params['up_new']);
		unset($post_params['up_promotion']);
		unset($post_params['quantity_lessthan_zero']);
		unset($post_params['up_need_order_day_check']);
		unset($post_params['up_need_order_day']);
		unset($post_params['up_promotion']);
		
		foreach($post_params as $name => $val)
		{
			if($name == 'up_price') {
				$post_params[$name] = $arr['gia'];
			}
			if($name == 'up_picture') {
				//$post_params[$name] = '@d:/1.jpg';
			}
			elseif($name == 'shipCos') {
				$post_params[$name] = 0;
			}
		}
		// default value
		
		$post_params['up_type'] = 1;
		$post_params['up_vat'] = 1;
		$post_params['up_warranty'] = 12;
		
		return array(
			'link' => $form_action,
			'fields' => $post_params
		);
	}
	
	private function checkProduct($arr)
	{
		
		/*
			check xem sản phẩm đã đc đăng chưa, bằng cách query link
			3819887
			
		*/
		$ch = curl_init();
       // curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_sCookies);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies_vatgia);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.0; S60/3.0 NokiaN73-1/2.0(2.0617.0.0.7) Profile/MIDP-2.0 Configuration/CLDC-1.1)');
        curl_setopt($ch, CURLOPT_URL, 'http://slave.vatgia.com/quantrigianhang/products/suadoi.php?record_id=' . $arr['lien_ket']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $data = curl_exec($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($data, 0, $header_size);
		$html = substr($data, $header_size);
        curl_close($ch);
		
		if(strpos($data, 'error.php') !== false)
		{
			return false;
		}
		
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $form = $dom->getElementsByTagName('form')->item(0);
		
		$form_action = $form->getAttribute('action');
        if (!strpos($form_action, "//")) {
            $form_action = "http://slave.vatgia.com/quantrigianhang/products/".$form_action;
        }
		$this->danh_sach_field($form);
		
		$post_params = $this->post_params;
		unset($this->post_params);
		
		unset($post_params['reset']);
		unset($post_params['shipCos_other']);
		unset($post_params['up_hot']);
		unset($post_params['up_new']);
		unset($post_params['up_promotion']);
		unset($post_params['quantity_lessthan_zero']);
		unset($post_params['up_need_order_day_check']);
		unset($post_params['up_need_order_day']);
		unset($post_params['up_promotion']);
		
		foreach($post_params as $name => $val)
		{
			if($name == 'up_price') {
				$post_params[$name] = $arr['gia'];
			}
			if($name == 'up_picture') {
				//$post_params[$name] = '@d:/1.jpg';
			}
			elseif($name == 'shipCos') {
				$post_params[$name] = 0;
			}
		}
		return array(
			'link' => $form_action,
			'fields' => $post_params
		);
	}
    private function danh_sach_field($node)
    {
	    if($node->hasChildNodes())
	    {
		    if($node->nodeName == 'select')
		    {
			    $name = $node->getAttribute('name');
			    
			    $num = 0;
			    $optionTags = $node->getElementsByTagName('option');
			    for ($i = 0; $i < $optionTags->length; $i++ ) {
			      if ($optionTags->item($i)->hasAttribute('selected') 
					     && $optionTags->item($i)->getAttribute('selected') === "selected") {
				       $num = $optionTags->item($i)->getAttribute('value');
			      }
			    }
			    $val = $num;
			    if(!empty($name)) $this->post_params[$name] = $val;
			    return ;
            }
		    foreach($node->childNodes as $k => $v)
		    {
			    $this->danh_sach_field($v);
		    }
		    
	    }
	    else
	    {
		    $name = '';
            if($node->nodeName == 'input')
		    {
			    $name = $node->getAttribute('name');
			    $val = $node->getAttribute('value');
            }
		    else if($node->nodeName == 'textarea')
		    {
			    $name = $node->getAttribute('name');
			    $val = $node->nodeValue;
            }
		    if(!empty($name)) $this->post_params[$name] = $val;
	    }
    }
    public function save($arr) 
    {
		$tmps = array();
		$tmps = $this->checkProduct($arr);
		
		if(empty($tmps))
		{
			// when not found, get field default to submit
			$tmps = $this->addProduct($arr);
		}
		
		$arr = $tmps;
		$form_action = $arr['link'];
		$fields = $arr['fields'];
		/* 
		echo $form_action;
		print_r($fields);
		exit;
		/**/
		
		$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		//$header[] = 'Content-Type: application/octet-stream';
		if($timeout > 1) $header[] = "Keep-Alive: ".$timeout;
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: no-cache";
		//$fields= '';
		// đăng nhập đén id vat gia
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $form_action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_sCookies_vatgia);
		if(!empty($fields))
		{
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
		}
		$data = curl_exec($ch);
        curl_close($ch);
		
        return true;
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