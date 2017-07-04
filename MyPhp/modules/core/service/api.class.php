<?php
class Core_Service_Api extends Service
{
    private $_aMapFunction = array();
    
    public function __construct()
    {
        $this->_aMapFunction = array(
            'check-code' => 'article:checkProductByCode',
            'capture' => 'article:captueImageProduct',
            'check-capture' => 'article:checkCaptueImageProduct',
            'check-article' => 'article:getArticleInfo'
        );
    }
    
    public function getMapFunction()
    {
        return $this->_aMapFunction();
    }
    
    public function call($aParmas)
    {
        $sLogPath = Core::getService('core.log')->getApiFile();
        if(!isset($aParmas['call']) || empty($aParmas['call'])) {
            Core_Error::log('Không có thao tác thực thi được gọi.', $sLogPath);
            return array(
                'status' => 'error',
                'message' => 'Không có thao tác thực thi được gọi.'
            );
        }
        if(!isset($this->_aMapFunction[$aParmas['call']])) {
            Core_Error::log('Hàm gọi không đúng.', $sLogPath);
            return array(
                'status' => 'error',
                'message' => 'Hàm gọi không đúng.'
            );
        }
        
        $sCall = $this->_aMapFunction[$aParmas['call']];
        $aCall = explode(':', $sCall);
        try {
            $aReturn = Core::getService($aCall[0])->$aCall[1]($aParmas);
            if (isset($aReturn['status'])) {
                if (isset($aReturn['redirect']) && $aReturn['redirect'] != '') {
                    Core::getLib('url')->send($aReturn['redirect'], null, $aReturn['message']);
                    //header('Location: '. $aReturn['redirect']);
                    return false;
                }
                return $aReturn;
            }
            else {
                return array(
                    'status' => 'success',
                    'data' => $aReturn
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Lỗi thực thi API: '. $aCall[0] .'-> '.$aCall[1] .', .', $sLogPath);
            return array(
                'status' => 'error',
                'message' => 'Lỗi thực thi API: '. $aCall[0] .'-> '.$aCall[1] .', .', $sLogPath
            );
        }
    }
}
?>
