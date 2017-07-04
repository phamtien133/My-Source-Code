<?php
class Project_Component_Controller_Add extends Component
{
    public function process()
    {
    	$aData = array();
    	$aVals = $this->request()->get('val');//biến $_REQUEST
    	$iId = $this->request()->getInt('id-edit', 0); //check req3
    	$bIsEdit = false;
    	//chuyen controller ma khong doi url
    	// if ($iId > 0) {
    	// 	return Core::getLib('module')->setController('project.edit');
    	// }

        //Kiểm tra nếu là edit
    	if ($iId > 0) {
    		$aData = Core::getService('project')->getDBSite(array(
    				'id' => $iId
    			));
    		if (!isset($aData['id']) || $aData['id'] < 1) {
    			return Core_Error::set('error', 'Du an khong ton tai.');
    		}
    		$bIsEdit = true;
    	}
        //Cập nhật
        if ($aVals['id'] > 0) {
            $CheckCondition = Core::getService('project')->setDBSite($aVals, 1);
            if($CheckCondition['status'] == 'success')
                $this->url()->send('project', null, 'Thêm Project thành công');
            else
                Core_Error::set('error', 'Có lỗi phát sinh, thêm Project thất bại.');  
        }
        //Thêm mới dự án
        if ($aVals['id'] <= 0 && !empty($aVals['submit'])) {
            $CheckCondition = Core::getService('project')->setDBSite($aVals);
            if($CheckCondition['status'] == 'success')
                $this->url()->send('project', null, 'Thêm Project thành công');
            else
                Core_Error::set('error', 'Có lỗi phát sinh, thêm Project thất bại.');  
        }
        $this->template()->assign(array(
                    'aData' => $aData,
                    'bIsEdit' => $bIsEdit
        ));
    }
}
?>