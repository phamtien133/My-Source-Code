<?php
class Umbala_Service_Umbala extends Service
{    
    public function __construct()
    {

    }
    //************************************************************
    //************************************************************
    //**                                                        **
    //**                         FOOTER                         **
    //**                                                        **
    //************************************************************
    //************************************************************
    // ***** HÀM XỬ LÝ CHO FOOTER - LOAD TẤT CẢ DỮ LIỆU CẦN *****
    public function getFooterData($aParam = array())
    {
        try {
            if(empty($aParam['module']) || empty($aParam['table'])|| empty($aParam['title']) || empty($aParam['id']) {
                return array(
                    'status' => 'error',
                    'message' => 'Dự liệu truyền vào bị rỗng'
                );
            }
            //* ***** TAB TIN TỨC *****
            $aNews = getNews($aParam)['message'];

            //* ***** TAB TTTVIÊN *****
            $aUserInfo = getUserInfo($aParam)['message'];
            $aBankInfo = getBankInfo($aParam)['message'];

            //* ***** TAB HỖ TRỢ TVIÊN *****
            $aUserSupport = getUserSupport($aParam)['message'];

            //* ***** TAB THẮC MẮC TVIÊN *****
            $aUserQuestion = getUserQuestion($aParam)['message'];

            $aData = array(
                'news' => $aNews,
                'user_info' => $aUserInfo,
                'bank_info' => $aBankInfo,
                'user_support' => $aUserSupport,
                'user_question' => $aUserQuestion
            );
            return array(
                'status' => 'success',
                'message' => $aData
            );
        } catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }  
    }

    /* ***** HÀM ĐỂ LẤY DANH SÁCH BÀI VIẾT ***** */
    //Hàm định dạng title
    function formatTitle($text, $chars)
    {
        if (strlen($text) > $chars) {
            $textx = "[...]";
        }

        $text = $text." ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= $text;

        return $text;
    }  
    //hàm lấy danh sách bài viết
    public function getNews($aParams = array())
    {
        try {
            if(empty($aParam['module']) || empty($aParam['table']) || empty($aParam['title'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            } else {                   
                $sLinkFull = '/' . $aParam['module'] . '/?';
                      
                if(empty($errors))
                {
                    $query = '';
                    $duong_dan_phan_trang = '';
                    $limit = $this->request()->get('limit') * 1;
                    if($limit < 1) $limit = 8;

                    if(!empty($limit)) {
                      $sLinkFull .= '&limit = ' . $limit;
                      $duong_dan_phan_trang .= '&limit = ' . $limit;
                    }

                    $trang_hien_tai = addslashes($this->request()->get('page')) * 1;

                    $tong_cong = $this->database()->select('count(id)')
                        ->from(Core::getT($aParam['table'])) //table = promotion_news
                        ->where("status != 2")
                        ->execute('getField');

                    if(!@$trang_hien_tai) $trang_hien_tai=1;
                    $trang_bat_dau = ($trang_hien_tai-1)*$limit;

                    $dir = $_SERVER['REQUEST_URI'];
                    $tmps = explode('/', $dir, 3);
                    $dir = '/'.$tmps[1].'/';

                    $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;

                    $aRows = $this->database()->select('title, status, date')
                        ->from(Core::getT($aParam['table']))
                        ->where("status != 2")
                        ->order('date DESC')
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');

                    foreach ($aRows as $rows)
                    {
                        $news_custom['title'][] = formatTitle($rows["title"], 60);
                        if($rows["status"]==0) $tmp = 'status_no';
                        else $tmp = 'status_yes';
                        $news_custom['status_text'][] = $tmp;
                        $news_custom['status'][] = $rows["status"];
                        $news_custom['date'][] = date('d/m/Y', $rows["date"]);
                    }
                
                $output = array(
                    'duong_dan_phan_trang',
                    'errors',
                    'news_custom',
                    'status',
                    'tong_trang',
                    'tong_cong',
                    'trang_hien_tai',
                    'sLinkFull',
                    'sLinkSort'
                );

                foreach($output as $key)
                {
                  $data[$key] = $key;
                }
                
                $aData = array(
                    'output' => $output,
                    'data' => $data
                );

                return array(
                    'status' => 'success',
                    'message' => $aData
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }  
    }

    //Hàm lấy chi tiết bài viết
    public function getNewsInfo($aParam) {
        try {
            if(empty($aParam['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            } else {

                $aRow = $this->database()->select('*')
                ->from(Core::getT('news')
                ->where("id = '" . $aParam['id'] . "'")
                ->execute('getRow');

                return array(
                    'status' => 'success',
                    'message' => $aRow
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        } 
    }
    /* ***** XỬ LÝ XONG HÀM LẤY DANH SÁCH BÀI VIẾT ***** */

    /* ***** HÀM LẤY TTTVIÊN ***** */
    //Hàm update level user
    public function updateUserLevel($aParam = array())
    {
        try {
            if(empty($aParam['id']) || empty($aParam['level'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $this->database()->update(Core::getT('user'), array(
                        'level' => $aParam['level']
                    ), 'id ='. $aParam['id']);
            return array(
                'status' => 'success',
                'message' => 'nâng cấp độ thành viên thành công'
                );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
    //Hàm lấy mật khẩu user
    public function getUserPassword($aParam = array())
    {
        try {
            if(empty($aParam['id'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aRow = $this->database()->select('password')
            ->from(Core::getT('user'))
            ->where("id = '" . $aParam['id'] . "'")
            ->execute('getField')
            
            return array(
                    'status' => 'success',
                    'message' => $aRow
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //Hàm update mật khẩu user
    public function updateUserPass($aParam = array())
    {
        try {
            if(empty($aParam['id']) || empty($aParam['password'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $this->database()->update(Core::getT('user'), array(
                        'password' => $aParam['password']
                    ), 'id ='. $aParam['id']);
            return array(
                'status' => 'success',
                'message' => 'Đổi mật khẩu thành công thành công'
                );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //Hàm lấy thông tin user
    public function getUserInfo($aParam = array())
    {
        try {
            if(empty($aParam['id'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aRow = $this->database()->select('*')
            ->from(Core::getT('user'))
            ->where("id = '" . $aParam['id'] . "'")
            ->execute('getRow')

            $sPassword = '********';

            $aData = array(
                'user_name' => $aRow['user_name'],
                'password' => $sPassword,
                'name' => $aRow['name'],
                'email' => $aRow['email'],
                'phone' => $aRow['phone'],
                'address' => $aRow['address']
            );

            return array(
                    'status' => 'success',
                    'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //Hàm lấy thông tin tài khoản ngân hàng
    public function getBankInfo($aParam = array())
    {
        try {
            if(empty($aParam['id'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aRow = $this->database()->select('*')
            ->from(Core::getT('bank_account'))
            ->where("id = '" . $aParam['id'] . "'")
            ->execute('getRow')

            return array(
                    'status' => 'success',
                    'message' => $aRow
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
    /* ***** XỬ LÝ XONG HÀM LẤY TTTVIÊN ***** */

    /* ***** HÀM XỬ LÝ HỖ TRỢ THÀNH VIÊN ***** */
    public function getUserSupport($aParam = array())
    {

    }

    /* ***** HÀM LẤY XỬ LÝ THẮC MẮC THÀNH VIÊN ***** */
    public function getUserQuestion($aParam = array())
    {
        
    }


    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ NHẤT                       **
    //**                                                        **
    //************************************************************
    //************************************************************
    // ***** HÀM XỬ LÝ CHO HÌNH THỨ NHẤT - LOAD TẤT CẢ DỮ LIỆU CẦN *****
    public function getAllData($aParam = array())
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['start_date']) || empty($aParam['id']) || empty($aParam['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Dự liệu truyền vào bị rỗng'
                );
            }
            
            //* ***** 5 TAB ĐẦU TIÊN *****
            //Đơn hàng trong ngày
            $aInvoiceInDate = getInvoiceInDate($aParam, 1)['message'];

            //Đơn hàng trong tháng + so sánh với tháng trước
            $aInvoiceInMonth = getInvoiceInMonth($aParam, 1)['message'];
            $aCompare = compareSumInvoice($aParam)['message'];

            //Doanh số trong tháng + so sánh với tỉ lệ tháng trước
            $aSalesInMonth = getSales($aParam, 1)['message'];
            $aRatio = getRatioSales($aParam)['message'];

            //Lợi nhuận ròng đến thời điểm hiện tại + start_date, end_date
            $aNetProfit = getNetProfit($aParam, 1)['message'];

            //Cấp độ thành viên
            $aUserLevel = getUserLevel($aParam)['message'];

            //* ***** BIỂU ĐỒ *****
            //Thông tin biểu đồ hóa đơn - invoice
            $aChartInfoInvoice = getChartInfo($aParam, 1)['message'];

            //Thông tin biểu đồ doanh thu
            $aChartInfoSales = getChartInfo($aParam, 0)['message'];

            $aData = array(
                'invoice_in_date' => $aInvoiceInDate,
                'invoice_in_month' => $aInvoiceInMonth,
                'compare' => $aCompare,
                'sales_in_month' => $aSalesInMonth,
                'ratio' => $aRatio,
                'net_profit' => $aNetProfit,
                'user_level' => $aUserLevel,
                'chart_info_invoice' => $aChartInfoInvoice,
                'chart_info_sales' => $aChartInfoSales
            );
            return array(
                        'status' => 'success',
                        'message' => $aData
            );
        } catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }          
    }

    //Hàm lấy tổng hóa đơn trong ngày - invoice in date
    public function getInvoiceInDate($aParam = array(), $bIsCurrentDate)
    {
        try {
            if(empty($aParam['current_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại'
                );
            }

            $aCurrentDate = strtotime(date('d/m/Y', $aParam['current_date']));

            if($bIsCurrentDate) {
                $dCurrentDate = strtotime(date('d/m/Y', getdate()));
            }

            $sField = $this->database()->select('count(id)')
            ->from(Core::getT('invoice'))
            ->where("date = ".$dCurrentDate)
            ->execute('getField');

            $aData = array(
                'order_sum' => $sField,
                'date' => date('d/m/Y', $aCurrentDate)
            );

            return array(
                'status' => 'success',
                'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }        
    }

    //Hàm lấy tổng hóa đơn tháng - invoice in month
    public function getInvoiceInMonth($aParam = array(), $bIsCurrentMonth)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = strtotime(date('d/m/Y', $aParam['start_date']));
            $aParam['end_date'] = strtotime(date('d/m/Y', $aParam['end_date']));

            if($bIsCurrentMonth) {
                $aCurrentDate = getdate();
                $sStartDate = date("1" . "/". $aCurrentDate["mon"] . "/" . $aCurrentDate["year"]);
                $aParam['start_date'] = strtotime($sStartDate);
                $aParam['end_date'] = strtotime(date('d/m/Y', $aCurrentDate));
            }

            $sField = $this->database()->select('count(id)')
            ->from(Core::getT('invoice'))
            ->where("date >= ".$aParam['start_date']." and date <= ".$aParam['end_date'])
            ->execute('getField');

            $aData = array(
                'order_sum' => $sField
            );

            return array(
                'status' => 'success',
                'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }        
    }

    //Hàm so sánh đơn hàng với tháng trước
    public function compareSumInvoice($aParam = array())
    {
        if(empty($aParam['start_date']) || empty($aParam['end_date']) 
            || empty($aParam2['start_date']) || empty($aParam2['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $iCurrentMonth = getOrderFormInMonth($aParam, 1)['message']['order_sum'];
        $iBeforeMonth = getOrderFormInMonth($aParam, 0)['message']['order_sum'];

        $aData = array(
            'compare' => $iCurrentMonth['order_sum'] - $iBeforeMonth['order_sum']
        );

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }

    //Hàm lấy doanh số tháng - sales
    public function getSales($aParam = array(), $bIsCurrentMonth)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = strtotime(date('d/m/Y', $aParam['start_date']));
            $aParam['end_date'] = strtotime(date('d/m/Y', $aParam['end_date']));

            if($bIsCurrentMonth) {
                $aCurrentDate = getdate();
                $sStartDate = date("1" . "/". $aCurrentDate["mon"] . "/" . $aCurrentDate["year"]);
                $aParam['start_date'] = strtotime(date('d/m/Y', $sStartDate));
                $aParam['end_date'] = strtotime(date('d/m/Y', $aCurrentDate));
            }

            $sField = $this->database()->select('sum(money)')
            ->from(Core::getT('invoice'))
            ->where("date >= ".$aParam['start_date']." and date <= ".$aParam['end_date'])
            ->execute('getField')

            $aData = array(
                'sales' => $sField
            );
            
            return array(
                'status' => 'success',
                'message' => $aData
            );   
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }    
        
    }

    //Hàm tính tỉ lệ doanh số
    public function getRatioSales($aParam = array())
    {
        if(empty($aParam['start_date']) || empty($aParam['end_date']) 
            || empty($aParam2['start_date']) || empty($aParam2['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $iCurrentMonth = getOrderFormInMonth($aParam, 1)['message']['sales'];
        $iBeforeMonth = getOrderFormInMonth($aParam, 0)['message']['sales'];

        $aData = array(
            'ratio_sales' => $iCurrentMonth['sales']/$iBeforeMonth['sales']
        );

        return array(
            'status' => 'success',
            'message' => $aData 
        );
    }

    /*  ***** CÁC HÀM ĐỂ TÍNH LỢI NHUẬN RÒNG *****
        lợi nhuận ròng = Lợi nhuận gộp - các chi phí liên quan - tổng thuế mỗi đơn hàng
        lợi nhuận gộp = doanh thu thuần - giá vốn
        giá vốn = giá tạo ra sp = giá mua vào 
    */
    //Hàm tính các loại thuế có liên quan trong hóa đơn: Thuế GTGT - value-added tax(VAT), thuế tiêu thụ đặc biệt - excise tax, thuế xuất khẩu - Export tax 
    public function getTax($aParam = array(), $bIsCurrentMonth)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = strtotime(date('d/m/Y', $aParam['start_date']));
            $aParam['end_date'] = strtotime(date('d/m/Y', $aParam['end_date']));
            
            if($bIsCurrentMonth) {
                $aCurrentDate = getdate();
                $sStartDate = date("1" . "/". $aCurrentDate["mon"] . "/" . $aCurrentDate["year"]);
                $aParam['start_date'] = strtotime(date('d/m/Y', $sStartDate));
                $aParam['end_date'] = strtotime(date('d/m/Y', $aCurrentDate));
            }

            $sField = $this->database()->select('sum((vat + excise_tax + export_tax) * money))')
            ->from(Core::getT('invoice'))
            ->where("date >= ".$aParam['start_date']." and date <= ".$aParam['end_date'])
            ->execute('getField')

            $aData = array(
                'tax' => $sField
            );

            return array(
                'status' => 'success',
                'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //Hàm tính giá vốn - Cost of goods sold
    public function getCostOfGoodsSold($aParam = array(), $bIsCurrentMonth)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = strtotime(date('d/m/Y', $aParam['start_date']));
            $aParam['end_date'] = strtotime(date('d/m/Y', $aParam['end_date']));
            
            if($bIsCurrentMonth) {
                $aCurrentDate = getdate();
                $sStartDate = date("1" . "/". $aCurrentDate["mon"] . "/" . $aCurrentDate["year"]);
                $aParam['start_date'] = strtotime(date('d/m/Y', $sStartDate));
                $aParam['end_date'] = strtotime(date('d/m/Y', $aCurrentDate));
            }

            $sField = $this->database()->select('sum(cost_of_goods_sold)')
            ->from(Core::getT('invoice'))
            ->where("date >= ".$aParam['start_date']." and date <= ".$aParam['end_date'])
            ->execute('getField')

            $aData = array(
                'cost_of_goods_sold' => $sField
            );

            return array(
                'status' => 'success',
                'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //Hàm tính lợi nhuận gộp - gross profit
    public function getGrossProfit($aParam, $bIsCurrentMonth)
    {   
        $iSales = 0;
        $iCostOfGoodsSold = 0;

        if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        if($bIsCurrentMonth) {
            $iSales = getSales($aParam, 1)['message']['sales'];
            $iCostOfGoodsSold = getCostOfGoodsSold($aParam, 1)['message']['cost_of_goods_sold'];
        } else {
            $iSales = getSales($aParam, 0)['message']['sales'];
            $iCostOfGoodsSold = getCostOfGoodsSold($aParam, 0)['message']['cost_of_goods_sold'];
        }
        
        $iGrossProfit = $iSales - $iCostOfGoodsSold;

        $aData = array(
            'gross_profit' => $iGrossProfit
        );

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }

    //Hàm tính các chi phí liên quan: phí dịch vụ - services cost, phí vận chuyển - transport cost
    public function getRelatedCost($aParam, $bIsCurrentMonth)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = strtotime(date('d/m/Y', $aParam['start_date']));
            $aParam['end_date'] = strtotime(date('d/m/Y', $aParam['end_date']));
            
            if($bIsCurrentMonth) {
                $aCurrentDate = getdate();
                $sStartDate = date("1" . "/". $aCurrentDate["mon"] . "/" . $aCurrentDate["year"]);
                $aParam['start_date'] = strtotime(date('d/m/Y', $sStartDate));
                $aParam['end_date'] = strtotime(date('d/m/Y', $aCurrentDate));
            }

            $sField = $this->database()->select('sum(services_cost + transport_cost)')
            ->from(Core::getT('invoice'))
            ->where("date >= ".$aParam['start_date']." and date <= ".$aParam['end_date'])
            ->execute('getField')

            $aData = array(
                'related_cost' => $sField
            );

            return array(
                'status' => 'success',
                'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //Hàm tính lợi nhuận ròng - net profit
    public function getNetProfit($aParam, $bIsCurrentMonth)
    {
        $iGrossProfit = 0;  //Lợi nhuận gộp
        $iRelatedCost = 0;  //Chi phí liên quan
        $iTax = 0;          //Tổng thuể mỗi đơn hàng
        $aCurrentDate = getdate();
        $dCurrentDate = $aCurrentDate["mday"] . "/" . $aCurrentDate["mon"] . "/" . $aCurrentDate["year"];

        if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        if($bIsCurrentMonth) {
            $iGrossProfit = getGrossProfit($aParam, 1)['message']['gross_profit'];
            $iRelatedCost = getRelatedCost($aParam, 1)['message']['related_cost'];
            $iTax = getTax($aParam, 1)['message']['tax'];            
        } else {
            $iGrossProfit = getGrossProfit($aParam, 0)['message']['gross_profit'];
            $iRelatedCost = getRelatedCost($aParam, 0)['message']['related_cost'];
            $iTax = getTax($aParam, 0)['message']['tax']; 
        }

        $iNetProfit =  $iGrossProfit - $iRelatedCost - $iTax;

        $aData = array(
            'net_profit' => $iNetProfit,
            'start_date' =>  $aParam['start_date'],
            'current_date_of_month' => $dCurrentDate
        );

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }
    /* ***** TÍNH XONG LỢI NHUẬN RÒNG ***** */

    //Hàm lấy cấp độ thành viên - user level
    public function getUserLevel($aParam = array())
    {
        if(empty($aParam['id'])) {
            return $array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $sField = $this->database()->select('level')
        ->from(Core::getT('user'))
        ->where("id = '" . $aParam['id'] . "'")
        ->execute('getField')

        $aData = array(
            'user_level' => $sField
        );

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }

    /* ***** HÀM ĐỂ VẼ BIỂU ĐỒ ***** 
        - Trả ra 3 giá trị là tổng đơn hàng/tổng doanh thu, mảng thông tin 1 điểm (label - giá trị) và khoảng cách giữa 2 điểm
        - red point là 15 ngày => 14 đoạn
    */
    //Hàm lấy giá trị ngày
    public function getDateChart($aParam = array())
    {
        if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $aParam['start_date'] = date('d/m/Y', $aParam['start_date']);
        $aParam['end_date'] = date('d/m/Y', $aParam['end_date']);
        $dDate = $aParam['start_date'];
        $aDate = array();
        $iCountDate = (strtotime($aParam['end_date']) - strtotime($aParam['start_date'])) / (60 * 60 * 24);

        if($iCountDate <= 15) {
            for($i = 0; $i < $iCountDate; i++) {
                array_push($aDate, $dDate);
                $dDate = date('d/m/Y', strtotime($dDate) + (60 * 60 * 24));
            }
        } else {
            $iIndex = 1;
            $i = 0;

            if($iCountDate % 15 == 0) {
                $iIndex = $iCountDate / 15;
            } else {
                $iIndex = ($iCountDate / 15) + 1;
            }

            $iPointMerge = $iCountDate / $iIndex;   //Biến lưu số điểm bị gộp                
            $iEntantPoint = $iCountDate % $iIndex;  //Biến lưu số điểm còn lại - số điểm không bị gộp

            //Lấy danh sách ngày bị gộp
            for($i; $i < $iPointMerge; $i++) {
                array_push($aData, $dDate);
                $dDate = date('d/m/Y', strtotime($dDate) + ($iIndex * 60 * 60 * 24));
            }

            //Lấy danh sách ngày không bị gộp
            for($i; $i < ($iEntantPoint + $iPointMerge); $i++) {
                array_push($aDate, $dDate);
                $dDate = date('d/m/Y', strtotime($dDate) + (60 * 60 * 24));
            }
        }

        $aData = $aDate;

        return array(
            'status' => 'success',
            'message' => $aData
        );     
    }

    //Hàm lấy khoảng cách giữa 2 điểm
    public function getWidthChart($aParam = array())
    {
        if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $aWidth = array();

        if($iCountDate <= 15) {
            for($i = 0; $i < $iCountDate; $i++) {
                array_push($aWidth, 1 / ($iCountDate - 1))
            }
        } else {
            $i = 0;
            $Width = 1;

            if($iCountDate % 15 == 0) {
                $iIndex = $iCountDate / 15;
            } else {
                $iIndex = ($iCountDate / 15) + 1;
            }

            $iPointMerge = $iCountDate / $iIndex;   //Biến lưu số điểm bị gộp                
            $iEntantPoint = $iCountDate % $iIndex;  //Biến lưu số điểm còn lại - số điểm không bị gộp

            //Khoảng cách các điểm bị gộp
            for($i; $i < $iPointMerge; $i++) {
                $Width = $iIndex / ($iCountDate - 1);             
                array_push($aWidth, $Width);
            }

            //Khoảng cách các điểm không bị gộp
            for($i; $i < ($iEntantPoint + $iPointMerge); $i++) {
                array_push($aWidth, $Width);
                $Width = 1 / ($iCountDate - 1);
            }
        }

        $aData = $aWidth;

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }

    //Hàm lấy giá trị của từng ngày
    public function getValueOfDate($aParam = array(), $bIsInvoice)
    {
        if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $sElement = '';
        $aData = array();
        $aDate = getDateChart($aParam)['message'];

        if($bIsInvoice) {
            $sElement = 'count(id)';
        } else {
            $sElement = 'money';
        }        

        foreach ($aDate as $dDate) {
            $dDate = strtotime(date('d/m/Y', $dDate));

            $iField = $this->database()->select($sElement)
            ->from(Core::getT($sTable))
            ->where("date = " . $dDate )
            ->execute('getField')

            array_push($aData, $iField)
        }

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }

    //Hàm lấy tổng
    public function getSumOfChart($aParam = array(), $bIsInvoice)
    {
        if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
            return array(
                'status' => 'error',
                'message' => 'dữ liệu truyền vào không tồn tại',
            );
        }

        $iSum = 0;        

        if($bIsInvoice) {
            $iSum = getInvoiceInMonth($aParam, 0)['message']['order_sum'];
        } else {
            $iSum = getSales($aParam, 0)['message']['order_sum'];
        }

        $aData = array(
            'sum_of_chart' => $iSum
        );

        return array(
            'status' => 'success',
            'message' => $aData
        );
    }
    //Hàm lấy thông tin chart
    public function getChartInfo($aParam = array(), $bIsInvoice)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $i = 0;
            $aPoint = array();
            $aDate = getDateChart($aParam)['message'];
            $aValue = getValueOfDate($aParam, $bIsInvoice)['message'];
            $aWidth = getWidthChart($aParam)['message'];
            $iSum = getSumOfChart($aParam, $bIsInvoice);

            foreach ($aDate as $dDate) {
                $dDate = date('d/m/Y', $dDate);

                array_push($aPoint, array(
                                        'date' => $dDate,
                                        'value' => $aValue[i],
                                        'width' => $aWidth[i]
                                    )
                );
            }

            $aData = array(
                'sum_of_chart' => $iSum,
                'point_of_chart' => $aPoint
            );

            return array(
                'status' => 'success',
                'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
    /* ***** XỬ LÝ XONG HÀM VẼ BIỂU ĐỒ ***** */
    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ HAI                        **
    //**                                                        **
    //************************************************************
    //************************************************************
    //---> Footer

    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ BA                         **
    //**                                                        **
    //************************************************************
    //************************************************************
    //Hàm xem báo cáo đơn hàng
    public function getInvoiceReport($aParam = array())
    {
        try {
            if(empty($aParam['module']) || empty($aParam['table']) || empty($aParam['title'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            } else {
                if(!empty($aParam['status'])) {
                    $sCondition = "status = " . $aParam['status'];
                } else {
                    $sCondition = "status != -1";
                }                

                if(!empty($aParam['id'])) {
                    $sCondition .= " and id = '" . $aParam['id'] . "'";
                } else {
                    $sCondition .= " and id != '-1";
                }

                if(!empty($aParam['start_date']) && !empty($aParam['end_date'])) {
                    $tStartDate = strtotime(data('d/m/Y', $aParam['start_date']));
                    $tEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $tCurrentDate = strtotime(getdate());
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                } else if(empty($aParam['end_date']) && !empty($aParam['start_date'])) {
                    $dStartDate = strtotime(data('d/m/Y', $aParam['start_date']));
                    $aParam['end_date'] = strtotime(getdate());
                    $dEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                } else if(empty($aParam['start_date']) && !empty($aParam['end_date'])) {
                    $dStartDate = strtotime(data('d/m/Y', "13/03/1996"));
                    $dEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                }

                $sLinkFull = '/' . $aParam['module'] . '/?';
                      
                if(empty($errors))
                {
                    $query = '';
                    $duong_dan_phan_trang = '';
                    $limit = $this->request()->get('limit') * 1;
                    if($limit < 1) $limit = 10;

                    if(!empty($limit)) {
                      $sLinkFull .= '&limit = ' . $limit;
                      $duong_dan_phan_trang .= '&limit = ' . $limit;
                    }

                    $trang_hien_tai = addslashes($this->request()->get('page')) * 1;

                    $tong_cong = $this->database()->select('count(id)')
                        ->from(Core::getT($aParam['table'])) //table = promotion_news
                        ->where($sCondition)
                        ->execute('getField');

                    if(!@$trang_hien_tai) $trang_hien_tai=1;
                    $trang_bat_dau = ($trang_hien_tai-1)*$limit;

                    $dir = $_SERVER['REQUEST_URI'];
                    $tmps = explode('/', $dir, 3);
                    $dir = '/'.$tmps[1].'/';

                    $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;

                    $aRows = $this->database()->select('id, date, status, money')
                        ->from(Core::getT($aParam['table']))
                        ->where($sCondition)
                        ->order('date DESC')
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');

                    foreach ($aRows as $rows)
                    {
                        $report_custom['id'][] = $rows["id"];
                        $report_custom['date'][] = $rows["date"];
                        $report_custom['status'][] = $rows["status"];
                        $report_custom['money'][] = $rows["money"];
                    }
                
                $output = array(
                    'duong_dan_phan_trang',
                    'errors',
                    'report_custom',
                    'tong_trang',
                    'tong_cong',
                    'trang_hien_tai',
                    'sLinkFull',
                    'sLinkSort'
                );

                foreach($output as $key)
                {
                  $data[$key] = $key;
                }
                
                $aData = array(
                    'output' => $output,
                    'data' => $data
                );

                return array(
                    'status' => 'success',
                    'message' => $aData
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }  
    }

    //Hàm xem chi tiết đơn hàng
    public function getInvoiceDetail($aParam = array())
    {
        try {
            if(empty($aParam['id']) || empty($aParam['table'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aRow = $this->database()->select('*')
            ->from(Core::getT($aParam['table']))    //table = invoice
            ->where("id = '" . $aParam['id'] . "'")
            ->execute('getRow')

            return $aRow;
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ TƯ                         **
    //**                                                        **
    //************************************************************
    //************************************************************
    /* 
    //Hàm tính số dư cuối kỳ - closing balance HÀM BÁO CÁO DOANH SÔ
        - Số dư đầu kỳ (opening balance) là số dư cuối kỳ của tháng trước/quý trước
        - Số dư cuối kỳ (closing balance) = tổng lợi nhuận - tổng các chi phí khác
        - Thanh toán = số dư đầu kỳ + nợ phát sinh - số dư cuối kỳ
        - Nợ phát sinh = doanh số phát sinh - chiết khấu (discount)
    */
    public function getClosingBalance($aParam = array(), $bIsOpeningBalance)
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = data('d/m/Y', $aParam['start_date']);
            $aParam['end_date'] = data('d/m/Y', $aParam['end_date']);

            //Kiểm tra nếu là số dư đầu kỳ
            if($bIsOpeningBalance) {
                $aParam['start_date'] = date('d/m/Y', strtotime($aParam['start_date']) - 30 * 60 * 60 * 24)
                $aParam['end_date'] = date('d/m/Y', strtotime($aParam['end_date']) - 30 * 60 * 60 * 24)
            }

            //Lấy tổng doanh thu
            $iSales = getSales($aParam, 0);

            //Lấy các chi phí liên quan
            $iRelatedCost = getRelatedCost($aParam, 0);

            //Lấy các loại thuế
            $iTax = getTax($aParam, 0);

            return $iSales - $iRelatedCost - $iTax;
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    public function getReportSales($aParam = array()) //???: các khoản thu khác, hoàn phí
    {
        try {
            if(empty($aParam['start_date']) || empty($aParam['end_date'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = data('d/m/Y', $aParam['start_date']);
            $aParam['end_date'] = data('d/m/Y', $aParam['end_date']);

            //Lấy số dư đầu kỳ
            $iOpeningBalance = getClosingBalance($aParam, 1); //1: số dư đầu kỳ

            /* ĐƠN HÀNG
                - Doanh thu bán hàng: getSales($aParam, 0)
                - Các khoản thu khác: ??? là cái gì ???
                - Các phí khác: getRelated($aParam, 0)
            */
            //Doanh thu bán hàng
            $iSales = getSales($aParam, 0);

            //Các khoản thu khác
            $iOtherCost = 0;

            //Các chi phí khác
            $iRelatedCost = getRelated($aParam, 0);

            //Tổng đơn hàng
            $iSumInvoice = $iSales + $iOtherCost + $iRelatedCost;
            // ***** TÍNH XONG ĐƠN HÀNG *****

            /*  HOÀN TIỀN
                status: -1 -> tất cả
                        0  -> hủy
                        1  -> mới
                        2  -> xác nhận
                        3  -> đang giao
                        4  -> hoàn thành
                - Hủy hoặc trả đơn hàng: 0
                - hoàn phí: ??? khi nào hoàn phí ???
            */

            //Hủy hoặc trả đơn hàng
            $iInvoiceCanceled = $this->database()->select('count(*) as order_sum')
            ->from(Core::getT('invoice'))
            ->where("status = 0")
            ->execute('getField');

            //Hoàn phí
            $iRefund = 0;

            //Tổng hoàn tiền
            $iSumRefund = $iInvoiceCanceled + $iRefund;
            // ***** TÍNH XONG HOÀN PHÍ *****

            //Số dư cuối
            $iClosingBalance = getClosingBalance($aParam, 0);

            /* THANH TOÁN ???: Doanh số phát sinh ở đâu, chiết khấu ở đâu
                - Thanh toán = số dư đầu kỳ + nợ phát sinh - số dư cuối kỳ
                - Nợ phát sinh = doanh số phát sinh - chiết khấu
            */

            
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
    //---------------------------------->Đang dừng lại phân tích chiết khấu 
    /* ***** XỬ LÝ XONG HÀM BÁO CÁO DOANH SỐ ***** */


    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ NĂM                        **
    //**                                                        **
    //************************************************************
    //************************************************************
    //Hàm xem báo cáo lợi nhuận thành viên
    public function getUserProfitReport($aParam = array())
    {
        try {
            if(empty($aParam['module']) || empty($aParam['table']) || empty($aParam['title'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            } else {
                if(!empty($aParam['status'])) {
                    $sCondition = "status = " . $aParam['status'];
                } else {
                    $sCondition = "status != -1";
                }                

                if(!empty($aParam['id'])) {
                    $sCondition .= " and id = '" . $aParam['id'] . "'";
                } else {
                    $sCondition .= " and id != '-1";
                }

                if(!empty($aParam['start_date']) && !empty($aParam['end_date'])) {
                    $tStartDate = strtotime(data('d/m/Y', $aParam['start_date']));
                    $tEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $tCurrentDate = strtotime(getdate());
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                } else if(empty($aParam['end_date']) && !empty($aParam['start_date'])) {
                    $dStartDate = strtotime(data('d/m/Y', $aParam['start_date']));
                    $aParam['end_date'] = strtotime(getdate());
                    $dEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                } else if(empty($aParam['start_date']) && !empty($aParam['end_date'])) {
                    $dStartDate = strtotime(data('d/m/Y', "13/03/1996"));
                    $dEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                }

                $sLinkFull = '/' . $aParam['module'] . '/?';
                      
                if(empty($errors))
                {
                    $query = '';
                    $duong_dan_phan_trang = '';
                    $limit = $this->request()->get('limit') * 1;
                    if($limit < 1) $limit = 10;

                    if(!empty($limit)) {
                      $sLinkFull .= '&limit = ' . $limit;
                      $duong_dan_phan_trang .= '&limit = ' . $limit;
                    }

                    $trang_hien_tai = addslashes($this->request()->get('page')) * 1;

                    $tong_cong = $this->database()->select('count(id)')
                        ->from(Core::getT($aParam['table'])) //table = promotion_news
                        ->where($sCondition)
                        ->execute('getField');

                    if(!@$trang_hien_tai) $trang_hien_tai=1;
                    $trang_bat_dau = ($trang_hien_tai-1)*$limit;

                    $dir = $_SERVER['REQUEST_URI'];
                    $tmps = explode('/', $dir, 3);
                    $dir = '/'.$tmps[1].'/';

                    $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;
                    //Lợi nhuận ròng - net profit, lợi nhuận gộp - gross profit
                    $aRows = $this->database()->select('id, date, status, net_profit, gross_profit')
                        ->from(Core::getT($aParam['table']))
                        ->where($sCondition)
                        ->order('date DESC')
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');

                    foreach ($aRows as $rows)
                    {
                        $report_custom['id'][] = $rows["id"];
                        $report_custom['date'][] = $rows["date"];
                        $report_custom['status'][] = $rows["status"];
                        $report_custom['net_profit'][] = $rows["net_profit"];
                        $report_custom['gross_profit'][] = $rows["gross_profit"];                        
                    }
                
                $output = array(
                    'duong_dan_phan_trang',
                    'errors',
                    'report_custom',
                    'tong_trang',
                    'tong_cong',
                    'trang_hien_tai',
                    'sLinkFull',
                    'sLinkSort'
                );

                foreach($output as $key)
                {
                  $data[$key] = $key;
                }
                
                $aData = array(
                    'output' => $output,
                    'data' => $data
                );

                return array(
                    'status' => 'success',
                    'message' => $aData
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }  
    }

    //Hàm xem chi tiết báo cáo lợi nhuận
    public function getUserProfitDetail($aParam = array())
    {
        try {
            if(empty($aParam['id']) || empty($aParam['table'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aRow = $this->database()->select('*')
            ->from(Core::getT($aParam['table']))    //table = user_profit 
            ->where("id = '" . $aParam['id'] . "'")
            ->execute('getRow')

            return $aRow;
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
    //Hàm xem chi tiết xử dụng lại hàm ở hình 3

    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ SÁU                        **
    //**                                                        **
    //************************************************************
    //************************************************************
    //Hàm xem báo cáo lợi nhuận thành viên
    public function getUserCostReport($aParam = array())
    {
        try {
            if(empty($aParam['module']) || empty($aParam['table']) || empty($aParam['title'])) {
                return array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            } else {
                if(!empty($aParam['status'])) {
                    $sCondition = "status = " . $aParam['status'];
                } else {
                    $sCondition = "status != -1";
                }                

                if(!empty($aParam['id'])) {
                    $sCondition .= " and id = '" . $aParam['id'] . "'";
                } else {
                    $sCondition .= " and id != '-1";
                }

                if(!empty($aParam['start_date']) && !empty($aParam['end_date'])) {
                    $tStartDate = strtotime(data('d/m/Y', $aParam['start_date']));
                    $tEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $tCurrentDate = strtotime(getdate());
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                } else if(empty($aParam['end_date']) && !empty($aParam['start_date'])) {
                    $dStartDate = strtotime(data('d/m/Y', $aParam['start_date']));
                    $aParam['end_date'] = strtotime(getdate());
                    $dEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                } else if(empty($aParam['start_date']) && !empty($aParam['end_date'])) {
                    $dStartDate = strtotime(data('d/m/Y', "13/03/1996"));
                    $dEndDate = strtotime(data('d/m/Y', $aParam['end_date']));
                    $sCondition .= " and date >= " . $dStartDate . " and date <= " . $dEndDate;
                }

                $sLinkFull = '/' . $aParam['module'] . '/?';
                      
                if(empty($errors))
                {
                    $query = '';
                    $duong_dan_phan_trang = '';
                    $limit = $this->request()->get('limit') * 1;
                    if($limit < 1) $limit = 10;

                    if(!empty($limit)) {
                      $sLinkFull .= '&limit = ' . $limit;
                      $duong_dan_phan_trang .= '&limit = ' . $limit;
                    }

                    $trang_hien_tai = addslashes($this->request()->get('page')) * 1;

                    $tong_cong = $this->database()->select('count(id)')
                        ->from(Core::getT($aParam['table'])) //table = promotion_news
                        ->where($sCondition)
                        ->execute('getField');

                    if(!@$trang_hien_tai) $trang_hien_tai=1;
                    $trang_bat_dau = ($trang_hien_tai-1)*$limit;

                    $dir = $_SERVER['REQUEST_URI'];
                    $tmps = explode('/', $dir, 3);
                    $dir = '/'.$tmps[1].'/';

                    $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;
                    //phí dịch vụ - services cost, phí vận chuyển - transport cost, phí thuế - tax cost
                    $aRows = $this->database()->select('id, date, status, services_cost, transport_cost, tax')
                        ->from(Core::getT($aParam['table']))
                        ->where($sCondition)
                        ->order('date DESC')
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');

                    foreach ($aRows as $rows)
                    {
                        $report_custom['id'][] = $rows["id"];
                        $report_custom['date'][] = $rows["date"];
                        $report_custom['status'][] = $rows["status"];
                        $report_custom['services_cost'][] = $rows["services_cost"];
                        $report_custom['transport_cost'][] = $rows["transport_cost"];    
                        $report_custom['tax'][] = $rows["tax"];                        
                    }
                
                $output = array(
                    'duong_dan_phan_trang',
                    'errors',
                    'report_custom',
                    'tong_trang',
                    'tong_cong',
                    'trang_hien_tai',
                    'sLinkFull',
                    'sLinkSort'
                );

                foreach($output as $key)
                {
                  $data[$key] = $key;
                }
                
                $aData = array(
                    'output' => $output,
                    'data' => $data
                );

                return array(
                    'status' => 'success',
                    'message' => $aData
                );
            }
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }  
    }
    //Hàm xem chi tiết xử dụng lại hàm ở hình 3


    //************************************************************
    //************************************************************
    //**                                                        **
    //**                    HÌNH THỨ BẢY                        **
    //**                                                        **
    //************************************************************
    //************************************************************
    // ***** HÀM LẤY BÁO CÁO TỔNG HỢP *****
    public function getSummaryReport($aParam = array())
    {
        try {
            if(empty($aParam['id']) || empty($aParam['table'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aParam['start_date'] = strtotime(date('d/m/Y', $aParam['start_date']));
            $aParam['end_date'] = strtotime(date('d/m/Y', $aParam['end_date']));

            /*  HOÀN TIỀN
                status: -1 -> tất cả
                        0  -> hủy
                        1  -> mới
                        2  -> xác nhận
                        3  -> đang giao
                        4  -> hoàn thành
                        5  -> hoàn trả
                - Hủy hoặc trả đơn hàng: 0
                - hoàn phí: ??? khi nào hoàn phí ???
            */

            //tổng đơn hàng bị hủy
            $iInvoiceCanceled = $this->database()->select('count(id)')
            ->from(Core::getT('invoice'))    //table = invoice 
            ->where("status = 0 and date >= " . $aParam['start_date'] . " and date <= " . $aParam['end_date'])
            ->execute('getField')

            //Tổng đơn hàng hoàn thành
            $iInvoiceCompleted = $this->database()->select('count(id)')
            ->from(Core::getT('invoice'))    //table = user_profit 
            ->where("status = 4 and date >= " . $aParam['start_date'] . " and date <= " . $aParam['end_date'])
            ->execute('getField')

            //Tổng đơn hàng bị hoàn trả
            $iInvoiceReturned = $this->database()->select('count(id)')
            ->from(Core::getT('invoice'))    //table = user_profit 
            ->where("status = 5 and date >= " . $aParam['start_date'] . " and date <= " . $aParam['end_date'])
            ->execute('getField')

            //Tổng đơn hàng
            $iInvoiceSum = $iInvoiceCanceled + $iInvoiceCompleted + $iInvoiceReturned;

            //Tổng lợi nhuận gộp - gross profit
            $iGrossProfitSum = $this->database()->select('sum(gross_profit)')
            ->from(Core::getT('invoice'))    //table = user_profit 
            ->where("status != -1 and date >= " . $aParam['start_date'] . " and date <= " . $aParam['end_date'])
            ->execute('getField')

            //Tổng chi phí phát sinh:??? lấy đâu ra
            $iCostIncurredSum = 0;

            //Tổng lợi nhuận ròng - net profit
            $iNetProfitSum = $iGrossProfitSum - $iCostIncurredSum;

            $aData = array(
                'invoice_canceled' => $iInvoiceCanceled,
                'invoice_completed' => $iInvoiceCompleted,
                'invoice_returned' => $iInvoiceReturned,
                'invoice_sum' => $iInvoiceSum,
                'gross_profit_sum' => $iGrossProfitSum,
                'cost_incurred_sum' => $iCostIncurredSum,
                'net_profit_sum' => $iNetProfitSum
            );

            return array(
                    'status' => 'success',
                    'message' => $aData
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }


    //************************************************************
    //************************************************************
    //**                                                        **
    //**                  HÌNH THỨ TÁM - CHÍN                   **
    //**                                                        **
    //************************************************************
    //************************************************************
    // ***** HÀM GHI LẠI/CHỈNH SỬA THÔNG TIN NGƯỜI MUA HÀNG *****
    public function setCustomerInfo($aParam = array(), $bIsEdited)
    {
        //Trường hợp chỉnh sửa dữ liệu
        if($bIsEdited) {
            try {
                if(empty($aParam['customer_name']) || empty($aParam['customer_phone']) || empty($aParam['customer_address'])) {
                    return $array(
                        'status' => 'error',
                        'message' => 'dữ liệu truyền vào không tồn tại',
                    );
                }

                $this->database()->update(Core::getT('customer'), array(
                        'customer_name' => $aParam['customer_name'],
                        'customer_phone' => $aParam['customer_phone'],
                        'customer_address' => $aParam['customer_address']
                    ), 'id ='. $aParam['id']);

                return array(
                    'status' => 'success',
                    'message' => 'Chỉnh sửa thành công'
                );
            }
            catch(Exception $e) {
                Core_Error::log('Error');
                return array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                );
            } 
        }

        //Trường hợp thêm mới dữ liệu
        try {
            if(empty($aParam['customer_name']) || empty($aParam['customer_phone']) || empty($aParam['customer_address'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            //Kiểm tra dữ liệu đã trùng
            $iCheck = $this->database()->select('count(*) as soluong')
            ->from(Core::getT('customer'))
            ->where("customer_name = '". $aParam['customer_name'] . "' and customer_phone = '".$aParam['customer_phone']."' and customer_address = '".$aParam['customer_address']."'")
            ->execute('getField');

            //Nếu trùng thì không thêm và thông báo lỗi
            if($iCheck != 0) {
                return array(
                    'status' => 'error',
                    'message' => 'Dữ liệu đã tồn tại'
                );                
            }

            $this->database()->insert(Core::getT('customer'), array(
                    'customer_name' => $aParam['customer_name'],
                    'customer_phone' => $aParam['customer_phone'],
                    'customer_address' => $aParam['customer_address']
            ));

            return array(
                'status' => 'success',
                'message' => 'Thêm thành công'
            );
            
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    // ***** HÀM GHI LẠI/CHỈNH SỬA THÔNG TIN NGƯỜI NHẬN HÀNG *****
    public function setRecerverInfo($aParam = array())
    {
        //Trường hợp chỉnh sửa dữ liệu
        if($bIsEdited) {
            try {
                if(empty($aParam['receiver_name']) || empty($aParam['receiver_phone']) || empty($aParam['receiver_address'])) {
                    return $array(
                        'status' => 'error',
                        'message' => 'dữ liệu truyền vào không tồn tại',
                    );
                }

                $this->database()->update(Core::getT('receiver'), array(
                        'receiver_name' => $aParam['receiver_name'],
                        'receiver_phone' => $aParam['receiver_phone'],
                        'receiver_address' => $aParam['receiver_address']
                    ), 'id ='. $aParam['id']);

                return array(
                    'status' => 'success',
                    'message' => 'Chỉnh sửa thành công'
                );
            }
            catch(Exception $e) {
                Core_Error::log('Error');
                return array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                );
            } 
        }

        //Trường hợp thêm mới dữ liệu
        try {
            if(empty($aParam['receiver_name']) || empty($aParam['receiver_phone']) || empty($aParam['receiver_address'])
                        || empty($aParam['recever_date']) || empty($aParam['recever_time'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            //Kiểm tra dữ liệu đã trùng
            $iCheck = $this->database()->select('count(*) as soluong')
            ->from(Core::getT('receiver'))
            ->where("receiver_name = '". $aParam['receiver_name'] . "' and receiver_phone = '".$aParam['receiver_phone']."' and receiver_address = '".$aParam['receiver_address']."'")
            ->execute('getField');

            //Nếu trùng thì không thêm và thông báo lỗi
            if($iCheck != 0) {
                return array(
                    'status' => 'error',
                    'message' => 'Dữ liệu đã tồn tại'
                );                
            }

            $this->database()->insert(Core::getT('receiver'), array(
                    'receiver_name' => $aParam['receiver_name'],
                    'receiver_phone' => $aParam['receiver_phone'],
                    'receiver_address' => $aParam['receiver_address'],
                    'recever_date' => strtotime(date('d/m/Y', $aParam['recever_date']));,
                    'recever_time' => $aParam['recever_time']
            ));

            return array(
                'status' => 'success',
                'message' => 'Thêm thành công'
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    // ***** HÀM GHI LẠI THÔNG TIN HÓA ĐƠN *****
    public function setInvoiceInfo($aParam = array())
    {
        try {
            //sale ở đây là giảm giá
            if(empty($aParam['status']) || empty($aParam['money']) || empty($aParam['sale']) 
                || empty($aParam['services_cost']) || empty($aParam['transport_cost']) || empty($aParam['tax'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            //Tổng tiền
            $iMoneySum = $aParam['money'] + $aParam['services_cost'] + $aParam['transport_cost'] + $aParam['tax'] - $aParam['sale'];

            //Tổng lợi nhuận thành viên
            $iUserProfitSum = 0; //???????????

            //Thêm dữ liệu vào db
            $this->database()->insert(Core::getT('invoice'), array(
                    'status' => $aParam['status'],
                    'money' => $aParam['money'],
                    'sale' => $aParam['sale'],
                    'services_cost' => $aParam['services_cost'],
                    'transport_cost' => $aParam['transport_cost'],
                    'tax' => $aParam['tax'],
                    'money_sum' => $iMoneySum,
                    'user_profit_sum' => $iUserProfitSum
            ));

            return array(
                'status' => 'success',
                'message' => 'Thêm thành công'
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    // ***** HÀM ĐỔI TÌNH TRẠNG ĐƠN HÀNG *****
    public function setInvoiceInfo($aParam = array())
    {
        try {
            //sale ở đây là giảm giá
            if(empty($aParam['status'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $this->database()->update(Core::getT('invoice'), array(
                'status' => $aParam['status']
            ), 'id ='. $aParam['id']);

            return array(
                'status' => 'success',
                'message' => 'Cập nhật tình trạng đơn hàng thành công'
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    // ***** HÀM LẤY THÔNG TIN NGƯỜI MUA HÀNG *****
    public function getCustomerInfo($aParam = array())
    {
        try {
            if(!isset($aParam['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Mã người mua hàng không tồn tại!'
                );
            }

            $aRow = $this->database()->select('customer_name, customer_phone, customer_address')
            ->from(Core::getT('customer'))
            ->where('id = '. $aParam['id'] )
            ->execute('getRow');

            return array(
                'status' => 'success',
                'message' => $aRow
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    // ***** HÀM LẤY THÔNG TIN NGƯỜI NHẬN HÀNG *****
    public function getCustomerInfo($aParam = array())
    {
        try {
            if(!isset($aParam['id'])) {
                return array(
                    'status' => 'error',
                    'message' => 'Mã người nhận hàng không tồn tại!'
                );
            }

            $aRow = $this->database()->select('receiver_name, receiver_phone, receiver_address, recever_date, recever_time')
            ->from(Core::getT('receiver'))
            ->where('id = '. $aParam['id'] )
            ->execute('getRow');

            //Đổi kiểu dữ liệu unit time -> date để hiển thị trên trang
            $aRow['recever_date'] = date('d/m/Y', $aRow['recever_date']);

            return array(
                'status' => 'success',
                'message' => $aRow
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }

    // ***** HÀM LẤY THÔNG TIN ĐƠN HÀNG THEO TÌNH TRẠNG ĐƠN HÀNG *****
    public function getCustomerInfo($aParam = array())
    {
        try {
            if(empty($aParam['status']) || empty($aParam['id'])) {
                return $array(
                    'status' => 'error',
                    'message' => 'dữ liệu truyền vào không tồn tại',
                );
            }

            $aRow = $this->database()->select('id, status, money, sale, services_cost, transport_cost, tax, money_sum, user_profit_sum')
            ->from(Core::getT('invoice'))
            ->where('id = '. $aParam['id'] . ' and status = ' . $aParam['status'])
            ->execute('getRow');

            return array(
                'status' => 'success',
                'message' => $aRow
            );
        }
        catch(Exception $e) {
            Core_Error::log('Error');
            return array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
    }
}
?>