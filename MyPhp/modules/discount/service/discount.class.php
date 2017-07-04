<?
class Discount_Service_Discount extends Service
{
    public function __construct()
    {
        $this->_sTable = Core::getT('discount');
    }
    
    public function getDiscount($aParam = array())
    {
    	//Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');
        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        //Kiểm tra dữ liệu truyền vào
        $iPage = isset($aParam['page']) ? $aParam['page'] : 1;
        $iPageSize = isset($aParam['page_size']) ? $aParam['page_size'] : 10;

        //Xử lý giá trị truyền vào
        if ($iPage < 1) {
            $iPage = 1;
        }
        if ($iPageSize < 1 || $iPageSize > 100) {
            $iPageSize = 10;
        }

        //Khai báo biến
        $iCnt = 0; //Tổng trang
        $aData = array(); //Dữ liệu
        $sConds = 'status != 2 AND domain_id = '.Core::getDomainId().$query; //Điều kiện select

        $sLinkFull = '/discount/index/?page_size='.$iPageSize;     //Đường dẫn full
        $sPagination = '/discount/index/?page_size='.$iPageSize;  //Đường dẫn phân trang

        //Sắp xếp dữ liệu
        $sOrder = '';
        $sSort = isset($aParam['sort']) ? $aParam['sort'] : '';
        if ($sSort == 'id_a') {
        	$sOrder = ' id ASC';
        } elseif ($sSort == 'name_d') {
            $sOrder = ' name DESC';
        }
        elseif ($sSort == 'name_s') {
            $sOrder = ' name ASC';
        }
        elseif ($sSort == 'name_code_d') {
            $sOrder = ' name_code DESC';
        }
        elseif ($sSort == 'name_code_a') {
            $sOrder = ' name_code ASC';
        }
        elseif ($sSort == 'program_type_d') {
            $sOrder = ' program_type DESC';
        }
        elseif ($sSort == 'program_type_a') {
            $sOrder = ' program_type ASC';
        }
        elseif ($sSort == 'apply_d') {
            $sOrder = ' apply DESC';
        }
        elseif ($sSort == 'apply_a') {
            $sOrder = ' apply ASC';
        }
        elseif ($sSort == 'type_d_value_a') {
            $sOrder = ' type DESC, value ASC';
        }
        elseif ($sSort == 'type_a_value_a') {
            $sOrder = ' type ASC, value ASC';
        }
        elseif ($sSort == 'start_time_d') {
            $sOrder = ' start_time DESC';
        }
        elseif ($sSort == 'start_time_a') {
            $sOrder = ' start_time ASC';
        }
        elseif ($sSort == 'end_time_d') {
            $sOrder = ' end_time DESC';
        }
        elseif ($sSort == 'end_time_a') {
            $sOrder = ' end_time ASC';
        }
        elseif ($sSort == 'total_item_d') {
            $sOrder = ' total_item DESC';
        }
        elseif ($sSort == 'total_item_a') {
            $sOrder = ' total_item ASC';
        }
        else $sOrder = ' id DESC';

        if (!empty($sSort)) {
            $sPagination .= '&sort='.$sSort;
            $sLinkFull .= '&sort='.$sSort;
            $sOrder = $sOrder.' , time DESC';
        }

        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sort');
        // tìm kiếm
        $sKeyword = isset($aParam['q']) ? $aParam['q'] : '';
        if (!empty($sKeyword)) {
            $sKeyword = urldecode($sKeyword);
            $sKeyword = trim(Core::getLib('input')->removeXSS($sKeyword));
            if (mb_strlen($sKeyword) > 100) {
                $sKeyword = '';
            }
        }

        if(!empty($sKeyWord)) {
            $sConds .= ' AND (name LIKE "%'.addslashes($sKeyWord).'%" OR name_code LIKE "%'.addslashes($sKeyWord).'%")';
            $sPagination .= '&q='.urlencode($sKeyword);
            $sLinkFull .= '&q='.urlencode($sKeyword);
        }

        //Tính tổng dữ liệu
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('discount'))
            ->where($sConds)
            ->execute('getField');

        //Lấy danh sách trích lọc
        if ($iCnt > 0) {
        	$aProgramType = array(
                0 => 'Đi siêu thị',
                1 => 'Nhà cung cấp',
            );
            
            $aValueType = array(
                0 => Core::getPhrase('language_phan-tram'),
                1 => Core::getPhrase('language_co-dinh')
            );
            
            $aApply = array(
                0 => 'Đơn hàng',
                1 => 'Sản phẩm',
            );

            $aRows = $this->database()->select('*')
                ->from(Core::getT('discount'))
                ->where($sConds)
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            foreach($aRows as $aRow)
            {                
                if ($aRow["type"] == 0) {
                    $sValueTxt = ($aRow["value"]*1).'%';
                } else {
                    $sValueTxt = Core::getService('core.currency')->formatMoney(array('money' => $aRow["value"]));
                }
                                
                if ($aRow["start_time"] > 0) {
                    $sStartTime = date('d-m-Y', $aRow["start_time"]);
                } else {
                    $sStartTime = '';
                }

                if ($aRow["end_time"] > 0) {
                    $sEndTime = date('d-m-Y', $aRow["end_time"]);
                } else {
                    $sEndTime = '';
                }
                
                if($aRow["status"] == 0) {
                	$sTmp = 'status_no';
                } else {
                	$sTmp = 'status_yes';
                }

                if (!in_array($aRow["custom_label_stt"], $aListType)) {
                	$aListType[] = $aRow["custom_label_stt"];
                }

                $aData[] = array(
                    'id' => $aRow["id"],
                    'name' => $aRow["name"],
                    'total_item' => $aRow["total_item"],
                    'name_code' => $aRow["name_code"],
                    'program_type' => $aRow["program_type"],
                    'program_txt' => isset($aProgramType[$aRow["program_type"]]) ? $aProgramType[$aRow["program_type"]] : '',
                    'vendor_id' => $aRow["vendor_id"],
                    'value' => $aRow["value"],
                    'type' => $aRow["type"],
                    'type_txt' => isset($aValueType[$aRow["type"]]) ? $aValueType[$aRow["type"]] : '',
                    'apply' => $aRow["apply"],
                    'apply_txt' => isset($aApply[$aRow["apply"]]) ? $aApply[$aRow["apply"]] : '',
                    'times_to_use' => $aRow["times_to_use"],
                    'status_text' => $sTmp,
                    'value_txt' => $sValueTxt,
                    'start_time' => $sStartTime,
                    'end_time' => $sEndTime,
                    'status' => $aRow["status"],
                );
            }
            $iStatus = 1;
        }

        return array(
            'status' => 'success',
            'data' => array(
                'page' => $iPage,
                'page_size' => $iPageSize,
                'total' => $iCnt,
                'list' => $aData,
                'pagination' => $sPagination, 
                'list_type' => $aListType,
                'status' => $iStatus,
            ),
        );
    }

    public function getView($aParam = array())
    {
    	//Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');
        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        $iId = isset($aParam['id']) ? $aParam['id'] : -1;

        $aData = array();

        if ($iId < 1) {
        	return array(
                'status' => 'error',
                'message' => 'Trang không tồn tại',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        } else {
            $aData = $this->database()->select('*')
                ->from(Core::getT('discount'))
                ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$iId)
                ->execute('getRow');
            if (!isset($aData['id'])) {
            	return array(
	                'status' => 'error',
	                'message' => 'Loại giảm giá không tồn tại',
	                'data' => array(
	                    'list' => $aParam,
	                ),
	            );
            } else {
                if ($aData["type"] == 0) {
                    $aData['value_txt'] = ($aData["value"]*1).'%';
                } else {
                    $aData['value_txt'] = Core::getService('core.currency')->formatMoney(array('money' => $aData["value"]));
                }
                
                if ($aData["start_time"] > 0) {
                    $aData['start_time'] = date('d-m-Y', $aData["start_time"]);
                }
                else {
                    $aData['start_time'] = '';
                }
                if ($aData["end_time"] > 0) {
                    $aData['end_time'] = date('d-m-Y', $aData["end_time"]);
                }
                else {
                    $aData['end_time'] = '';
                }
            }
        }
    }

    public function initCreate($aParam = array())
    {
    	//Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $sPageType = $oSession->get('session-page_type');
        $aPermission = $oSession->get('session-permission');

        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }

        if ($iId < 0) {
        	$iId = 0;
        }

        $aListOperator = array('-', '+', '*', '/', '=');

        $aValueType = array(
            0 => Core::getPhrase('language_phan-tram'),
            1 => Core::getPhrase('language_co-dinh')
        );

        $aListPriceApply = array(Core::getPhrase('language_gia-ban'), Core::getPhrase('language_thanh-tien'));

        $aData['apply'] = 0;
        $aData['status'] = 1;
        
        $aProgramType = array(
            0 => 'Đi siêu thị',
            1 => 'Nhà cung cấp',
        );
        
        $aVendorValue = array();

        $aRows = $this->database()->select('id, name')
            ->from(Core::getT('vendor'))
            ->where('domain_id ='.Core::getDomainId().' AND status = 1')
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aVendorValue[$aRow['id']] = array(
                'id' => $aRow['id'],
                'name' => $aRow['name'],
            );
        }
        
        $aApply = array(
            0 => 'Đơn hàng',
            1 => 'Sản phẩm',
        );

        $aCondsSelect= array();

        if ($iId > 0) {
            $aTmp = array(
                'id',
                'name',
                'total_item',
                'name_code',
                'program_type',
                'vendor_id',
                'value',
                'type',
                'formula',
                'price_apply',
                'total_amount',
                'start_time',
                'end_time',
                'times_to_use',
                'apply',
                'is_default',
                'status',
                'conds',
            );
            // lấy đề tài stt và tên miền stt
            $rows = $this->database()->select(implode(',', $aTmp))
                ->from(Core::getT('discount'))
                ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id ='.$iId)
                ->execute('getRow');
            /*
            foreach($aTmp as $sVal)
            {
                $$sVal = $rows[$sVal];
            }
            $tong_tien_ap_dung = $total_amount;
            // convert thoi_gian_bat_dau
            $iStartTime = date('d-m-Y H:i:s', $rows["start_time"]);
            $sEndTime = date('d-m-Y H:i:s', $rows["end_time"]);
            // 
            */
            if (isset($rows['id'])) {
                $aData['id'] = $rows['id'];
                $aData['name'] = $rows['name'];
                $aData['total_item'] = $rows['total_item'];
                $aData['name_code'] = $rows['name_code'];
                $aData['program_type'] = $rows['program_type'];
                $aData['vendor_id'] = $rows['vendor_id'];
                $aData['type'] = $rows['type'];
                $aData['value'] = $rows['value'];
                $aData['formula'] = $rows['formula'];
                $aData['price_apply'] = $rows['price_apply'];
                $aData['total_amount'] = $rows['total_amount'];
                if ($rows['start_time'] > 0) {
                    $aData['start_time'] = date('Y/m/d', $rows['start_time']);
                } else {
                    $aData['start_time'] = '';
                }

                if ($rows['end_time'] > 0) {
                    $aData['end_time'] = date('Y/m/d', $rows['end_time']);
                } else {
                    $aData['end_time'] = '';
                }
                
                
                $aData['times_to_use'] = $rows['times_to_use'];
                $aData['apply'] = $rows['apply'];
                $aData['is_default'] = $rows['is_default'];
                $aData['status'] = $rows['status'];
                $aConds = unserialize($rows['conds']);
                $aData['conds'][$aConds['type']] = $aConds;
                foreach ($aConds as $sKey => $sVal) {
                    if ($sKey == 'type') {
                        continue;
                    }
                    $aCondsSelect[$aConds['type']][$sKey] = 'on';
                }
                
            }

            if ($iId > 0) {
                // lấy giá trị
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('discount_detail'))
                    ->where('discount_id ='.$iId)
                    ->order('position')
                    ->execute('getRows');
                
                foreach ($aRows as $rows) {
                    $aData['value']['id'][] = $rows['product_id'];
                    
                    $aData['value']['position'][] = $rows['position'];
                    $aData['value']['id'][] = $rows['id'];
                    $aData['value']['status'][] = $rows['status'];
                    
                    if ($rows['product_id'] > 0) {
                        if($rows['type'] == 1) {
                        	$aList['category'][] = $rows['product_id'];	
                        } 
                        else {
                        	$aList['article'][] = $rows['product_id'];
                        }
                    }
                    
                    if ($rows['type'] == 0) {
                    	$rows['type'] = 'article';
                    } else if ($rows['type'] == 1) {
                    	$rows['type'] = 'category';
                    }
                    $aData['value']['status'][] = $rows['type'];
                    $aConds = array();
                    if (!empty($rows['conds'])) {
                        $aConds = unserialize($rows['conds']);
                    }
                }
                // lấy danh sách
                if (!empty($aList['article'])) {
                    $aRows = $this->database()->select('id, title, detail_path')
                        ->from(Core::getT('article'))
                        ->where('id IN ('.implode(',', $aList['article']).')')
                        ->execute('getRows');
                    
                    foreach ($aRows as $rows) {
                        foreach ($aData['value']['id'] as $n => $sVal) {
                            if ($rows['id'] == $sVal && $aData['value']['status'][$n] == 'article') {
                                $aData['value']['status'][$n] = $rows['title'];
                                $aData['value']['link'][$n] = $rows['detail_path'];
                            }
                        }
                    }
                }
                // lấy danh sách đề tài
                if (!empty($aList['category'])) {
                    $aRows = $this->database()->select('id, name as title, detail_path')
                        ->from(Core::getT('category'))
                        ->where('id IN ('.implode(',', $aList['category']).')')
                        ->execute('getRows');
                    
                    foreach ($aRows as $rows) {
                        foreach($aData['value']['id'] as $n => $sVal) {
                            if($rows['id'] == $sVal && $aData['value']['type'][$n] == 'category') {
                                $aData['value']['name'][$n] = $rows['title'];
                                $aData['value']['status'][$n] = $rows['detail_path'];
                            }
                        }
                    }
                }
            }
        }
        else {
            $aData['status'] = 1;
        }
        $iStatusGlobal=1;

        return array(
            'status' => 'success',
            'data' => array(
                'list' => $aData,
                'status_global' => $iStatusGlobal,
                'value_type' => $aValueType,
                'list_price_apply' => $aListPriceApply,
                'program_type' => $aProgramType,
                'vendor_value' => $aVendorValue,
                'apply' => $aApply,
                'conds_select' => $aCondsSelect,
            ),
        );

    }

    public function create($aParam = array())
    {
    	//Kiểm tra quyền truy cập
        $oSession = Core::getLib('session');
        $aPermission = $oSession->get('session-permission');

        // if ($aPermission['manage_extend'] != 1) {
        //     return array(
        //         'status' => 'error',
        //         'message' => Core::getPhrase('language_khong-co-quyen-truy-cap'),
        //     );
        // }

        
        $iId = 0;
        $aParentFilterValueList = array();
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
        }

        if ($iId < 0) $iId = 0;

        if ($iId < 1) {
        	//Chỉ cho tạo mới
            $aData['name'] = $aVals['name'];
            //$aData['name_code'] = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($aVals['name_code'])));
            $aData['name_code'] = isset($aVals['name_code']) ? $aVals['name_code'] : '';
            $aData['program_type'] = isset($aVals['program_type']) ? $aVals['program_type'] : -1;
            $aData['vendor_id'] = isset($aVals['vendor_id']) ? $aVals['vendor_id'] : -1;
            $aData['total'] = isset($aVals['total']) ? $aVals['total'] : -1;
            if ($aData['program_type'] != 1) {
                $aData['vendor_id'] = 0;
            }
             
            $aData['status'] = $aVals['status']*1;
            if ($aData['status'] != 1) {
            	$aData['status'] = 0;
            }
            
            // $phan_tram = $aVals['phan_tram']*1;
            // if($phan_tram < 0) $phan_tram = 0;
            
            $aData['value'] = $aVals['value']*1;
            if ($aData['value'] < 0) {
            	$aData['value'] = 0;
            }
            
            $aData['type'] = $aVals['type']*1;
            if (!isset($aValueType[$aData['type']])) {
            	$aData['type'] = 0;
            }
            
            // $cong_thuc = $aVals['cong_thuc']*1;
            // if(!isset($aListOperator[$cong_thuc])) $cong_thuc = 0;
            
            // thiết lập mặc định cho $cong_thuc
            // $cong_thuc = 0;
            /*
            $gia_ap_dung = $aVals['gia_ap_dung']*1;
            if(!isset($gia_ap_dung_danh_sach[$gia_ap_dung])) $gia_ap_dung = 0;
            
            $tong_tien_ap_dung = $aVals['tong_tien_ap_dung']*1;
            if($tong_tien_ap_dung < 0) $tong_tien_ap_dung = 0;
            */
            $aData['start_time'] = $aVals['start_time'];
            $aData['end_time'] = $aVals['end_time'];
            
            $sStartTime = $aVals['start_time'];
            $sEndTime = $aVals['end_time'];
            
            if (is_numeric($aVals['times_to_use'])) {
                $aData['times_to_use'] = $aVals['times_to_use']*1;
                if ($aData['times_to_use'] > 9999) {
                	$aData['times_to_use'] = 1;
                }
            } else
                $aData['times_to_use'] = 0;
            
            $aData['apply'] = $aVals['apply']*1;

            if ($aData['apply'] < 0) {
                $aData['apply'] = 0;
            }
            
            foreach ($aVals['gt_ma_so'] as $i => $sVal) {
                if ($sVal < 1) {
                	continue;
                }
                $aData['value']['id'][] = $sVal*1;
                
                $aData['value']['type'][] = $aVals["gt_loai"][$i];
                
                if ($aVals["gt_trang_thai"][$i] != 1) {
                	$aData['value']['status'][] = 0;
                } else {
                	$aData['value']['status'][] = 1;
                }
                
                $iValId = $aVals["gt_stt"][$i]*1;
                if($iValId < 1) {
                	$iValId = 0;
                }
                
                $aData['value']['id'][] = $iValId;
            }
            
            $aData['total_item'] = isset($aVals['total_item']) ? $aVals['total_item'] : -1;
            $aConds = array();
            $aData['conds'] = isset($aVals['conds']) ? $aVals['conds'] : array();
            $aCondsSelect = isset($aVals['conds_select']) ? $aVals['conds_select'] : array();
        }

        if (!in_array($aData['program_type'], array_keys($aProgramType))) {
        	return array(
                'status' => 'error',
                'message' => 'Chưa chọn loại chương trình giảm giá',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }
        else {
            if ($aData['program_type'] == 1) {
                if ($aData['vendor_id'] < 1) {
                	return array(
		                'status' => 'error',
		                'message' => 'Chưa chọn nhà cung cấp',
		                'data' => array(
		                    'list' => $aParam,
		                ),
		            );
                }
            }
        }
        
        if (mb_strlen($aData['name']) < 1 || mb_strlen($aData['name']) > 225) {
        	return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225),
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }
        
        if ($aData['total_item'] < 1) {
        	return array(
                'status' => 'error',
                'message' => 'Số lượng mã giảm giá phải lớn 0',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }
        
        if (mb_strlen($aData['name_code']) < 1 || mb_strlen($aData['name_code']) > 10) {
        	return array(
                'status' => 'error',
                'message' => sprintf(Core::getPhrase('language_x-phai-tu-x-den-x-ky-tu'), 'Mã giảm giá', 1, 10),
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }
        
        if (!in_array($aData['type'], array_keys($aValueType))) {
        	return array(
                'status' => 'error',
                'message' => 'Chưa chọn loại giảm giá',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }
        else {
            if ($aData['type'] == 0) {
                if ($aData['value'] <= 0 || $aData['value'] > 100) {
                	return array(
		                'status' => 'error',
		                'message' => 'Phần trăm giảm giá phải lớn hơn 0 và nhỏ hơn 100',
		                'data' => array(
		                    'list' => $aParam,
		                ),
		            );
                }
            }
        }
        
        if ($aData['times_to_use'] < 1) {
        	return array(
                'status' => 'error',
                'message' => 'Số lẩn sử dung phải lớn hơn 0',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }

        // check thoi gian bat dau
        if (@!$iStartTime) {
        	return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_thoi-gian-bat-dau-chua-duoc-nhap'),
                'data' => array(
                    'list' => $aParam,
                ),
            );
        } else {
            $iStartTime = strtotime($iStartTime);
            if ($iStartTime === false) {
            	return array(
	                'status' => 'error',
	                'message' => Core::getPhrase('language_thoi-gian-bat-dau-khong-ton-tai'),
	                'data' => array(
	                    'list' => $aParam,
	                ),
	            );
            }
            /*
            // tách năm và giờ phút
            $tmp=explode(" ", $iStartTime);
            // tách năm
            $ngay_thang = explode("-", $tmp[0]);
            $ngay_thang[0] *= 1;
            $ngay_thang[1] *= 1;
            $ngay_thang[2] *= 1;
            
            if(!@checkdate($ngay_thang[1], $ngay_thang[0], $ngay_thang[2])) $aErrors[] = Core::getPhrase('language_thoi-gian-bat-dau-khong-ton-tai');
            else
            {
                // tách giờ phút
                $thoi_gian = explode(":", $tmp[1]);
                $thoi_gian[0] *= 1;
                $thoi_gian[1] *= 1;
                $thoi_gian[2] *= 1;
                if($thoi_gian[0] < 0 || $thoi_gian[0] > 23) $thoi_gian[0] = 0;
                if($thoi_gian[1] < 0 || $thoi_gian[1] > 59) $thoi_gian[1] = 0;
                if($thoi_gian[2] < 0 || $thoi_gian[2] > 59) $thoi_gian[2] = 0;
                $iStartTime_int = mktime($thoi_gian[0], $thoi_gian[1], $thoi_gian[2], $ngay_thang[1], $ngay_thang[0], $ngay_thang[2]);
                unset($thoi_gian);
            }
            unset($ngay_thang);
            */
        }
        // end
        // check thoi gian bat dau
        if (@!$sEndTime) {
        	return array(
                'status' => 'error',
                'message' => Core::getPhrase('language_thoi-gian-ket-thuc-chua-duoc-nhap'),
                'data' => array(
                    'list' => $aParam,
                ),
            );
        } else {            
            $sEndTime = strtotime($sEndTime);
            if ($sEndTime === false) {
            	return array(
	                'status' => 'error',
	                'message' => Core::getPhrase('language_thoi-gian-ket-thuc-khong-ton-tai'),
	                'data' => array(
	                    'list' => $aParam,
	                ),
	            );
            }
            /*
            // tách năm và giờ phút
            $tmp=explode(" ", $sEndTime);
            // tách năm
            $ngay_thang = explode("-", $tmp[0]);
            $ngay_thang[0] *= 1;
            $ngay_thang[1] *= 1;
            $ngay_thang[2] *= 1;
            if(!@checkdate($ngay_thang[1], $ngay_thang[0], $ngay_thang[2])) $aErrors[] = Core::getPhrase('language_thoi-gian-ket-thuc-khong-ton-tai');
            else
            {
                // tách giờ phút
                $thoi_gian = explode(":", $tmp[1]);
                $thoi_gian[0] *= 1;
                $thoi_gian[1] *= 1;
                $thoi_gian[2] *= 1;
                if($thoi_gian[0] < 0 || $thoi_gian[0] > 23) $thoi_gian[0] = 0;
                if($thoi_gian[1] < 0 || $thoi_gian[1] > 59) $thoi_gian[1] = 0;
                if($thoi_gian[2] < 0 || $thoi_gian[2] > 59) $thoi_gian[2] = 0;
                $sEndTime_int = mktime($thoi_gian[0], $thoi_gian[1], $thoi_gian[2], $ngay_thang[1], $ngay_thang[0], $ngay_thang[2]);
                unset($thoi_gian);
            }
            unset($ngay_thang);
            */
        }
        
        if ($iStartTime > $sEndTime) {
        	return array(
                'status' => 'error',
                'message' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }

        //Hóa đơn
        if ($aData['apply'] == 0) {
            $aConds['type'] = 'order';
            //check select
            if (!isset($aCondsSelect['order']) || empty($aCondsSelect['order'])) {
            	return array(
	                'status' => 'error',
	                'message' => 'Chưa chọn điều kiện giảm giá',
	                'data' => array(
	                    'list' => $aParam,
	                ),
	            );
            }
            else {
                foreach ($aCondsSelect['order'] as $sKey => $sVal) {
                    if ($sKey == 'price' && $sVal == 'on') {
                        $aConds[$sKey] = $aData['conds']['order'][$sKey];
                        if (!isset($aData['conds']['order']['price']['from']) || $aData['conds']['order']['price']['from'] <= 0) {
                        	return array(
				                'status' => 'error',
				                'message' => 'Điều kiện giá trị hóa đơn phải lớn hơn 0',
				                'data' => array(
				                    'list' => $aParam,
				                ),
				            );
                        }
                    }
                    if ($sKey == 'all' && $sVal == 'on') {
                        $aConds[$sKey] = 1;
                    }
                }
            }
            
        }
        else if ($aData['apply'] == 1) {
            //san phâm
            $aConds['type'] = 'product';
            if (!isset($aCondsSelect['product']) || empty($aCondsSelect['product'])) {
            	return array(
	                'status' => 'error',
	                'message' => 'Chưa chọn điều kiện giảm giá',
	                'data' => array(
	                    'list' => $aParam,
	                ),
	            );
            }
            else {
                foreach ($aCondsSelect['product'] as $sKey => $sVal) {
                    if ($sKey == 'price' && $sVal == 'on') {
                        $aConds[$sKey] = $aData['conds']['product'][$sKey];
                        if (!isset($aData['conds']['product']['price']['from']) || $aData['conds']['product']['price']['from'] <= 0
                            || !isset($aData['conds']['product']['price']['to']) || $aData['conds']['product']['price']['to'] <= 0
                            || $aData['conds']['product']['price']['from'] > $aData['conds']['product']['price']['to']
                        ) {
                        	return array(
				                'status' => 'error',
				                'message' => 'Điều kiện giá giá tiền sản phẩm không hợp lệ',
				                'data' => array(
				                    'list' => $aParam,
				                ),
				            );
                        }
                    }
                    
                    if ($sKey == 'list' && $sVal == 'on') {
                        $aConds[$sKey] = $aData['conds']['product'][$sKey];
                        if (!isset($aData['conds']['product']['list']['id']) || empty($aData['conds']['product']['list']['id'])) {
                        	return array(
				                'status' => 'error',
				                'message' => 'Chưa có sản phẩm áp dụng giảm giá',
				                'data' => array(
				                    'list' => $aParam,
				                ),
				            );
                        }
                        else {
                            //Xử lý danh sách sản phẩm                            
                        }
                    }
                }
            }
        }

        //Kiểm tra tồn tại mã
        $sCond = '';
        if ($iId > 0) {
            $sCond = ' AND id != '.$iId;
        }
        $iCnt = $this->database->select('count(*)')
            ->from(Core::getT('discount'))
            ->where('domain_id ='.Core::getDomainId().' AND name_code = \''.$aData['name_code'].'\''.$sCond)
            ->execute('getField');
        if ($iCnt > 0) {
        	return array(
                'status' => 'error',
                'message' => 'Mã giảm giá đã tồn tại',
                'data' => array(
                    'list' => $aParam,
                ),
            );
        }

        // lọc các phần tử có mã số trùng nhau
        for($i = 0; $i < count($aData['value']['id']); $i++)
        {
            for($j = $i + 1; $j < count($aData['value']['id']); $j++)
            {
                if($aData['value']['id'][$i] == $aData['value']['id'][$j]) unset($aData['value']['id'][$j]);
            }
        }
        
        // tính vị trí = $i;
        $tong = count($aData['value']['id']);
        for($i = 0; $i < $tong; $i++)
        {
            $sVali_tri[$i] = $i;
        }

        $danh_sach = array();
        foreach($aData['value']['id'] as $k => $sVal)
        {
            if($aData['value']['type'][$k] == 'article')
            {
                if ($sVal > 0) {
                    $danh_sach[] = $sVal;
                }
            }
        }
        if(!empty($danh_sach))
        {
            $rows = $this->database->select('group_concat("", id) id')
                ->from(Core::getT('article'))
                ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id =('.implode(',', $danh_sach).')')
                ->execute('getRow');
            $stt_list = explode(',', $rows['id']);
            for($i = 0; $i < count($aData['value']['id']); $i++)
            {
                if($aData['value']['type'][$i] == 'article' && $aData['value']['id'][$i] > 0)
                {
                    $tiep = false;
                    for($j = 0; $j < count($stt_list); $j++)
                    {
                        if($aData['value']['id'][$i] == $stt_list[$j])
                        {
                            $tiep = true;
                            break;
                        }
                    }
                    if(!$tiep)
                    {
                    	return array(
			                'status' => 'error',
			                'message' => Core::getPhrase('language_bai-viet').Core::getPhrase('language_khong-ton-tai-du-lieu').$aData['value']['id'][$i],
			                'data' => array(
			                    'list' => $aParam,
			                ),
			            );
                    }
                }
            }
        }

        $danh_sach = array();
        foreach($aData['value']['id'] as $k => $sVal)
        {
            if($aData['value']['type'][$k] == 'category')
            {
                $danh_sach[] = $sVal;
            }
        }
        if(!empty($danh_sach))
        {
            $rows = $this->database->select('group_concat("", id) id')
                ->from(Core::getT('category'))
                ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND id =('.implode(',', $danh_sach).')')
                ->execute('getRow');
           
            $stt_list = explode(',', $rows['id']);
            for($i = 0; $i < count($aData['value']['id']); $i++)
            {
                if($aData['value']['type'][$i] == 'category' && $aData['value']['id'][$i] > 0)
                {
                    $tiep = false;
                    for($j = 0; $j < count($stt_list); $j++)
                    {
                        if($aData['value']['id'][$i] == $stt_list[$j])
                        {
                            $tiep = true;
                            break;
                        }
                    }
                    if(!$tiep)
                    {
                    	return array(
			                'status' => 'error',
			                'message' => Core::getPhrase('language_de-tai').Core::getPhrase('language_khong-ton-tai-du-lieu').$aData['value']['id'][$i],
			                'data' => array(
			                    'list' => $aParam,
			                ),
			            );
                    }
                }
            }
        }

        $aInsert = array(
            'program_type' => $aData['program_type'],
            'vendor_id' => $aData['vendor_id'],
            'name' => $aData['name'],
            //'total_item' => $aData['total_item'],
            //'name_code' => $aData['name_code'],
            'value' => $aData['value'],
            'type' => $aData['type'],
            'formula' => 0,
            'price_apply' => 0,
            'total_amount' => 0,
            'times_to_use' => $aData['times_to_use'],
            'apply' => $aData['apply'],
            //'is_default' => 'mac_dinh',
            'status' => $aData['status'],
        );
        
        foreach($tmps as $key => $sVal)
        {
            $aInsert[$key] = $$sVal;
        }
        $aInsert['start_time'] = $iStartTime;
        $aInsert['end_time'] = $sEndTime;
        $aInsert['is_default'] = 1;
        $aInsert['conds'] = serialize($aConds);
        // cập nhật dữ liệu
        if($iId > 0)
        {
            $this->database->update(Core::getT('discount'), $aInsert, 'id ='.$iId);
            $stt = $iId;
        }
        else
        {
            $aInsert['total_item'] = $aData['total_item'];
            $aInsert['name_code'] = $aData['name_code'];
            $aInsert['user_id'] = Core::getUserId();
            $aInsert['domain_id'] = Core::getDomainId();
            $aInsert['time'] = CORE_TIME;
            
            $stt = $this->database->insert(Core::getT('discount'), $aInsert);
            
            if ($stt > 0) {
                $iCount = 0;
                $aArray = array();
                $iTotalCharacter = 10;
                while($iCount < $aData['total_item']) {
                    $iNumber = Core::getService('core.tools')->getRandomCode($iTotalCharacter);
                    
                    if(!in_array($iNumber, $aArray)) {
                        $aArray[] = $iNumber;
                        
                        $aInsertItem = array(
                            'discount_id' => $stt,
                            'code' => $aData['name_code'].$iNumber,
                            'total_used' => 0,
                            'last_time_used' => 0,
                            'status' => 1,
                            'domain_id' => Core::getDomainId(), 
                        );
                        $this->database->insert(Core::getT('discount_item'), $aInsertItem);
                        $iCount++;
                    }
                }
            }
        }
        /*
        if($iId > 0 && $aData['apply'] == 0)
        {
            //Xóa chi tiết giảm giá (nếu có) khi áp dụng = 0
            $this->database->delete(Core::getT('discount_detail'), 'discount_id = '.$iId);
        }
        else if($aData['apply'] == 1)
        {
            if($iId > 0)
            {
                $this->database->delete(Core::getT('discount_detail'), 'discount_id = '.$iId);
            }
            
            // thêm các dữ liệu không tồn tại
            foreach($aData['value']['id'] as $i => $sVal)
            {
                if($aData['value']['type'][$i] == 'article') $tmp = 0;
                else $tmp = 1;
                
                $aInsertDetail = array(
                    'discount_id' => $stt,
                    'product_id' => $aData['value']['id'][$i],
                    'type' => $tmp,
                    'status' => $aData['value']['status'][$i],
                    'position' => $sVali_tri[$i],
                );
                $this->database->insert(Core::getT('discount_detail'), $aInsertDetail);
            }
        }
        */
        // ghi log hệ thống
        Core::getService('core.tools')->saveLogSystem(array('action' => 'create_edit_discount'.'-'.$iId,'content' => 'phpinfo',));
        // end

        $status_global=3;

        $status_global=1;
        $aRows = $this->database->select('id, title article_name, detail_path')
            ->from(Core::getT('article'))
            ->where('id IN ('.implode(',', $aData['value']['id']).')')
            ->execute('getRows');
        
        foreach ($aRows as $rows)
        {
            foreach($aData['value']['id'] as $k => $sVal)
            {
                if($sVal == $rows['id'])
                {
                    $gt_ten[$k] = $rows['article_name'];
                    $gt_duong_dan[$k] = $rows['detail_path'];
                }
            }
        }



    }
}
?>
