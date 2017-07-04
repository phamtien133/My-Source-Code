<?php
class Core_Service_Core extends Service
{
    public function __construct()
    {
    }

    public function getTimeZones()
    {
        if(CORE_USE_DATE_TIME) {
            $aTimeZones = DateTimeZone::listIdentifiers(2047);
            sort($aTimeZones);
            foreach ($aTimeZones as $iKey => $sTimeZone)
            {
                $aTimeZones['z' . $iKey] = $sTimeZone;
                unset($aTimeZones[$iKey]);
            }
            return $aTimeZones;
        }
        elseif (isset($aZones) && is_array($aZones) && !empty($aZones)) {
            return $aZones;
        }
        $aTimezones = array(
            '-12' => '(GMT -12:00) Eniwetok, Kwajalein',
            '-11' => '(GMT -11:00) Midway Island, Samoa',
            '-10' => '(GMT -10:00) Hawaii',
            '-9' => '(GMT -9:00) Alaska',
            '-8' => '(GMT -8:00) Pacific Time (US &amp; Canada)',
            '-7' => '(GMT -7:00) Mountain Time (US &amp; Canada)',
            '-6' => '(GMT -6:00) Central Time (US &amp; Canada), Mexico City',
            '-5' => '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima',
            '-4.5' => '(GMT -4:30) Caracas',
            '-4' => '(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago',
            '-3.5' => '(GMT -3:30) Newfoundland',
            '-3' => '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
            '-2' => '(GMT -2:00) Mid-Atlantic',
            '-1' => '(GMT -1:00 hour) Azores, Cape Verde Islands',
            '0' => '(GMT) Western Europe Time, London, Lisbon, Casablanca',
            '1' => '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris',
            '2' => '(GMT +2:00) Kaliningrad, South Africa',
            '3' => '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
            '3.5' => '(GMT +3:30) Tehran',
            '4' => '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
            '4.5' => '(GMT +4:30) Kabul',
            '5' => '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
            '5.5' => '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi',
            '5.75' => '(GMT +5:45) Kathmandu',
            '6' => '(GMT +6:00) Almaty, Dhaka, Colombo',
            '6.5' => '(GMT +6:30) Yangon, Cocos Islands',
            '7' => '(GMT +7:00) Bangkok, Hanoi, Jakarta',
            '8' => '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
            '9' => '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
            '9.5' => '(GMT +9:30) Adelaide, Darwin',
            '10' => '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
            '11' => '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
            '12' => '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka'
        );

        return $aTimezones;
    }

    public function getMenus()
    {
        $sCacheId = $this->cache()->set('tm|'.Core::getDomainId().'|menu');
        $aMenus = $this->cache()->get($sCacheId);
        if (!$aMenus) {
            $aRows = $this->database()->select('id, name_code, name')
                ->from(Core::getT('menu'))
                ->where('domain_id ='. Core::getDomainId() .' AND status = 1')
                ->execute('getRows');
            $aLists = array();
            foreach ($aRows as $aRow) {
                $aLists[$aRow['id']] = array(
                    'name_code' => $aRow['name_code'],
                    'name' => $aRow['name']
                );
            }
            if (!empty($aLists)) {
                // get value of menu
                $aTmps = array();
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('menu_value'))
                    ->where('menu_id IN ('.implode(',', array_keys($aLists)).') AND status = 1')
                    ->order('menu_id, priority DESC, name ASC')
                    ->execute('getRows');
                $sTargetWindows = '';
                foreach ($aRows as $aRow) {
                    switch($aRow["target_windows"]) {
                        case 1:
                            $sTargetWindows = ' target="_blank"';
                            break;
                        case 2:
                            $sTargetWindows = ' location="no" statusbar="no" menubar="no" scrollbars="yes" titlebar="yes" toolbar="no" resizable="yes" onclick="return onLinkClick(this)"';
                            break;
                        case 3:
                            $sTargetWindows = ' rel="no-follow"';
                            break;
                        case 4:
                            $sTargetWindows = ' target="_blank" rel="no-follow"';
                            break;
                        case 5:
                            $sTargetWindows = ' location="no" statusbar="no" menubar="no" scrollbars="yes" titlebar="yes" toolbar="no" resizable="yes" onclick="return onLinkClick(this)" rel="no-follow"';
                            break;
                        default:
                            $sTargetWindows = '';
                            break;
                    }
                    $aRow['image_path'] = str_replace('"', '&quot;', $aRow['image_path']);
                    $aTmps[$aRow['menu_id']][] = array(
                        $aRow['id'],
                        $aRow['name'],
                        $aRow['parent_id'],
                        0,
                        $aRow['path'],
                        $sTargetWindows,
                        $aRow['description'],
                        $aRow['image_path'],
                        $aRow['column'],
                        'note' => $aRow['note']
                    );
                }

                // update index count.
                foreach (array_keys($aLists) as $iMenuId) {
                    foreach ($aTmps[$iMenuId] as  $aValue) {
                        // cập nhật số mục cho đề tài trước
                        if($aValue[2] != -1) {
                            foreach ($aTmps[$iMenuId] as $iKey => $aTmp) {
                                if ($aTmp[0] == $aValue[2]) {
                                    if($aTmps[$iMenuId][$iKey][3] < 1)
                                        $aTmps[$iMenuId][$iKey][3] = 1;
                                    else
                                        $aTmps[$iMenuId][$iKey][3]++;
                                    break;
                                }
                            }
                        }
                    }
                }
                // add value to menu
                foreach ($aTmps as $iKey => $aValue) {
                    $aMenus[$aLists[$iKey]['name_code']]['value'] = $aValue;
                    $aMenus[$aLists[$iKey]['name_code']]['name'] = $aLists[$iKey]['name'];
                }
                unset($aLists);
                unset($aTmps);
                $this->cache()->save($sCacheId, $aMenus);
            }
        }
        return $aMenus;
    }

    public function setSession()
    {

    }

    public function checkBot()
    {
        $aBotList = array("Teoma", "alexa", "froogle", "Gigabot", 'bingbot', "inktomi",
        "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
        "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
        "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
        "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
        "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
        "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
        "Butterfly","Twitturls","Me.dium","Twiceler", 'Bot');

        foreach ($aBotList as $sBot){
            if (strpos($_SERVER['HTTP_USER_AGENT'], $sBot) !== false)
                return $sBot;
        }
        return '';
    }

    public function checkSession($aParams = array())
    {
        $bIsReturn = false;
        $sSid = '';
        if (isset($aParams['sid'])) {
            $sSid = $aParams['sid'];
            $sSid = htmlspecialchars(Core::getLib('input')->removeXSS($sSid));
        }

        if (!empty($sSid) && preg_match('/^[a-z0-9-.A-Z]+$/', $sSid) == 0) {
            return array(
                'status' => 'error',
                'message' => 'deny-session',
            );
        }

        $sKid = '';
        if (isset($aParams['kid'])) {
            $sKid = $aParams['kid'];
            $sKid = htmlspecialchars(Core::getLib('input')->removeXSS($sKid));
        }
        if(!empty($sKid) && preg_match('/^[a-z0-9-.A-Z]+$/', $sKid) == 0)
        {
            Error::set('error', 'deny-code');
            return array('status' => 'error', 'message' => 'deny-kid',);
        }

        if(!empty($sSid)) session_id($sSid);
        $oSession = Core::getLib('session');

        if ($oSession->getArray('session-user', 'id') == 0 && $sKid != '') {
            $sType = 'login_user_api';
            $sActiveCode = $sKid;

            // login with kid
            $iId = $this->database()->select('note')
                ->from(Core::getT('active_code'))
                ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND active_code = "'.addslashes($sActiveCode).'" AND type = "'.addslashes($sType).'" AND status != 2')
                ->execute('getField');
            if($iId > 0) {
                $aRow = $this->database()->select('id, code, username, user_group_id, email, password, last_visit')
                    ->from(Core::getT('user'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id = '.$iId.' AND status != 2')
                    ->execute('getRow');

                Core::getService('user.auth')->loginSuccess($aRow);
            }
        }
        // ket thuc session

        if (empty($_SESSION) || $oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-domain', 'id') < 1) {
            return array('status' => 'error', 'message' => 'empty-session');
            //echo json_encode(array('status' => 'error'));
            //exit();
        }

        // check quyen
        if ((isset($aParams['account']) && $aParams['account'] == 1) || (isset($aParams['bypass_upload']) && $aParams['bypass_upload'] == 1)) {
            // trang account thì không kiểm tra quyền upload file.
            //Bổ sung không kiểm tra quyền upload file khi có param truyền vào
        }
        else {
            if ( $oSession->getArray('session-permission', 'upload_file') != 1) {
                return array('status' => 'error');
                //echo json_encode(array('status' => 'error'));
                //exit();
            }
        }

        return array(
            'sid' => session_id(),
            'user' => array(
                'id' => $oSession->getArray('session-user', 'id'),
                'key' => $oSession->getArray('session-user', 'code'),
                'name' => $oSession->getArray('session-user', 'name'),
            ),
            'permission' => $oSession->get('session-permission'),
            'domain' => array(
                'id' => $oSession->getArray('session-domain', 'id'),
                'name' => $oSession->getArray('session-domain', 'name'),
            ),
            'status' => 'success',
        );
    }

    public function getKeyword($aParams = array())
    {
        /* Gia lap *
        $aParams['title'] = 'Kati Nescher - Đóa hồng nở muộn';
        $aParams['description'] = 'Ở độ tuổi 28, với Kati Nescher, tất cả chỉ mới bắt đầu nhưng hứa hẹn sẽ tỏa sáng rực rỡ hơn bất kỳ nụ hồng non nớt nào';

        /*
        $aParams['content'] = 'Motif điển hình của tích “vịt con hóa thành thiên nga” trong thế giới người mẫu kể về các cô thiếu nữ chỉ vừa chớm trăng rằm độ 14-15 tuổi, tóc vàng, da trắng xanh, cao nhòng và đến từ các vùng nông thôn tỉnh lẻ Đông Âu bỗng chốc được phát hiện, để rồi một tháng sau đó sải bước run rẩy trên sàn diễn Milan, Paris, nơi chứng kiến những cuộc cạnh tranh nhan sắc khốc liệt. Kati Nescher không thuộc về số đông ấy, bởi lẽ cô đã quá tuổi 27 và đang đơn thân chăm sóc cậu con trai hai tuổi.

        Trước đó vài năm, Kati đã từng thử làm người mẫu ảnh cho một vài đại lý địa phương, nhưng không thành công, sau đó cô quay về làm thông dịch viên. Cho đến một ngày, cô bạn làm nghề makeup dúi vào tay Kati số điện thoại của một quản lý người mẫu có tiếng ở Cologne, và khuyên cô “hãy cho mình cơ hội thứ hai”.

        “Đó là một quản lý người mẫu cực kỳ uy tín, người đem lại thành công cho 6 siêu mẫu nổi tiếng trước đây, thế nên tôi hơi căng thẳng đôi chút. Tôi gọi cho người quản lý, gửi vài tấm ảnh. Ít ngày sau bà hẹn tôi một cuộc gặp trực tiếp ở Cologne và mọi thứ cứ thế diễn ra”, Kati nhớ lại quãng thời gian cô quyết định theo đuổi ước mơ của mình tới cùng. Một tháng sau, Kati Nescher sải bước lần đầu tiên trên sàn diễn Haute Couture Thu Đông 2011 trứ danh, đánh dấu chặng khởi đầu suôn sẻ hơn cho người phụ nữ mang quốc tịch Đức, sinh trưởng ở Nga và sở hữu đôi gò má cao sắc lẻm, đôi mắt sâu thẳm gây ấn tượng mạnh.﻿

        Liệu một người phụ nữ đã làm mẹ, chuẩn bị chạm ngưỡng 30, có đủ xuân sắc để đọ dung nhan với các thiếu nữ trẻ đang xâm chiếm thời trang cao cấp?! Câu trả lời không nằm ở tuổi tác, mà quay về bản năng đàn bà vốn hao mòn theo đà “ép lúa non” của cả một giai đoạn thời trang chạy theo vẻ đẹp hao gầy, chưa chín tới. Kati Nescher toát lên sự chín chắn, từng trải và nếm đủ mùi vị cuộc sống, chính những trải nghiệm sâu sắc ấy giúp cô làm vừa lòng tất cả những nhân vật quyền lực đầu ngành trong giới thời trang. Hóa ra việc Kati chạm ngưỡng thời trang không phải muộn màng như nhiều người lầm tưởng, Cô vào nghề thật kịp thời, đúng lúc, theo cùng trào lưu quay về nền tảng phẩm chất thực thụ của người phụ nữ. Vì chung qui thì mục đích trước hết của thời trang là làm cho phái đẹp ngày càng đẹp hơn.﻿

        Tuần lễ thời trang Thu-Đông 2012 đưa Kati Nescher đến gần với danh hiệu nữ hoàng catwalk. Tổng cộng suốt một tháng ròng rã qua bốn kinh đô mốt lớn nhất thế giới, Kati có mặt trong cả thảy 63 show diễn, với con số kỷ lục 12 phiên mở màn và 7 lần kết màn cho toàn những nhà thời trang hàng đầu như Chanel, Marc Jacobs, Oscar de la Renta, Versace… Vượt ra khỏi phạm vi sàn catwalk và hậu trường bận rộn của tuần lễ thời trang, Kati Nescher lọt vào mắt xanh của “ông già tóc bạch kim” Karl Lagerfeld để trở thành nàng thơ mới nhất cho nhà Chanel đồng thời xuất hiện trong quảng cáo của Nina Ricci, Tom Ford, Alexander Wang, cũng như gom về cho mình trang bìa uy tín của Vogue China, V Magazine.

        Đằm thắm và duyên dáng, Kati Nescher đúc kết lại thành công của mình suốt quãng thời gian qua bằng sự khiêm nhường. “Tôi nghĩ tất cả đều phụ thuộc vào việc bạn bày tỏ sự tôn trọng như thế nào với toàn bộ cỗ máy thời trang vận hành chung quanh, đó là các NTK, các nhiếp ảnh gia, nghệ sỹ trang điểm, làm tóc, stylist và thậm chí là cả các nhân viên làm móng hay chạy việc hậu trường. Họ đều đóng góp vào thành công sự nghiệp của tôi. Thế nên, công việc của tôi là mở to tất cả các giác quan, để lắng nghe và thấu hiểu”. Với trái tim đam mê thuần khiết ấy, thời trang sẽ còn rộng tay chào đón bước thăng hoa sắp tới của Kati Nescher.﻿

        Bài: VALLEY - Ảnh: TƯ LIỆU﻿
        ';
        /* */

        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $sTitle =     trim($aParams['title']);
        $sDescription =     trim($aParams['description']);
        $sContent=     trim($aParams['content']);

        if ($sTitle=='' && $sDescription=='' && $sContent=='') {
            $error = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }
        elseif ($oSession->getArray('session-user', 'id') < 1 ) {
            $error = Core::getPhrase('language_khong-co-quyen-truy-cap');
        }

        $error = '';
        if(!$error)
        {
            // lay noi dung trong the alt va the title
            preg_match_all('/alt="(.*?)"/', $sContent, $tmp);
            $alt = $tmp[1];

            preg_match_all('/title="(.*?)"/', $sContent, $tmp);
            $title = $tmp[1];
            if($title)
            {
                d(1); die();
                foreach($title as $v)
                {
                    $alt[] = $v;
                }
            }
            if($alt)
            {
                d(2); die();
                for($i=0;$i<=count($alt);$i++)
                {
                    for($j=$i+1;$j<=count($alt);$j++)
                    {
                        if($alt[$i] == $alt[$j]) unset($alt[$j]);
                    }
                }
                $alt = implode('.', $alt);
                $tmps = Core::getService('core.tools')->separatedKeyword(Core::getLib('input')->removeXSS($alt));

                foreach($tmps[0] as $tmp)
                {
                    $tmp = Core::getService('core.tools')->processKeyword($tmp);
                    if(empty($tmp)) continue;
                    $alt_tu_khoa_2_ty_tu[] = $tmp;
                }
            }

            // - end
            // lay noi dung trong tieu deCore::getLib('input')->rem
            $sTitle = Core::getLib('input')->removeXSS($sTitle);
            $tmp = explode(' ', $sTitle);
            for($i=0;$i<count($tmp);$i++)
            {
                if(mb_strlen($tmp[$i], 'utf8') == strlen($tmp[$i]))
                {
                    $tu = trim($tmp[$i]);
                    $sTitle_tu_khoa_1_ky_tu[] = Core::getService('core.tools')->processKeyword($tu);;
                }
            }
            $tmps = Core::getService('core.tools')->separatedKeyword($sTitle);
            foreach($tmps[0] as $tmp)
            {
                $tmp = Core::getService('core.tools')->processKeyword($tmp);
                if(empty($tmp)) continue;
                $sTitle_tu_khoa_2_ty_tu[] = $tmp;
            }
            // - end
            // lay noi dung trong mo ta
            $sDescription = Core::getLib('input')->removeXSS($sDescription);
            $tmps = Core::getService('core.tools')->separatedKeyword($sDescription);
            foreach($tmps[0] as $tmp)
            {
                $tmp = Core::getService('core.tools')->processKeyword($tmp);
                if(empty($tmp)) continue;
                $sDescription_tu_khoa_2_ty_tu[] = $tmp;
            }
            // - end
            // lay noi dung trong tieu de
            $sContent = Core::getLib('input')->removeXSS($sContent);
            $tmps = Core::getService('core.tools')->separatedKeyword($sContent);
            foreach($tmps[0] as $tmp)
            {
                $tmp = Core::getService('core.tools')->processKeyword($tmp);
                if(empty($tmp)) continue;
                $sContent_tu_khoa_2_ty_tu[] = $tmp;
            }
            // - end

            // so tieu de voi mo ta ( 3 ky tu )

            if($sTitle_tu_khoa_1_ky_tu)
            {
                for($i=0;$i<count($sTitle_tu_khoa_1_ky_tu);$i++)
                {
                    unset($vi_tri);
                    while(1)
                    {
                        $vi_tri = @strpos($sDescription, $sTitle_tu_khoa_1_ky_tu[$i], $vi_tri);
                        if($vi_tri !== false)
                        {
                            $sTitle_tieu_de_so_lan[$sTitle_tu_khoa_1_ky_tu[$i]]++;
                            $vi_tri = $vi_tri+1;
                        }
                        else break;
                    }
                }
            }
            // - end
            // so tieu de voi alt ( 2 ky tu )
            for($i=0;$i<count($sTitle_tu_khoa_2_ty_tu);$i++)
            {
                for($j=0;$j<count($alt_tu_khoa_2_ty_tu);$j++)
                {
                    if($sTitle_tu_khoa_2_ty_tu[$i] == $alt_tu_khoa_2_ty_tu[$j])
                    {
                        $sTitle_alt_so_lan[$sTitle_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            // - end
            // so tieu de voi mo ta ( 2 ky tu )
            for($i=0;$i<count($sTitle_tu_khoa_2_ty_tu);$i++)
            {
                for($j=0;$j<count($sDescription_tu_khoa_2_ty_tu);$j++)
                {
                    if($sTitle_tu_khoa_2_ty_tu[$i] == $sDescription_tu_khoa_2_ty_tu[$j])
                    {
                        $sTitle_mo_ta_so_lan[$sTitle_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            // - end
            // so tieu de voi noi dung ( 2 ky tu )
            for($i=0;$i<count($sTitle_tu_khoa_2_ty_tu);$i++)
            {
                for($j=0;$j<count($sContent_tu_khoa_2_ty_tu);$j++)
                {
                    if($sTitle_tu_khoa_2_ty_tu[$i] == $sContent_tu_khoa_2_ty_tu[$j])
                    {
                        $sTitle_noi_dung_so_lan[$sTitle_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            // - end
            // so mo ta voi mo ta
            for($i=0;$i<count($sDescription_tu_khoa_2_ty_tu);$i++)
            {
                for($j=$i+1;$j<count($sDescription_tu_khoa_2_ty_tu);$j++)
                {
                    if($sDescription_tu_khoa_2_ty_tu[$j] != NULL && $sDescription_tu_khoa_2_ty_tu[$i] == $sDescription_tu_khoa_2_ty_tu[$j])
                    {
                        $sDescription_tu_khoa_2_ty_tu[$j] = NULL;
                        $sDescription_mo_ta_so_lan[$sDescription_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            //-end
            // so mo ta voi alt
            for($i=0;$i<count($sDescription_tu_khoa_2_ty_tu);$i++)
            {
                for($j=0;$j<count($alt_tu_khoa_2_ty_tu);$j++)
                {
                    if($sDescription_tu_khoa_2_ty_tu[$i] == $alt_tu_khoa_2_ty_tu[$j])
                    {
                        $sDescription_alt_so_lan[$sDescription_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            //-end
            // so mo ta voi noi dung
            for($i=0;$i<count($sDescription_tu_khoa_2_ty_tu);$i++)
            {
                for($j=0;$j<count($sContent_tu_khoa_2_ty_tu);$j++)
                {
                    if($sDescription_tu_khoa_2_ty_tu[$i] == $sContent_tu_khoa_2_ty_tu[$j])
                    {
                        $sDescription_noi_dung_so_lan[$sDescription_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            //-end
            // so noi dung voi noi dung
            for($i=0;$i<count($sContent_tu_khoa_2_ty_tu);$i++)
            {
                for($j=$i+1;$j<count($sContent_tu_khoa_2_ty_tu);$j++)
                {
                    if($sContent_tu_khoa_2_ty_tu[$j] != NULL && $sContent_tu_khoa_2_ty_tu[$i] == $sContent_tu_khoa_2_ty_tu[$j])
                    {
                        $sContent_tu_khoa_2_ty_tu[$j] = NULL;
                        $sContent_noi_dung_so_lan[$sContent_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            //-end
            // so noi dung voi alt
            for($i=0;$i<count($sContent_tu_khoa_2_ty_tu);$i++)
            {
                for($j=0;$j<count($alt_tu_khoa_2_ty_tu);$j++)
                {
                    if($sContent_tu_khoa_2_ty_tu[$i] == $alt_tu_khoa_2_ty_tu[$j])
                    {
                        $sContent_alt_so_lan[$sContent_tu_khoa_2_ty_tu[$i]]++;
                    }
                }
            }
            //-end

            // gop mang


            /*
            danh sach diem duoc tinh bang cach:
            lay so diem tuong ung cua 2 ben cong lai chia hai.
            tieu de : 7
            mo ta    : 5
            the alt    : 3
            noi dung: 1
            vi du:
            tieu de voi noi dung diem la (7+1)/2 = 4
            */
            // tieu de voi tieu de
            if($sTitle_tieu_de_so_lan)
            {
                foreach($sTitle_tieu_de_so_lan as $v => $k)
                {
                    $mang[$v] += $k*7;
                }
            }
            // tieu de voi alt
            if($sTitle_alt_so_lan)
            {
                foreach($sTitle_alt_so_lan as $v => $k)
                {
                    $mang[$v] += $k*5;
                }
            }
            // tieu de voi mo ta
            if($sTitle_mo_ta_so_lan)
            {
                foreach($sTitle_mo_ta_so_lan as $v => $k)
                {
                    $mang[$v] += $k*6;
                }
            }
            // tieu de voi noi dung
            if($sTitle_noi_dung_so_lan)
            {
                foreach($sTitle_noi_dung_so_lan as $v => $k)
                {
                    $mang[$v] += $k*4;
                }
            }
            // mo ta voi mo ta
            if($sDescription_mo_ta_so_lan)
            {
                foreach($sDescription_mo_ta_so_lan as $v => $k)
                {
                    $mang[$v] += $k*5;
                }
            }
            // mo ta voi alt
            if($sDescription_alt_so_lan)
            {
                foreach($sDescription_alt_so_lan as $v => $k)
                {
                    $mang[$v] += $k*4;
                }
            }
            // mo ta voi noi dung
            if($sDescription_noi_dung_so_lan)
            {
                foreach($sDescription_noi_dung_so_lan as $v => $k)
                {
                    $mang[$v] += $k*3;
                }
            }
            // noi dung voi noi dung
            if($sContent_noi_dung_so_lan)
            {
                foreach($sContent_noi_dung_so_lan as $v => $k)
                {
                    $mang[$v] += $k;
                }
            }
            // noi dung voi alt
            if($sContent_alt_so_lan)
            {
                foreach($sContent_alt_so_lan as $v => $k)
                {
                    $mang[$v] += $k*2;
                }
            }
            arsort($mang);
            $n = 0;
            foreach($mang as $v => $k)
            {
                $n++;
                if($n>50 && $k<2) break;
                if(mb_strlen($v, 'utf8') < 26) $mang_moi[$v] = $k;
            }
            foreach($mang_moi as $v => $k)
            {
                echo $k.'<->'.$v.'<-vietspider->';
            }
        }
    }

    public function updateProjectStatus($aParams = array())
    {
        $iId=@$aParams["id"]*1;
        $sCond = '= '.$iId;
        $iStatus = '';
        if ($aParams["status"] == '1') {
            $iStatus = '0';
        } else {
             $iStatus = '1';
        }
        if (isset($aParams['type'])) {
            $sType = $aParams['type'];
        }
        if ($sType == 'project') {
            $this->database()->update(Core::getT('site'), array('status' => $iStatus), 'id'. $sCond); 
        }
    }

    public function updateStatus($aParams = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $iId=@$aParams["id"]*1;
        if ($iId == 0)
            $aList=@$aParams["list"];
        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Core_Error::set('error', 'Deny(1)');
            $bIsReturn = true;
        }
        elseif ($iId==0 && $aList == '') {
            Core_Error::set('error', 'Deny(2)');
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'manage_extend') != 1) {
            Core_Error::set('error', 'Deny(3)');
            $bIsReturn = true;
        }

        $sType = '';
        if (isset($aParams['type'])) {
            $sType = $aParams['type'];
        }

        $aTypeList = array (
            'filter',
            'filter_general',
            'redirect',
            'receive_news',
            'receive_news_group',
            'menu',
            'menu_value',
            'tab',
            'vendor',
            'discount',
            'image_extend',
            'top_article',
            'user_group',
            'language',
            'comment',
            'unit',
            'cart',
            'area',
            'advertisement',
            'ads_position',
            'email_group',
            'email',
            'marketing',
            'discount_item',
            'ads_unit',
            'ads_campaign',
            'image_extend_general',
            'surcharge',
            'project',
        );
        if (!in_array($sType, $aTypeList)) {
            Core_Error::set('error', 'Deny(4)');
            $bIsReturn = true;
        }

        $iStatus = addslashes(@$aParams["status"]*1);
        if ($iStatus != 1 && $iStatus != 2)
            $iStatus=0;

        if (!$bIsReturn) {
            if ($iId == 0 && $sType != 'menu_value') {
                $aList = explode(',', $aList);
                foreach ($aList as $Val) {
                    if ($Val*1>0)
                    $aTmp[] = $Val*1;
                }
                $aList = implode(',', $aTmp);

                $sCond = 'in ('.$aList.')';
                unset($aList);
                $aRows = $this->database()->select('id')
                    ->from(Core::getT($sType))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    // tái tạo điều kiện
                    $aList[] = $aRow['id'];
                }
                $sCond = 'in ('.implode(',', $aList).')';
            }
            else {
                $aList[] = $iId;
                $sCond = '= '.$iId;
            }

            if ($sType == 'user_group') {
                //kiểm tra có quyền thay đổi nhóm thành viên hay không (tạm thời chỉ xét có quyền sửa thành viên hay ko)
                if ($oSession->getArray('session-permission', 'edit_user') != 1) {
                    Core_Error::set('error', 'Deny(5)');
                    $bIsReturn = true;
                }
                else {
                    //cập nhật cho nhóm thành viên
                    $this->database()->update(Core::getT('user_group'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                }
            }
            elseif ($sType == 'redirect') {
                $this->database()->update(Core::getT('redirect'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'cart' || $sType == 'advertisement' || $sType == 'ads_position' || $sType == 'ads_unit' || $sType == 'ads_campaign') {
                $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'status != 2 AND id '.$sCond);
                //clear cache
                Core::getService('core')->removeCache();
            }
            elseif ($sType == 'area') {
                $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'menu_value') {
                // kiểm tra có quyền tương tác với Menu value ko
                $aRow = $this->database()->select('group_concat("", id) id')
                    ->from(Core::getT('menu_value'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond.' AND status != 2')
                    ->execute('getRow');

                if (!empty($aRow['id'])) {
                    $sCond = ' IN ('.$aRow['id'].')';
                    $this->database()->update(Core::getT('menu_value'), array('status' => $iStatus),'status != 2 AND id '.$sCond);

                    Core::getService('core.tools')->updateMenu();

                    /*Bỏ , liên quan tới table Cache
                    // xóa cache cũ tên miền
                    $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$oSession->getArray('session-domain', 'id');
                    $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                    xoa_cache_thu_muc(array(
                        'link' => $oSession->getArray('session-domain', 'id'),
                        'type' => 'all',
                    ));
                    xoaGiaTriSession();
                    */
                    // end;
                }
            }
            elseif ($sType == 'receive_news') {
                $this->database()->update(Core::getT('receive_news'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'vendor') {
                $this->database()->update(Core::getT('vendor'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                //update status for vendor page.
                $this->database()->update(
                    Core::getT('category_display'),
                    array(
                        'status' => $iStatus,
                    ),
                    'object_type = 2 AND object_id '.$sCond.' AND status != 2'
                );
                // update status vendor in categoyr
                $this->database()->update(
                    Core::getT('category_display'),
                    array(
                        'status' => $iStatus,
                    ),
                    'item_type = 2 AND item_id '.$sCond.' AND status != 2'
                );
                //clear cache
                $this->removeCache();
            }
            elseif ($sType == 'tab') {
                // kiểm tra có quyền tương tác với the
                $aRow = $this->database()->select('group_concat("", id) id')
                    ->from(Core::getT('tab'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond.' AND status != 2')
                    ->execute('getRow');
                if (!empty($aRow['id'])) {
                    $sCond = ' IN ('.$aRow['id'].')';

                    $this->database()->update(Core::getT('tab'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                    $this->database()->update(Core::getT('tab_category'), array('status' => $iStatus), 'status != 2 AND tab_id '.$sCond);
                    $this->database()->update(Core::getT('tab_article'), array('status' => $iStatus), 'status != 2 AND tab_id '.$sCond);
                }
            }
            elseif ($sType == 'top_article') {
                // kiểm tra có quyền tương tác
                $aRow = $this->database()->select('group_concat("", id) id')
                    ->from(Core::getT('top_article'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond.' AND status != 2')
                    ->execute('getRow');
                if (!empty($aRow['id'])) {
                    $sCond = ' IN ('.$aRow['id'].')';

                    $this->database()->update(Core::getT('top_article'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                    //$this->database()->update(Core::getT('top_article_value'), array('status' => $iStatus), 'top_article_id '.$sCond);

                    //$sql='UPDATE `'.TABLEPREFIX.'top_bai_viet_gt` SET `top_bai_viet_stt` '.$sCond;
                    //$db->query($sql) or myErrorHandler(E_ERROR, "System error".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
                }
            }
            elseif ($sType == 'project') {
                $this->database()->update(Core::getT('site'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
            }
            else {
                if ($this->database()->update(Core::getT($sType), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond) ) {
                    if ($sType == 'filter') {
                        $this->database()->update(Core::getT('filter_value'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND filter_id '.$sCond);
                        //Delete
                        $this->database()->delete(Core::getT('filter_value_article'), 'filter_id '.$sCond);
                        $this->database()->delete(Core::getT('filter_value_category'), 'filter_id '.$sCond);

                        /* liên kết kho
                        if (!empty($config['erpLink']))
                            chayNgam($config['erpLink'], array(
                                'list' => $aList,
                                'type' => 'filter_create_edit',
                                'action' => 'delete',
                            ));
                        */
                    }
                    elseif ($sType == 'filter_general') {
                        $this->database()->update(Core::getT('filter'), array('filter_general_id' => 0), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND filter_general_id '.$sCond);
                    }
                    /* Bỏ ...
                    // xóa cache cũ tên miền
                    $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$oSession->getArray('session-domain', 'id');
                    $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                    xoa_cache_thu_muc(array(
                        'link' => $oSession->getArray('session-domain', 'id'),
                        'type' => 'all',
                    ));
                    // end
                    xoaGiaTriSession(0);
                    cap_nhat_sitemap();
                    */
                }
            }
            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array('action' => $sType.'-'.$iId,'content' => 'phpinfo',));
        }
        if ($bIsReturn) {
            echo '<-errorvietspider->'.Core_Error::get('error');
        }
    }

    public function getRelated($aParams = array())
    {
        /*
        $_POST['type'] = 'vatgia';
        $_POST['key_word'] = 'samsung';
        /* */

        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $sKeyWord = $aParams['keyword'];

        if ($sKeyWord == '')
            $sError = 'Deny';
        elseif ($oSession->getArray('session-user', 'id') < 1 )
            $sError = 'Deny';
        if (!$sError) {
            $iPage = $aParams['p'];
            $sType = $aParams['type'];

            $sKeyWord = trim(Core::getLib('input')->removeXSS($sKeyWord));
            $sKeyWord = mb_strtolower($sKeyWord, 'UTF8');
            if ($sType == 'user') {
                $aRows = $this->database()->select('id, username, openid, email')
                    ->from(Core::getT('user'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND status = 0 AND ( username LIKE "%'.addslashes($sKeyWord).'%")')
                    ->order('username')
                    ->limit(15)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    if ($aRow['openid'] > 0) {
                        if($aRow['openid'] == 2)
                            $sOpenid = 'Yahoo';
                        elseif($aRow['openid'] == 2)
                            $sOpenid = 'Facebook';
                        else
                            $sOpenid = 'Google';
                    }
                    else
                        $sOpenid = 'None';
                    echo $aRow["id"].'<->'.$aRow["username"].'<->'.$sOpenid.'<->'.$aRow['email'].'<-vietspider->';
                }
            }
            elseif ($sType == 'menu') {
                $aRows = $this->database()->select('id, name')
                    ->from(Core::getT('menu'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND status=1 AND (name LIKE "%'.addslashes($sKeyWord).'%" OR name_code LIKE "%'.addslashes($sKeyWord).'%")')
                    ->order('name')
                    ->limit(15)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    echo $aRow["id"].'<->'.$aRow["name"].'<-vietspider->';
                }
            }
            elseif ($sType == 'slide') {
                $aRows = $this->database()->select('id, name')
                    ->from(Core::getT('slide'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND status=1 AND (name LIKE "%'.addslashes($sKeyWord).'%" OR name_code LIKE "%'.addslashes($sKeyWord).'%")')
                    ->order('name')
                    ->limit(15)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    echo $aRow["id"].'<->'.$aRow["name"].'<-vietspider->';
                }
            }
            elseif ($sType == 'youtube') {
                $sData = lay_du_lieu_curl('http://gdata.youtube.com/feeds/api/videos?q='.urlencode($sKeyWord));
                if($sData != '') {
                    $oRss = simplexml_load_string($sData);
                    foreach ($oRss->entry as $Video) {
                        $sTitle = (string)$Video->title;
                        $iId = (string)$Video->id;
                        $iId = substr($iId, strrpos($iId, '/')+1);
                        echo $iId.'<->'.$sTitle.'<-vietspider->';
                    }
                }
            }
            elseif ($sType == 'vatgia') {
                /* tạm thời chưa sử dụng
                include $config['dir'].'/includes/class.vatgia.php';

                $oVatgia = new vatgia();
                $iPage *= 1;
                if ($iPage < 2)
                    $iPage = 0;
                $aTmps = $oVatgia->searchProduct(array(
                    'keyword' => urlencode($sKeyWord),
                    'page' => $iPage,
                ));
                echo json_encode($aTmps);
                */
                exit;
            }
            else {
                if ($sType == 'de_tai_bai_viet') {
                    //Lấy thông tin đề tài
                    $aRows = $this->database()->select('id, name as title, detail_path')
                        ->from(Core::getT('category'))
                        ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND status=1 AND (name LIKE "%'.addslashes($sKeyWord).'%" OR path LIKE "%'.addslashes($sKeyWord).'%")')
                        ->order('name')
                        ->limit(15)
                        ->execute('getRows');

                    foreach ($aRows as $aRow) {
                        $iKey = $aRow["id"];
                        $aNewsId[] = $iKey;
                        $aNewsName[$iKey] = $aRow["title"];
                        $aNewsPath[$iKey] = $aRow["detail_path"];
                        $aNewsType[$iKey] = 'category';
                    }
                    for ( $i=0; $i < count($aNewsId); $i++) {
                        $iKey = $aNewsId[$i];
                        echo $iKey.'<->'.$aNewsPath[$iKey].'<->'.$aNewsName[$iKey].'<->'.$aNewsType[$iKey].'<-vietspider->';
                    }
                    unset($aNewsId);
                }
                //Lấy thông tim bài viết
                $aRows = $this->database()->select('id, title, detail_path')
                    ->from(Core::getT('article'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND status=1 AND (title LIKE "%'.addslashes($sKeyWord).'%" OR path LIKE "%'.addslashes($sKeyWord).'%")')
                    ->order('title')
                    ->limit(15)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $iKey = $aRow["id"];
                    $aNewsId[] = $iKey;
                    $aNewsName[$iKey] = $aRow["title"];
                    $aNewsPath[$iKey] = $aRow["detail_path"];
                    $aNewsType[$iKey] = 'article';;
                }
                for ( $i=0; $i < count($aNewsId); $i++) {
                    $iKey = $aNewsId[$i];
                    echo $iKey.'<->'.$aNewsPath[$iKey].'<->'.$aNewsName[$iKey].'<->'.$aNewsType[$iKey].'<-vietspider->';
                }
            }
        }
    }

    public function savePosition($aParams = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $sType = $aParams['type'];
        if (empty($sType)) {
            exit();
        }
        // ket thuc session
        $aArray  = array();
        foreach ($aParams as $Key => $Val) {
            $Val *= 1;
            $Key *= 1;
            if ($Val > 0 && $Key > 0 && !isset($aArray[$Key])) {
                $aArray[$Key] = $Val;
            }
        }
        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap').'(1)');
            $bIsReturn = true;
        }
        elseif (empty($aArray)) {
            Core_Error::set('error', 'Deny(1)');
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'create_menu') != 1) {
            Core_Error::set('error', 'Deny(2)');
            $bIsReturn = true;
        }

        $aTypeList = array(
            'filter',
            'filter_general',
            'image_extend'
        );
        if (!in_array($sType, $aTypeList)) {
            Core_Error::set('error', 'Deny(3)');
            $bIsReturn = true;
        }

        if (!$bIsReturn) {
            $bFlag = false;
            foreach ($aArray as $Key => $Val) {
                if( $this->database()->update(Core::getT($sType), array('position' => $Val), 'id = '.$Key)) {
                    $bFlag = true;
                }
            }
            if ($bFlag) {
                Core::getService('core.tools')->updateMenu();
                /* Bỏ, liên quan table Cache
                // xóa cache cũ tên miền
                $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$_SESSION['session-ten_mien']['stt'];
                $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                xoa_cache_thu_muc(array(
                    'link' => $oSession->getArray('session-domain', 'id'),
                    'type' => 'all',
                ));
                xoaGiaTriSession();
                */
                // end;
            }
        }

        if ($bIsReturn) {
            echo '<-errorvietspider->'.Core_Error::get('error');
        }
        else {
            echo '<b>'.Core::getPhrase('language_da-cap-nhat-thanh-cong').'</b>';
        }
    }

    public function checkNameCode($aParam = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $sNameCode = $aParam["name_code"];
        $iId = $aParam["id"];
        $sType = $aParam["type"];

        $sError = '';

        $aSessionUser= $oSession->get('session-user');

        if ($aSessionUser['id'] < 1 || $aSessionUser['priority_group'] == -1) {
            $sError = 1;
        }
        elseif (strlen($sNameCode)>225) {
            $sError = 1;
        }

        $aTypeList = array (
            'filter',
            'filter_general',
            'menu',
            'tab',
            'discount',
            'image_extend',
            'slide',
            'user_group',
            'information_flow',
            'receive_news_group',
            'vendor',
            'unit',
            'area',
            'email_group',
        );
        if (!in_array($sType, $aTypeList)) {
            $sError='Deny(4)';
        }
        if (empty($sError)) {
            $sCond = '';
            $sQueryDomain = ' AND domain_id='.Core::getDomainId();
            $sQueryCode = 'name_code = "'.addslashes($sNameCode);

            $sNameCode = Core::getService('core.tools')->encodeUrl($sNameCode);
            $sNameCode = Core::getLib('input')->removeXSS(trim($sNameCode));
            if ($iId > 0)
                $sCond = ' AND id !='.$iId;

            if ($sType == 'information_flow') {
                $sCond .= ' AND user_id = '.Core::getUserId();
            }
            if ($sType == 'area') {
                $iParentId = isset($aParam['pid']) ? $aParam['pid'] : -1;
                if ($iParentId < 1) {
                    $iParentId = -1;
                }
                $sCond .= ' AND parent_id ='.$iParentId;
                $sQueryDomain = '';
                $sQueryCode = 'code = "'.addslashes($sNameCode);
            }
            $aRow = $this->database()->select('id')
                ->from(Core::getT($sType))
                ->where($sQueryCode.$sQueryDomain.'" '.$sCond)
                ->execute('getRow');
            if($aRow['id'] > 0)
                $sError = 1;
        }
        if (empty($sError)) {
            echo 2;
        }
        else {
            echo 1;
        }
    }

    public function updateShopCustom($aParams = array())
    {
        $type = 'update_shop_custom';
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        $iId = @$aParams["id"]*1;
        $sList = @$aParams["list"];
        $iStatus = addslashes(@$aParams["status"]*1);
        if ($iStatus!=1)
            $iStatus=0;
        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($iId==0 && $sList == '') {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'manage_extends') != 1) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $bIsReturn = true;
        }
        if (!$bIsReturn) {
            $sCond = '';
            if ($iId == 0) {
                $sList = implode(',', $sList);
                $sCond = 'IN ('.$sList.')';
            }
            else
                $sCond = '= '.$iId;
            $this->database()->update(Core::getT('shop_custom_label'), array('status' => $iStatus), 'domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond);
            $status=1;
            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array('action' => $type.'-'.$id,'content' => 'phpinfo',));
            return true;
        }
        return false;
    }

    public function deleteObject($aParams = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $sType = $aParams['type'];

        $iId = @$aParams["id"]*1;
        if ($iId == 0)
            $sList = @$aParams["list"];

        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Core_Error::set('error', 'Deny(1)');
            $bIsReturn = true;
        }
        elseif ($iId==0 && $sList == '') {
            Core_Error::set('error', 'Deny(2)');
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'manage_extend') != 1) {
            Core_Error::set('error', 'Deny(3)');
            $bIsReturn = true;
        }

        $aTypeList = array (
            'filter',
            'filter_general',
        );
        if (!in_array($sType, $aTypeList)) {
            Core_Error::set('error', 'Deny(3)');
            $bIsReturn = true;
        }

        if (!$bIsReturn) {
            if ($iId == 0) {
                $sList = explode(',', $sList);
                foreach ($sList as $Val) {
                    if ($Val*1 > 0)
                        $aTmp[] = $Val*1;
                }
                $sList = implode(',', $aTmp);

                $sCond = 'in ('.$sList.')';
                unset($sList);

                $aRows = $this->database()->select('id')
                    ->from(Core::getT($sType))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    // tái tạo điều kiện
                    $aList[] = $aRow['id'];
                }
                $sCond = 'in ('.implode(',', $aList).')';
            }
            else {
                $aList[] = $iId;
                $sCond = '= '.$iId;
            }
            // trang thái 2 cho xoá
            $iStatus = 2;
            // lấy danh sách trích lọc stt
            $bFlag = $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND id '.$sCond);
            if ($bFlag > 0) {
                if ($sType == 'filter') {
                    $this->database()->update(Core::getT('filter_value'), array('status' => $iStatus),'filter_id '.$sCond);

                    $this->database()->delete(Core::getT('filter_value_article'), 'filter_id '.$sCond);

                    $this->database()->delete(Core::getT('filter_value_category'), 'filter_id '.$sCond);
                    /* liên kết kho
                    if (!empty($config['erpLink']))
                        chayNgam($config['erpLink'], array(
                            'list' => $aList,
                            'action' => 'delete',
                        ));
                    */
                }
                else {
                    //Cập nhật id trích lọc tổng của trich lọc về 0
                    $this->database()->update(Core::getT('filter'), array('filter_general_id' => 0), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND filter_general_id '.$sCond);
                }
                /*
                // xóa cache cũ tên miền
                $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$oSession->getArray('session-domain', 'id');
                $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                xoa_cache_thu_muc(array(
                    'link' => $oSession->getArray('session-domain', 'id'),
                    'type' => 'all',
                ));
                // end
                xoaGiaTriSession(0);
                cap_nhat_sitemap();
                */
            }
            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array('action' => 'detele_object'.'-'.$iId,'content' => 'phpinfo',));
        }
        if ($bIsReturn) {
            echo '<-errorvietspider->'.Core_Error::get('error');
        }
    }

    public function searchObject($aParam = array())
    {
        /*Gia lap *
        $aParam = array(
            'keyword' => 'banh',
            'type' => 'product',
        );
        /* */
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $sKeyWord = $aParam['keyword'];

        $sError = '';
        if ($sKeyWord == '')
            $sError = 'Deny(1)';
        elseif ($oSession->getArray('session-user', 'id') < 1 )
            $sError = 'Deny(2)';

        $aMappingType = array(
            'user',
            'product',
            'news',
        );

        $sType = $aParam['type'];

        if (!in_array($sType, $aMappingType)) {
            $sError = 'Deny(3)';
        }

        $iVendorId = isset($aParam['vendor_id']) ? $aParam['vendor_id'] : -1;

        $aOutput = array();

        if (!$sError) {

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
                if ($iPageSize < 1 || $iPageSize > 30) {
                    $iPageSize = 10;
                }
            }

            $sKeyWord = trim(Core::getLib('input')->removeXSS($sKeyWord));
            $sKeyWord = mb_strtolower($sKeyWord, 'UTF8');

            $aData = array();
            if ($sType == 'product') {
                $sConds = '';
                if ($iVendorId > 0) {
                    $sConds = ' AND i.vendor_id ='.$iVendorId;
                }

                $iCnt = $this->database()->select('count(*)')
                    ->from(Core::getT('filter_influence'), 'i')
                    ->join(Core::getT('article') , 'a', 'i.article_id = a.id')
                    ->where('a.domain_id = '.$oSession->getArray('session-domain', 'id').' AND a.status = 1 AND (a.title LIKE "%'.addslashes($sKeyWord).'%" OR a.path LIKE "%'.addslashes($sKeyWord).'%")'.$sConds)
                    ->execute('getField');

                if ($iCnt > 0) {
                    $aRows = $this->database()->select('i.id, i.article_id, i.vendor_id, i.price_sell, i.price_discount, i.price_coin_buy, i.price_coin_rental, i.sku, i.sku_vendor, a.title, a.detail_path, a.image_path')
                        ->from(Core::getT('filter_influence'), 'i')
                        ->join(Core::getT('article') , 'a', 'i.article_id = a.id')
                        ->where('a.domain_id = '.$oSession->getArray('session-domain', 'id').' AND a.status = 1 AND (a.title LIKE "%'.addslashes($sKeyWord).'%" OR a.path LIKE "%'.addslashes($sKeyWord).'%")'.$sConds)
                        ->order('a.title')
                        ->limit($iPage, $iPageSize,$iCnt)
                        ->execute('getRows');

                    foreach ($aRows as $aRow) {
                        if (!empty($aRow['sku'])) {
                            $aRow['sku'] = str_replace(Core::getDomainId().'|', '', $aRow['sku']);
                        }
                        if (!empty($aRow['sku_vendor'])) {
                            $aRow['sku_vendor'] = str_replace(Core::getDomainId().'|'.$aRow['vendor_id'].'|', '', $aRow['sku_vendor']);
                        }

                        $aData[] = $aRow;
                    }
                }

            }
            $aOutput = array(
                'status' => 'success',
                'total' => 0,
                'page' => $iPage,
                'page_size' =>$iPageSize,
                'data' => $aData,
            );
        }
        else {
            $aOutput = array(
                'status' => 'error',
                'message' => $sError,
            );
        }

        return $aOutput;
    }

    public function addProduct($aParam = array())
    {
        /*Gia lap *
        $aParam = array(
            'article_id' => 6193,
            'order_id' => 2433,
            'filer_influence_id' => 6193,
            'quantity' => 2,
        );
        /* */

        $iId =  isset($aParam['article_id']) ? $aParam['article_id'] : -1;
        $iQuantity =  isset($aParam['quantity']) ? $aParam['quantity'] : -1;
        $iIdInfluence =  isset($aParam['filer_influence_id']) ? $aParam['filer_influence_id'] : -1;
        $iOrderId =  isset($aParam['order_id']) ? $aParam['order_id'] : -1;

        $sError = '';
        if ($iId < 1 || $iIdInfluence < 1 || $iOrderId < 1 || $iQuantity < 1) {
            $sError = 'Deny (1)';
        }

        if  (Core::getUserId() < 1) {
            $sError = 'Deny (2)';
        }

        $aOutput = array();
        if (empty($sError)) {
            $aOrder = $this->database()->select('id, status_deliver')
                ->from(Core::getT('shop_order'))
                ->where('id ='.$iOrderId)
                ->execute('getRow');

            $aDelivery = array(
                'da-nhan-hang' => 'Đã hoàn thành',
                'da-huy' => 'Đã hủy',
            );

            if (!isset($aOrder['id']) || in_array($aOrder['status_deliver'], $aDelivery)) {
                $sError = 'Deny (3)';
            }

            if (empty($sError)) {
                $aInfluence = $this->database()->select('i.id, i.article_id, i.vendor_id, i.price_sell, i.price_discount, i.price_coin_buy, i.price_coin_rental, i.sku, i.sku_vendor, a.title, a.category_id, a.detail_path, a.image_path')
                    ->from(Core::getT('filter_influence'), 'i')
                    ->join(Core::getT('article'), 'a', 'a.id = i.article_id')
                    ->where('i.id ='.$iIdInfluence. ' AND i.sku != \'\'')
                    ->execute('getRow');

                if (!isset($aInfluence['id'])) {
                    $sError = 'Deny (4)';
                }
                else {
                    //insert order_detail
                    if (!empty($aInfluence['sku'])) {
                        $aInfluence['sku'] = str_replace(Core::getDomainId().'|', '', $aInfluence['sku']);
                    }
                    $aInsert = array(
                        'shop_order_id' => $iOrderId,
                        'article_id' => $iId,
                        'sku' => $aInfluence['sku'],
                        'quantity' => $iQuantity,
                        'price_discount' => $aInfluence['price_discount'],
                        'unit_price' => $aInfluence['price_sell'] - $aInfluence['price_discount'],
                        'vendor_id' => $aInfluence['vendor_id'],
                        'status' => 1,
                        'category_id' => $aInfluence['category_id'],
                    );

                    $aOrderDt = $this->database()->select('id, article_id, sku, quantity')
                        ->from(Core::getT('shop_order_dt'))
                        ->where('shop_order_id ='.$iOrderId.' AND article_id ='.$iId.' AND sku =\''.$aInfluence['sku'].'\'')
                        ->execute('getRow');

                    if (isset($aOrderDt['id'])) {
                        $iQuantity = $aOrderDt['quantity'] + $iQuantity;
                        $aInsert['quantity'] = $iQuantity;
                        $this->database()->update(Core::getT('shop_order_dt'), array('quantity' => $iQuantity), 'id ='.$aOrderDt['id']);
                    }
                    else {
                        $aOrderDt['id'] = $this->database()->insert(Core::getT('shop_order_dt'), $aInsert);
                    }

                    $aInsert['id'] = $aOrderDt['id'];
                    $aInsert['title'] = $aInfluence['title'];
                    $aInsert['detail_path'] = $aInfluence['detail_path'];
                    $aInsert['image_path'] = $aInfluence['image_path'];
                    $aOutput['data'] = $aInsert;
                }
            }
        }

        if (!empty($sError)) {
            $aOutput['status'] = 'error';
            $aOutput['message'] = $sError;
        }
        else {
            $aOutput['status'] = 'success';
        }
        return $aOutput;
    }

    public function registerReceiveNews($aParam =array())
    {
        /* Gia Lap *
        $aParam = array(
            'email' => 'name111@gmail.com',
            'status' => 1,
        );
        /* */

        $sType = 'receive_news';

        $iId = -1;
        if (isset($aParam['id'])) {
            $iId = $aParam['id'];
            unset($aParam['id']);
        }

        $iStatus = 1;

        $iGroupId = 0;
        $aGroups = array();
        $rows = array(
                'id' => 0,
                'name' => 'Default',
                'name_code' => 'default',
        );
        $aGroups[] = array(
                'id' => $rows['id'],
                'name' => $rows['name'],
                'name_code' => $rows['name_code'],
            );
        // lấy danh sách nhóm
        $aRows = $this->database()->select('*')
                ->from(Core::getT('receive_news_group'))
                ->where('domain_id ='. Core::getDomainId() .' AND status != 2')
                ->execute('getRows');
        foreach ($aRows as $rows) {
            $aGroups[] = array(
                'id' => $rows['id'],
                'name' => $rows['name'],
                'name_code' => $rows['name_code'],
            );
        }
        $aFields = array(
            'sFullname' => 'fullname',
            'iSex' => 'sex',
            'sNickname' => 'nickname',
            'sEmail' => 'email',
            'iStatus' => 'status',
            'sBirthday' => 'birthday',
            'sPhoneNumber' => 'phone_number',
            'iGroupId' => 'receive_news_group_id'
        );

        $aErrors = array();

        $iUserId = Core::getUserId();

        //if ($iUserId < 1) {
//            $aErrors[] = 'Không có quyền truy cập';
//        }

        if(empty($aErrors))
        {
            foreach($aFields as $sKey => $v)
            {
                $$sKey = $aParam[$v];
            }

            // check fullname
            if (!empty($sFullname) && (mb_strlen($sFullname)<5 || mb_strlen($sFullname)>225))
                $aErrors[] = sprintf(Core::getPhrase('language_x-phai-nhieu-hon-x-va-it-hon-x-ky-tu'), Core::getPhrase('language_ho-va-ten'), 6, 225);
            // end

            // check nickname
            if(mb_strlen($sNickname)>225) $sNickname = mb_substr($sNickname, 0, 225);
            // end

            if(!empty($sBirthday))
            {
                $aArr=explode("/", $sBirthday);
                if(count($aArr)!=3) $aArr=explode("-", $sBirthday);
                @$dd=$aArr[0];
                @$mm=$aArr[1];
                @$yy=$aArr[2];
                $sBirthdayTmp = $yy.'-'.$mm.'-'.$dd;
                if(!@checkdate($mm,$dd,$yy)) $error.=Core::getPhrase('language_ngay-sinh-khong-ton-tai');
                else
                {
                    $sBirthday = $dd.'-'.$mm.'-'.$yy;
                }
            }
            else $sBirthday = '';

            // Check email
            if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $sEmail)==0) $aErrors[] = Core::getPhrase('language_hop-thu-khong-dung-cau-truc');
            if (strlen($sEmail)<=3 || strlen($sEmail)>224) $aErrors[] = sprintf(Core::getPhrase('language_hop-thu-phai-it-nhat-x-ky-tu-va-nho-hon-x-ky-tu'), 3, 224);
            $sEmail = strtolower($sEmail);

            $iSex = $iSex*1;
            if($iSex !=1 ) $iSex = 0;

            $iStatus = $aParam['status']*1;
            if($iStatus != 1) $iStatus = 0;

            $iGroupId = isset($aParam['receive_news_group_id']) ? $aParam['receive_news_group_id'] : 0;
            if($iGroupId < 0) $iGroupId = 0;

            $aList[] = array(
                'fullname' => $sFullname,
                'nickname' => $sNickname,
                'email' => $sEmail,
                'sex' => $iSex,
                'status' => $iStatus,
                'birthday' => $sBirthdayTmp,
                'phone_number' => $sPhoneNumber,
                'receive_news_group_id' => $iGroupId,
            );
        }

        // kiểm tra xem id đã tồn tại chưa
        if(empty($aErrors) && $iId > 0)
        {
            // lấy đề tài stt và tên miền stt
            $rows = $this->database()->select('id')
                    ->from(Core::getT('receive_news'))
                    ->where('domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId)
                    ->execute('getField');
            if($rows < 1) $aErrors[] = sprintf(Core::getPhrase('language_x-khong-ton-tai'), Core::getPhrase('language_du-lieu'));
            // end
        }

        if(empty($aErrors))
        {
            $sQuery = '';
            // kiểm tra id
            if($iId > 0)
            {
                $sQuery = ' id != '.$iId.' AND ';
            }
            // end
            // lấy đề tài stt và tên miền stt
            $iPosition = 0;
            while (1) {
                $sSql = '';
                $iCount = 0;

                for ($i = $iPosition; $i < count($aList); $i++) {
                    $iCount++;
                    if($iCount == 100) break;
                    $sEmail = $aList[$i]['email'];

                    if(empty($sEmail)) continue;
                    $sSql .= '"'.addslashes($sEmail).'",';
                }

                $iPosition = $i;
                if ($iCount > 100 || $sSql == '') break;
                $sSql = rtrim($sSql, ',');

                $aRows = $this->database()->select('email')
                        ->from(Core::getT('receive_news'))
                        ->where($sQuery.'domain_id ='. Core::getDomainId().' AND status != 2 AND email IN ('.$sSql.')')
                        ->execute('getRows');

                foreach ($aRows as $rows) {
                    foreach ($aList as $key => $val) {
                        if($aList[$key]['email'] != $rows['email']) continue;
                        unset($aList[$key]);
                    }
                }
            }
            if(empty($aList)) $aErrors[] = 'Email đã tồn tại';
        }

        if (empty($aErrors)) {
            $aUpdate = array();
            // tiến hành update or insert
            // cập nhật
            if ($iId > 0) {

                foreach ($aList as $val) {
                    foreach($aFields as $v) {
                        $aUpdate[$v] = $val[$v];
                    }
                }

                $this->database()->update(
                    Core::getT('receive_news'),
                    $aUpdate,
                    'domain_id ='. Core::getDomainId() .' AND status != 2 AND id = '.$iId
                );
            }
            else {
                foreach ($aList as $val) {
                    $aUpdate = array();
                    foreach ($aFields as $sKey => $v) {
                        if (!isset($val[$v]) || $val[$v] === null) {
                            continue;
                        }
                        $aUpdate[$v] = $val[$v];
                    }
                    //
                    $aUpdate['code'] = Core::getService('core.tools')->getUniqueCode();

                    $aUpdate['time'] = CORE_TIME;
                    $aUpdate['domain_id'] = Core::getDomainId();

                    $iId = $this->database()->insert(
                    Core::getT('receive_news'),
                        $aUpdate
                    );
                }
            }
            // ghi log hệ thống
            /*
            Core::getService('core.tools')->saveLogSystem(array(
                'action' => $sType.'-'.$iId,
                'content' => 'phpinfo',
            ));
            */
            // end
        }

        $aOutput = array();
        if (empty($aErrors)) {
            $aOutput['status'] = 'success';
            $aOutput['id'] = $iId;
        }
        else {
            $aOutput['status'] = 'error';
            $aOutput['message'] = $aErrors;
        }
        return $aOutput;
    }

    public function removeCache($aParam = array())
    {
        // tạm thời clear cache trực tiếp.
        $this->callRemoveCache();
        return false;

        $this->cache()->remove();
        // add vào cronjob.
        $iDelayTime = (isset($aParam['delay_time'])) ? $aParam['delay_time'] : 10;
        $iDelayTime = $iDelayTime * 60;

        Core::getService('core.tools')->addCronJob(array(
            'type' => 'all',
            'type_id' => 0,
            'time' => CORE_TIME,
            'perform_time' => CORE_TIME + $iDelayTime,// 30*60*60
            'action' => 'clear_cache',
            'content' => serialize(array(
                'data' => '',
            )),
            'status' => 0,
        ));
    }

    public function callRemoveCache()
    {
        $sDomainName = Core::getDomainName();
        if (empty($sDomainName)) {
            return false;
        }
        $sSubDomain = 'www.';
        $this->cache()->remove();
        $sUrl = $sSubDomain.$sDomainName.'/tools/clearcache.php?localauth=1&pc2id='.$_COOKIE['pc2id'].'&'; // link server
        //$sUrl = $sSubDomain.$sDomainName.'/tools/clearcache.php'; // link local
        Core::getService('core.tools')->openProcess(
            array(
                'url' => $sUrl,
            ),
            array()
        );
        $sUrl = $sDomainName.'/222.php?localauth=1&pc2id='.$_COOKIE['pc2id'].'&';
        Core::getService('core.tools')->openProcess(
            array(
                'url' => $sUrl,
                'domain' => -1,
            ),
            array()
        );
    }

    public function convertUnit()
    {
        //Lấy Unit từ table article_unit chuyển qua table article theo id (vì id 2 bên giống nhau)
        $iCnt = $this->database()->select('count(*)')
            ->from(Core::getT('article_unit'))
            ->where('1')
            ->execute('getField');

        $iMax = 3000;
        $iPage = ceil($iCnt/$iMax);
        $iCount = 0;
        for ($i = 0; $i < $iPage ; $i++) {
            $aRows = $this->database()->select('id, unit_id')
                ->from(Core::getT('article_unit'))
                ->where('1')
                ->order('id ASC')
                ->limit($i + 1, $iMax, $iCnt)
                ->execute('getRows');

            foreach ($aRows as $aRow) {
                if ($aRow['unit_id'] > 0) {
                    $this->database()->update(
                        Core::getT('article'),
                        array(
                            'unit_id' => $aRow['unit_id'],
                        ),
                        'id ='.$aRow['id']
                    );
                }
            }
        }
    }

    public function deleteSetting($aParam = array())
    {
        $oSession = Core::getLib('session');
        $sAct = isset($aParam['act']) ? $aParam['act'] : '';
        $sType = isset($aParam['type']) ? $aParam['type'] : '';
        $aMapping = array(
            'setting_gmail' => 'email',
            'setting_facebook' => 'facebook',
            'setting_floor_link' => 'floor_link',
        );

        $aOutput = array();
        if (Core::getUserId() < 1 || $sAct != 'del' || !in_array($sType, array_keys($aMapping))) {
            Core_Error::set('error', Core::getPhrase('language_khong-co-quyen-truy-cap'));
            $aOutput['status'] = 'error';
            $aOutput['err'] = Core_Error::get('error');

        }

        if (Core_Error::isPassed()) {
            $aData = '';
            $oSession->remove('session-'.$sType);
            $this->database()->update(
                Core::getT('domain_setting'),
                array(
                    $aMapping[$sType] => $aData,
                ),
                'domain_id ='.Core::getDomainId()
            );

            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array('action' => $sType.'-'.$id,'content' => 'phpinfo',));
            $aOutput['status'] = 'success';
        }

        return $aOutput;
    }

    public function sendEmail($aParams = array())
    {
        $bIsReturn = false;
        $oSession = Core::getLib('session');
        /*
        //$argv[] = '--filepostd:/server/he_thong/xu_ly/cache/data/343/343_76158_1399360378.0389.sys';
        if(!empty($argv)) {
            $_GET = $_POST = array(); //Reset
            foreach ($argv as $iKey => $Value) {
                if (substr($Value,0,5) == '--get') {
                    $sIn = substr($Value, 5);
                    $oTmps = unserialize(base64_decode($sIn));
                    $_GET = $oTmps;
                }
                elseif (substr($Value,0,6) == '--post') {
                    $sIn = substr($Value, 6);
                    if (substr($sIn, -1, 1) == '"')$sIn = substr($sIn, 0, -1);
                        $oTmps = unserialize(base64_decode($sIn));
                    $_POST = $oTmps;
                }
                elseif (substr($Value,0,10) == '--filepost') {
                    $sIn = substr($Value, 10);
                    if (substr($sIn, -1, 1) == '"')
                        $sIn = substr($sIn, 0, -1);
                    // mơ tập tin tạm
                    $oTmps = unserialize(file_get_contents($sIn));
                    $_POST = $oTmps;
                    unlink($sIn);
                }
            }
            $config['dir'] = __DIR__;
            $config['dir'] = substr($config['dir'], 0, strpos($config['dir'], 'xu_ly') + 5);
            $config['dir'] = str_replace('\\', '/', $config['dir']);
        }
        else
            $config['dir'] = '..';
        set_time_limit(600);
        ignore_user_abort(true);
        /* gia lap
        if(empty($_POST['sid']))
        {
            $mang = 'me@vohoangtuan.com
    Go|me@vohoangtuan.com
     Khách hàng liên hệ từ trang web: tragop.vn
    : <b>Đặt mua trả góp - tragop.vn</b><br /> Họ và tên: <b>Võ Hoàng Tuấn</b><br /> Hộp thư: <b>0903090209@yahoo.com</b><br /> Số điện thoại: <b>090309020903</b><br /> Nội dung: <br />Mua trả góp sản phẩm<br />http://tragop.vn/dien-lanh/may-giat/cua-ngang/may-giat-electrolux-ewf1073.html<br />Chương trình góp Tín dụng ANZ<br />Địa chỉ:Hồ chí minh
    don_hang_cho_xu_ly
    a:1:{s:3:"stt";i:1240;}
    8123eipadnjqr7go2nclid8mu4';
            $mang = explode("\n", $mang);
            $_POST['e'] = trim($mang[0]);
            $_POST['n'] = trim($mang[1]);
            $_POST['t'] = trim($mang[2]);
            $_POST['c'] = trim($mang[3]);
            $_POST['loai'] = trim($mang[4]);
            $_POST['ghi_chu'] = trim($mang[5]);
            $_POST['sid'] = trim($mang[6]);
        }
        /* */
        $sSid = $aParams['sid'];
        if ($sSid == '') {
            Core_Error::set('error', 'deny-1');
            $bIsReturn = true;
        }

        if (isset($aParams['domain']))
            $_SERVER['HTTP_HOST'] = $aParams['domain'];

        //$aParams['loai'] == 'don_hang_cho_xu_ly'
        if ($aParams['type'] == 'order_waiting_process') {
            $aPaymentGateway = array(
                'ngan_luong' => Core::getPhrase('language_qua-ngan-luong'),
                'bao_kim' => Core::getPhrase('language_qua-bao-kim'),
                'onepay' => (Core::getPhrase('language_qua-onepay') != '' ? Core::getPhrase('language_qua-onepay') : 'onepay'),
                'smartlink' => (Core::getPhrase('language_qua-smartlink') != '' ? Core::getPhrase('language_qua-smartlink') : 'smartlink'),
                'paypal' => (Core::getPhrase('language_qua-paypal') != '' ? Core::getPhrase('language_qua-paypal') : 'paypal'),
                'vtcpay' => (Core::getPhrase('language_qua-vtcpay') != '' ? Core::getPhrase('language_qua-vtcpay') : 'vtcpay'),
                'chuyen_khoan' => Core::getPhrase('language_qua-chuyen-khoan'),
                'noi_nhan' => Core::getPhrase('language_tai-noi-nhan'),
                'diem' => 'Điểm',
                'cong-thanh-toan' => 'Cổng thanh toán'
            );
            $sEmail = $aParams['e'];
            $sUserName = $aParams['n'];
            $aNote = $aParams['note'];
            $sTitle = $oSession->getArray('session-domain', 'name').': Đơn hàng đang chờ xử lý';
            // convert ghi_chu to array
            $aNote = unserialize($aNote);
            // Get ID from ghi_chu
            $iId = $aNote['id'];

            // lấy thông tin order của hàng hóa
            $aOrder = Core::getService('shop.order')->getByDetail(array(
                'id' => $iId,
            ));

            /*
            $aShopOrder = array();
            $aShopOrderDetail = array();
            $aArticle = array();

            $aRows = $this->database()->select('*')
                ->from(Core::getT('shop_order'))
                ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id = '.$iId)
                ->execute('getRows');

            foreach ($aRows as $aRow) {
                $aShopOrder['user_id'][] = $aRow["user_id"];
                $aShopOrder['ip'][] = long2ip($aRow["ip"]);

                $aShopOrder['id'][] = $aRow["id"];
                $aShopOrder['code'][] = $aRow["code"];
                $aShopOrder['total_amount'][] = Core::getService('core.currency')->formatMoney(array('money' => $aRow["total_amount"] ));
                $aShopOrder['total_product'][] = $aRow["total_product"];
                $aShopOrder['customer'][] = array($aRow["fullname"], $aRow["address"], $aRow["phone_number"]);
                $aShopOrder['payment_gateway'][] = $aPaymentGateway[$aRow["payment_gateway"]];
                $aShopOrder['time'][] = date(Core::getPhrase('language_dinh_nghia_thoi_gian_full'), $aRow["time"]);
                $aShopOrder['note'][] = $aRow["note"];
            }

            // thông tin chi tiết đơn hàng
            $aRows = $this->database()->select('a.id as id, a.title as name, detail_path, quantity, price_discount, unit_price, note, vendor_id')
                ->from(Core::getT('shop_order_dt'), 's')
                ->join(Core::getT('article'), 'a', ' a.id = s.article_id')
                ->where('shop_order_id = '.$iId)
                ->execute('getRows');

            foreach ($aRows as $aRow) {
                $aShopOrderDetail['id'][] = $aRow["id"];
                $aShopOrderDetail['name'][] = $aRow["name"];
                $aShopOrderDetail['path'][] = 'http://'.$oSession->getArray('session-domain', 'name').$aRow["detail_path"];
                $aShopOrderDetail['quantity'][] = $aRow["quantity"];
                $aShopOrderDetail['price_discount'][] = Core::getService('core.currency')->formatMoney(array('money' => $aRow["price_discount"]));
                $aShopOrderDetail['unit_price'][] = Core::getService('core.currency')->formatMoney(array('money' => $aRow["unit_price"] + $aRow["price_discount"]));
                $aShopOrderDetail['amount'][] = Core::getService('core.currency')->formatMoney(array('money' => $aRow["quantity"]*$aRow["unit_price"]));
                $aShopOrderDetail['note'][] = $aRow["note"];
                $aShopOrderDetail['vendor_id'][] = $aRow["vendor_id"];
            }

            // lấy Thông tin bài viết
            if (!empty($aShopOrderDetail['id'])) {
                $aRows = $this->database()->select('*')
                    ->from(Core::getT('shop_article'))
                    ->where('article_id IN ('.implode(',', $aShopOrderDetail['id']).')')
                    ->execute('getRows');

                foreach ($aRows as $aRow) {
                    $iTmp = $aRow['article_id'];
                    unset($aRow['id']);
                    unset($aRow['article_id']);
                    unset($aRow['domain_id']);
                    //Chuyển những kiểu dữ liệu kiểu số
                    foreach ($aRow as $Key => $Val) {
                        if(is_nan($iKey))
                            continue;
                        if ($Key == 'price_sell'
                            || $Key == 'price_discount'
                            || $Key == 'weight'
                            || $Key == 'buy_quantity'
                            || $Key == 'total_quantity'
                            || $Key == 'necessary_quantity') {
                            $Val *= 1;
                        }

                        $aRow[$Key] = $Val;
                    }
                    for ( $i=0; $i < count($aShopOrderDetail['id']); $i++) {
                        // kiem tra xem co gia cho san pham nay ko
                        if ($aShopOrderDetail['id'][$i] == $iTmp) {
                                foreach ($aRow as $Key => $Val) {
                                    $aArticle[$Key][$i] = $Val;
                                }
                                $aArticle['amount'][$i] = $aRow["price_sell"]*1 - $aRow["price_discount"]*1;
                                $Key = 'deliver_method';
                                if ($aArticle[$Key][$i] == 1)
                                    $aArticle[$Key][$i] = 'Delivery Receipt';
                                else
                                    $aArticle[$Key][$i] = 'Delivery Products';
                        }
                    }
                }
            }

            */

            $sContent = Core::getLib('template')->assign(array(
                    'aOrder' => $aOrder,
                    'sUserName' => $sUserName,
                    'sDomain' => $aParams['domain'],
                )
            )->getLayout('email_order', true);

            Core::getService('core.tools')->sendEmail($sEmail, $sUserName, $sTitle, $sContent);
        }
        else if ($aParams['type'] == 'export_data') // xoa, ko con sử dụng, vì dùng notification
        {
            $sEmail = $aParams['e'];
            $sUserName = $aParams['n'];
            $aNote = $aParams['ghi_chu'];
            $sTitle = $oSession->getArray('session-domain', 'name').': Export data';
            // convert ghi_chu to array
            $aNote = unserialize($aNote);
            // Get ID from ghi_chu
            $sLinkUrl = $aNote['url'];

            $sTracking =
                '
                <span style="">
                    <img src="http://'.$oSession->getArray('session-domain', 'name').'/tools/trackEmail.php?key='.$ma_so.'&ukey={ma_so_thanh_vien}&type='.$den.'" style="border:0;width:1px;height:1px;" />
                    <bgsound src="http://'.$oSession->getArray('session-domain', 'name').'/tools/trackEmail.php?key='.$ma_so.'&ukey={ma_so_thanh_vien}&type='.$den.'" volume="-10000"/>
                </span>
                ';

            $sLinkViewWeb = 'http://'.$oSession->getArray('session-domain', 'name').'/tools/viewEmail.php?key='.$ma_so.'&ukey={ma_so_thanh_vien}&type='.$den;

            $iEmailTemplateId = 1;
            $sContent = 'Download export data: <a href="'.$sLinkUrl.'" target="_blank">'.$sLinkUrl.'</a>.';
            // end
            ob_start();
            include $config['dir'].'/templates/newsletter/'.$iEmailTemplateId.'/index.php';
            $sContent = ob_get_clean();
            ob_end_clean();
            if ($sContent == '') {
                Core_Error::set('error', 'Not found template');
                return fasle;
            }
            Core::getService('core')->sendEmail($sEmail, $sUserName, $sTitle, $sContent);
        }
        elseif ($aParams['type'] == 'newsletter') {
            $sUrl = 'http://'.$oSession->getArray('session-domain', 'name').'/tools/unsubscription.php';

            $iDebug = $aParams['debug']*1;

            $aTo = unserialize(urldecode($aParams['to']));
            $sTitle = $aParams['t'];
            $sContent = $aParams['c'];
            $iLinkId = $aParams['link_id']*1;
            $aAdditional = array();
            if (!empty($aParams['additional'])) {
                $aAdditional = unserialize(urldecode($aParams['additional']));
            }
            $aFrom = '';
            if (!empty($aParams['n'])) {
                $aFrom = unserialize(urldecode($aParams['n']));
            }
            // Kiểm tra danh sách bài viết có tồn tại hay không
            if (!$bIsReturn) {
                $aRow = $this->database()->select('id, link_id as template_email_id')
                    ->from(Core::getT('top_article'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id = '.$iLinkId)
                    ->execute('getRow');
                if ($aRow['id'] < 1) {
                    Core_Error::set('error', 'Article List does not exist!');
                    $bIsReturn = true;
                }
                $iEmailTemplateId = $aRow['template_email_id'];
            }
            // lấy danh sách bài viết từ form mẫu
            if (!$bIsReturn) {
                $aRows = $this->database()->select('a.id as id, a.title as title, a.description as description, a.profile_image as profile_image, a.article_extend_id as extends, a.detail_path as detail_path, a.image_path as image_path')
                    ->from(Core::getT('top_article_value'), 't')
                    ->join(Core::getT('article'), 'a', 'a.id = t.article_id AND a.status != 2')
                    ->where('t.top_article_id = '.$iLinkId)
                    ->order('t.position')
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aNewsId[] = $aRow["id"];
                    $sTmp = stripslashes($aRow["title"]);
                    $aNewsName[] = $sTmp;
                    $aNewsNameHtml[] = htmlspecialchars($sTmp);
                    $aNewsDes[] = $aRow["description"];
                    $aNewsImagePath[] = $aRow["image_path"];
                    $aNewsProfileImage[] = $aRow["profile_image"];
                    // cập nhật link bổ xung
                    /*
                    ví dụ
                    ?utm_source=Email&utm_medium={link}&utm_term={link}&utm_content={title}&utm_campaign=Email_marketing
                    */
                    $sLinkTmp = 'http://'.$oSession->getArray('session-domain', 'name').$aRow["detail_path"];
                    if (!empty($aAdditional['link'])) {
                        if (strpos($aAdditional['link'], '?') === false) {
                            $aAdditional['link'] = '?'.$aAdditional['link'];
                        }
                        $sTmps = $aAdditional['link'];
                        $sTmps = str_replace('{link}', urlencode($sLinkTmp), $sTmps);
                        $sTmps = str_replace('{title}', urlencode($sTmp), $sTmps);
                        $sLinkTmp .= $sTmps;
                    }
                    $aNewsLinkPath[] = $sLinkTmp;

                    $aNewsExtend[] = $aRow["extends"];
                }
                for ( $i=0; $i<count($aNewsId); $i++) {
                    // kiem tra xem co gia cho san pham nay ko
                    if ($aNewsExtend[$i]>0) {
                        $aArticleList[] = $aNewsId[$i];
                    }
                }

                // web shop
                if (!empty($aArticleList)) {
                    $aRows = $this->database()->select('*')
                        ->from(Core::getT('shop_article'))
                        ->where('article_id IN ('.implode(',', $aArticleList).')')
                        ->execute('getRows');
                    foreach ($aRows as $aRow) {
                        $iTmp = $aRow['article_id'];
                        unset($aRow['id']);
                        unset($aRow['article_id']);
                        unset($aRow['domain_id']);
                        foreach ($aRow as $iKey => $sVal) {
                            if (is_nan($iKey))
                                continue;
                            if($iKey == 'price_sell'
                                || $iKey == 'price_discount'
                                || $iKey == 'trong_luong'
                                || $iKey == 'so_luong_da_mua'
                                || $iKey == 'so_luong_tong'
                                || $iKey == 'so_luong_can')
                                $iKey *= 1;
                            $aRow[$iKey] = $sVal;
                        }
                        for ( $i=0; $i<count($aNewsId); $i++) {
                            // kiem tra xem co gia cho san pham nay ko
                            if ($aNewsId[$i] == $iTmp) {
                                    foreach ($aRow as $iKey => $Val) {
                                        $aArticle[$iKey][$i] = $Val;
                                    }
                                    $aArticle['amount'][$i] = $aRow["price_sell"]*1 - $aRow["price_discount"]*1;
                            }
                        }
                    }
                }
            }
            $sSelect = '';
            $sCond = '';
            $sTable = '';
            // thêm từ mã số đến mã số.
            if (!$bIsReturn) {
                $iFromNumber = $aParams['from']*1;
                if ($iFromNumber < 1)
                    $iFromNumber = 1;
                $iFromNumber -= 1;
                $iToNumber = $aParams['to']*1;
            }
            // tiến hành lấy danh sách email trong nhom_nhan_email
            if (!$bIsReturn) {
                if ($aTo['type'] == 'newsletter') {
                    if ($aTo['id'] > 0) {
                        $sCond = 'receive_news_group_id = '.$aTo['id'];
                    }
                    $sSelect = 'code, fullname as name, nickname, sex, email';
                    $sTable = 'receive_news';
                }
                else if($aTo['loai'] == 'member') {
                    if($aTo['id'] > 0) {
                        $sCond = 'user_group_id = '.$aTo['id'];
                    }
                    $sSelect = 'code, username as name, sex, email';
                    $sTable = 'user';
                }
                $sCond = 'domain_id='.$oSession->getArray('session-domain', 'id').' AND status = 1'.$sCond;
                $aRows = $this->database()->select($sSelect)
                    ->from($sTable)
                    ->where($sCond)
                    ->limit($iFromNumber - 1, $iToNumber - $iFromNumber +1)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    $aList[] = array(
                        'code' => $aRow['code'],
                        'fullname' => $aRow['name'],
                        'email' => $aRow['email'],
                        'nickname' => $aRow['nickname'],
                        'sex' => $aRow['sex'],
                    );
                }
                if (empty($aList)) {
                    Core_Error::set('error', 'Email receiver is empty!');
                    $bIsReturn = true;
                }
            }
            $sReceiver = '';
            foreach ($aList as $Val) {
                $sReceiver .= $Val['fullname']."\n".$Val['email']."\n".$Val['nickname']."\n".$Val['sex']."\t\n";
            }
            // lấy danh sách bài viết

            // tính dữ liệu
            $iQuantity = count($aList);
            //$ma_so = lay_chuoi_duy_nhat();
            $sCode = Core::getService('manage.tools')->getRandomCode();
            $aInsert = array (
                'code' => addslashes($sCode),
                'quantity' => $iQuantity,
                'title' => addslashes($sTitle),
                'template' => $iEmailTemplateId,
                'time' => time(),
                'domain_id' => $oSession->getArray('session-domain', 'id')
            );
            // lưu db
            $iId = $this->database()->insert(Core::getT('log_email'), $aInsert);
            $sTracking =
                '
                <span style="">
                    <img src="http://'.$oSession->getArray('session-domain', 'name').'/tools/trackEmail.php?key='.$sCode.'&ukey={ma_so_thanh_vien}&type='.$aTo.'" style="border:0;width:1px;height:1px;" />
                    <bgsound src="http://'.$oSession->getArray('session-domain', 'name').'/tools/trackEmail.php?key='.$sCode.'&ukey={ma_so_thanh_vien}&type='.$aTo.'" volume="-10000"/>
                </span>
                ';
            $sLinkViewWeb = 'http://'.$oSession->getArray('session-domain', 'name').'/tools/viewEmail.php?key='.$sCode.'&ukey={ma_so_thanh_vien}&type='.$aTo;

            // end
            ob_start();
            include $config['dir'].'/templates/newsletter/'.$iEmailTemplateId.'/index.php';
            $sContentTmp = ob_get_clean();
            ob_end_clean();
            if ($sContentTmp == '') {
                Core_Error::set('error', 'Not found template');
                $bIsReturn = true;
            }
            /* tối ưu nội dung */
            // remove Simgle line comment
            $sContentTmp = preg_replace("#[\t|\n]\/\/(.*?)[\n|\r\n]#", "\n", $sContentTmp);
            // remove white-space between 2 tags
            $sContentTmp = preg_replace('~>\s+<~', '><', $sContentTmp);
            // remove comment HTML
            $sContentTmp = preg_replace('#\<\!--(.*?)-->#is', '', $sContentTmp);
            // remove comment JS
            $sContentTmp = preg_replace('#\/\*(.*?)\*\/#is', '', $sContentTmp);

            $sContentTmp = Core::getLib('input')->removeBreakLine(array('text' =>$sContentTmp));

            $sContent = $sContentTmp;
            unset($sContentTmp);

            $sFileCache = $config["dir"].'/cache/email/';

            $sFileCache .= $oSession->getArray('session-domain', 'name');
            if(!file_exists($sFileCache))
            {
                mkdir($sFileCache, 0777);
                chmod($sFileCache, 0777);
            }
            $sFileCache .= '/'.$iId;

            $fCache = fopen($sFileCache, 'w');
            fwrite($fCache, $sContent);
            fclose($fCache);
            /*
                Xét về mặc lập trình sẽ như sau:

            I. Xác thực người dùng
                1. Người dùng xác thực Email
                    Kiểm tra Email người dùng đã được xác thực Trên hệ thống web chưa ( có thể email đã được xác thực trong tài khoản khác )
                    a. Nếu chưa
                        $ses->verifyEmailAddress('user@example.com')
                        Đưa ra thông báo Yêu cầu vào email trên để xác thực
                        Sau khi xác thực xong, hệ thống kiểm tra lại để tiến hành lưu email đã xác thực.
                    b. Nếu đã:
                        Gửi 1 email đến địa chỉ email, xác thực bằng Hệ thống

                2. Đưa ra thông báo Yêu cầu vào email trên để xác thực
            II. Gửi email
                1. Hiển thị các email xác thực với tài khoản trên
                2. Nhóm thành viên được nhận Email.
                3. Tiêu đề
                4. Nội dung
            */

            /*
             tat SSL de chay
             Co the bat bang cach cho import SSL CA vao
             link tai file: http://curl.haxx.se/docs/caextract.html
             Them vao PHP
             curl.cainfo=c:\php\cacert.pem

             Hoac
             curl_setopt($ch, CURLOPT_SSLCERT, '/cert/mycert.pem');
            */

            /*
            Array
            (
                [Max24HourSend] => 10000.0
                [MaxSendRate] => 5.0
                [SentLast24Hours] => 1.0
                [RequestId] => 303938c7-7dc2-11e2-9baf-4d39d4ec9b5e
            )
            print_r($ses->getSendQuota());
            /* */
            /*

            Array
            (
                [SendDataPoints] => Array
                    (
                        [0] => Array
                            (
                                [Bounces] => 0
                                [Complaints] => 0
                                [DeliveryAttempts] => 1
                                [Rejects] => 0
                                [Timestamp] => 2013-02-23T13:36:00Z
                            )

                    )

                [RequestId] => 30ead54d-7dc2-11e2-8a26-ddaa026437bc
            )
            print_r($ses->getSendStatistics());
            /* */

            // danh sach email da duoc xac thuc
            //print_r($ses->listVerifiedEmailAddresses());

            // xóa 1 email đã xác thực
            //$ses->deleteVerifiedEmailAddress('user@example.com');

            // thêm link unsubscription
            $iCount = 0;
            $iSendTime = time();
            /*
            $tu = '"A Tuấn Việt Nam" <no-reply@superdeals.vn>';
            unset($list);
            $list[] = array(
                'fullname' => $aRow['name'],
                'email' => 'me@vohoangtuan.com',
                'nickname' => $aRow['danh_xung'],
                'sex' => $aRow['gioi_tinh'],
            );
            */
            //$db->query('TRUNCATE `eratown_log_email`');
            //$tu['email'] = 'info@ffashion.vn';

            $iStep = 1;
            if ($oSession->getArray('session-domain', 'name') == 'ffashion.vn' || $oSession->getArray('session-domain', 'name') == 'soleevent.vn') {
                $iStep = 2;
            }
            if ($iStep == 1) {
                /*
                For hard bounces:

                https://api.elasticemail.com/mailer/list/bounced?username=yourusername&api_key=yourapikey

                For unsubscribes (including abuse reports):

                https://api.elasticemail.com/mailer/list/unsubscribed?username=yourusername&api_key=yourapikey

                https://api.elasticemail.com/mailer/list/bounced?username=b9fd335a-3822-48d0-a587-3acf57020d41&api_key=b9fd335a-3822-48d0-a587-3acf57020d41

                get bou
                https://api.elasticemail.com/mailer/list/bounced?username=b9fd335a-3822-48d0-a587-3acf57020d41&api_key=b9fd335a-3822-48d0-a587-3acf57020d41&detailed=true

                Sau khi send xong, đọc danh sách để xóa db
                https://api.elasticemail.com/mailer/status/log?format=csv&compress=true&username=your username&api_key=your api key&status=0&from=5/19/2011%2010:54:20%20PM&to=5/20/2011%2010:54:20%20PM
                */
                $smtpsecure = '';
                $host = 'smtp.elasticemail.com';
                $port = 2525;
                $username = 'b9fd335a-3822-48d0-a587-3acf57020d41';
                $password = 'b9fd335a-3822-48d0-a587-3acf57020d41';
            }
            elseif ($iStep == 2) {
                $smtpsecure = 'tls';
                $host = 'email-smtp.us-east-1.amazonaws.com';
                $port = 25;
                $username = 'AKIAJDORWVIMU5M4JQQQ';
                $password = 'AknXs77rJIb72YNP/SK6+BQpcTzfNa224m95/7swS5g6';
            }

            $oMail             = Core::getLib('phpmailer');
            $oMail->IsSMTP(); // telling the class to use SMTP
            $oMail->SMTPDebug     = $iDebug; //debug
            $oMail->SMTPAuth   = true;                  // enable SMTP authentication
            if(!empty($smtpsecure))
                $oMail->SMTPSecure = $smtpsecure;                 // sets the prefix to the servier
            $oMail->Host       = $host;      // sets GMAIL as the SMTP server
            $oMail->Port       = $port;                   // set the SMTP port for the GMAIL server
            $oMail->Username   = $username;  // GMAIL username
            $oMail->Password   = $password;            // GMAIL password
            $oMail->CharSet    = 'utf-8';
            $oMail->ContentType= 'text/html';
            $oMail->SetFrom($aFrom['email'], $aFrom['name']);
            $oMail->IsHTML(true);

            if ($iDebug) {
                unset($aList);
                $aList[] = array(
                        'code' => '12',
                        'fullname' => 'Tuan test',
                        'email' => 'me@vohoangtuan.com',
                        'nickname' => '',
                        'sex' => '',
                    );
            }
            foreach ($aList as $aVal) {
                $iCount++;
                if ($iCount == 6) {
                    // cập nhật db khi gửi
                    $iSent = $this->database()->select('sent_quantity')
                        ->from(Core::getT('log_email'))
                        ->where('id = '.$iId)
                        ->execute('getField');
                    $iSent = $iSent*1 + $iCount -1;
                    $this->database()->update(Core::getT('log_email'), array('sent_quantity' => $iSent), 'id ='.$iId);
                    $iCount = 0;
                    if (time() - $iSendTime < 1)
                        sleep(1);
                    $iSendTime = time();
                }
                $sContentTmp = $sContent;
                $sContentTmp = str_replace(
                    array(
                        '{user_code}',
                        '{fullname}',
                        '{email}',
                        '{nickname}',
                        '{sex}'
                    ),
                    array(
                        $aVal['code'],
                        $aVal['fullname'],
                        $aVal['email'],
                        $aVal['nickname'],
                        $aVal['sex'],
                    ),
                    $sContentTmp
                );
                $sTitleTmp = $sTitle;
                $sTitleTmp = str_replace(
                    array(
                        '{fullname}',
                        '{email}',
                        '{nickname}',
                        '{sex}'
                    ),
                    array(
                        $aVal['fullname'],
                        $aVal['email'],
                        $aVal['nickname'],
                        $aVal['sex'],
                    ),
                    $sTitleTmp
                );
                $sLinkViewWeb = 'http://'.$oSession->getArray('session-domain', 'name').'/tools/viewEmail.php?key='.$sCode.'&ukey='.$aVal['code'].'&type='.$aTo;
                $oMail->AltBody    = 'To view this email message, open the email in with HTML compatibility OR visit:'.PHP_EOL.$sLinkViewWeb; // optional, comment out and test
                $oMail->Subject    = $sTitleTmp;
                $oMail->Body = $sContentTmp;
                //$oMail->ClearAddresses();
                //$oMail->ClearAttachments();
                $oMail->ClearAllRecipients();
                $oMail->ClearCustomHeaders();

                $oMail->AddAddress($aVal['email'], $aVal['fullname']);
                $oMail->AddCustomHeader('List-Unsubscribe: <'.$sUrl.'?e='.$aVal['email'].'&Subject=Unsubscribe>, <mailto:'.$aFrom['email'].'?subject='.$aVal['email'].'>');

                $oMail->Send();
                unset($sContentTmp);
            }
            // cập nhật db khi hoàn thành
            $iTmp = $this->database()->select('quantity')
                ->from(Core::getT('log_email'))
                ->where('id = '.$iId)
                ->execute('getField');
            $this->database()->update(Core::getT('log_email'), array('sent_quantity' => $iTmp), 'id = '.$iId);
        }
        else {
            $sEmail = $aParams['e'];
            $sUserName = $aParams['n'];
            $sTitle = $aParams['t'];
            $sContent = $aParams['c'];
            Core::getService('core.tools')->sendEmail($sEmail, $sUserName, $sTitle, $sContent);
        }
        if($bIsReturn)
            return false;
        return true;
    }

    public function getDomainSetting($aParams = array())
    {
        $sDomain = isset($aParams['name-code']) ? $aParams['name-code'] : '';
        if (empty($sDomain)) {
            return array(
                'status' => 'error',
                'message' => 'Lỗi dữ liệu.'
            );
        }

        if ($sDomain == 'self') {
            // tự lấy thông tin của chính tên miền đang gọi.

        }
        else {

        }
    }

    public function initSession()
    {
        if (!defined('CORE_NO_SESSION')) {
            Core::getLib('session.handler')->init();
        }
        return array(
            'status' => 'success',
            'data' => array(
                'sid' => session_id()
            )
        );
    }

    public function updateStatusApi($aParams = array())
    {
        //Hàm thay thế cho hàm updateStatus nhưng trả về theo status
        $bIsReturn = false;
        $oSession = Core::getLib('session');

        $iId=@$aParams["id"]*1;
        if ($iId == 0)
            $aList=@$aParams["list"];
        if ($oSession->getArray('session-user', 'id') < 1 || $oSession->getArray('session-user', 'priority_group') == -1) {
            Core_Error::set('error', 'Deny(1)');
            $bIsReturn = true;
        }
        elseif ($iId==0 && $aList == '') {
            Core_Error::set('error', 'Deny(2)');
            $bIsReturn = true;
        }
        elseif ($oSession->getArray('session-permission', 'manage_extend') != 1) {
            Core_Error::set('error', 'Deny(3)');
            $bIsReturn = true;
        }

        $sType = '';
        if (isset($aParams['type'])) {
            $sType = $aParams['type'];
        }

        $aTypeList = array (
            'filter',
            'filter_general',
            'redirect',
            'receive_news',
            'receive_news_group',
            'menu',
            'menu_value',
            'tab',
            'vendor',
            'discount',
            'image_extend',
            'top_article',
            'user_group',
            'language',
            'comment',
            'unit',
            'cart',
            'area',
            'advertisement',
            'ads_position',
            'email_group',
            'email',
            'marketing',
            'discount_item',
            'ads_unit',
            'ads_campaign',
            'image_extend_general',
            'package_option',
        );
        if (!in_array($sType, $aTypeList)) {
            Core_Error::set('error', 'Deny(4)');
            $bIsReturn = true;
        }

        $iStatus = addslashes(@$aParams["status"]*1);
        if ($iStatus != 1 && $iStatus != 2)
            $iStatus=0;

        if (!$bIsReturn) {
            if ($iId == 0 && $sType != 'menu_value') {
                $aList = explode(',', $aList);
                foreach ($aList as $Val) {
                    if ($Val*1>0)
                    $aTmp[] = $Val*1;
                }
                $aList = implode(',', $aTmp);

                $sCond = 'in ('.$aList.')';
                unset($aList);
                $aRows = $this->database()->select('id')
                    ->from(Core::getT($sType))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond)
                    ->execute('getRows');
                foreach ($aRows as $aRow) {
                    // tái tạo điều kiện
                    $aList[] = $aRow['id'];
                }
                $sCond = 'in ('.implode(',', $aList).')';
            }
            else {
                $aList[] = $iId;
                $sCond = '= '.$iId;
            }

            if ($sType == 'user_group') {
                //kiểm tra có quyền thay đổi nhóm thành viên hay không (tạm thời chỉ xét có quyền sửa thành viên hay ko)
                if ($oSession->getArray('session-permission', 'edit_user') != 1) {
                    Core_Error::set('error', 'Deny(5)');
                    $bIsReturn = true;
                }
                else {
                    //cập nhật cho nhóm thành viên
                    $this->database()->update(Core::getT('user_group'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                }
            }
            elseif ($sType == 'redirect') {
                $this->database()->update(Core::getT('redirect'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'cart' || $sType == 'advertisement' || $sType == 'ads_position' || $sType == 'ads_unit' || $sType == 'ads_campaign') {
                $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'status != 2 AND id '.$sCond);
                //clear cache
                Core::getService('core')->removeCache();
            }
            elseif ($sType == 'area') {
                $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'package_option') {
                $this->database()->update(Core::getT($sType), array('status' => $iStatus), 'status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'menu_value') {
                // kiểm tra có quyền tương tác với Menu value ko
                $aRow = $this->database()->select('group_concat("", id) id')
                    ->from(Core::getT('menu_value'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond.' AND status != 2')
                    ->execute('getRow');

                if (!empty($aRow['id'])) {
                    $sCond = ' IN ('.$aRow['id'].')';
                    $this->database()->update(Core::getT('menu_value'), array('status' => $iStatus),'status != 2 AND id '.$sCond);

                    Core::getService('core.tools')->updateMenu();

                    /*Bỏ , liên quan tới table Cache
                    // xóa cache cũ tên miền
                    $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$oSession->getArray('session-domain', 'id');
                    $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                    xoa_cache_thu_muc(array(
                        'link' => $oSession->getArray('session-domain', 'id'),
                        'type' => 'all',
                    ));
                    xoaGiaTriSession();
                    */
                    // end;
                }
            }
            elseif ($sType == 'receive_news') {
                $this->database()->update(Core::getT('receive_news'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
            }
            elseif ($sType == 'vendor') {
                $this->database()->update(Core::getT('vendor'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                //update status for vendor page.
                $this->database()->update(
                    Core::getT('category_display'),
                    array(
                        'status' => $iStatus,
                    ),
                    'object_type = 2 AND object_id '.$sCond.' AND status != 2'
                );
                // update status vendor in categoyr
                $this->database()->update(
                    Core::getT('category_display'),
                    array(
                        'status' => $iStatus,
                    ),
                    'item_type = 2 AND item_id '.$sCond.' AND status != 2'
                );
                //clear cache
                $this->removeCache();
            }
            elseif ($sType == 'tab') {
                // kiểm tra có quyền tương tác với the
                $aRow = $this->database()->select('group_concat("", id) id')
                    ->from(Core::getT('tab'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond.' AND status != 2')
                    ->execute('getRow');
                if (!empty($aRow['id'])) {
                    $sCond = ' IN ('.$aRow['id'].')';

                    $this->database()->update(Core::getT('tab'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                    $this->database()->update(Core::getT('tab_category'), array('status' => $iStatus), 'status != 2 AND tab_id '.$sCond);
                    $this->database()->update(Core::getT('tab_article'), array('status' => $iStatus), 'status != 2 AND tab_id '.$sCond);
                }
            }
            elseif ($sType == 'top_article') {
                // kiểm tra có quyền tương tác
                $aRow = $this->database()->select('group_concat("", id) id')
                    ->from(Core::getT('top_article'))
                    ->where('domain_id = '.$oSession->getArray('session-domain', 'id').' AND id '.$sCond.' AND status != 2')
                    ->execute('getRow');
                if (!empty($aRow['id'])) {
                    $sCond = ' IN ('.$aRow['id'].')';

                    $this->database()->update(Core::getT('top_article'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond);
                    //$this->database()->update(Core::getT('top_article_value'), array('status' => $iStatus), 'top_article_id '.$sCond);

                    //$sql='UPDATE `'.TABLEPREFIX.'top_bai_viet_gt` SET `top_bai_viet_stt` '.$sCond;
                    //$db->query($sql) or myErrorHandler(E_ERROR, "System error".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);
                }
            }
            else {
                if ($this->database()->update(Core::getT($sType), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND status != 2 AND id '.$sCond) ) {
                    if ($sType == 'filter') {
                        $this->database()->update(Core::getT('filter_value'), array('status' => $iStatus), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND filter_id '.$sCond);
                        //Delete
                        $this->database()->delete(Core::getT('filter_value_article'), 'filter_id '.$sCond);
                        $this->database()->delete(Core::getT('filter_value_category'), 'filter_id '.$sCond);

                        /* liên kết kho
                        if (!empty($config['erpLink']))
                            chayNgam($config['erpLink'], array(
                                'list' => $aList,
                                'type' => 'filter_create_edit',
                                'action' => 'delete',
                            ));
                        */
                    }
                    elseif ($sType == 'filter_general') {
                        $this->database()->update(Core::getT('filter'), array('filter_general_id' => 0), 'domain_id ='.$oSession->getArray('session-domain', 'id').' AND filter_general_id '.$sCond);
                    }
                    /* Bỏ ...
                    // xóa cache cũ tên miền
                    $sql = 'UPDATE `'.TABLEPREFIX.'cache` SET trang_thai = 2 WHERE `ten_mien_stt` = '.$oSession->getArray('session-domain', 'id');
                    $result = $db->query($sql) or myErrorHandler(E_ERROR, "System error(7.1.1)".$sql.$db->error(), $_SERVER['SCRIPT_FILENAME'].'-'.end(get_included_files()), 0);

                    xoa_cache_thu_muc(array(
                        'link' => $oSession->getArray('session-domain', 'id'),
                        'type' => 'all',
                    ));
                    // end
                    xoaGiaTriSession(0);
                    cap_nhat_sitemap();
                    */
                }
            }
            // ghi log hệ thống
            Core::getService('core.tools')->saveLogSystem(array('action' => $sType.'-'.$iId,'content' => 'phpinfo',));
        }

        if (Core_Error::isPassed()) {
            return array(
                'status' => 'success',
            );
        }
        else {
            return array(
                'status' => 'error',
                'message' => Core_Error::getString(),
            );
        }
    }
}
