<?php
class Log_Service_Session extends Service
{
    public function __construct()
    {
        $this->_sTable = 'sessions';
    }
    
    public function setUserSession($aDomain, $aSetting)
    {
        $oSession = Core::getLib('session');
        $oRequest = Core::getLib('request');
        
        // get domain info and set domain session
        $iDomainIdSession = $oSession->getArray('session-domain', 'id');
        if(!$iDomainIdSession || $iDomainIdSession < 1){
            foreach($aDomain as $iKey => $sValue){
                $oSession->setArray('session-domain', $iKey, $sValue);
            }
            // update session to database
            $this->database()->update($this->_sTable, array(
                'domain_id' => $aDomain['id']
            ), 'domain_id = 0 AND id =\''. $this->database()->escape(session_id()).'\'');
        }
        
        $sSessionDir = $oSession->get('session-path');
        if(empty($sSessionDir)){
            $oSession->set('session-path', Core::getParam('core.dir'));
        }

        $oSession->set('session-mobile', $_SERVER['USER_MOBILE']);
        
        $sDomain = $oSession->getArray('session-domain', 'domain');
        if(!empty($sDomain)){
            Core::setCookie('csid', 'session_id', CORE_TIME+(3600*24*1), '/', $_SERVER['HTTP_HOST']);
        }

        // remove session if user are banned 
        if($oRequest->get('ban') == 1){
            $oSession->set('session-topic_index', 1);
            $oSession->setArray('session-domain', 'id', -1);
            exit(); // need check to decide that we need exit at here or not.
        }
        $sAffid = $oRequest->get('affid');
        if($sAffid){
            $sAffid = Core::getLib('input')->removeXSS($sAffid);
            // create cookie
            Core::setCookie('affid', $sAffid, CORE_TIME + 31536000, '/', ".".$_SERVER['HTTP_HOST']); //31536000 = 365*24*3600
        }
        unset($sAffid);
        
        $oSession->set('session-topic_index', $aSetting['category_index']);
        $oSession->set('session-openid_login', $aSetting['openid_login']);
        $oSession->set('session-language', $aSetting['language']);
        $oSession->set('session-page_type', $aSetting['page_type']);
        $oSession->set('session-twitter_key', $aSetting['twitter_key']);
        $oSession->set('session-twitter_secret', $aSetting['twitter_secret']);
        $oSession->set('session-facebook_appid', $aSetting['facebook_appid']);
        $oSession->set('session-facebook_appsecret', $aSetting['facebook_appsecret']);
        $oSession->set('session-facebook_pageid', $aSetting['facebook_pageid']);
        $oSession->set('session-facebook_appmessage', $aSetting['facebook_appmessage']);
        $oSession->set('session-facebook_link_url', $aSetting['facebook_link_url']);
        $oSession->set('session-facebook_link_pic', $aSetting['facebook_link_pic']);
        $oSession->set('session-facebook_link_des', $aSetting['facebook_link_des']);
        $oSession->set('session-comment_facebook', $aSetting['comment_facebook']);
        $oSession->set('session-display_facebook_button', $aSetting['display_facebook_button']);
        $oSession->set('session-display_twitter_button', $aSetting['display_twitter_button']);
        $oSession->set('session-display_google_plus_button', $aSetting['display_google_plus_button']);
        $oSession->set('session-domain_login', $aSetting['domain_login']);
    }
    
    public function getToken()
    {
        static $sToken;
        
        if ($sToken) {
            return $sToken;
        }
        
        $sToken = (md5(Core::getLib('request')->getIdHash() . md5(Core::getParam('core.salt'))));
        
        return $sToken;
    }
    
    public function verifyToken()
    {
        // CSRF
        if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if (!isset($_POST[Core::getTokenName()]['security_token'])) {
                echo 'No security token has been set within the posted form. All forms must contain a security token in order for our site to handle its requests.';
                return false;
            }
            
            $sToken = $this->getToken();

            if ($sToken != $_POST[Core::getTokenName()]['security_token']) {            
                echo 'Cross site forgery request (CSFR) detected.  Please note all such attempts are logged.';
                return false;
            }
        }        
    }
}
?>
