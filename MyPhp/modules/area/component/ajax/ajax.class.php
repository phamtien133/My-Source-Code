<?php
class Area_Component_Ajax_Ajax extends Ajax
{
    public function loadCities()
    {
        $aVals = $this->get('val');
        $iId = isset($aVals['id']) ? $aVals['id'] : -1;
        if ($iId < 1) {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Không có quốc gia được chọn'
            );
            echo json_encode($aReturn);exit;
        }
        $aReturn = Core::getService('area')->loadCities(array('id' => $iId));
        
        if (count($aReturn)) {
            $aReturn = array(
                'status' => 'success',
                'data' => $aReturn
            );
            echo json_encode($aReturn);exit;
        }
        else {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Không có dữ liệu tỉnh, thành'
            );
            echo json_encode($aReturn);exit;
        }
    }
}
?>
