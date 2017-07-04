<?php
class VNPAYSMS
{
    private $sysInfo = array(
        'url' => '',
        'sender' => '',
        'username' => '',
        'password' => ''
    );
    
    public function __construct($aInput)
    {
        //$this->setupMerchant($aInput);
    }
    
    public function setupMerchant($aInput)
    {
        foreach($this->sysInfo as $k => $v)
        {
            if(!isset($aInput[$k])) continue;
            
            $this->sysInfo[$k] = $aInput[$k];
        }
    }

    public function request($aInput)
    {
        if(empty($this->sysInfo['url'])) return array(
            'code' => -1,
            'text' => 'error',
            'description' => 'url',
        );
        
        if(!isset($aInput['type']) || empty($aInput['type'])) $aInput['type'] = 'SMS';
        
        $client = new SoapClient($this->sysInfo['url']);
        
        $result = $client->SendMT(array(
            'Destination' => $aInput['to'],
            'Sender' => $this->sysInfo['sender'],
            'KeywordName' => 'VNPAYkey',
            'OutContent' => $aInput['content'],
            'ContentType' => $aInput['type'],
            'Username' => $this->sysInfo['username'],
            'Password' => $this->sysInfo['password'],
        ));
        
        // tách chuỗi
        $tmps = explode('|', $result->SendMTResult);
        return array(
            'code' => $tmps[0],
            'text' => $tmps[0],
            'description' => $tmps[1],
            'note' => $this->getResponseDescription($tmps[0]),
            'reponse' => serialize($result)
        );
    }
    
    private function getResponseDescription($responseCode) {
        
        switch ($responseCode) {
            case "00" :
                $result = "Thành công";
                break;
            case "01" :
                $result = "Sai số điện thoại";
                break;
            case "02" :
                $result = "Độ dài tin nhắn không hợp lệ";
                break;
            case "03" :
                $result = "Sai Sender";
                break;
            case "04" :
                $result = "Sai Keyword";
                break;
            case "06" :
                $result = "Sai UserName/Password";
                break;
            case "07" :
                $result = "Địa chỉ IP không được phép truy cập";
                break;
            case "11" :
                $result = "Sai loại tin nhắn";
                break;
            case "-1" :
                $result = "Gửi tin không thành công";
                break;
            default :
                $result = "Giao dịch thất bại - Không xác định được lỗi - (".$responseCode.')';
        }
        return $result;
    }
}
?>
