<?php
class User_Component_Controller_Group_Detail extends Component
{
    public function process()
    {
        $iPage = $this->request()->get('page', 1);
        $iPageSize = $this->request()->get('limit', 15);
        $iGroupId = $this->request()->get('id', 0);

        $aData = array();
        $sError = '';
        $aReturn = Core::getService('user.group')->getMemberByGroup(array(
            'gid' => $iGroupId,
            'page' => $iPage,
            'page-size' => $iPageSize,
        ));
        //d($aReturn);die;
        if ($aReturn['status'] == 'success') {
            $aData = $aReturn;
        }
        else {
            $sError = isset($aReturn['message']) ? $aReturn['message'] : 'Lỗi hệ thống';
        }
        $this->template()->setHeader(array(
            'user_group_member.js' => 'site_script',
        ));

        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'aData' => $aData,
        ));
    }
}
?>
