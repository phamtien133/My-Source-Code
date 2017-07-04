<?php
class User_Component_Controller_Group_Index extends Component
{
    public function process()
    {
        if (!Core::isUser())
            return false;
        
		$sReq3 = $this->request()->get('req3', '');
        if (!empty($sReq3) && ($sReq3 == 'add' || $sReq3 == 'edit')) {
            return Core::getLib('module')->setController('user.group.add');
        }
        $iParentId = 0;
		if (is_int($sReq3)) {
            $iParentId = (int) $sReq3;
        }
        
        $iPage = $this->request()->get('page', 1);
        $iPageSize = $this->request()->get('limit', 15);
        $sOrder = $this->request()->get('order', 'id DESC');
        
		$sLinkFull = '/user/group';
        $sPaginationUrl = '';
        if ($iPageSize != 15) {
            $sLinkFull .= '/?limit='.$iPageSize;
            $sPaginationUrl .= '&limit='.$iPageSize;
        }
        
        // xử lý tìm kiếm
        $sKeyword = $this->request()->get('q', '');
        if (!empty($sKeyword)) {
            $sKeyword = urldecode($sKeyword);
            $sKeyword = Core::getLib('input')->removeXSS($sKeyword);
            $sKeyword = trim($sKeyword);
            // giới hạn số lượng ký tự trong từ khóa
            if(mb_strlen($sKeyword) > 100) 
                $sKeyword = '';
            
            if (!empty($sKeyword)) {
                $sPaginationUrl .= '&q='.urlencode($sKeyword);
                $sLinkFull .= '&q='.urlencode($sKeyword);
            }
        }
        
        // xử lý sắp xếp
        $sOrder = $this->request()->get('order', '');
        if (!empty($sOrder)) {
            if ($sOrder == 2 || $sOrder == 3) {
                $sOrder = ' name ASC';
            }
            elseif ($sOrder == 4 || $sOrder == 5){
                $sOrder = ' time ASC';
            }
            else{
                $sOrder = ' id DESC';
            }
            if ($sOrder != ' id DESC') {
                $sPaginationUrl .= '&order='.$sOrder;
                $sLinkFull .= '&order='.$sOrder;
            }
        }
        // thực hiện lấy dữ liệu
        $aResult = Core::getService('user.group')->get(array(
            'page' => $iPage,
            'page_size' => $iPageSize,
            'order' => $sOrder,
            'keyword' => $sKeyword,
            'parent_id' => $iParentId
        ));
        $iTotal = 0;
        $aLists = array();
        if ($aResult['status'] == 'success') {
            $iTotal = $aResult['data']['total'];
            $aLists = $aResult['data']['list'];
        }

        $sDir = $_SERVER['REQUEST_URI'];
        $aTmps = explode('/', $sDir, 3);
        $sDir = '/'.$aTmps[1].'/group/';
        $sPaginationUrl = $sDir .'?'.$sPaginationUrl;
        
        $iTotalPage =ceil($iTotal/$iPageSize);
        
        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'order');
        
        $page['title'] = Core::getPhrase('language_nhom');
        $this->template()->setTitle($page['title']);
        $this->template()->setHeader(array(
            'usergroup.js' => 'site_script',
        ));
		$this->template()->assign(array(
            'iPage' => $iPage,
			'iTotalPage' => $iTotalPage,
			'sLinkSort' => $sLinkSort,
            'aLists' => $aLists,
            'sPaginationUrl' => $sPaginationUrl,
            'iParentId' => $iParentId
		));
    }
}
?>