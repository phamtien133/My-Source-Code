<?php
class Core_Component_Ajax_Ajax extends Ajax
{
    
    public function checkNameCode()
    {
        $aVals = Core::getLib('request')->getArray('val');
        Core::getService('core')->checkNameCode($aVals);
        exit;
    }
    
    public function getFilterCategory()
    {
        $aVals = Core::getLib('request')->getArray('val');
        
        $aReturn = array();
        if(!empty($aVals)) {
            $aResult = Core::getService('category')->getFilterCategory($aVals);
            if (Core_Error::isPassed()) {
                $aReturn['status'] = 'success';
                if (!empty($aResult))
                    $aReturn['data'] = $aResult;
            }
            else {
                $aReturn['status'] = 'error';
                $aReturn['error'] = Core_Error::get();
            }
        }
        else {
            $aReturn['status'] = 'error';
        }
        echo json_encode($aReturn); exit;
    }
    
    public function checkSession()
    {
        $aVals = Core::getLib('request')->getRequests();
        
        $aReturn = Core::getService('core')->checkSession($aVals);
        echo json_encode($aReturn);exit;
    }
    
    public function getKeyword()
    {
        $aVals = Core::getLib('request')->getArray('val');
        
        Core::getService('core')->getKeyword($aVals);
        exit;
    }
    
    public function updateStatus()
    {
        $aVals = Core::getLib('request')->getArray('val');
        Core::getService('core')->updateStatus($aVals);
        exit;
    }

    public function updateProjectStatus()
    {
        $aVals = Core::getLib('request')->getArray('val');
        Core::getService('core')->updateProjectStatus($aVals);
        exit;
    }
    
    public function getRelated()
    {
        $aVals = Core::getLib('request')->getArray('val');
        Core::getService('core')->getRelated($aVals);
        exit;
    }
    
    public function savePosition()
    {
        $aVals = Core::getLib('request')->getArray('val');
        
        Core::getService('core')->savePosition($aVals);
        exit;
    }
    
    public function deleteObject()
    {
        $aVals = Core::getLib('request')->getArray('val');
        
        Core::getService('core')->deleteObject($aVals);
        exit;
    }
    
    public function searchObject()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('core')->searchObject($aVals);
        echo json_encode($aReturn); exit;
    }
    
    public function addProduct()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aReturn = Core::getService('core')->addProduct($aVals);
        echo json_encode($aReturn); exit;
    }
    
    public function registerReceiveNews()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aVals['status'] = 1;
        $aReturn = Core::getService('core')->registerReceiveNews($aVals);
        echo json_encode($aReturn); exit;
    }
    
    public function deleteSetting()
    {
        $aVals = Core::getLib('request')->getArray('val');
        $aVals['status'] = 1;
        $aReturn = Core::getService('core')->deleteSetting($aVals);
        echo json_encode($aReturn); exit;
    }
    
    public function loadDistrict()
    {
        $iCityId = $this->get('city');
        if (!$iCityId) {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Không có thành phố được chọn'
            );
            echo json_encode($aReturn);exit;
        }
        $aReturn = Core::getService('core.area')->getDistricts($iCityId);
        
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
                'message' => 'Không có dữ liệu quận,huyện'
            );
            echo json_encode($aReturn);exit;
        }
    }
    
    public function loadWard()
    {
        $iDistrictId = $this->get('district');
        if (!$iDistrictId) {
            $aReturn = array(
                'status' => 'error',
                'message' => 'Không có quận/huyện được chọn'
            );
            echo json_encode($aReturn);exit;
        }
        $aReturn = Core::getService('core.area')->getById(array(
            'pid' => $iDistrictId,
            'level' => 4
        ));
        
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
                'message' => 'Không có dữ liệu phường,xã'
            );
            echo json_encode($aReturn);exit;
        }
    }
}
?>