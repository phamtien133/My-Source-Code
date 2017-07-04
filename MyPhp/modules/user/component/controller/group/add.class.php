<?php
class User_Component_Controller_Group_Add extends Component
{
    public function process()
    {
		$this->database = Core::getLib('database');
		$oSession = Core::getLib('session');
		$aVals = Core::getLib('request')->getRequests();

        $sArticleId = $this->request()->get('req4');
		$tmps = explode('_', $sArticleId, 2);

		$id = 0;
		$key = '';
		if($tmps[0] == 'id') $id = $tmps[1]*1;
		else $key = $tmps[1];

		$type = 'create_edit_user_group';

        $iOptionAddUser = Core::getParam('core.setting_create_user_when_add_group');
        $iIsDuplicate = 1;

		if(empty($errors))
		{
			if(!empty($_POST))
			{
				if(empty($errors))
				{
					$data_arr['name'] = $_POST['name'];
					$data_arr['name_code'] = Core::getService('core.tools')->encodeUrl(trim(Core::getLib('input')->removeXSS($_POST['name_code'])));
                    $data_arr['list_id'] = isset($_POST['list_id']) ? $_POST['list_id'] : array();
                    if (!is_array($data_arr['list_id'])) {
                        $data_arr['list_id'] = array();
                    }

                    $data_arr['list_name'] = isset($_POST['list_name']) ? $_POST['list_name'] : array();
                    if (!is_array($data_arr['list_name'])) {
                        $data_arr['list_name'] = array();
                    }

                    $data_arr['list_email'] = isset($_POST['list_email']) ? $_POST['list_email'] : array();
                    if (!is_array($data_arr['list_email'])) {
                        $data_arr['list_email'] = array();
                    }

					$data_arr['status'] = $_POST['status']*1;
					if($data_arr['status'] != 1) $data_arr['status'] = 0;
				}

				if(empty($errors))
				{
					if(mb_strlen($data_arr['name']) < 1 || mb_strlen($data_arr['name']) > 225) $errors[] = sprintf(Core::getPhrase('language_ten-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), 1, 225);
					if(mb_strlen($data_arr['name_code']) < 1 || mb_strlen($data_arr['name_code']) > 225) $errors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ma-ten'), 1, 225);
				}
				if(empty($errors))
				{
					// kiểm tra id
					if($id > 0)
					{
                        $aGroup = $this->database->select('id, is_duplidate')
                                ->from(Core::getT('user_group'))
                                ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$id)
                                ->execute('getRow');
                        if (isset($aGroup['id'])) {
                            $iIsDuplicate = $aGroup['is_duplidate'];
                        }
                        else {
                            $errors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu'));
                        }
					}
					// end
				}
				if(empty($errors))
				{
					$sql = '';
					if($id > 0) $sql = ' AND id != '.$id;
					// kiểm tra mã tên đã tồn tại chưa
					$rows = $this->database->select('count(id)')
								->from(Core::getT('user_group'))
								->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND name_code = "'.addslashes($data_arr['name_code']).'"'.$sql)
								->execute('getField');
					if($rows > 0) $errors[] = Core::getPhrase('language_ten-da-ton-tai');
				}

                //kiểm tra thông tin user
                $aListId = array();
                $aMappingInfoUser = array();
                if(empty($errors) && !empty($data_arr['list_id'])) {
                    $aIdTmp = array(); //danh sách những user tồn tại
                    $aRows = $this->database->select('*')
                        ->from(Core::getT('user'))
                        ->where('status != 2 AND domain_id ='.Core::getDomainId().' AND id IN ('.implode(',', $data_arr['list_id']).')')
                        ->execute('getRows');
                    foreach ($aRows as $aRow) {
                        $aIdTmp[] = $aRow['id'];
                        $aMappingInfoUser[$aRow['id']] = array(
                            'email' => $aRow['email'],
                            'fullname' => $aRow['fullname'].' ('.$data_arr['name'].')',
                            'address' => $aRow['address'],
                            'city' => $aRow['city'],
                            'country' => $aRow['country'],
                            'area_id' => $aRow['area_id'],
                            'phone_number' => $aRow['phone_number'],
                            'password' => $aRow['password'],
                            'password_security' => $aRow['password_security'],
                        );
                    }

                    //duyệt sắp xếp lại thứ tự và loại bỏ các trường hợp không tồn tại
                    $aListTmp = array();
                    foreach ($data_arr['list_id']  as $sKey => $iVal) {
                        if (in_array($iVal, $aIdTmp) && !in_array($iVal, $aListTmp)) {
                            $aListTmp[] = $iVal;
                        }
                    }
                    $aListId = $aListTmp;
                }

				if(empty($errors))
				{
					if($id > 0)
					{

                        $this->database->update(
                            Core::getT('user_group'),
                            array(
								'name' => $data_arr['name'],
								'name_code' => $data_arr['name_code'],
								'status' => $data_arr['status'],
							),
                            'domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$id
                        );
						$stt = $id;
                        /*
						if($this->database->affectedRows() > 0)
						{
							$tmps = array();

							// lấy giá trị thẻ
							$sql = $this->database->select(' group_concat("", article_id)')
										->from(Core::getT('tab_article'))
										->where('tab_id ='.$id.' AND status != 2')
										->group('article_id')
										->execute('getField');

							// lấy đề tài stt các bài viết liên quan đến thẻ trên.
							$trows = array();
							if(!empty($sql))
							{
								$trows = $this->database->select('category_id')
									->from(Core::getT('article'))
									->where('domain_id ='. Core::getDomainId() .' AND id IN ('.$sql.')')
									->group('category_id')
									->execute('getRows');
							}
							foreach($trows as $rows)
							{
								$tmps[] = $rows['category_id'];
								xoa_cache_thu_muc(array(
									'link' => $_SESSION['session-ten_mien']['stt'].'/de_tai/'.$rows['de_tai_stt'],
									'type' => 'de_tai',
									'id' => $rows['de_tai_stt'],
								));
							}
						}
                        */

                        //Xóa chi tiết tất cả các user
                        //$this->database->delete(Core::getT('user_group_member'), 'group_id ='.$id.' AND domain_id ='.Core::getDomainId());
					}
					else
					{
                        $stt = $this->database->insert(
                            Core::getT('user_group'),
                            array(
								'name' => $data_arr['name'],
								'name_code' => $data_arr['name_code'],
								'status' => $data_arr['status'],
								'time' => time(),
								'domain_id ' => Core::getDomainId(),
							)
                        );
					}

                    //cập nhật danh sách user
                    //if (!empty($aListId)) {
                        //Lấy danh sách user củ
                        $aOld = array();
                        if ($id > 0) {
                            $aRows = $this->database->select('*')
                                ->from(Core::getT('user_group_member'))
                                ->where('status != 2 AND group_id ='.$id)
                                ->execute('getRows');
                            foreach ($aRows as $aRow) {
                                $aOld[$aRow['user_id']] = $aRow['id'];
                            }
                        }

                        foreach ($aListId as $iVal) {
                            if ($id > 0) {
                                if (isset($aOld[$iVal])) {
                                    //đã có trước đó => bỏ qua
                                    unset($aOld[$iVal]);
                                    continue;
                                }
                            }
                            $aInsertDt = array(
                                'group_id' => $stt,
                                'user_id' => $iVal,
                                'create_time' => CORE_TIME,
                                'status' => 1,
                            );
                            $this->database->insert(Core::getT('user_group_member'), $aInsertDt);
                            if ($iOptionAddUser == 1 && $iIsDuplicate) {
                                //Clone user
                                $aClone = $aMappingInfoUser[$iVal];
                                $aClone['code'] = Core::getService('core.tools')->getUniqueCode();
                                $aClone['reference_id'] = $iVal;
                                $aClone['user_group_id'] = $stt;
                                $aClone['status'] = 0;
                                $aClone['domain_id'] = Core::getDomainId();
                                $iIdTmp = $this->database->insert(Core::getT('user'), $aClone);
                                if ($iIdTmp > 0) {
                                    $this->database->update(Core::getT('user'), array('username' => 'profile'.$iIdTmp), 'id ='.$iIdTmp);
                                }
                            }
                        }
                        if ($id > 0 && !empty($aOld)) {
                            //xóa những user ko còn trong danh sách gửi lên
                            $this->database->update(Core::getT('user_group_member'), array('status' => 2), 'id IN ('.implode(',', $aOld).')');
                            if ($iOptionAddUser == 1) {
                                //Xóa các reference user
                                foreach ($aOld as $iKey => $iVal) {
                                    $this->database->update(Core::getT('user'), array('status' => 2), 'reference_id ='.$iKey.' AND user_group_id ='.$stt);
                                }
                            }
                        }
                    //}
					// ghi log hệ thống
					Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$id,'content' => 'phpinfo',));;
					// end
					$status=3;
                    //redirect page
                    $sDir = $_SERVER['REQUEST_URI'];
                    $aTmps = explode('/', $sDir, 3);
                    $sDir = '/'.$aTmps[1].'/group/';
                    header('Location: '.$sDir);
				}
				if(!empty($errors)) $status=1;
			}
			else
			{
				if($id > 0)
				{
					$rows = $this->database->select('id, name, name_code, status')
						->from(Core::getT('user_group'))
						->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$id)
						->limit(1)
						->execute('getRow');
					if (isset($rows['id'])) {
                        $data_arr['name'] = $rows['name'];
                        $data_arr['name_code'] = $rows['name_code'];
                        $data_arr['status'] = $rows['status'];
                        $data_arr['list_id'] = array();
                        $data_arr['list_name'] = array();
                        $data_arr['list_email'] = array();

                        //Lấy thông tin danh sách thành viên
                        $aRows = $this->database->select('id, user_id')
                            ->from(Core::getT('user_group_member'))
                            ->where('status != 2 AND group_id ='.$id)
                            ->execute('getRows');
                        $aUserId = array();
                        foreach ($aRows as $aRow) {
                            if (!in_array($aRow['user_id'], $aUserId)) {
                                $aUserId[] = $aRow['user_id'];
                            }
                        }
                        $aMappingUsers = array();
                        if (!empty($aUserId)) {
                            $aTmps = $this->database->select('id, fullname, email, phone_number')
                                ->from(Core::getT('user'))
                                ->where('domain_id ='.Core::getDomainId().' AND id IN ('.implode(',', $aUserId).')')
                                ->execute('getRows');
                            foreach ($aTmps as $aTmp) {
                                $aMappingUsers[$aTmp['id']] = $aTmp;
                            }
                        }
                        foreach ($aRows as $aRow) {
                            $data_arr['list_id'][] = $aRow['user_id'];
                            $data_arr['list_name'][] = isset($aMappingUsers[$aRow['user_id']]['fullname']) ? $aMappingUsers[$aRow['user_id']]['fullname'] : 'unKnow';
                            $data_arr['list_email'][] = isset($aMappingUsers[$aRow['user_id']]['email']) ? $aMappingUsers[$aRow['user_id']]['email'] : 'unKnow';
                        }
                    }


				}
				else
				{
					$data_arr['status'] = 1;
				}
				$status=1;
			}
		}
		$aPage['title'] = 'Nhóm thành viên';
		$output = array(
            'id',
			'duong_dan',
			'duong_dan_phan_trang',
			'status',
            'data_arr',
			'errors',
            'aPage',
		);
		foreach($output as $key)
		{
			$data[$key] = $$key;
		}

        $this->template()->setHeader(array(
            'marketing.css' => 'site_css',
            'user_group.js' => 'site_script',
        ));


        $this->template()->setTitle($aPage['title']);
		$this->template()->assign(array(
			'output' => $output,
			'data' => $data,
		));
    }
}
?>