<?php
class Core_Service_Convert extends Service
{
    public function __construct()
    {
        
    }
    
    public function updateProductSku()
    {
        // hàm này cần chỉnh lại khi 1 sản phẩm chạy trên nhiều vendor.
        $aFilters = $this->database()->select('article_id, vendor_id, sku')
            ->from(Core::getT('filter_influence'))
            ->where('status = 1')
            ->execute('getRows');
            
        $aArticles = array();
        foreach ($aFilters as $aValue) {
            $aArticles[$aValue['article_id']] = str_replace('1|', '', $aValue['sku']);
        }
        unset($aFilters);
        $aDisplays = $this->database()->select('id, article_id, sku')
            ->from(Core::getT('category_display_article'))
            ->where('status = 1')
            ->execute('getRows');
        
        foreach ($aDisplays as $aItem) {
            if ($aItem['sku'] != $aArticles[$aItem['article_id']]) {
                // cập nhật lại sku trong bảng display.
                $this->database()->update(Core::getT('category_display_article'), array(
                    'sku' => $aArticles[$aItem['article_id']]
                ), 'id ='. $aItem['id']);
            }
        }
    }
    
    public function updateProductByVendor()
    {
        $aVendorList = array(93);
        
    }
    /**
    * cập nhật hiển thị sản phẩm hết hàng.
    * 
    */
    public function updateProductStock()
    {
        $aRows = $this->database()->select('*')
            ->from(Core::getT('filter_influence'))
            ->where('quantity = 0')
            ->execute("getRows");
        
        $aArticleId = array();
        foreach ($aRows as $aRow) {
            $aArticleId[] = str_replace('1|', '' , $aRow['sku']);
        }
        if (count($aArticleId)) {
            $sCond = '\''. implode('\',\'', $aArticleId) . '\'';
            $this->database()->update(Core::getT('category_display_article'), array(
                'status' => 0
            ), 'sku IN ('.$sCond.')');
            
        }
        
    }
    
    
    public function updateParentCategory()
    {
        $aParents = $this->database()->select('*')
            ->from(Core::getT('category_display'))
            ->where('`object_id` = 136 AND `object_type` = 1 AND `item_type` = 2')
            ->execute('getRows');
            
        foreach ($aParents as  $aParent) {
            // lấy danh sách detail
            $aDetails = $this->database()->select('*')
                ->from(Core::getT('category_display_detail'))
                ->where('parent_id ='. $aParent['id'])
                ->execute('getRows');
            foreach ($aDetails as  $aDetail) {
                $iCount = $this->database()->select('COUNT(*)')
                    ->from(Core::getT('category_display_article'))
                    ->where('status = 1 AND detail_id ='. $aDetail['id'])
                    ->execute('getField');
                if ($iCount < 1) {
                    $this->database()->update(Core::getT('category_display_detail'), array(
                        'status' => 0
                    ), 'id ='. $aDetail['id']);
                }
            }
        }
    }
    
    public function addVendorToOnSale()
    {
        $aVendor = array();
        $iOnSaleId = 137;
        
        // lấy danh sách vendor đang được kích hoạt.
        $aRows = $this->database()->select('id')
            ->from(Core::getT('vendor'))
            ->where('status = 1 AND domain_id ='. Core::getDomainId())
            ->execute('getRows');
        foreach ($aRows as $aRow) {
            if (in_array($aRow['id'], $aVendor))
                continue;
            $aVendor[] = $aRow['id'];
        }
        foreach ($aVendor as $iVendor) {
            $iParentId = $this->database()->select('id')
                ->from(core::getT('category_display'))
                ->where('object_id = '. $iOnSaleId . ' AND object_type = 1 AND item_type = 2 AND item_id ='. $iVendor)
                ->execute('getField');
            
            if(!$iParentId) {
                $iParentId = $this->database()->insert(Core::getT('category_display'), array(
                    'object_id' => $iOnSaleId,
                    'object_type' => 1,
                    'item_type' => 2,
                    'item_id' => $iVendor,
                    'total_dislay_item' => 0,
                    'status' => 1,
                    'position' => 0,
                    'domain_id' => 1
                ));
            }
            
            // lay danh sach sp sale.
            $aProducts = $this->database()->select('*')
                ->from(Core::getT('filter_influence'))
                ->where('vendor_id ='. $iVendor . ' AND status = 1 AND quantity > 0 AND price_discount > 0')
                ->execute('getRows');
                
            foreach ($aProducts as $aProduct) {
                $sSku = str_replace('1|', '', $aProduct['sku']);
                $iField = $this->database()->select('id')
                    ->from(Core::getT('category_display_article'))
                    ->where('parent_id = '. $iParentId. ' AND article_id = '. $aProduct['article_id']. ' AND sku = \''. $this->database()->escape($sSku).'\'')
                    ->execute('getField');
                
                if ($iField) {
                    // đã tồn tại, cập nhật lại trạng thái
                    $this->database()->update(Core::getT('category_display_article'), array(
                        'status' => 1
                    ), 'id ='. $iField);
                }
                else {
                    //add to category_display_article
                    $aInsertDisplay = array(
                        'parent_id' => $iParentId,
                        'detail_id' => 0,
                        'article_id' => $aProduct['article_id'],
                        'sku' => $sSku,
                        'position' => 0,
                        'status' => 1
                    );
                    $this->database()->insert(Core::getT('category_display_article'), $aInsertDisplay);
                }
            }
        }
    }
    
    public function updateBuyCount()
    {
        $aRows = $this->database()->select('article_id, count(article_id) as total')
            ->from(Core::getT('shop_order_dt'))
            ->where('update_time > 1449874800')
            ->group('article_id')
            ->execute('getRows');
        
        foreach ($aRows as $aRow) {
            $this->database()->update(Core::getT('article'), array(
                'total_buy' => $aRow['total']
            ),'id ='. $aRow['article_id']);
        }
    }
    
    /**
    * cập nhật giá trị cha liên kết bị sai
    * 
    */
    public function updateFilterValue()
    {
        $aParent = array(
            332 => 2642,
            331 => 2641,
            330 => 2640,
            329 => 2639,
            328 => 2638,
            327 => 2637,
            326 => 2636,
            325 => 2635,
            324 => 2634,
            323 => 2633,
            322 => 2632,
            321 => 2631,
            320 => 2630,
            319 => 2629,
            318 => 2628,
            317 => 2627,
            316 => 2626,
            315 => 2625,
            314 => 2624,
            313 => 2623,
            312 => 2622,
            311 => 2621,
            310 => 2620,
            309 => 2619,
        );
        
        // lấy danh sách dữ liệu
        $aRows = $this->database()->select('*')
            ->from(Core::getT('filter_value'))
            ->where('filter_id = 12 AND status = 1')
            ->execute("getRows");
        
        foreach ($aRows as $aRow) {
            $aRow['parent_filter_value_list'] = unserialize($aRow['parent_filter_value_list']);
            
            if (!empty($aRow['parent_filter_value_list'])) {
                foreach ($aRow['parent_filter_value_list'] as $iKey => $iValue) {
                    if (isset($aParent[$iValue])) {
                        $aRow['parent_filter_value_list'][$iKey] = $aParent[$iValue];
                    }
                }

                $this->database()->update(Core::getT('filter_value'), array(
                    'parent_filter_value_list' => serialize($aRow['parent_filter_value_list'])
                ), 'id = '. $aRow['id']);
            }
        }
    }
    
    public function updateLinkHttps()
    {
        $sType = 'article';
        $bIsAll = 1;
        // update cho sản phẩm.
        if ($bIsAll || $sType == 'article') {
            $aArticles = $this->database()->select('id , image_path')
                ->from(Core::getT('article'))
                ->execute('getRows');
            
            foreach ($aArticles as $aArticle) {
                if (strpos($aArticle['image_path'], 'http://' ) !== false) {
                    $sImagePath = str_replace('http://', '//', $aArticle['image_path']);
                    $this->database()->update(Core::getT('article'), array(
                        'image_path' => $sImagePath
                    ), 'id ='. $aArticle['id']);
                }
            }
        }
        
        if ($bIsAll || $sType == 'article_link') {
            $aArticles = $this->database()->select('id , path')
                ->from(Core::getT('image_extend_link'))
                ->execute('getRows');
            
            foreach ($aArticles as $aArticle) {
                if (strpos($aArticle['path'], 'http://' ) !== false) {
                    $sImagePath = str_replace('http://', '//', $aArticle['path']);
                    $this->database()->update(Core::getT('image_extend_link'), array(
                        'path' => $sImagePath
                    ), 'id ='. $aArticle['id']);
                }
            }
        }
        
        if ($bIsAll || $sType == 'category') {
            $aRows = $this->database()->select('id , image_path')
                ->from(Core::getT('category'))
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                if (strpos($aRow['image_path'], 'http://' ) !== false) {
                    $sImagePath = str_replace('http://', '//', $aRow['image_path']);
                    $this->database()->update(Core::getT('category'), array(
                        'image_path' => $sImagePath
                    ), 'id ='. $aRow['id']);
                }
            }
        }
        
        if ($bIsAll || $sType == 'vendor') {
            $aRows = $this->database()->select('id , image_path')
                ->from(Core::getT('vendor'))
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                if (strpos($aRow['image_path'], 'http://' ) !== false) {
                    $sImagePath = str_replace('http://', '//', $aRow['image_path']);
                    $this->database()->update(Core::getT('vendor'), array(
                        'image_path' => $sImagePath
                    ), 'id ='. $aRow['id']);
                }
            }
        }
        
        if ($bIsAll || $sType == 'ads') {
            $aRows = $this->database()->select('id , image_path')
                ->from(Core::getT('ads_link'))
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                if (strpos($aRow['image_path'], 'http://' ) !== false) {
                    $sImagePath = str_replace('http://', '//', $aRow['image_path']);
                    $this->database()->update(Core::getT('ads_link'), array(
                        'image_path' => $sImagePath
                    ), 'id ='. $aRow['id']);
                }
            }
        }
    }
    
    public function fakeArticle()
    {
        // lấy danh sách các vendor và tổng các lượt thống kê. 
        $aTmps = $this->database()->select('id, total_buy, total_customer, total_cart')
            ->from(Core::getT('vendor'))
            ->execute('getRows');
        $aVendors = array();
        foreach ($aTmps as $aTmp) {
            $aVendors[$aTmp['id']]['total_buy'] = $aTmp['total_buy'] + 300 - $aTmp['id'];
            $aVendors[$aTmp['id']]['total_customer'] = $aTmp['total_customer'] + 150 - $aTmp['id'];
            $aVendors[$aTmp['id']]['total_cart'] = $aTmp['total_cart'] + 400 - $aTmp['id'];
        }
        
        $aArticles = $this->database()->select('id, category_child_5, total_view, total_buy, total_like, fake_count')
            ->from(Core::getT('article'))
            ->execute('getRows');
        
        foreach ($aArticles as $aArticle) {
            if (!isset($aVendors[$aArticle['category_child_5']])) {
                $iRandom = rand(0, 50);
            }
            else {
                $iMax = $aVendors[$aArticle['category_child_5']]['total_buy'] - 100;
                if ($iMax < 0) {
                    $iMax = 10;
                }
                $iRandom = rand(0, $iMax);
            }
            $iTotalBuy = $aArticle['total_buy'] + $iRandom - $aArticle['fake_count'];
            $iTotalLike = $aArticle['total_like'] + $iRandom * 3/2 - $aArticle['fake_count'];
            $iTotalView = $aArticle['total_view'] + $iRandom * 2 - $aArticle['fake_count'];
            if ($iTotalBuy < 0) {
                $iTotalBuy = rand(0, 20);;
            }
            if ($iTotalLike < 0) {
                $iTotalLike = rand(10, 30);
            }
            if ($iTotalView) {
                $iTotalView = rand(30, 50);
            }
            if ($iTotalView < $iTotalBuy || $iTotalView < $iTotalLike) {
                $iTotalView = $iTotalBuy + $iTotalLike;
            }
            
            $this->database()->update(Core::getT('article'), array(
                'total_view' =>  $iTotalView,
                'total_buy' =>  $iTotalBuy,
                'total_like' =>  $iTotalLike,
                'fake_count' => $iRandom
            ), 'id = '. $aArticle['id']);
        }
    }
    
    public function removeProductNotPrice()
    {
        $aList  = array(
            'I65d5IrfHk',
            'gLMzoJrfHk',
            'qYcOJJrfHk',
            'RJ068',
            'UwFSAJrfHk',
            'yQSRmJrfHk',
            'CfTR5IrfHk',
            'RN0007',
            'q6Y49JrfHk',
            'AWaiwmwLIk',
            'i8gjYmWoJk',
            '0jc0kJrfHk',
            'gZxZiKrfHk',
            'RJ045',
            '8713260028210',
            'YcJ0QvDsIk',
            '8850273229101',
            '8852047137230',
            'cPbUkJrfHk',
            '2uLdGEg4Ik',
            '0fE29JrfHk',
            '2CcXmJrfHk'
        );
        $sConds = '';
        foreach ($aList as $sValue) {
            $sConds .= '\'' . $sValue . '\',';
        }
        $sConds = rtrim($sConds, ',');
        // lấy thông tin sản phẩm.
        $aRows = $this->database()->select('id')
            ->from(Core::getT('article'))
            ->where('code IN ('. $sConds.')')
            ->execute('getRows');
        
        $aArticles = array();
        foreach ($aRows as $aRow) {
            $aArticles[$aRow['id']] = array();
        }
        
        if (count($aArticles)) {
            // lấy sku đang có hiện tại.
            $aRows = $this->database()->select('article_id, sku')
                ->from(Core::getT('filter_influence'))
                ->where('article_id IN ('. implode(',', array_keys($aArticles)).') AND quantity > 0 AND status = 1')
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                $aRow['sku'] = str_replace(Core::getDomainId(). '|', '', $aRow['sku']);
                $aArticles[$aRow['article_id']][] = $aRow['sku'];
            }
            
            // lấy dữ liệu bài viết đang hiển thị.
            $aRows = $this->database()->select('id, article_id, sku') 
                ->from(Core::getT('category_display_article'))
                ->where('article_id IN ('. implode(',', array_keys($aArticles)).')')
                ->execute("getRows");
            foreach ($aRows as $aRow) {
                if (!in_array($aRow['sku'], $aArticles[$aRow['article_id']])) {
                    $this->database()->delete(Core::getT('category_display_article'), 'id ='. $aRow['id']);
                }
            }
        }
    }
    
    public function convertTitleParse()
    {
        mb_internal_encoding("UTF-8");
        // lấy tất cả danh sách sản phẩm hiện tại.
        $iTotal = $this->database()->select('COUNT(*)')
            ->from(Core::getT('article'))
            //->where('title_parse IS NULL')
            ->execute('getField');
            
        if ($iTotal > 5000) {
            // thực hiện phân trang lấy dữ liệu nhằm tránh quá tải 
            $iPageSize = 5000;
            $iPage = ($iTotal % $iPageSize) == 0 ? ($iTotal / $iPageSize) : (int) ($iTotal / $iPageSize) + 1;
            for($iCnt = 1; $iCnt <= $iPage; $iCnt++) {
                $aRows = $this->database()->select('id, title')
                    ->from(Core::getT('article'))
                    //->where('title_parse IS NULL')
                    ->order('time DESC')
                    ->limit($iCnt, $iPageSize, $iTotal)
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    //$sTitleParse = mb_strtolower($aRow['title'], 'UTF8');
                    $sTitleParse = strtolower($aRow['title']);
                    $sTitleParse = trim($sTitleParse, ' ');
                    $sTitleParse = core::getLib('url')->removeSpecialChar($sTitleParse);
                    $this->database()->update(Core::getT('article'), array(
                        'title_parse' => $sTitleParse
                    ), 'id ='. $aRow['id']);
                }
            }
        }
        else {
            $aRows = $this->database()->select('id, title')
                ->from(Core::getT('article'))
                //->where('title_parse IS NULL')
                ->order('time DESC')
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                //$sTitleParse = mb_strtolower($aRow['title'], 'UTF8');
                $sTitleParse = strtolower($aRow['title']);
                $sTitleParse = trim($sTitleParse, ' ');
                $sTitleParse = core::getLib('url')->removeSpecialChar($sTitleParse);
                $this->database()->update(Core::getT('article'), array(
                    'title_parse' => $sTitleParse
                ), 'id ='. $aRow['id']);
            }
        }
    }
    
    /**
    * hàm check detail path có đúng theo cấu trúc không.
    * lỗi phát sinh do thay đổi danh mục.
    * 
    */
    public function checkDetailPath()
    {
        $iTotal = $this->database()->select('COUNT(*)')
            ->from(Core::getT('article'))
            ->where('status = 1')
            ->execute("getField");
            
        if ($iTotal > 5000) {
            $iPageSize = 5000;
            $iPage = ($iTotal % $iPageSize) == 0 ? ($iTotal / $iPageSize) : (int) ($iTotal / $iPageSize) + 1;
            for($iCnt = 1; $iCnt <= $iPage; $iCnt++) {
                $aRows = $this->database()->select('id, code, title, detail_path, path')
                    ->from(Core::getT('article'))
                    ->where('status = 1')
                    ->order('id DESC')
                    ->limit($iCnt, $iPageSize, $iTotal)
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    if (strpos($aRow['detail_path'], $aRow['path']) === false) {
                        core_log($aRow, 'a+');
                    }
                }
            }
        }
        else {
            $aRows = $this->database()->select('id, code, title, detail_path, path')
                ->from(Core::getT('article'))
                ->where('status = 1')
                ->execute('getRows');
            
            foreach ($aRows as $aRow) {
                if (strpos($aRow['detail_path'], $aRow['path']) === false) {
                    core_log($aRow, 'a+');
                }
            }
        }
    }
    
    
    /**
    * hàm cập nhật lại tất cả trong hệ thống detail path
    * cập nhật luôn cho phần nhiều siêu thị trong 1 sản phẩm.
    * 
    */
    public function updateDetailPath()
    {
        $iTotal = $this->database()->select('COUNT(*)')
            ->from(Core::getT('article'))
            ->where('status = 1')
            ->execute("getField");
            
        if ($iTotal > 5000) {
            $iPageSize = 5000;
            $iPage = ($iTotal % $iPageSize) == 0 ? ($iTotal / $iPageSize) : (int) ($iTotal / $iPageSize) + 1;
            for($iCnt = 1; $iCnt <= $iPage; $iCnt++) {
                $aRows = $this->database()->select('id, code, title, detail_path, path')
                    ->from(Core::getT('article'))
                    ->where('status = 1')
                    ->order('id DESC')
                    ->limit($iCnt, $iPageSize, $iTotal)
                    ->execute('getRows');
                
                foreach ($aRows as $aRow) {
                    
                }
            }
        }
        else {
            
        }
    }
    
    public function removeDuplicateVendorCategory()
    {
        $aRows = $this->database()->select('*')
            ->from(Core::getT('vendor_category'))
            ->execute("getRows");
            
        $aExits = array();
        
        foreach ($aRows as $aRow) {
            $sKey = $aRow['vendor_id']. '_'. $aRow['category_id'];
            if (isset($aExits[$sKey])) {
                $this->database()->delete(Core::getT('vendor_category'), 'ids = '. $aRow['ids']);
            }
            else {
                $aExits[$sKey] = 1;
            }
        }
    }
}
?>
