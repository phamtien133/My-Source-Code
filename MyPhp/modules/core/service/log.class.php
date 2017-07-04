<?php
class Core_Service_Log extends Service
{
    private $_sSystemFile = '';
    private $_sApiFile = '';
    public function __construct()
    {
        $this->_sTable = Core::getT('');
        
        $this->_sApiFile = DIR_FILE . 'log'. DS . 'api'. DS . 'error_'.date('d_m_y', time()).'.php';
        $this->_sSystemFile = DIR_FILE . 'log'. DS . 'he_thong'. DS . 'error_'.date('d_m_y', time()).'.php';
        
    }
    
    public function getApiFile()
    {
        return $this->_sApiFile;
    }
    
    public function getSystemFile()
    {
        return $this->_sSystemFile;
    }
}
?>
