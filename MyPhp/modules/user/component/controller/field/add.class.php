<?php
class User_Component_Controller_Field_Add extends Component
{
    public function process()
    {
        $aVals = Core::getLib('request')->getRequests();
        $iId = 0;
        if (isset($aVals['id'])) {
            $iId = $aVals['id'];
        }
        $aPage = array();
        $aData = array();

        $aErrors = array();
        $bIsSubmit = false;

        $aFieldGroup = Core::getService('user.field')->getGroup();


        if (isset($aVals['val']) && !empty($aVals['val'])) {
            $bIsSubmit = true;
            $aReturn = Core::getService('user.field')->add($aVals['val']);
            if ($aReturn['status'] == 'success') {
                $aData = $aReturn['data'];
                $aData['status_global'] = 1;
            }
            else {
                $aErrors[] = $aReturn['message'];
            }
        }

        if ($iId < 0) $iId = 0;
        if ($iId > 0) {
            $aPage['title'] = 'Cập nhật Custom Field';
            if (!$bIsSubmit) {
                $aReturn = Core::getService('user.field')->getById(array('id' => $iId));
                if ($aReturn['status'] == 'success') {
                    $aData = $aReturn['data'];
                }
                else {
                    $aErrors[] = $aReturn['message'];
                }
            }

        }
        else {
            if (!$bIsSubmit) {
                $aData['status'] = 1;
                $aData['type'] = 'text';
            }
            $aPage['title'] = 'Tạo Custom Field';
        }

        $aData['type_list'] = array(
            'text' => 'Text',
            'checkbox' => 'CheckBox',
            'radio' => 'RadioBox',
            'select' => 'SelectBox',
            'multiselect' => 'Multi Select',
        );

        $this->template()->setHeader(array(
            'marketing.css' => 'site_css',
            'user_field_detail.js' => 'site_script',
        ));

        $this->template()->setTitle($aPage['title']);
		$this->template()->assign(array(
            'aPage' => $aPage,
            'aData' => $aData,
			'aErrors' => $aErrors,
            'iId' => $iId,
            'aFieldGroup' => $aFieldGroup
		));
    }
}
?>