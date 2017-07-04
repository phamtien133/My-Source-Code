<?php
class User_Component_Controller_Index extends Component
{
    public function process()
    {
		$this->database = Core::getLib('database');
		$oSession = Core::getLib('session');
		$aVals = Core::getLib('request')->getRequests();

        $sType = '';
        if (isset($aVals['type'])) {
            $sType = $aVals['type'];
        }
        $sLinkFull = '/user/index';
        $sQueryType = '';

        //Lấy danh sách những user được phân quyền vào trang admin
        $aRows = $this->database->select('object_id')
                        ->from(Core::getT('permission'))
                        ->where('priority > 0 AND domain_id ='.Core::getDomainId())
                        ->execute('getRows');
        $aListId = array();
        foreach ($aRows as $aRow) {
            if (!in_array($aRow['object_id'], $aListId)) {
                $aListId[] = $aRow['object_id'];
            }
        }

        if ($sType == 'user') {
            $sType = 'user';
            $sLinkFull .= '/?type=user';
            $sQueryType = ' AND user_group_id > 0';
            //hoặc trường hợp được phân quyền vào trang admin
            if (!empty($aListId)) {
                $sQueryType = 'AND (user_group_id > 0 OR id IN ('.implode(',', $aListId).'))';
            }
        }
        else {
            $sType = 'member';
            $sLinkFull .= '/?type=member';
            //$sQueryType = ' AND user_group_id = 0';
            //loại đi những user đã được phần quyền vào trang admin
            if (!empty($aListId)) {
                //$sQueryType = 'AND (user_group_id = 0 && id NOT IN ('.implode(',', $aListId).'))';
            }

        }

        $aPermission = $oSession->get('session-permission');

        $errors = array();
		if($oSession->getArray('session-permission', 'permission_user') != 1)
		{
			$errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
		}

        if (!empty($aVals['req2'])) {
			if($aVals['req2'] == 'edit') {
            	return Core::getLib('module')->setController('user.add');
			}
			else if($aVals['req2'] == 'permission') {
            	return Core::getLib('module')->setController('user.permission');
			}
			else if($aVals['req2'] == 'group') {
            	return Core::getLib('module')->setController('user.group.index');
			}
        }

		/*

			Chức năng sửa thành viên
				Với openid, ko cho đổi mật khẩu, tuy nhiên, cho đổi tên truy cập

		*/

		$page['title']=Core::getPhrase('language_quan-ly-thanh-vien');
		if(empty($errors))
		{
			$query = '';
			// lấy tổng số bài viết muốn show
			$limit = $_GET['limit']*1;
			if($limit < 1) $limit = 15;

			// tìm kiếm
			$tu_khoa = '';
			if(isset($_GET["q"]))
			{
				$tu_khoa = $_GET["q"];
				$tu_khoa = urldecode($tu_khoa);
				$tu_khoa = trim(Core::getLib('input')->removeXSS($tu_khoa));
				if(mb_strlen($tu_khoa) > 100) $tu_khoa = '';
			}
			if(!empty($tu_khoa))
			{
                $sLinkFull .= '&q='.$tu_khoa;
				$query .= ' AND (username LIKE "%'.addslashes($tu_khoa).'%" OR email LIKE "%'.addslashes($tu_khoa).'%" OR phone_number LIKE "%'.addslashes($tu_khoa).'%")';
				//$query .= ' AND MATCH(tieu_de) AGAINST ("%'.addslashes($tu_khoa).'%" IN BOOLEAN MODE)';
				$duong_dan_phan_trang .= '&q='.urlencode($tu_khoa);
			}
			if(!empty($limit))
			{
				$duong_dan_phan_trang .= '&limit='.$limit;
                $sLinkFull .= '&limit='.$limit;
			}
			$tmp = '';
			$sap_xep = $_GET['sap_xep'];

            /*
                Quy định sắp xếp:
                0: username ASC (mặc định)
                1: username DESC
                2: email ASC
                3: email DESC
                4: sdt ASC
                5: sdt DESC
                6: join_time ASC
                7: join_time DESC

            */

			if($sap_xep == 1) $tmp = ' username DESC';
            elseif($sap_xep == 2) $tmp = ' email ASC';
            elseif($sap_xep == 3) $tmp = ' email DESC';
            elseif($sap_xep == 4) $tmp = ' phone_number ASC';
            elseif($sap_xep == 5) $tmp = ' phone_number DESC';
            elseif($sap_xep == 6) $tmp = ' join_time ASC';
			elseif($sap_xep == 7) $tmp = ' join_time DESC';
			else $tmp = ' username ASC';
			if ($sap_xep > 0) {
                $sLinkFull .= '&sap_xep='.$sap_xep;
            }
			$trang_hien_tai=addslashes($_GET["page"])*1;

            if(!@$trang_hien_tai) $trang_hien_tai=1;
            $trang_bat_dau = ($trang_hien_tai-1)*$limit;

			$iCnt = $this->database->select('count(id)')
					->from(Core::getT('user'))
					->where('domain_id ='. Core::getDomainId() .' AND status != 2 '.$query.$sQueryType)
					->execute('getField');

            $dir = $_SERVER['REQUEST_URI'];
            $tmps = explode('/', $dir, 3);
            $dir = '/'.$tmps[1].'/index/';

			$danh_sach = array(
				'0' => 'none',
				'1' => 'google',
				'2' => 'yahoo',
				'3' => 'facebook',
				'4' => 'twitter',
				'5' => 'instagram',
			);
            $aRows = array();
            if (Core::getParam('core.main_server') == 'sup.') {
                //Lấy danh sách thành viên từ vendor_permission, lấy danh sách tất cả thành viên có nhóm thành viên được phân quyền
                $iVendorId = $oSession->get('session-vendor');
                $aPermissionVendors = $this->database->select('id, vendor_id, object_id, object_type')
                    ->from(Core::getT('vendor_permission'))
                    ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND vendor_id ='.$iVendorId)
                    ->execute('getRows');

                $aGroups = array();
                $aUserId = array();
                foreach ($aPermissionVendors as $aPermissionVendor) {
                    if ($aPermissionVendor['object_type'] == 0) {
                        if(!in_array($aPermissionVendor['object_id'], $aUserId)) {
                            $aUserId[] = $aPermissionVendor['object_id'];
                        }
                    }
                    elseif ($aPermissionVendor['object_type'] == 1) {
                        if(!in_array($aPermissionVendor['object_id'], $aGroups)) {
                            $aGroups[] = $aPermissionVendor['object_id'];
                        }
                    }
                }
                if (!empty($aGroups)) {
                    //Lấy danh sách thành viên từ nhóm từ viên
                    $aTmps = $this->database->select('id')
                        ->from(Core::getT('user'))
                        ->where('domain_id ='.Core::getDomainId().' AND status != 2 AND user_group_id IN ('.implode(',', $aGroups).')')
                        ->execute('getRows');

                    foreach ($aTmps as $aTmp) {
                        if (!in_array($aTmp['id'], $aUserId)) {
                            $aUserId[] = $aTmp['id'];
                        }
                    }
                }
                $iCnt = 0;
                if (!empty($aUserId)) {
                    $iCnt = $this->database->select('count(id)')
                        ->from(Core::getT('user'))
                        ->where('domain_id ='. Core::getDomainId() .' AND status != 2 '.$query.' AND id IN ('.implode(',', $aUserId).')')
                        ->execute('getField');

                    $aRows = $this->database->select('id stt, code ma_so, username ten_truy_cap, email hop_thu, password mat_khau, openid, join_time ngay_tham_gia, status trang_thai, address, sex')
                        ->from(Core::getT('user'))
                        ->where('domain_id ='. Core::getDomainId() .' AND status != 2 '.$query.' AND id IN ('.implode(',', $aUserId).')')
                        ->order($tmp)
                        ->limit($trang_bat_dau.', '.$limit)
                        ->execute('getRows');
                }


            }
            else {
                // với trường hợp tồn tại email, openid = 0, và ko tồn tại tên truy cập là dùng chức năng Đăng nhập nhanh
                $aRows = $this->database->select('id stt, code ma_so, username ten_truy_cap, email hop_thu, password mat_khau, openid, join_time ngay_tham_gia, status trang_thai, address, profile_image, phone_number, last_visit')
                    ->from(Core::getT('user'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 '.$query.$sQueryType)
                    ->order($tmp)
                    ->limit($trang_bat_dau.', '.$limit)
                    ->execute('getRows');
            }

            $duong_dan_phan_trang = $dir.'?'.'type='.$sType.$duong_dan_phan_trang;

            $tong_cong = $iCnt*1;
            $tong_trang=ceil($tong_cong/$limit);


            $_SESSION[$type]=1;
            unset($stt);

			foreach ($aRows as $rows) {
				$stt[] = $rows["stt"];
				$ma_so[] = $rows["ma_so"];
				$thoi_gian_dang_ky[] = date('d-m-Y', $rows["ngay_tham_gia"]);
                if (empty($rows["hop_thu"])) {
                    $rows["hop_thu"] = 'Chưa có T.T';
                }
				$hop_thu[] = $rows["hop_thu"];

                if (empty($rows["phone_number"])) {
                    $rows["phone_number"] = 'Chưa có T.T';
                }
                $sdt[] = $rows["phone_number"];
                $last_visit[] =date('d-m-Y', $rows["last_visit"]);

                $trang_thai[] = $rows["trang_thai"];
                switch($rows["trang_thai"]) {
                    case 0:
                    case 1:
                        $trang_thai_text[] = 'Đang kích hoạt';
                        break;
                    case 3:
                        $trang_thai_text[] = 'Cấm truy cập';
                        break;
                    default:
                        $trang_thai_text[] = 'Đang kích hoạt';
                        break;
                }

				$openid[] = $danh_sach[$rows['openid']];
				$ten[] = $rows["ten_truy_cap"];
                if (empty($rows['address'])) {
                    $aAddress[] = 'Chưa có T.T';
                }
                else {
                    $aAddress[] = $rows['address'];
                }

                if (empty($rows['profile_image'])) {
                    if($rows['sex'] == 2)
                        $sFileName = 'female.png';
                    else
                        $sFileName = 'male.png';
                    $aProfileImage[]=  Core::getParam('core.image_path'). 'styles/web/global/images/noimage/'.$sFileName;
                }
                else {
                    $aProfileImage[] = $rows['profile_image'];
                }
			}
			$status=1;
		}
		else $status=4;

		$output = array(
			'menu',
			'limit',
			'tu_khoa',
			'sap_xep',
			'duong_dan',
			'duong_dan_phan_trang',
			'trang_hien_tai',
			'tong_cong',
			'tong_trang',
			'trang_bat_dau',
			'status',
			'stt',
            'sdt',
			'ma_so',
			'thoi_gian_dang_ky',
			'hop_thu',
			'trang_thai',
			'trang_thai_text',
			'openid',
			'ten',
            'sType',
            'aAddress',
            'errors',
            'aProfileImage',
            'sLinkSearch',
            'sLinkFull',
            'sLinkSort',
            'last_visit'
		);

        $sLinkSearch = Core::getService('core.tools')->splitUrl($sLinkFull, 'q');
        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sap_xep');

		foreach($output as $key)
		{
			$data[$key] = $$key;
		}

        $this->template()->setTitle($page['title']);
        $this->template()->setHeader(array(
            'user.js' => 'site_script',
        ));

		$this->template()->assign(array(
			'output' => $output,
			'data' => $data,
		));
    }
}
?>
