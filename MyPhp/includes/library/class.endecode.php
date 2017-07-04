<?php
// thuật toán mã hóa và giải mã
class endecode {
	public $content = ''; // du lieu dau vao
	private $secretPass          = '';
	
	public function __construct() {
		$this->secretPass = 'kl23jhflk73#OO#*U$O(*YO';
	}
	protected function Encode($content,$pwd)
	{
		$pwd_length = strlen($pwd);
		for ($i = 0; $i < 255; $i++) {
			$key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
			$counter[$i] = $i;
		}
		for ($i = 0; $i < 255; $i++) {
			$x = ($x + $counter[$i] + $key[$i]) % 256;
			$temp_swap = $counter[$i];
			$counter[$i] = $counter[$x];
			$counter[$x] = $temp_swap;
		}
		for ($i = 0; $i < strlen($content); $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $counter[$a]) % 256;
			$temp = $counter[$a];
			$counter[$a] = $counter[$j];
			$counter[$j] = $temp;
			$k = $counter[(($counter[$a] + $counter[$j]) % 256)];
			$Zcipher = ord(substr($content, $i, 1)) ^ $k;
			$Zcrypt .= chr($Zcipher);
		}
		return $Zcrypt;
	}
	public function ma_hoa()
	{
		$this->content = gzencode($this->content, 9);
		return bin2hex($this->Encode($this->content, $this->secretPass));
	}
	public function giai_ma()
	{
		for ($i=0;$i<strlen($this->content);$i+=2) {
			$bindata.=chr(hexdec(substr($this->content,$i,2)));
		}  
		$bindata = $this->Encode($bindata, $this->secretPass);
		$g=tempnam('/tmp','ff');
		@file_put_contents($g,$bindata);
		ob_start();
		readgzfile($g);
		$bindata=ob_get_clean();
		return $bindata;
	}
}
?>