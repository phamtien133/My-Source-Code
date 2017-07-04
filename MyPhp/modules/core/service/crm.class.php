<?php
class Core_Service_Crm extends Service
{
    public function __construct()
    {
        
    }
    
    public function getOrderByUser($aParam = array())
    {
        /* Gia lap *
        $aParam = array(
            'uid' => 1329,
        );
        /* */
        
        $bIsApi = isset($aParam['api']) ? $aParam['api'] : false;
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        $iDomainId = Core::getDomainId();
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }
        }
        
        $iCnt = 0;
        $aData = array();
        if (Core_Error::isPassed()) {
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('shop_order'))
                ->where('domain_id ='.(int)$iDomainId.' AND status != 2 AND user_id ='.(int)$iUserId)
                ->execute('getField');
            
            if ($iCnt > 0) {
                $aRows = $this->database()->select('id, code, total_amount, total_product, fullname, address, phone_number, payment_gateway, deliver_time_from, deliver_time_to, time, create_time, surcharge, money_recieve, status_deliver, status')
                    ->from(Core::getT('shop_order'))
                    ->where('domain_id ='.(int)$iDomainId.' AND status != 2 AND user_id ='.(int)$iUserId)
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->order('create_time DESC')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    //convert time to GMT +7
                    $sTimeNone = '--/--/--';
                    if($aRow['deliver_time_from'] > 0) {
                        $aRow['deliver_time_from'] = Core::getLib('date')->convertFromGmt($aRow['deliver_time_from'], Core::getParam('core.default_time_zone_offset'));
                        $aRow['deliver_time_from'] = date("d/m/Y H:i:s",$aRow['deliver_time_from']);
                    }
                    else {
                        $aRow['deliver_time_from'] = $sTimeNone;
                    }
                    
                    if ($aRow['deliver_time_to'] > 0) {
                        $aRow['deliver_time_to'] = Core::getLib('date')->convertFromGmt($aRow['deliver_time_to'], Core::getParam('core.default_time_zone_offset'));
                        $aRow['deliver_time_to'] = date("d/m/Y H:i:s",$aRow['deliver_time_to']);
                    }
                    else {
                        $aRow['deliver_time_to'] = $sTimeNone;
                    }
                    if ($aRow['create_time'] == 0) {
                        $aRow['create_time'] = $aRow['time'];
                    }
                    if ($aRow['create_time'] > 0) {
                        $aRow['create_time'] = Core::getLib('date')->convertFromGmt($aRow['create_time'], Core::getParam('core.default_time_zone_offset'));
                        $aRow['create_time'] = date("d/m/Y H:i",$aRow['create_time']);
                    }
                    else {
                        $aRow['create_time'] = $sTimeNone;
                    }
                    
                    //mapping deliver status
                    $aMappingStatus = array(
                        'bi-tra-ve' => 'Bị trả về',
                        'da-huy' => 'Đã hủy',
                        'da-nhan-hang' => 'Đã nhận hàng',
                        'da-xac-nhan' => 'Đã xác nhận',
                        'dang-giao-hang' => 'Đang giao hàng',
                        'khong-nhan-hang' => 'Không nhận hàng',
                    );
                    
                    if (empty($aRow['status_deliver'])) {
                        $aRow['status_deliver'] = 'Đang chờ xử lý';
                    }
                    else {
                        $aRow['status_deliver'] = $aMappingStatus[$aRow['status_deliver']];
                    }
                    
                    //mapping payment gateway
                    
                    $aData[] = $aRow;
                }
            }
        }
        if ($bIsApi) {
            $aOutput = array();
            if (Core_Error::isPassed()) {
                $aOutput['status'] = 'success';
                $aOutput['data'] = array(
                    'total' => $iCnt,
                    'page' => $iPage,
                    'page_size' => $iPageSize,
                    'data' => $aData,
                );
            }
            else {
                $aOutput['status'] = 'error';
                $aOutput['message'] = Core_Error::get('error');
            }
            return $aOutput;
        }
        else {
            return array(
                'total' => $iCnt,
                'page' => $iPage,
                'page_size' => $iPageSize,
                'data' => $aData,
            );
        }
    }
    
    public function getActivityByUser($aParam = array())
    {
        /* Gia lap *
        $aParam = array(
            'uid' => 1329,
        );
        /* */
        
        $bIsApi = isset($aParam['api']) ? $aParam['api'] : false;
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        $iDomainId = Core::getDomainId();
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }
        }
        
        $iCnt = 0;
        $aData = array();
        if (Core_Error::isPassed()) {
            $aViews = $this->getViewByUser(array(
                'uid' => $iUserId,
                'page' => $iPage,
                'page_size' => $iPageSize,
            ));
            $aLike = $this->getLikeByUser(array(
                'uid' => $iUserId,
                'page' => $iPage,
                'page_size' => $iPageSize,
            ));
            $aComment = $this->getCommentByUser(array(
                'uid' => $iUserId,
                'page' => $iPage,
                'page_size' => $iPageSize,
            ));
        }
        
        
        if ($bIsApi) {
            $aOutput = array();
            if (Core_Error::isPassed()) {
                $aOutput['status'] = 'success';
                $aOutput['data'] = array(
                    'page' => $iPage,
                    'page_size' => $iPageSize,
                    'data' => array(
                        'view' => $aViews,
                        'like' => $aLike,
                        'comment' => $aComment,
                    ),
                );
            }
            else {
                $aOutput['status'] = 'error';
                $aOutput['message'] = Core_Error::get('error');
            }
            return $aOutput;
        }
        else {
            return array(
                'page' => $iPage,
                'page_size' => $iPageSize,
                'data' => array(
                    'view' => $aViews,
                    'like' => $aLike,
                    'comment' => $aComment,
                ),
            );
        }
    }
    
    public function getViewByUser($aParam = array())
    {
        /* Gia lap *
        $aParam = array(
            'uid' => 1329,
        );
        /* */
        
        $bIsApi = isset($aParam['api']) ? $aParam['api'] : false;
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        $iDomainId = Core::getDomainId();
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }
        }
        
        $iCnt = 0;
        $aData = array();
        if (Core_Error::isPassed()) {
            //Lấy danh sách id của những sản phẩm đã xem từ log
            $aLogSIDs = $this->database()->select('id')
                ->from(Core::getT('log_access_sid'))
                ->where('domain_id ='.(int)$iDomainId.' AND user_id ='.(int)$iUserId)
                ->order('update_time DESC')
                ->execute('getRows');
            
            $aIdTmp = array();
            foreach ($aLogSIDs as $aLogSID) {
                $aIdTmp[] = $aLogSID['id'];
            }
            if(!empty($aIdTmp)) {
                //Lấy thông tin từ log truy cập
                $aLogAccess = $this->database()->select('id, object_id, vendor_id')
                    ->from(Core::getT('log_access_article'))
                    ->where('log_access_sid_id IN ('.implode(',', $aIdTmp).')')
                    ->order('update_time DESC, create_time DESC')
                    ->execute('getRows');
                
                //Lấy thông tin chi tiết sàn phẩm
                $aArticleId = array();
                foreach ($aLogAccess as $aRow) {
                    if (!in_array($aRow['object_id'], $aArticleId)) {
                        $aArticleId[$aRow['object_id']][$aRow['vendor_id']] = $aRow['vendor_id'];
                    }
                }
                
                //Lấy theo trang
                if (!empty($aArticleId)) {
                    $iCnt = count($aArticleId);
                    $aTmp = array_keys($aArticleId);
                    $aTmp = array_slice($aTmp, ($iPage - 1)*$iPageSize, $iPageSize);
                    $aProductList = Core::getService('user')->getProducts(array(
                        'list' => $aTmp
                    ));
                    
                    // update price by vendor.
                    $aTmps = $this->database()->select('vendor_id, article_id, price_sell, price_discount, quantity, sku')
                        ->from(Core::getT('filter_influence'))
                        ->where('article_id IN ('. implode(',', array_keys($aArticleId)).') AND status = 1')
                        ->execute('getRows');

                    $aVendorDatas = array();
                    foreach ($aArticleId as $iArticleKey => $aValue) {
                        foreach ($aTmps as $iKey => $aTmp) {
                            if ($iArticleKey != $aTmp['article_id'])
                                continue;
                            if(!in_array($aTmp['vendor_id'], $aArticleId[$iArticleKey])) {
                                unset($aTmps[$iKey]);
                                continue;
                            }
                            $aVendorDatas[$iArticleKey][] = $aTmp;
                            unset($aTmps[$iKey]);
                        }
                    }

                    $aDatas = array();
                    // map vendor and article.
                    foreach ($aProductList as $iKey => $aProduct) {
                        foreach ($aVendorDatas[$aProduct['id']] as $aVendor) {
                            $sSaveKey = $aVendor['vendor_id']. '_'. $aProduct['id']; 
                            $aDatas[$sSaveKey] =  $aProduct;
                            $aDatas[$sSaveKey]['sku'] = str_replace(Core::getDomainId().'|', '', $aVendor['sku']);
                            $aDatas[$sSaveKey]['price_sell'] = $aVendor['price_sell'];
                            $aDatas[$sSaveKey]['price_discount'] = $aVendor['price_discount'];
                            $aDatas[$sSaveKey]['quantity'] = $aVendor['quantity'];
                            $aDatas[$sSaveKey]['vendor_id'] = $aVendor['vendor_id'];
                        }
                    }
                    if (!empty($aDatas)) {
                        foreach ($aDatas as $aRow) {
                            $aData[] = $aRow;
                        }
                    }
                }
            }
        }
        
        if ($bIsApi) {
            $aOutput = array();
            if (Core_Error::isPassed()) {
                $aOutput['status'] = 'success';
                $aOutput['data'] = array(
                    'total' => $iCnt,
                    'page' => $iPage,
                    'page_size' => $iPageSize,
                    'data' => $aData,
                );
            }
            else {
                $aOutput['status'] = 'error';
                $aOutput['message'] = Core_Error::get('error');
            }
            return $aOutput;
        }
        else {
            return array(
                'total' => $iCnt,
                'page' => $iPage,
                'page_size' => $iPageSize,
                'data' => $aData,
            );
        }
    }
    
    public function getLikeByUser($aParam = array())
    {
        /* Gia lap *
        $aParam = array(
            'uid' => 1329,
        );
        /* */
        
        $bIsApi = isset($aParam['api']) ? $aParam['api'] : false;
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        $iDomainId = Core::getDomainId();
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }
        }
        
        $iCnt = 0;
        $aData = array();
        if (Core_Error::isPassed()) {
            //Lấy thông tin những bài viết được like
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('like'))
                ->where('domain_id ='.(int)$iDomainId.' AND type_name = \'article\' AND type_sub > 0 AND status !=2 AND user_id ='.(int)$iUserId)
                ->execute('getField');
            
            if ($iCnt > 0) {
                $aRows = $this->database()->select('id, type_id, type_name, time, type_sub')
                    ->from(Core::getT('like'))
                    ->where('domain_id ='.(int)$iDomainId.' AND type_name = \'article\' AND type_sub > 0 AND status !=2 AND user_id ='.(int)$iUserId)
                    ->order('time DESC')
                    ->execute('getRows');
                
                $aProductId = array();
                $aProductSku = array();
                $aSubId = array();
                foreach ($aRows as $aRow) {
                    if (!in_array($aRow['type_id'], $aProductId)) {
                        $aProductId[] = $aRow['type_id'];
                    }
                    if (!empty($aRow['type_sub']))
                        $aSubId[] = $aRow['type_sub'];
                }
                
                //Lấy thông tin chi tiết bài viết theo id
                /*
                if (!empty($aProductId)) {
                    $aProductList = $this->getProducts(array(
                        'list' => $aProductId
                    ));
                }
                */
                //Lấy thông tin chi tiết bài viết theo sku
                if (!empty($aSubId)) {
                    $aData = $this->getProductsByInfluence(array(
                        'list' => $aSubId
                    ));
                    
                }
            }
        }
        
        if ($bIsApi) {
            $aOutput = array();
            if (Core_Error::isPassed()) {
                $aOutput['status'] = 'success';
                $aOutput['data'] = array(
                    'total' => $iCnt,
                    'page' => $iPage,
                    'page_size' => $iPageSize,
                    'data' => $aData,
                );
            }
            else {
                $aOutput['status'] = 'error';
                $aOutput['message'] = Core_Error::get('error');
            }
            return $aOutput;
        }
        else {
            return array(
                'total' => $iCnt,
                'page' => $iPage,
                'page_size' => $iPageSize,
                'data' => $aData,
            );
        }
    }
    
    public function getCommentByUser($aParam = array())
    {
        /* Gia lap *
        $aParam = array(
            'uid' => 1329,
        );
        /* */
        
        $bIsApi = isset($aParam['api']) ? $aParam['api'] : false;
        $iUserId = isset($aParam['uid']) ? $aParam['uid'] : -1;
        if ($iUserId < 1) {
            Core_Error::set('error', 'Empty user');
        }
        
        $iDomainId = Core::getDomainId();
        
        $iPage = 1;
        $iPageSize = 10;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 10;
            }
        }
        
        $iCnt = 0;
        $aData = array();
        if (Core_Error::isPassed()) {
            //Lấy cmt có parent_id = -1 và type_name =article, type_sub > 0
            //Là  cmt của sản phẩm
            $sConds = ' AND user_id = '.$iUserId.' AND parent_id = -1 AND status != 2 and type_name =\'article\' AND type_sub > 0';
            $iCnt = $this->database()->select('count(*)')
                ->from(Core::getT('comment'))
                ->where('domain_id ='.$iDomainId.$sConds)
                ->execute('getField');
            
            if ($iCnt > 0) {
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('comment'))
                    ->where('domain_id ='.$iDomainId.$sConds)
                    ->order('time DESC')
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->execute('getRows');
                
                $aSubId = array();
                foreach ($aRows as $aRow) {
                    if (!empty($aRow['type_sub']))
                        $aSubId[] = $aRow['type_sub'];
                }
                //Lấy thông tin chi tiết bài viết theo sku
                if (!empty($aSubId)) {
                    $aData = $this->getProductsByInfluence(array(
                        'list' => $aSubId
                    ));
                }
                foreach ($aRows as $aRow) {
                    if (isset($aData[$aRow['type_sub']])) {
                        if ($aRow['time'] > 0) {
                            $aRow['time'] = Core::getLib('date')->convertFromGmt($aRow['time'], Core::getParam('core.default_time_zone_offset'));
                            $aRow['time'] = date('H:m:i d/m/Y', $aRow['time']);
                        }
                        $aData[$aRow['type_sub']]['comment'][] = array(
                            'content' => $aRow['content'],
                            'time' => $aRow['time'],
                        );
                    }
                }
            }
        }
        
        if ($bIsApi) {
            $aOutput = array();
            if (Core_Error::isPassed()) {
                $aOutput['status'] = 'success';
                $aOutput['data'] = array(
                    'total' => $iCnt,
                    'page' => $iPage,
                    'page_size' => $iPageSize,
                    'data' => $aData,
                );
            }
            else {
                $aOutput['status'] = 'error';
                $aOutput['message'] = Core_Error::get('error');
            }
            return $aOutput;
        }
        else {
            return array(
                'total' => $iCnt,
                'page' => $iPage,
                'page_size' => $iPageSize,
                'data' => $aData,
            );
        }
    }
    
    
    /**
    * Lấy thông tin của tất cả các sản phẩm được truyền vào qua list trích lọc ảnh hưởng truyền vào
    * 
    * @param mixed $aParam
    */
    public function getProductsByInfluence($aParam = array())
    {
        $aInflu = array();
        if (isset($aParam['list'])) {
            $aInflu = $aParam['list'];
        }
        
        if (empty($aInflu)) {
            return array();
        }
        
        $aData = array();
        $aDataTmp = array();
        $aId = array();
        if (!empty($aInflu)) {
            $aRows = $this->database()->select('id, sku, vendor_id, article_id')
                ->from(Core::getT('filter_influence'))
                ->where('id IN ('.implode(',', $aInflu).')')
                ->execute('getRows');
            
            $aVendors = array();
            foreach ($aRows as $aRow) {
                if (!in_array($aRow['article_id'], $aId)) {
                    $aId[] = $aRow['article_id'];
                }
                //Loại bỏ domain trong sku
                $iLength = strlen($sDomainId);
                if ($iLength > 0) {
                    $aRow['sku'] = substr($aRow['sku'], $iLength);
                }
                $aDataTmp[$aRow['id']]['vendor_id'] = $aRow['vendor_id'];
                $aDataTmp[$aRow['id']]['id'] = $aRow['article_id'];
            }
        }
        
        //sắp xếp lại array data để lấy lại thứ tự input
        foreach ($aInflu as $iIdTmp) {
            $aData[$iIdTmp] = $aDataTmp[$iIdTmp];
        }
        
        if (empty($aId)) {
            return array();
        }
        $aArticles = array();
        $aMapping = array();
        $aRows = $this->database()->select('title, id, description, image_path, detail_path, expire_time, image_extend')
            ->from(Core::getT('article'))
            ->where('id IN ('.implode(',', $aId).')')
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            $aTmp = array();
            $aTmp['id'] = $aRow['id'];
            $aTmp['link_id'] = $aRow["link_id"];
            $aTmp['name'] = $aRow['title'];
            $aTmp['name_html'] = htmlspecialchars($aRow['title']);
            $aTmp['description'] = $aRow['description'];
            $aTmp['expire_time'] = $aRow["expire_time"]*1;
            $aTmp['image_path'] = $aRow["image_path"];
            $aTmp['path'] = $aRow["detail_path"];
            $aTmp['image_extend'] = $aRow["image_extend"];
            $aTmp['group'] = $aRow["group_article"];
            
            $aMapping[$aRow['id']] = $aTmp;
        }

        //Foreach lần nữa để giữ nguyên thứ tự của của dữ liệu input vào
        foreach ($aId as $sIdTmp) {
            $aArticles[$sIdTmp] = $aMapping[$sIdTmp];
        }
        
        if (!empty($aArticles)) {
            // tính lại danh sách bài viết
            $aTmps = array();
            $aArticleImageExtendList = array();
            foreach ($aArticles as $Key => $Val) {
                $aTmps[] = $Val['id'];
                    
                if ($Val['image_extend'] > 0) {
                    $aArticleImageExtendList[] = $Val['id'];
                    $aArticles[$Key]['image_extend'] = array();
                }
            }
            // web shop
            if (!empty($aTmps)) {
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('shop_article'))
                    ->where('article_id IN ('.implode(',', $aTmps).')')
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    foreach ($aRow as $Key => $Val) {
                        /*
                        if (is_nan($Key))
                            continue;
                        */
                        if ($Key == 'price_sell'
                            || $Key == 'price_discount'
                            || $Key == 'weight'
                            || $Key == 'buy_quantity'
                            || $Key == 'total_quantity'
                            || $Key == 'necessary_quantity')
                            $Val *= 1;
                        
                        $aRow[$Key] = $Val;
                    }
                    
                    foreach ($aArticles as $Key => $Val) {
                        if ($Val['id'] != $aRow["article_id"])
                            continue;
                        
                        foreach ($aRow as $sKey => $sVal) {
                            if ($sKey == 'id' || $sKey == 'article_id')
                                continue;
                            $aArticles[$Key][$sKey] = $sVal;
                        }
                        $aArticles[$Key]['amount'] = $aRow["price_sell"]*1 - $aRow["price_discount"]*1;
                        
                        $sKey = 'deliver_method';
                        if ($aArticles[$Key][$sKey] == 1)
                            $aArticles[$Key][$sKey] = 'Delivery Receipt';
                        else
                            $aArticles[$Key][$sKey] = 'Delivery Products';
                        //----
                    }
                }
            }
            // Hình mở rộng cho phần Liên kết
            if (!empty($aArticleImageExtendList)) {
                // lấy danh sách hình ảnh
                $aRows = $this->database()->select('id, name_code')
                    ->from(Core::getT('image_extend'))
                    ->where('status = 1 AND domain_id ='.(int)Core::getDomainId())
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    $aImageExtendIdToNameCode[$aRow["id"]] = $aRow["name_code"];
                }
                
                $aRows = $this->database()->select('object_id as article_id, image_extend_id, path')
                    ->from(Core::getT('image_extend_link'))
                    ->where('object_type = 0 AND object_id IN ('.implode(',', $aArticleImageExtendList).')')
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    $sImageExtendNameCode = $aImageExtendIdToNameCode[$aRow["image_extend_id"]];
                    foreach ($aArticles as $Key => $aVal) {
                        if ($aVal['id'] == $aRow["article_id"]) {
                            $aArticles[$Key]['image_extend'][$sImageExtendNameCode] = $aRow["path"];
                        }
                    }
                }
            }
        }
        
        //mapping
        foreach ($aData as $sKey => $aVal) {
            $aData[$sKey] = $aArticles[$aVal['id']];
            $aData[$sKey]['vendor_id'] = $aVal['vendor_id'];
        }
        
        return $aData;
    }
    
    public function getCurrentUser()
    {
        $aRow = $this->database()->select('*')
            ->from(core::getT('crm_user'))
            ->where('user_id = '. Core::getUserId() .' AND status = 1')
            ->execute('getRow');
        
        return $aRow;
    }
    
    public function getUser()
    {
        $aRows = $this->database()->select('*')
            ->from(Core::getT('crm_user'))
            ->where('domain_id = '. Core::getDomainId())
            ->execute('getRows');
        
        $aUsers = array();
        foreach ($aRows as $aRow) {
            $aUsers[$aRow['user_id']] = $aRow;
        }
        // get user info
        if (count($aUsers)) {
            $aRows = $this->database()->select('id, username, fullname')
                ->from(core::getT('user'))
                ->where('id IN ('.implode(',', array_keys($aUsers)).')')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                $aUsers[$aRow['id']]['info'] = $aRow;
            }
        }
        return $aUsers;
    }
}
?>
