<?php
class Domain_Service_Domain extends Service
{
    private $_sSettingTable = '';

    private $_iDomainId = 0;

    private $_sDomainName = '';

    public function __construct()
    {
        $this->_sTable = Core::getT('domain');
        $this->_sSettingTable = Core::getT('domain_setting');
    }

    public function getByHost($sHost)
    {
        $sCacheId = $this->cache()->set('domain|'.$sHost);
        $aDomain = $this->cache()->get($sCacheId);
        if(!$aDomain){
            // get list domain from database
            $aDomain = $this->database()->select('id, code, domain, path, status')
                ->from($this->_sTable)
                ->where('domain = \''.$this->database()->escape($sHost).'\' AND status = 1')
                ->execute('getRow');

            // format domain
            if($aDomain['id'] > 0){
                // if domain path contains "http://", it synonymous with redirect link
                if(strpos($aDomain['path'], '://') !== false){
                    $aDomain['stt'] = -2;
                }
                else if($aDomain['path'] != '') // if domain not empty, assign new domain data with this path
                {
                    $aDomain = $this->database()->select('id, code, domain, path, status')
                        ->from($this->_sTable)
                        ->where('domain = \''.$this->database()->escape($aDomain['path']).'\' AND status = 0')
                        ->execute('getRow');
                }
            }else{
                $aDomain['id'] = -1;
                $aDomain['name'] = $sHost;
            }
            // save cache
            $this->cache()->save($sCacheId, $aDomain);
        }
        return $aDomain;
    }

    public function getDomainId()
    {
        return $this->_iDomainId;
    }

    public function getDomainName()
    {
        return $this->_sDomainName;
    }

    public function getDomainSetting($aDomain)
    {

        $sCacheId = $this->cache()->set('tm|'.$aDomain['id']);
        $aSetting = $this->cache()->get($sCacheId);
        if(!$aSetting){
            $aRow = $this->database()->select('*')
                ->from($this->_sSettingTable)
                ->where('domain_id = '. $aDomain['id'])
                ->execute('getRow');

            if($aRow['domain_id'] > 0){
                // unset
                unset($aRow['facebook']);
                unset($aRow['floor_link']);
                //unset($aRow['total_view']);
                unset($aRow['domain_id']);
                $aSetting = array();
                foreach($aRow as $iKey => $sValue){
                    if(is_numeric($iKey)) continue;
                    $aSetting[$iKey] = $sValue;
                }
                $aSetting['receive_newsletter'] = true;

                // convert to array with the group value
                $aGroup = array(
                    'avatar',
                    'setting_support',
                    'top_post',
                    'order_time'
                );
                foreach($aSetting as $iKey => $sValue){
                    if(!empty($sValue) && in_array($iKey, $aGroup)){
                        $aSetting[$iKey] = unserialize($sValue);
                        if($iKey == 'avatar' && empty($aSetting[$iKey]['logo'])){
                            $aSetting[$iKey]['logo'] = 'http://img.'.$aDomain['domain'].'/sys/logo.png';
                        }
                    }
                }
                if(empty($aSetting['footer_copy_right'])) $aSetting['footer_copy_right'] = '<a href="//fi.ai" rel="nofollow">Fi Technology</a>';

                // get currency info
                $aCurrency = $this->database()->select('*')
                    ->from(Core::getT('currency'))
                    ->where('id = '. $aSetting['currency_id'])
                    ->execute('getRow');

                foreach($aCurrency as $iKey => $sValue){
                    if(is_numeric($iKey)) continue;
                    $aSetting['currency'][$iKey] = $sValue;
                }
                unset($aSetting['currency_id']);
                $aBlock = $this->getDomainHtmlBlock($aDomain);
                if(count($aBlock)){
                    foreach($aBlock as $iKey => $sValue){
                        $aSetting[$iKey] = $sValue;
                    }
                }
                $this->cache()->save($sCacheId, $aSetting);
            }
        }
        // set param for domain setting.
        Core::getLib('setting')->setParam($aSetting, 'setting');
        $sServerPort = $_SERVER['SERVER_PORT'];
        if ($sServerPort == 80) {
            $sServerPort = '';
        }
        else {
            $sServerPort = ':'.$sServerPort;
        }
        // set core path
        $sCorePath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $aDomain['domain'] .$sServerPort. Core::getParam('core.folder');
        $sImagePath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http'). '://'. $aDomain['domain']. $sServerPort. Core::getParam('core.folder'). 'static/';
        $sMainPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http'). '://'. Core::getParam('core.main_server'). $aDomain['domain'].$sServerPort. Core::getParam('core.folder');
        $sPayPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http'). '://'. Core::getParam('core.pay_server'). $aDomain['domain'].':8080'. Core::getParam('core.folder'). 'tools/api.php';
        $sPayShortPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http'). '://'. Core::getParam('core.pay_server'). $aDomain['domain'].$sServerPort . Core::getParam('core.folder');
        $sStaticUrl = $sCorePath . 'static/';
        $sStaticScriptUrl = $sStaticUrl . 'jscript/';

        Core::getLib('setting')->setParam('core.path', $sCorePath);
        Core::getLib('setting')->setParam('core.image_path', $sImagePath);
        Core::getLib('setting')->setParam('core.main_path', $sMainPath);
        Core::getLib('setting')->setParam('core.pay_path', $sPayPath);
        Core::getLib('setting')->setParam('core.pay_sortpath', $sPayShortPath);
        Core::getLib('setting')->setParam('core.url_static', $sStaticUrl);
        Core::getLib('setting')->setParam('core.url_static_script', $sStaticScriptUrl);

        return $aSetting;
    }

    public function getDomainHtmlBlock($aDomain)
    {
        $sCacheId = $this->cache()->set('tmmr|'.$aDomain['id']);
        $aBlock = $this->cache()->get($sCacheId);
        if(!$aBlock){
            $aBlock = array();
            $aRows = $this->database()->select('block, value')
                ->from(core::getT('domain_block'))
                ->where('domain_id = '. $aDomain['id'])
                ->execute('getRows');
            $oInput = Core::getLib('input');
            foreach($aRows as $aRow){
                $aRow['value'] = $oInput->formatBbcode($aRow['value']);
                $aBlock['column_'.$aRow['block']] = $aRow['value'];
            }

            $this->cache()->save($sCacheId, $aBlock);
        }
        return $aBlock;
    }

    public function getDomainByUrl($aParam)
    {
        $sUrl = (isset($aParam['url'])) ? $aParam['url'] : '';
        $sDomain = '';
        if (!empty($sUrl) && strpos($sUrl, '://') !== false ) {
            $iLocation = mb_strpos($sUrl, '/', 7, 'utf8');
            if ($iLocation !== false) {
                $sDomain = mb_substr($sUrl, 7, ($iLocation - 7), 'utf8');
            }
            else
                $sDomain = mb_substr($sUrl.'.', 7, -1, 'utf8');
            // remove port
            $aTmps = explode(':', $sDomain);
            $sDomain = $aTmps[0];

            if(isset($aParam['filter']) && !empty($aParam['filter'])) {
                $aTmps = explode('.', $sDomain);
                $iLocation = count($aTmps) - 1;
                $sDomain = $aTmps[$iLocation - 1]. '.'. $aTmps[$iLocation];
            }
            return $sDomain;
        }
        else
            return '';
    }

    public function checkUrl($aParam)
    {
        if (!isset($aParam['url']) || !$aParam['url'])
            return false;
        if(!empty($aParam['url']) && strpos($aParam['url'], '//') !== false && $this->getDomainByUrl($aParam['url']) != Core::getDomainName())
            return false;
        return true;
    }

    public function init()
    {
        Core::getLib('session.handler')->init();
        $oSession = Core::getLib('session');
        if ($oSession->getArray('session-domain', 'id') < 1) {
            $aTmp = explode('.', $_SERVER['HTTP_HOST'], 2);
            if ($aTmp[0] == Core::getParam('core.main_server')) {
                $_SERVER['HTTP_HOST'] = $aTmp[1];
                $iPos = strpos($_SERVER['HTTP_HOST'], ':');
                if($iPos !== false)
                    $_SERVER['HTTP_HOST'] = substr($_SERVER['HTTP_HOST'], 0, $iPos);
            }
        }
        // thiết lập lại phiên làm việc.
        $aDomain = $this->getByHost($_SERVER['HTTP_HOST']);
//d($aDomain);die;
        $_SERVER['WEB_ID'] = $aDomain['id'];
        if ($_SERVER['WEB_ID'] == -2) {
            header('Location: '.$aDomain['path']);
            exit();
        }
        else if($_SERVER['WEB_ID'] < 1){
            return;
        }

        $aSetting = $this->getDomainSetting($aDomain);

        Core::getService('log.session')->setUserSession($aDomain, $aSetting);

        $oLang = Core::getLib('language');

        $this->_iDomainId = $aDomain['id'];
        $this->_sDomainName = $aDomain['domain'];
    }

    public function loadSession()
    {
        $aDomain = $this->getByHost($_SERVER['HTTP_HOST']);
        if(!isset($aDomain['id']))
            return false;
        $aSetting = $this->getDomainSetting($aDomain);
        Core::getService('log.session')->setUserSession($aDomain, $aSetting);
        $oLang = Core::getLib('language');
    }

    public function getDomainForApi($aParam = array())
    {
        $sDomain = isset($aParam['name-code']) ? $aParam['name-code'] : '';
        if (empty($sDomain)) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu.'
            );
        }

        if ($sDomain == 'self') {
            // tự lấy thông tin của chính tên miền đang gọi.

        }
        else {
            $aDomain = $this->getByHost($sDomain);
            if (!isset($aDomain['id']) || $aDomain['id'] == -1) {
                return array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Tên miền không tồn tại.'
                );
            }
            $this->_iDomainId = $aDomain['id'];
            $this->_sDomainName = $aDomain['domain'];

            $aSetting = $this->getDomainSetting($aDomain);

            $aData = array(
                'domain_id' => $aDomain['id'],
                'domain_name' => $aDomain['domain'],
                'setting' => $aSetting
            );
            return array(
                'status' => 'success',
                'data' => $aData
            );
        }
    }
}
