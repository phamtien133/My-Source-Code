<?php
class Menu_Component_Controller_List extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();
        //if (!empty($aVals['req2']) && $aVals['req2'] == 'edit') {
//            return Core::getLib('module')->setController('menu.add');
//        }

        $aTmps = explode('_', $aVals['req3'], 2);
        $id = 0;
        if ($aTmps[0] == 'id')
            $id = $aTmps[1]*1;
        else
            $sCode = $aTmps[1];

        $output = array(
            'status',
            'menu_chinh',
            'duong_dan_tao_menu',
            'id',
            'menu',
            'nhom_uu_tien',
        );

        $page['title']=Core::getPhrase('language_quan-ly-menu');


        if($id < 1)
        {
            $error=Core::getPhrase('language_khong-co-quyen-truy-cap');
        }

        $aPermission = $oSession->get('session-permission');

        if($aPermission['create_menu']!=1)
        {
            // check xem có quyền chi tiết ko
            if(empty($aPermission['edit_menu']))
            {
                $error = Core::getPhrase('language_khong-co-quyen-truy-cap').'(1)';
            }
        }

        if(!$error)
        {
            $rows = $this->database->select('*')
                ->from(Core::getT('menu'))
                ->where('domain_id ='.Core::getDomainId().' AND id ='.$id.' AND status != 2')
                ->execute('getRow');
            // lấy thông tin của Menu lớn
            $menu_chinh = array(
                'name' => $rows["name"],
                'name_code' => $rows["name_code"],
            );


            $nhom_uu_tien = array();
            // menu
            $menu = array();
            $aRows = $this->database->select('*')
                ->from(Core::getT('menu_value'))
                ->where('menu_id = '.$id.' AND status != 2')
                ->order('priority DESC')
                ->execute('getRows');

            foreach ($aRows as $rows)
            {
                $val = $rows["parent_id"];
                if($val == -1) $val = 0;

                $tmp = $rows['priority'];

                if($nhom_vi_tri[$val] == 0)
                {
                    if($tmp == 0)
                    {
                        if($nhom_vi_tri[$val] < 1) $nhom_vi_tri[$val] = 999;
                        $nhom_vi_tri[$val]--;
                    }
                    else $nhom_vi_tri[$val] = $tmp;
                }
                else $nhom_vi_tri[$val]--;

                $menu[] = array($rows["id"], $rows["name"], $rows["parent_id"], 0, $rows["path"], $rows["target_windows"], $nhom_vi_tri[$val], $rows["status"]);
                if(!in_array($val, $nhom_uu_tien)) $nhom_uu_tien[] = $val;
            }
            foreach($menu as $key => $val)
            {
                // cập nhật số mục cho đề tài trước
                if($val[2] != -1)
                {
                    foreach($menu as $k => $v)
                    {
                        if($v[0] == $val[2])
                        {
                            if($menu[$k][3] < 1) $menu[$k][3] = 1;
                            else $menu[$k][3]++;
                            break;
                        }
                    }
                }
            }
            $status=1;
        }
        else $status=4;

        foreach($output as $key)
        {
            $data[$key] = $$key;
        }


        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'output' => $output,
            'data' => $data,
        ));
    }
}
?>
