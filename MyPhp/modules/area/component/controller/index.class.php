<?php
class Area_Component_Controller_Index extends Component
{
    public function process()
    {
        $this->database = Core::getLib('database');
        $oSession = Core::getLib('session');
        $aVals = Core::getLib('request')->getRequests();

        //Điều hướng page
        if (isset($aVals['req2'])) {
            if ($aVals['req2'] == 'edit' || $aVals['req2'] == 'add') {
                return Core::getLib('module')->setController('area.add');
            }
        }

        //$page['title'] = Core::getPhrase('language_quan-ly-shop-custom');
        $page['title'] = 'Quản lý khu vực';

        $aPermission = $oSession->get('session-permission');
        $sPageType = $oSession->get('session-page_type');

        $sLinkFull = '/area/?';

        if($aPermission['manage_extend'] != 1 && $sPageType == 'marketplace')
        {
            $errors[] = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }

        if(empty($errors))
        {
            if($id == 0)
            {
                $query = '';
                $duong_dan_phan_trang = '';
                $limit = $_GET['limit']*1;
                if($limit < 1) $limit = 15;

                if(!empty($limit))
                {
                    $sLinkFull .= 'limit='.$limit;
                    $duong_dan_phan_trang .= '&limit='.$limit;
                }

                //Cấp độ (1: Quốc gia, 2: Tỉnh thành, 3: Quận huyện)
                $aDegrees = array(
                    1 => 'Quốc gia',
                    2 => 'Tỉnh thành',
                    3 => 'Quận huyện',
                );


                //Mặc định là tỉnh thành của Việt nam
                $iDegree = isset($_GET['lvl']) ? $_GET['lvl'] : -1;
                if ($iDegree < 1 || $iDegree > 3) {
                    $iDegree = 2;

                }

                $iCountryId = isset($_GET['country']) ? $_GET['country'] : -1;
                if ($iDegree == 2 && $iCountryId < 1) {
                    $sCode = 'vn';
                    $aRow = $this->database->select('id')
                        ->from(Core::getT('area'))
                        ->where('status != 2 AND code =\''.$sCode.'\'')
                        ->execute('getRow');
                    if (isset($aRow['id'])) {
                        $iCountryId = $aRow['id'];
                    }
                    else {
                        $iCountryId = -1;
                    }
                }

                $iCityId = isset($_GET['city']) ? $_GET['city'] : -1;

                $iParentId -1;

                if ($iDegree == 1) {
                    $iParentId = -1;
                }
                elseif ($iDegree == 2) {
                    $iParentId = $iCountryId;
                }
                elseif ($iDegree == 3) {
                    $iParentId = $iCityId;
                }

                $sLinkFull .= '&lvl='.$iDegree;
                $duong_dan_phan_trang .= '&lvl='.$iDegree;

                if ($iCountryId > 0) {
                    $sLinkFull .= '&country='.$iCountryId;
                    $duong_dan_phan_trang .= '&country='.$iCountryId;
                }

                if ($iCityId > 0) {
                    $sLinkFull .= '&city='.$iCityId;
                    $duong_dan_phan_trang .= '&city='.$iCityId;
                }


                $query .= ' AND degree ='.$iDegree.' AND parent_id ='.$iParentId;

                //danh sách tất cả các quốc gia
                $aCountries = array();
                $aRows = $this->database->select('id, name')
                    ->from(Core::getT('area'))
                    ->where('status != 2 AND degree = 1')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aCountries[] = array(
                        'id' => $aRow['id'],
                        'name' => $aRow['name'],
                    );
                }
                //danh sách tất cả các tỉnh thành
                $aCities = array();
                $aRows = $this->database->select('id, name')
                    ->from(Core::getT('area'))
                    ->where('status != 2 AND degree = 2 AND parent_id ='.$iCountryId)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aCities[] = array(
                        'id' => $aRow['id'],
                        'name' => $aRow['name'],
                    );
                }

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
                    $query .= ' AND ( name LIKE "%'.addslashes($tu_khoa).'%" OR code LIKE "%'.addslashes($tu_khoa).'%")';
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
                    4: code DESC
                    5: code ASC

                */

                if($sap_xep == 1) $order = 'id ASC';
                elseif($sap_xep == 2) $order = ' name DESC';
                elseif ($sap_xep == 3) {
                    $order = ' name ASC';
                }
                elseif ($sap_xep == 4) {
                    $order = ' code DESC';
                }
                elseif ($sap_xep == 5) {
                    $order = ' code ASC';
                }
                else $order = ' id DESC';
                if ($sap_xep > 0) {
                    $duong_dan_phan_trang .= '&sap_xep='.$sap_xep;
                    $sLinkFull .= '&sap_xep='.$sap_xep;
                }

                $trang_hien_tai = addslashes($_GET["page"])*1;
                $tong_cong = $this->database->select('count(id)')
                        ->from(Core::getT('area'))
                        ->where('status != 2'.$query)
                        ->execute('getField');

                $tong_trang=ceil($tong_cong/$limit);
                if(!@$trang_hien_tai) $trang_hien_tai=1;
                $trang_bat_dau = ($trang_hien_tai-1)*$limit;

                $dir = $_SERVER['REQUEST_URI'];
                $tmps = explode('/', $dir, 3);
                $dir = '/'.$tmps[1].'/';

                $duong_dan_phan_trang = $dir.'index/?'.$duong_dan_phan_trang;

                $aRows = $this->database->select('*')
                        ->from(Core::getT('area'))
                        ->where('status != 2'.$query)
                        ->order($order)
                        ->limit($trang_hien_tai, $limit, $tong_cong)
                        ->execute('getRows');

                foreach ($aRows as $rows)
                {
                    $shop_custom['id'][] = $rows["id"];
                    $shop_custom['name'][] = $rows["name"];
                    $shop_custom['code'][] = $rows["code"];
                    if($rows["status"]==0) $tmp = 'status_no';
                    else $tmp = 'status_yes';
                    $shop_custom['status_text'][] = $tmp;
                    $shop_custom['status'][] = $rows["status"];
                }
                $status=1;


            }
        }
        else $status=4;

        $output = array(
            'duong_dan_phan_trang',
            'errors',
            'shop_custom',
            'status',
            'tong_trang',
            'tong_cong',
            'trang_hien_tai',
            'sap_xep',
            'tu_khoa',
            'sLinkFull',
            'sLinkSort',
            'aCountries',
            'iCountryId',
            'aCities',
            'iCityId',
            'aDegrees',
            'iDegree',

        );

        $sLinkSort = Core::getService('core.tools')->splitUrl($sLinkFull, 'sap_xep');

        foreach($output as $key)
        {
            $data[$key] = $$key;
        }

        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
            'area.js' => 'site_script',
        ));

        $this->template()->setTitle($page['title']);
        $this->template()->assign(array(
            'output' => $output,
            'data' => $data,
        ));

    }
}
?>
