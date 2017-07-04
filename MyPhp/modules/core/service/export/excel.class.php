<?php
if(file_exists(DIR_MODULE . 'core'. DS . 'service' . DS . 'export'. DS . 'lib' . DS. 'PHPExcel.php'))
{
    require(DIR_MODULE . 'core'. DS . 'service' . DS . 'export'. DS . 'lib' . DS. 'PHPExcel.php');
}
class Core_Service_Export_Excel extends Service
{
    private $_oObject = null;
    
    private $_oWriter = null;
    
    private $_oActiveSheet = null;
    private $_aListCol = array();
    public function __construct()
    {
        
    }
    
    public function setHeader()
    {
        $this->_oObject->getProperties()->setCreator("onWEB.vn")
             ->setLastModifiedBy("onWEB.vn")
             ->setTitle("Office 2007 XLSX Test Document")
             ->setSubject("Office 2007 XLSX Test Document")
             ->setDescription("DB of content.")
             ->setKeywords("db content")
             ->setCategory("topic");
    }
    public function setListCol($aList)
    {
        $this->resetCol();
        $this->_aListCol = $aList;
    }
    
    public function resetCol()
    {
        $this->_aListCol = array();
    }
    
    public function export($aData, $aMap, $sFileName)
    {
        if(!is_array($aData) || !count($aData))
        {
            //return false;
        }
        set_time_limit(6000);
        ini_set('memory_limit', '500M');
        error_reporting(1);
        
        $this->_oObject->setActiveSheetIndex(0);
        $oActiveSheet = $this->_oObject->getActiveSheet();
        
        // sample
        //$oActiveSheet->SetCellValue('A1', 'Hello');
        
        $oWriter = PHPExcel_IOFactory::createWriter($this->_oObject, 'Excel2007');
        $oWriter->save($sFileName);
    }
    
    private function inventory($aData, $sFileName)
    {
        // get template
        $sTemplateFile = DIR_FILE . 'templates' . DS . Core::getLib('url')->getShortDomain() . DS . 'inventory.xlt';
        
        $aMapTemplate = array(
            'company' => array(
                'name' => 'A1',
                'addr' => 'A2',
                'info' => 'A3'
            ),
            'time' => 'F5',
            'store' => 'E6',
            'data' => 'A-9',
        );
        
        $oReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->_oObject = $oReader->load($sTemplateFile);
        $this->_oObject->setActiveSheetIndex(0);
        $oActiveSheet = $this->_oObject->getActiveSheet();
        
        foreach($aMapTemplate as $iKey => $aValue)
        {
            if(isset($aData[$iKey]) && $iKey != 'data')
            {
                if(is_array($aValue))
                {
                    foreach($aValue as $iSubKey => $sValue)
                    {
                        if(isset($aData[$iKey][$iSubKey]))
                        {
                            $oActiveSheet->SetCellValue($sValue, $aData[$iKey][$iSubKey]);
                            continue;
                        }
                    }
                    continue;    
                }
                $oActiveSheet->SetCellValue($aValue, $aData[$iKey]);
                continue;
            }
        }
        
        if(isset($aData['data']))
        {
            // tách lấy cột và dòng hiện tại ra
            $aTemp = explode('-', $aMap['data']);
            $iCol = ord($aTemp[0]);
            $iRow = $aTemp[1];
            $iCnt = 1;
            foreach($aData['data'] as $iKey => $aValue)
            {
                $iTemp = $iCol;
                $oActiveSheet->SetCellValue(chr($iTemp).$iRow, $iCnt);
                $oActiveSheet->SetCellValue(chr($iTemp++).$iRow, $aValue['info']['ten_sp']);
                $oActiveSheet->SetCellValue(chr($iTemp++).$iRow, $aValue['info']['don_vi_tinh']);
                $oActiveSheet->SetCellValue(chr($iTemp++).$iRow, $aValue['ton_dau_ky']);
                $oActiveSheet->SetCellValue(chr($iTemp++).$iRow, $aValue['nhap_trong_ky']);
                $oActiveSheet->SetCellValue(chr($iTemp++).$iRow, $aValue['xuat_trong_ky']);
                $oActiveSheet->SetCellValue(chr($iTemp++).$iRow, $aValue['ton_cuoi_ky']);
                $iRow++;
            }
        }
        
        $oWriter = PHPExcel_IOFactory::createWriter($this->_oObject, 'Excel2007');
        $oWriter->save($sFileName);
    }
    
    public function loadTemplate($sTemplateFile)
    {
        $oReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->_oObject = $oReader->load($sTemplateFile);
    }
    
    public function SetCellValue($sLocation, $sValue)
    {
        $this->_oActiveSheet->SetCellValue($sLocation, $sValue);
    }
    
    public function setActiveSheetIndex($iIndex = 0)
    {
        $this->_oObject->setActiveSheetIndex($iIndex);
        $this->_oActiveSheet = $this->_oObject->getActiveSheet();
    }
    
    public function insertImage($sImagePath, $sLocation)
    {
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setWorksheet($this->_oActiveSheet);
        $objDrawing->setPath($sImagePath);
        $objDrawing->setCoordinates($sLocation);
    }
    /**
    * thực hiện merge các ô chỉ định $aDatas và chèn dữ liệu $sValue tương ứng
    * 
    * @param mixed $aDatas
    * @param mixed $aValue
    */
    public function mergeCells($aDatas, $sValue)
    {
        if(!is_array($aDatas) || !count($aDatas))
            return false;
        $sMerge = $aDatas[0] .':'. $aDatas[count($aDatas) - 1];
        
        $this->_oActiveSheet->mergeCells($sMerge);
        $this->borderBottom($aDatas[0]);
        $this->borderRight($aDatas[0]);
        $this->align($aDatas[0], 'center');
        $this->SetCellValue($aDatas[0], $sValue);
    }
    
    public function sumCells($aDatas)
    {
        if(!isset($aDatas['value']) || !isset($aDatas['index']))
            return false;
        if(!is_array($aDatas['value']) || !count($aDatas['value']))
            return false;
            
        $sSumConds = '=';
        foreach($aDatas['value'] as $sLocation)
        {
            $sSumConds .= $sLocation.'+';
        }
        $sSumConds = rtrim($sSumConds, '+');
        $this->borderBottom($aDatas['index']);
        $this->borderRight($aDatas['index']);
        $this->SetCellValue($aDatas['index'], $sSumConds);
    }
    
    public function save($sFileName)
    {
        $this->_oWriter = PHPExcel_IOFactory::createWriter($this->_oObject, 'Excel2007');
        $this->_oWriter->save($sFileName);
    }
    
    public function borderBottom($sLocation)
    {
        $this->_oActiveSheet->getStyle($sLocation)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    
    public function borderRight($sLocation)
    {
        $this->_oActiveSheet->getStyle($sLocation)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    
    public function align($sLocation, $sType ='')
    {
        $sHAlign = '';
        switch($sType)
        {
            case 'left':
                $sHAlign = PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
                break;
            case 'right':
                $sHAlign = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                break;
            case 'center':
                $sHAlign = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                break;
            default:
                $sHAlign = PHPExcel_Style_Alignment::HORIZONTAL_GENERAL;
                break;
        }
        $this->_oActiveSheet->getStyle($sLocation)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->_oActiveSheet->getStyle($sLocation)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }
}
?>
