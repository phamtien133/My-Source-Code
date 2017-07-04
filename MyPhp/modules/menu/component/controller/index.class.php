<?php
class Menu_Component_Controller_Index extends Component
{
    public function process()
    {
		$this->database = Core::getLib('database');
		$oSession = Core::getLib('session');
		$aVals = Core::getLib('request')->getRequests();

        if (isset($aVals['req2']) && !empty($aVals['req2'])) {
            if ($aVals['req2'] == 'edit') {
                return Core::getLib('module')->setController('menu.add');
            }
            elseif ($aVals['req2'] == 'list') {
                return Core::getLib('module')->setController('menu.list');
            }
            elseif ($aVals['req2'] == 'detail') {
                return Core::getLib('module')->setController('menu.detail');
            }
        }

        //if (!isset($aVals['req2']) || empty($aVals['req2'])) {
//            $aVals['limit'] = 0;
//            $aVals['q'] = '';
//        }

		$output = array(
			'menu',
			'limit',
			'tu_khoa',
			'sap_xep',
			'duong_dan_phan_trang',
			'trang_hien_tai',
			'tong_cong',
			'tong_trang',
			'trang_bat_dau',
			'status',
            'sLinkFull',
            'sLinkSort',
		);


		$page['title'] = Core::getPhrase('language_quan-ly-menu');

        $iPerManageMenu = $oSession->getArray('session-permission', 'create_menu');
        $iPerEditMenu = $oSession->getArray('session-permission', 'edit_menu');

		$tmp = $iPerManageMenu;

		if ($tmp != 1)
            $tmp = $iPerEditMenu;
		// check xem có quyền chi tiết ko
		if ($tmp != 1 && empty($tmp)) {
			$error = Core::getPhrase('language_khong-co-quyen-truy-cap').'(1)';
		}

        $sLinkFull = '/menu';

		if(!$error)
		{
			if($id==0)
			{
				// lấy tổng số bài viết muốn show
				$limit = $_GET['limit']*1;
				if($limit < 1) $limit = 15;
                if(!empty($limit))
                {
                    $sLinkFull .= '/?limit='.$limit;
                    $duong_dan_phan_trang .= '&limit='.$limit;
                }


				// tìm kiếm
				$tu_khoa = '';
				if(isset($_GET["q"]) && !empty($_GET["q"]))
				{
					$tu_khoa = $_GET["q"];
					$tu_khoa = urldecode($tu_khoa);
					$tu_khoa = trim(Core::getLib('input')->removeXSS($tu_khoa));
					if(mb_strlen($tu_khoa) > 100) $tu_khoa = '';
				}
				if(!empty($tu_khoa))
				{
					$query .= ' AND (name LIKE "%'.addslashes($tu_khoa).'%" OR name_code LIKE "%'.addslashes($tu_khoa).'%")';
					//$query .= ' AND MATCH(tieu_de) AGAINST ("%'.addslashes($tu_khoa).'%" IN BOOLEAN MODE)';
					$duong_dan_phan_trang .= '&q='.urlencode($tu_khoa);
                    $sLinkFull .= '&q='.urlencode($tu_khoa);
				}

                $order = '';
                $sap_xep = $_GET['sap_xep'];

                /*
                    Quy định sắp xếp:
                    0: id DESC (mặc định)
                    1: id ASC
                    2: name DESC
                    3: name ASC

                */
                if($sap_xep == 1) $order = ' id ASC, BINARY time DESC';
                elseif($sap_xep == 2) $order = ' name DESC, BINARY time DESC';
                elseif ($sap_xep == 3) $order = ' name ASC, BINARY time DESC';
                else {
                    $order = 'id DESC,BINARY time DESC';
                }
                if ($sap_xep > 0) {
                    $duong_dan_phan_trang .= '&sap_xep='.$sap_xep;
                    $sLinkFull .= '&sap_xep='.$sap_xep;
                }

				$trang_hien_tai=addslashes($_GET["page"])*1;
				if($quyen_sql != '') $quyen_sql = ' AND '.$quyen_sql;

				if($iPerManageMenu != 1)
				{
					$query .= ' AND id IN ('.$iPerEditMenu.')';
				}
                $dir = $_SERVER['REQUEST_URI'];
                $tmps = explode('/', $dir, 3);
                $dir = '/'.$tmps[1].'/';

                $duong_dan_phan_trang = $dir.'?'.$duong_dan_phan_trang;

				$aRows = $this->database->select('count(id)')
						->from(Core::getT('menu'))
						->where('domain_id ='. Core::getDomainId().' AND status != 2 '.$query)
						->execute('getField');

				$tong_cong = $aRows*1;
				$tong_trang=ceil($tong_cong/$limit);
				if(!@$trang_hien_tai) $trang_hien_tai=1;
				$trang_bat_dau = ($trang_hien_tai-1)*$limit;

				$aRows = $this->database->select('*')
					->from(Core::getT('menu'))
					->where('domain_id ='. Core::getDomainId().' AND status != 2 '.$query)
					->order($order)
                    ->limit($trang_hien_tai, $limit, $tong_cong)
					->execute('getRows');

				foreach ($aRows as $rows) {
					if($rows["status"]==0) $tmp = 'status_no';
					else $tmp = 'status_yes';
					$rows['status_text'] = $tmp;
					$menu[] = array(
						'id' => $rows["id"],
						'name' => $rows["name"],
						'code' => $rows["code"],
						'status' => $rows["status"],
						'status_text' => $rows["status_text"]
					);
				}
				$status = 1;
			}
		}
		else $status=4;

        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sap_xep');
		foreach($output as $key)
		{
			$data[$key] = $$key;
		}

        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));

        $this->template()->setTitle($page['title']);
		$this->template()->assign(array(
			'output' => $output,
			'data' => $data,
		));
    }
}
?>