<?php
class Session_Handler_Default
{
    private $_iLifeTime = 0;

    /**
     * Loads session handler. All we do here is start a session.
     *
     */
    public function init()
    {
        //$this->_iLifeTime = get_cfg_var("session.gc_maxlifetime");
        $this->_iLifeTime = 4 * 60 * 60;
        session_set_save_handler(
                array(&$this, 'open'),
                array(&$this, 'close'),
                array(&$this, 'read'),
                array(&$this, 'write'),
                array(&$this, 'destroy'),
                array(&$this, 'gc')
        );

        if(!isset($_SESSION)){
            session_start();
        }
    }

    function open( $save_path, $session_name )
    {
        global $sess_save_path;
        $sess_save_path = $save_path;

        // Don't need to do anything. Just return TRUE.
        return true;
   }

   function close()
   {
      return true;
   }

   function read( $iId )
   {
        $oSession = Core::getLib('session');
        //if($oSession->getArray('session-domain', 'id') < 1)
        //    $oSession->setArray('session-domain', 'id', 0);
        // Set empty result
        // Fetch session data from the selected database
        $sQuery = '';
        $oDatabase = Core::getLib('database');
        $sData = $oDatabase->select('value')
            ->from('sessions')
            ->where($sQuery .' id = "'. $oDatabase->escape($iId). '" AND expire > '. CORE_TIME)
            ->execute('getField');
        // remove các addslashes nếu có
        return (string) $sData;
   }

   function write( $iId, $sData )
   {
       $oSession = Core::getLib('session');
        if($oSession->getArray('session-domain', 'id') < 1)
            $oSession->setArray('session-domain', 'id', 0);

        // Build query
        $iTime = CORE_TIME + $this->_iLifeTime;
        $sQuery = '';
        //$query = '`ten_mien_stt`='.$_SESSION['session-ten_mien']['stt'].' AND ';

        // first checks if there is a session with this id
        $oDatabase = Core::getLib('database');

        $iCount = $oDatabase->select('count(id)')
            ->from('sessions')
            ->where($sQuery . ' id = "'. $oDatabase->escape($iId). '"')
            ->execute("getField");

        $sKeyword = (string)Core::getService('core.tools')->searchEngineQueryString();

        $sTmp = Core::getService('core.tools')->selfURL();

        $aUpdate = array();
        if ($_SERVER['SCRIPT_NAME'] == '/includes/run.php' && strpos($sTmp, '/tools/') === false ) {
            $aUpdate['path'] = $oDatabase->escape($sTmp);
        }
        if ($sKeyword != '') {
            $aUpdate['key_word'] = $oDatabase->escape($sKeyword);
        }

        // if there is
        if ($iCount > 0) {
            // update the existing session's data
            // and set new expiry time
            // 1 số trường hợp ten_mieN_stt là 0, vì vậy cũng nên update ten_mien_stt
            $aUpdate['value'] = $sData;
            $aUpdate['expire'] =  $iTime;
            $oDatabase->update('sessions', $aUpdate, $sQuery . ' id = "'. $oDatabase->escape($iId). '"');
        }
        else {
            if ($oSession->getArray('session-user', 'id') > 0) {
                $aUpdate['user_id'] = $oSession->getArray('session-user', 'id');
                $aUpdate['user_fullname'] = $oSession->getArray('session-user', 'name');
            }

            if ($oSession->getArray('session-user', 'priority_group') == 0)
                $oSession->setArray('session-user', 'priority_group', -1);

            $aUpdate['id'] = $iId;
            $aUpdate['bot'] = Core::getService('core')->checkBot();
            $aUpdate['ip'] = (int) Core::getLib('request')->getIp(true);
            $aUpdate['value'] = $sData;
            $aUpdate['expire'] =  $iTime;
            $aUpdate['priority_group'] = $oSession->getArray('session-user', 'priority_group');
            $aUpdate['domain_id'] = $oSession->getArray('session-domain', 'id');

            $oDatabase->insert('sessions', $aUpdate);
        }
      return TRUE;
   }

    function destroy( $id )
    {
        Core::getLib('database')->delete('sessions', 'id ='. $id);
        return TRUE;
    }

   function gc()
   {
      // Build DELETE query.  Delete all records who have passed the expiration time
      Core::getLib('database')->delete('sessions', 'expire <'. CORE_TIME);
      // Always return TRUE
      return true;
   }
}

?>