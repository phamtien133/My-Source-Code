<?php
class User_Service_Statistics extends Service
{
    public function __construct()
    {
        
    }
    
    public function cronjob($aParam = array())
    {
        // lấy thời gian cập nhật gần nhất
        $iTime = $this->database()->select('update_user')
            ->from(Core::getT('package_cronjob'))
            ->where('id = 1')
            ->execute('getField');
        // lấy dữ liệu theo khoảng thời gian 
        $aRows = $this->database()->select('*')
            ->from(Core::getT('user'))
            ->where('status = 0 AND join_time >= '. $iTime .' AND join_time < '. CORE_TIME)
            ->execute('getRows');
            
        $aDatas = array();
        if (count($aRows)) {
            //  đọc và thống kê dữ liệu vào mảng chung trước khi thêm vào csdl.
            // mảng data sẽ lưu dạng [thang][ngay][gio][du_lieu]
            foreach ($aRows as $aRow) {
                // chuyển thời gian qua múi giờ đang xét.
                $iCreatime = Core::getLib('date')->convertFromGmt($aRow['join_time'], Core::getParam('core.default_time_zone_offset'));
                $iYear = date('Y', $iCreatime);
                $iMonth = date('n', $iCreatime);
                $iDay = date('j', $iCreatime);
                $iHour = date('G', $iCreatime);
                //gom nhóm dữ liệu bên trong theo từng package id
                if (isset($aDatas[$iYear])) {
                    if (isset($aDatas[$iYear][$iMonth])) {
                        if (isset($aDatas[$iYear][$iMonth][$iDay])) {
                            if ($aDatas[$iYear][$iMonth][$iDay][$iHour]) {
                                $aDatas[$iYear][$iMonth][$iDay][$iHour]['total_register']++;
                            }
                            else {
                                $aDatas[$iYear][$iMonth][$iDay][$iHour] = array();
                                $aDatas[$iYear][$iMonth][$iDay][$iHour]['total_register'] = 1;
                            }
                        }
                        else {
                            $aDatas[$iYear][$iMonth][$iDay] = array();
                            $aDatas[$iYear][$iMonth][$iDay][$iHour] = array();
                            $aDatas[$iYear][$iMonth][$iDay][$iHour]['total_register'] = 1;
                        }
                    }
                    else {
                        $aDatas[$iYear][$iMonth] = array();
                        $aDatas[$iYear][$iMonth][$iDay] = array();
                        $aDatas[$iYear][$iMonth][$iDay][$iHour] = array();
                        $aDatas[$iYear][$iMonth][$iDay][$iHour]['total_register'] = 1;
                    }
                }
                else {
                    $aDatas[$iYear] = array();
                    $aDatas[$iYear][$iMonth] = array();
                    $aDatas[$iYear][$iMonth][$iDay] = array();
                    $aDatas[$iYear][$iMonth][$iDay][$iHour] = array();
                    $aDatas[$iYear][$iMonth][$iDay][$iHour]['total_register'] = 1;
                }
            }
            
            if (count($aDatas)) {
                // thực hiện lưu dữ liệu vào database.
                foreach ($aDatas as $iKeyYear => $aYear) {
                    foreach ($aYear as $iKeyMonth => $aMonth) {
                        foreach ($aMonth as $iKeyDay => $aDay) {
                            foreach ($aDay as $iKeyHour => $aHour) {
                                $iTotal = 0;
                                foreach ($aHour as $iItemId => $iValue) {
                                    $iTotal += $iValue;
                                }
                                $iCreateTime = Core::getLib('date')->mktime($iKeyHour, 0, 0 , $iKeyMonth, $iKeyDay, $iKeyYear);
                                $aInsert = array(
                                    'total_register' => $iTotal,
                                    'year_number' => $iKeyYear,
                                    'month_number' => $iKeyMonth,
                                    'day_number' => $iKeyDay,
                                    'hour_number' => $iKeyHour,
                                    'create_time' => $iCreateTime,
                                    'status' => 1
                                );
                                $iId = $this->database()->insert(Core::getT('user_statistics'), $aInsert);
                            }
                        }
                    }
                }
            }
        }
        
        // cập nhật lại thời gian chạy cron gần nhất.
        $this->database()->update(Core::getT('package_cronjob'), array(
            'update_user' => CORE_TIME
        ), 'id = 1');
    }
    
    public function getStatistics($aParam = array())
    {
        $aDatas = array();
        $aDatas['country'] = array();
        // tính tổng số các user theo từng nước.
        $aRows = $this->database()->select('country, COUNT(country) as total')
            ->from(Core::getT('user'))
            ->where('status = 0')
            ->group(country)
            ->execute('getRows');
        
        $aTmp = array();
        $iTotalUser = 0;
        foreach ($aRows as $aRow) {
            $iTotalUser += $aRow['total'];
            if ($aRow['country'] > 0) {
                $aTmp[] = $aRow['country'];
            }
        }
        if (count($aTmp)) {
            // lấy thông tin quốc gia
            $aTmps  = $this->database()->select('id, name')
                ->from(Core::getT('area'))
                ->where('status = 1 AND degree = 1 AND id IN ('.implode(',', $aTmp).')')
                ->execute('getRows');
                
            $aCountry = array();
            foreach ($aTmps as $aTmp) {
                $aCountry[$aTmp['id']] = $aTmp;
            }
            
            foreach ($aRows as $iKey => $aRow) {
                if (isset($aCountry[$aRow['country']])) {
                    $aValue = $aCountry[$aRow['country']];
                    $aValue['total_user'] = $aRow['total'];
                    $aDatas['country'][] = $aValue;
                    unset($aRows[$iKey]);
                }
            }
            // đưa các dữ liệu không có country vào một mảng chưa xác định.
            $iTotalUndefine = 0;
            foreach ($aRows as $aRow) {
                $iTotalUndefine += $aRow['total'];
            }
            $aUndefine = array(
                'name' => 'Chưa xác định',
                'total_user' => $iTotalUndefine
            );
            $aDatas['country'][] = $aUndefine;
        }
        // lấy dữ liệu thống kê theo biểu đồ.
        $iStartTime = isset($aParam['from']) ? $aParam['from'] : 0;
        $iEndTime = isset($aParam['to']) ? $aParam['to'] : 0;
        // 
        $oDate = Core::getLib('date');
        $bIsPass = true;
        if (!empty($iStartTime) && $iStartTime !== 0) {
            $aTmp = Core::getService('api.validate')->checkDate(array(
                'data' => $iStartTime,
            ));
            if ($aTmp['status'] == 'error') {
                $bIsPass = false;
                $aDatas['chart'] = $aTmp;
            }
            else {
                $iStartTime = $aTmp['data'];
                // do mặc định hàm strtotime là lưu theo time zone gmt (đã set mặc định), nên phải chuyển về múi giờ đang xét cho đúng với dữ liệu thống kê.
                $iStartTime = $oDate->convertFromGmt($iStartTime, Core::getParam('core.default_time_zone_offset'));
                $iStartTime = $oDate->mktime(0,0,0, date('n', $iStartTime), date('j', $iStartTime), date('Y', $iStartTime));
                // do dữ liệu thống kê lưu theo đúng múi giờ đang mặc định nên không chuyển về gmt.
                //$iStartTime = $oDate->convertToGmt($iStartTime);
            }
        }
        if (!empty($iEndTime) && $iEndTime !== 0) {
            $aTmp = Core::getService('api.validate')->checkDate(array(
                'data' => $iEndTime,
            ));
            if ($aTmp['status'] == 'error') {
                $bIsPass = false;
                $aDatas['chart'] = $aTmp;
            }
            else {
                $iEndTime = $aTmp['data'];
                // do mặc định hàm strtotime là lưu theo time zone gmt (đã set mặc định), nên phải chuyển về múi giờ đang xét cho đúng với dữ liệu thống kê.
                $iEndTime = $oDate->convertFromGmt($iEndTime, Core::getParam('core.default_time_zone_offset'));
                $iEndTime = $oDate->mktime(23,59,59, date('n', $iEndTime), date('j', $iEndTime), date('Y', $iEndTime));
                // do dữ liệu thống kê lưu theo đúng múi giờ đang mặc định nên không chuyển về gmt.
                //$iEndTime = $oDate->convertToGmt($iEndTime);
            }
            
        }
        
        $aDatas['total_user'] = $iTotalUser;
        if ($bIsPass) {
            $aDatas['chart'] = $this->getChartData(array(
                'start' => $iStartTime,
                'end' => $iEndTime
            ));
        }
        
        return array(
            'status' => 'success',
            'data' => $aDatas
        );
    }
    
    public function getChartData($aParam = array())
    {
        $iStartTime = isset($aParam['start']) ? $aParam['start'] : 0;
        $iEndTime = isset($aParam['end']) ? $aParam['end'] : 0;
        // xử lý thời gian gởi lên. do thời gian gởi lên phụ thuộc vào múi giờ, nên do đó nếu dữ liệu mặc định thì cần chuyển qua giờ theo múi giờ đang chọn.
        if ($iEndTime == 0) {
            $iEndTime = CORE_TIME;
            // do dữ liệu thống kê đang là dữ liệu theo múi giờ hiện tại, do đó phải chuyển thời gian này qua theo đúng múi giờ
            $iEndTime = Core::getLib('date')->convertFromGmt($iEndTime, Core::getParam('core.default_time_zone_offset'));
        }
        if ($iStartTime == 0) {
            // nếu có endtime thì mặc định thời gian bắt đầu là 7 ngày trước 
            $iStartTime = $iEndTime - 7 * 24 * 60 * 60;
            // chuyển thời gian về đúng 0 hour, 0 minute, 0 second.
            $iStartTime = Core::getLib('date')->mktime(0,0,0,date('n',$iStartTime), date('j',$iStartTime), date('Y',$iStartTime));
        }
        
        // lấy danh sách mảng index dữ liệu theo thời gian
        $aDatas = $this->_getTimeReport(array(
            'start_time' => $iStartTime,
            'end_time' => $iEndTime
        ));
        $iTotal = 0;
        $iTotalWithdraw = 0;
        // lấy dữ liệu để tiến hành thống kê
        $aRows = $this->database()->select('*')
            ->from(Core::getT('user_statistics'))
            ->where('status = 1 AND create_time >= '. $iStartTime .' AND create_time <='. $iEndTime)
            ->execute('getRows');
        $aStatistics = array(); 
        if (count($aRows)) {
            foreach ($aRows as $aRow) {
                $iTotal += $aRow['total_register'];
                // gom nhóm các dữ liệu theo năm, tháng, ngày để  tổng hợp.
                if (isset($aStatistics[$aRow['year_number']])) {
                    if (isset($aStatistics[$aRow['year_number']][$aRow['month_number']])) {
                        if (isset($aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']])) {
                            $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']]['value']['total_register'] += $aRow['total_register'];
                        }
                        else {
                            $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']] = array();
                            $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']]['value']['total_register'] = $aRow['total_register'];
                        }
                    }
                    else {
                        $aStatistics[$aRow['year_number']][$aRow['month_number']] = array();
                        $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']] = array();
                        $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']]['value']['total_register'] = $aRow['total_register'];
                    }
                }
                else {
                    $aStatistics[$aRow['year_number']] = array();
                    $aStatistics[$aRow['year_number']][$aRow['month_number']] = array();
                    $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']] = array();
                    $aStatistics[$aRow['year_number']][$aRow['month_number']][$aRow['day_number']]['value']['total_register'] = $aRow['total_register'];
                }
            }
        }
        // tính tổng user
        $iTotalUser = $this->database()->select('COUNT(*)')
            ->from(Core::getT('user'))
            ->where('status = 0')
            ->execute('getField');
        
        // tính lại tổng user trước thời điểm thống kê
        $iCnt = $iTotalUser - $iTotal;
        // duyệt lại để thêm dữ liệu tổng user
        
        $sFormat = $aDatas['format'];
        $aTmps = $aDatas['date'];
        // map giữa data và thời gian.
        foreach ($aStatistics as $iYeay => $aYear) {
            foreach ($aYear as $iMonth => $aMonth) {
                foreach ($aMonth as $iDay => $aDay) {
                    $sIndex = '';
                    if ($sFormat == 'j-n') {
                        $sIndex = $iDay.'-'.$iMonth;
                    }
                    else if ($sFormat == 'j-n-Y') {
                        $sIndex = $iDay.'-'.$iMonth .'-'.$iYeay;
                    }
                    if (isset($aTmps[$sIndex])) {
                        $aTmps[$sIndex] = $aDay;
                    }
                }
            }
        }
        // do mảng day là mảng đã sắp xếp nên duyệt mảng này để đếm tổng lượng đăng ký
        foreach ($aTmps as $sKey => $aValue) {
            $iCnt += $aValue['value']['total_register'];
            $aTmps[$sKey]['value']['total_user'] = $iCnt;
        }
        
        $aDatas = array();
        $aDatas['label'] = array_keys($aTmps);
        $aDatas['data'] = $aTmps;
        $aDatas['total_register'] = $iTotal;
        $aDatas['total_user'] = $iTotalUser;
        $aDatas['start_time'] = date('d/m/Y', $iStartTime);
        $aDatas['end_time'] = date('d/m/Y', $iEndTime);
        
        return $aDatas;
    }
    
    private function _getTimeReport($aParam = array())
    {
        if(!isset($aParam['start_time']) && !isset($aParam['end_time']))
            return array();
        $aFulls = array();
        // tính toán mốc thời gian cho biểu đồ. tối đa 10 mức
        $iAmount = abs($aParam['end_time'] - $aParam['start_time']);
        $iTotal = floor($iAmount/(60*60*24)) + 1;
        $sType = 'day';
        // tính khoảng cách giữa 2 code trong biểu đồ
        $iDistance = floor($iTotal/10);
        if ($iDistance >= 30) {
            $sType = 'month';
            $iDistance = floor($iDistance /30);
        } 
        elseif($iDistance < 1) {
            $iDistance = 1;
        }
        $iStartTime = $aParam['start_time'];
        $iEndTime = $aParam['end_time'];
        $aDates = array();
        $sFormat = '';
        switch($sType)
        {
            case 'month':
                if (date('Y', $iStartTime) == date('Y', $iEndTime)) {
                    $iCurrentMonth = date('n', $iStartTime);
                    while ($iCurrentMonth <= date('n', $iEndTime)) {
                        $aDates[$iCurrentMonth.'-'.date('Y', $iStartTime)] = array();
                        $iCurrentMonth += $iDistance;
                    }
                }
                else {
                    $iCurrentYear = date('Y', $iStartTime);
                    $iCurrentMonth = date('n', $iStartTime);
                    while($iCurrentYear <= date('Y', $iEndTime)) {
                        while($iCurrentMonth <= 12) {
                            $aDates[$iCurrentMonth.'-'.$iCurrentYear] = array();
                            $iCurrentMonth += $iDistance;
                        }
                        if($iCurrentMonth > 12)
                            $iCurrentMonth = $iCurrentMonth - 12;
                        $iCurrentYear++;
                    }
                }
                break;
            case 'day':
                $iCurrentTime = $iStartTime;
                $iCnt = 1;
                $sFormat = 'j-n';
                if (date('Y', $iStartTime) != date('Y', $iEndTime)) {
                    $sFormat = 'j-n-Y';
                }
                while($iCnt <= $iTotal) {
                    $aDates[date($sFormat, $iCurrentTime)] = array();
                    $iCurrentTime += $iDistance * 24 * 60 * 60;
                    $iCnt += $iDistance;
                }
                break;
        }
       
        return array(
            'type' => $sType,
            'format' => $sFormat,
            'date' => $aDates,
        );
    }
}
?>
