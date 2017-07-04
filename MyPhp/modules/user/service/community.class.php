<?php
class User_Service_Community extends Service
{   
    public function __construct()
    {
        
    }
    
    public function getId()
    {
        $sPath = 'cong-dong';
        $aCategories = array();
        $iCategoryId = $this->database()->select('id')
            ->from(Core::getT('category'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND path = \''. $this->database()->escape($sPath).'\'')
            ->execute('getField');
        if ($iCategoryId > 0)
            return $iCategoryId;
        return 0;
    }
    
    public function getCategory($aParam =array())
    {
        $oSession = Core::getLib('session');
        
        $sPath = 'cong-dong';
        $aCategories = array();
        $aRow = $this->database()->select('id, parent_list, child_list')
            ->from(Core::getT('category'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND path = \''. $this->database()->escape($sPath).'\'')
            ->execute('getRow');
        $sChildList = '';
        $iId = -1;
        if (isset($aRow['id'])) {
            $sChildList = $aRow['child_list'];
            $iId = $aRow['id'];
        }
        if (!empty($sChildList)) {
            $aRows = $this->database()->select('id, name, parent_id, parent_list, path, detail_path')
                ->from(Core::getT('category'))
                ->where('status =1 AND id IN ('.$sChildList.')')
                ->order('position DESC')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                //bỏ trường hợp là chính đề tài cộng đồng
                if ($aRow['parent_id'] == -1) {
                    continue;
                }
                //những đề tài có parent_id là id cộng đồng thì bằng -1
                if ($aRow['parent_id'] == $iId) {
                    $aRow['parent_id'] = -1;
                }
                $aCategories[$aRow['parent_id']][$aRow['id']] = $aRow;
            }
        }
        //re-sort to display if neccessary
        //get breadcrumb
        $iIdCurrent = -1;
        if (isset($aParam['id']) && !empty($aParam['id'])) {
            $iIdCurrent = $aParam['id'];
        }
        $sParentList = '';
        if ($iIdCurrent != -1) {
            $aRow = $this->database()->select('id, parent_list')
                ->from(Core::getT('category'))
                ->where('status =1 AND id ='.(int)$iIdCurrent)
                ->execute('getRow');
            if(isset($aRow['parent_list'])) {
                $sParentList = $aRow['parent_list'];
            }
        }
        
        $aBreadCrumbList = array();
        if (!empty($sParentList)) {
            // lay ten cac de tai cha
            $aRows = $this->database()->select('id, name, parent_id, detail_path')
                ->from(Core::getT('category'))
                ->where('id IN ('.$sParentList.')')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                
                $sNameHtml = addslashes($aRow['name']);
                $aParentCategory[] = array (
                    $aRow['id'],
                    $aRow['name'],
                    $aRow['parent_id'],
                    $sNameHtml,
                    $aRow['detail_path']
                );
            }
            
            $iTmp = $iIdCurrent;
            $bIsExist = false;
            while (!$bIsExist) {
                $bIsExist = true;
                foreach ($aParentCategory as $Val) {
                    if ($Val[0] == $iTmp) {
                        $bIsExist = false;
                        $aBreadCrumbList[] = array(
                            'id' => $Val[0],
                            'parent_id' => $Val[2],
                            'name' => $Val[1],
                            'path' => $Val[4],
                        );
                        $iTmp = $Val[2];
                        if ($iTmp == '-1') {
                            $bIsExist = true;
                            break;
                        }
                    }
                }
            }
            // sắp xếp để đưa Đề tài đầu lên trước
            krsort($aBreadCrumbList);
            //sắp xếp lại index (key) autoincrement
            $aBreadCrumbList = array_values($aBreadCrumbList);
        }
        
        return array(
            'breadcrumb' => $aBreadCrumbList,
            'category' => $aCategories
        );
    }
     
    public function get($aParam = array())
    {
        $oSession = Core::getLib('session');
        $sDomainName = Core::getDomainName();
        $iDomainId = Core::getDomainId();
        //$iUserId = 2338;
        $iUserId = Core::getUserId();
        $aSessionPermission = $oSession->get('session-permission');
        
        //category_id :id community or child in community
        $iId = -1;
        if (isset($aParam['id']) && $aParam['id'] > 0) {
            $iId = $aParam['id'];
        }
        if ($iId == -1) {
            return array();
        }
        
        //get category
        $aCategory = $this->database()->select('child_list, parent_list')
            ->from(Core::getT('category'))
            ->where('domain_id ='.(int)$iDomainId.' AND id ='.(int)$iId)
            ->execute('getRow'); 
        if (!isset($aCategory['child_list'])) {
            return array();
        }
        
        $sChildList = $aCategory['child_list'];
        $sParentList = $aCategory['parent_list'];
        //get breadcrumb
        $aBreadCrumbList = array();
        if (!empty($sParentList)) {
            // lay ten cac de tai cha
            $aRows = $this->database()->select('id, name, parent_id, detail_path')
                ->from(Core::getT('category'))
                ->where('id IN ('.$sParentList.')')
                ->execute('getRows');
            foreach ($aRows as $aRow) {
                
                $sNameHtml = addslashes($aRow['name']);
                $aParentCategory[] = array (
                    $aRow['id'],
                    $aRow['name'],
                    $aRow['parent_id'],
                    $sNameHtml,
                    $aRow['detail_path']
                );
            }
            
            $iTmp = $iId;
            $bIsExist = false;
            while (!$bIsExist) {
                $bIsExist = true;
                foreach ($aParentCategory as $Val) {
                    if ($Val[0] == $iTmp) {
                        $bIsExist = false;
                        $aBreadCrumbList[] = array(
                            'id' => $Val[0],
                            'parent_id' => $Val[2],
                            'name' => $Val[1],
                            'path' => $Val[4],
                        );
                        $iTmp = $Val[2];
                        if ($iTmp == '-1') {
                            $bIsExist = true;
                            break;
                        }
                    }
                }
            }
            // sắp xếp để đưa Đề tài đầu lên trước
            krsort($aBreadCrumbList);
            //sắp xếp lại index (key) autoincrement
            $aBreadCrumbList = array_values($aBreadCrumbList);
        }
        
        //get info article in category list
        $iPage = 1;
        $iPageSize = 10;
        if(isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if(isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 10) {
                $iPageSize = 10;
            }    
        }
        
        //get aticle latest in category community
        $aTopLastArticles = $this->getArticles(array(
            'category_id' => $iId,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'order' => 'time'
        ));
        $aTopCommentArticles = $this->getArticles(array(
            'category_id' => $iId,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'order' => 'comment'
        ));
        $aTopLikeArticles = $this->getArticles(array(
            'category_id' => $iId,
            'page' => $iPage,
            'page_size' => $iPageSize,
            'order' => 'like'
        ));
        
        return array (
            'breadcrumb' => $aBreadCrumbList,
            'top_last' => $aTopLastArticles,
            'top_comment' => $aTopCommentArticles,
            'top_like' => $aTopLikeArticles,
        );
        
    }
    
    public function getComments($aParam = array())
    {
        //check article id list input
        $aArticleIdList = array();
        if (isset($aParam['article_list']) && !empty($aParam['article_list'])) {
            $aArticleIdList = $aParam['article_list'];
        }
        if (empty($aArticleIdList)) {
            return array();
        }
        //end
        
        $iPage = 1;
        $iPageSize = 20;
        if(isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if(isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 20;
            }    
        }
        
        $oSession = Core::getLib('session');
        $sDomainName = Core::getDomainName();
        $iDomainId = Core::getDomainId();
        //$iUserId = 2338;
        $iUserId = Core::getUserId();
        $aSessionPermission = $oSession->get('session-permission');
        $sConds = ' AND status = 1';
        if ($aSessionPermission['manage_comment'] == 1) {
            $sConds = ' AND (status = 1 OR status = 0)';
        }
        
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('comment'))
            ->where('domain_id = '.(int)$iDomainId.$sConds.' AND type_name =\'article\''.' AND type_id IN ('.implode(',', $aArticleIdList).')')
            ->execute('getField');
        if ($iCnt > 0) {
            //get coment : $aComments['article_id']= comments
            $aComments = array();
            $aUserId = array();
             $aRows = $this->database()->select('id, content, time, total_like, total_comment, user_id, user_fullname, extend, type_name, type_id, status')
                ->from(Core::getT('comment'))
                ->where('domain_id = '.(int)$iDomainId.$sConds.' AND type_name =\'article\''.' AND type_id IN ('.implode(',', $aArticleIdList).')')
                ->execute('getRows');
             foreach ($aRows as $aRow) {
                 if (!in_array($aRow['user_id'], $aUserId)) {
                     $aUserId[] = $aRow['user_id'];
                 }
             }
             //get user info
             $aMappingUser = array();
             if (!empty($aUserId)) {
                $aMappingUser = $this->getUserInfo(array(
                    'user_list' => $aUserId,
                ));
             }
             foreach ($aRows as $aRow) {
                 $aTmp = array();
                 $aTmp['id'] = $aRow['id'];
                 $aTmp['content'] = $aRow['content'];
                 //convert time to GMT+7
                 $aRow['time'] = Core::getLib('date')->convertFromGmt($aRow['time'], Core::getParam('core.default_time_zone_offset'));
                 $aTmp['time'] = date("d/m/Y H:i",$aRow['time']);
                 
                 $aTmp['total_like'] = $aRow['total_like'];
                 $aTmp['total_comment'] = $aRow['total_comment'];
                 
                 $aTmp['extend'] = $aRow['extend'];
                 $aTmp['type_name'] = $aRow['type_name'];
                 $aTmp['type_id'] = $aRow['type_id'];
                 $aTmp['status'] = $aRow['status'];
                 //user info
                 $aTmp['user'] = $aMappingUser[$aRow['user_id']];
                 $aComments[$aRow['type_id']][$aRow['id']] = $aTmp;
             }
             return $aComments;
        }
        return array();
    }
    
    public function getUserInfo($aParam = array())
    {
        $aUserId = array();
        if (isset($aParam['user_list']) && !empty($aParam['user_list'])) {
            $aUserId = $aParam['user_list'];
        }
        $aMappingUser = array();
        if (!empty($aUserId)) {
            $aTmps = $this->database()->select('id, code, username, fullname, profile_image, sex, is_verify')
                ->from(Core::getT('user'))
                ->where('id IN ('.implode(',', $aUserId).')')
                ->execute('getRows');
            foreach ($aTmps as $sKey => $aTmp) {
                if (empty($aTmp['profile_image'])) {
                    //set default profile image
                    if($aTmp['sex'] == 2)
                        $sFileName = 'female.png';
                    else
                        $sFileName = 'male.png';
                    $aTmp['profile_image'] =  Core::getParam('core.image_path'). 'styles/web/global/images/noimage/'.$sFileName;
                }
                
                if (empty($aTmp['fullname'])) {
                    $aTmp['fullname'] = $aTmp['username'];
                }
                $aMappingUser[$aTmp['id']] = $aTmp;
            }
        }
        return $aMappingUser;
    }
    
    public function getContentArticle($aParam = array())
    {
        $iId = -1;
        if (isset($aParam['id']) && !empty($aParam['id'])) {
            $iId = $aParam['id'];
        }
        if ($iId < 1) {
            return '';
        }
        
        // nếu bài viết nội dung rỗng, kiểm tra xem có tồn tại thẻ nội dung bài viết
        $aTabContent = array();
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('tab_article'))
            ->where('status != 2 AND article_id = '.(int)$iId.' AND tab_id = 0')
            ->execute('getField');
        if ($iCnt < 1) {
            /*
                Bỏ Join củ
                + Lấy thông tin thẻ đề tài order vị trí
                + Lấy thông tin Thẻ
            */
            $aTabContentMapping = array();
            
            $aRows = $this->database()->select('tab_id')
                ->from(Core::getT('tab_category'))
                ->where('status != 2 AND category_id = '.(int)$iCategoryId)
                ->order('position DESC')
                ->execute('getRows');
            if (count($aRows) > 0) {
                $aTmp = array();
                foreach ($aRows as $aRow) {
                    $aTmp[] = $aRow['tab_id'];
                }
                // kiem tra $aTmp rong hay ko
                $aTabs = $this->database()->select('id, name, name_code')
                    ->from(Core::getT('tab'))
                    ->where('id IN ('.implode(',', $aTmp).')')
                    ->execute('getRows');
                $aMappingTab = array();
                foreach ($aTabs as $aTab) {
                    $aMappingTab[$aTab['id']] = $aTab;
                }
                
                foreach ($aRows as $aRow) {
                    // xử lý tên thẻ
                    $aRow['name'] = str_replace(
                        array(
                            '{article_name}',
                            '{category_name}',
                        ),
                        array(
                            $sArticleName,
                            $sCategoryName
                        ),
                        $aMappingTab[$aRow['tab_id']]['name']
                    );
                    // end
                    $aTabContent[$aMappingTab[$aRow['tab_id']]['name_code']] = array(
                        'name' => $aRow['name'],
                        'id' => $aRow['tab_id'],
                        'status' => 0
                    );
                    $aTabContentMapping[$aRow['tab_id']] = $aMappingTab[$aRow['tab_id']]['name_code'];
                }
            }
            if (!empty($aTabContent)) {
                /*
                    Bỏ Join củ
                    + Lấy thông tin thẻ bài viết
                    + Lấy thông tin thẻ nội dung
                */
                $aTabArticles = $this->database()->select('tab_id, tab_content_id')
                    ->from(Core::getT('tab_article'))
                    ->where('status != 2 AND article_id ='.(int)$iId.' AND tab_id IN ('.implode(',', array_keys($aTabContentMapping)).')')
                    ->execute('getRows');
                if (count($aTabArticles) > 0) {
                    $aTmp = array();
                    foreach ($aTabArticles as $aTabArticle) {
                        $aTmp[] = $aTabArticle['tab_content_id'];
                    }
                    $aTabContents = $this->database()->select('id, content')
                        ->from(Core::getT('tab_content'))
                        ->where('id IN ('.implode(',', $aTmp).')')
                        ->execute('getRows');
                    $aMappingTab = array();
                    foreach ($aTabContents as $aRow) {
                        $aMappingTab[$aRow['id']] = $aRow['content'];
                    }
                    
                    foreach ($aTabArticles as $aTabArticle) {
                        $aTabArticle['content'] = $aMappingTab[$aTabArticle['tab_content_id']];
                        if (!empty($aTabArticle['content'])) {
                            $aTabArticle['content'] = Core::getLib('input')->formatBbcode($aTabArticle['content']);
                            $aTabContent[$aTabContentMapping[$aTabArticle['tab_id']]]['status'] = 1;
                        }
                        $aTabContent[$aTabContentMapping[$aTabArticle['tab_id']]]['content'] = $aTabArticle['content'];
                    }
                }
                unset($aTabContentMapping);
            }
            if (isset($aTabContent['content']['name'])) {
                return $aTabContent['content']['content'];
            }
        }
        else {
            /*
                Bỏ Join củ
                + Lấy thông tin thẻ bài viết
                + Lấy thông tin thẻ nội dung
            */
            $aTabArticle = $this->database()->select('tab_id, tab_content_id')
                    ->from(Core::getT('tab_article'))
                    ->where('status != 2 AND article_id ='.(int)$iId)
                    ->execute('getRow');
            if (isset($aTabArticle['tab_id'])) {
                $aTmp = $this->database()->select('id, content')
                    ->from(Core::getT('tab_content'))
                    ->where('id ='.(int)$aTabArticle['tab_content_id'])
                    ->execute('getRow');;
                if (isset($aTmp['id'])) {
                    $aTmp['content'] = Core::getLib('input')->formatBbcode($aTmp['content']);
                    return $aTmp['content'];
                }
            }
        }
        return '';
    }
    
    public function getArticles($aParam = array())
    {
        $oSession = Core::getLib('session');
        
        //category_id :id community or child in community
        $iCategoryId = -1;
        if (isset($aParam['category_id']) && $aParam['category_id'] > 0) {
            $iCategoryId = $aParam['category_id'];
        }
        if ($iCategoryId < 1) {
            return array();
        }
        $iPage = 1;
        $iPageSize = 20;
        if (isset($aParam['page'])) {
            $iPage = $aParam['page'];
            if ($iPage < 1) {
                $iPage = 1;
            }
        }
        
        if (isset($aParam['page_size'])) {
            $iPageSize = $aParam['page_size'];
            if ($iPageSize < 1 || $iPageSize > 30) {
                $iPageSize = 20;
            }    
        }
        //order
        $sOrder = 'time DESC';
        if (isset($aParam['order'])) {
            if ($aParam['order'] == 'time') {
                $sOrder = 'time DESC';
            }
            elseif ($aParam['order'] == 'comment') {
                $sOrder = 'total_comment, time DESC';
            }
            elseif ($aParam['order'] == 'like') {
                $sOrder = 'total_like, time DESC';
            }
        }
        //get category list
        $aCategory = $this->database()->select('child_list')
            ->from(Core::getT('category'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND id ='.(int)$iCategoryId)
            ->execute('getRow');
        if (!isset($aCategory['child_list'])) {
            return array();
        }
        $sChildList = $aCategory['child_list'];
        $aArticles = array();
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('article'))
            ->where('domain_id ='.(int)Core::getDomainId().' AND status != 2 AND category_id IN ('.$sChildList.')')
            ->execute('getField');
        if ($iCnt > 0) {
            $aRows = $this->database()->select('id, title, description, user_id, user_fullname, time, update_time, total_view, total_comment, total_like, path, detail_path, position, category_id')
                ->from(Core::getT('article'))
                ->where('domain_id ='.(int)Core::getDomainId().' AND status != 2 AND category_id IN ('.$sChildList.')')
                ->order($sOrder)
                ->limit($iPage, $iPageSize, $iCnt)
                ->execute('getRows');
            if (count($aRows) > 0) {
                $aUserId = array();
                $aArticleId = array();
                foreach ($aRows as $aRow) {
                    if (!in_array($aRow['user_id'], $aUserId)) {
                        $aUserId[] = $aRow['user_id'];
                    }
                    if (!in_array($aRow['id'], $aArticleId)) {
                        $aArticleId[] = $aRow['id'];
                    }
                }
                //get user info
                $aMappingUser = array();
                if (!empty($aUserId)) {
                    $aMappingUser = $this->getUserInfo(array(
                        'user_list' => $aUserId,
                    ));
                }
                //get detail comment
                $aMappingContent = array();
                if (!empty($aArticleId)) {
                    $aMappingComment = $this->getComments(array(
                        'article_list' => $aArticleId,
                    ));
                }
                
                //maping to return data
                foreach ($aRows as $aRow) {
                    $aTmp = array();
                    $aTmp['id'] = $aRow['id'];
                    $aTmp['title'] = $aRow['title'];
                    $aTmp['discription'] = $aRow['discription'];
                    //convert time to GMT+7
                    $aRow['time'] = Core::getLib('date')->convertFromGmt($aRow['time'], Core::getParam('core.default_time_zone_offset'));
                    $aRow['update_time'] = Core::getLib('date')->convertFromGmt($aRow['update_time'], Core::getParam('core.default_time_zone_offset'));
                    
                    $aTmp['time'] = date("d/m/Y H:i",$aRow['time']);
                    $aTmp['update_time'] = date("d/m/Y H:i",$aRow['update_time']);
                    
                    $aTmp['total_view'] = $aRow['total_view'];
                    $aTmp['total_comment'] = $aRow['total_comment'];
                    $aTmp['total_like'] = $aRow['total_like'];
                    
                    $aTmp['path'] = $aRow['path'];
                    $aTmp['detail_path'] = $aRow['detail_path'];
                    $aTmp['position'] = $aRow['position'];
                    $aTmp['category_id'] = $aRow['category_id'];
                    
                    $aTmp['user'] = $aMappingUser[$aRow['user_id']];
                    
                    $aTmp['comment'] = $aMappingComment[$aRow['id']];
                    
                    //get content
                    $aTmp['content'] = $this->getContentArticle(array(
                        'id' => $aRow['id'],
                    ));
                    
                    $aArticles[$aRow['id']] = $aTmp;
                }
            }
        }
        
        return array (
            'page' => $iPage,
            'page_size' => $iPageSize,
            'total' => $iCnt,
            'data' => $aArticles,
        );
    }
    
}
?>
