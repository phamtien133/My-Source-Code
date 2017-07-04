<link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/reset.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<link rel="stylesheet" type="text/css" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/css/jquery.selectbox.css">
<link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/main.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? if(Core::getLib('module')->getFullControllerName() == 'user.login' || Core::getLib('module')->getFullControllerName() == 'user.register') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/login.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'de_tai_dang_anh') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/css/jquery.Jcrop.min.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/upload.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/alertify.core.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'bai_viet_xem_anh') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/view_image.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'de_tai_kham_pha' || $this->_aVars['display'] == 'de_tai_tac_gia') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/discovery.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/discovery_masonry.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'de_tai_tai_khoan') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/alertify.core.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/profile.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/combo_image.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/discovery_masonry.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'trang_chu' || $this->_aVars['display'] == 'de_tai_tim_kiem'):?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/discovery.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/combo_image.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/discovery_masonry.css?v=<?= $this->$this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'trang_chu' || $this->_aVars['display'] == 'de_tai_tim_kiem'):?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/search.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/jquery.nouislider.min.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>

<? if($this->_aVars['display'] == 'de_tai_tin_tuc') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/news.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>
<? if($this->_aVars['display'] == 'bai_viet_tin_tuc') :?>
    <link rel="stylesheet" href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/css/news_detail.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css"/>
<? endif?>

<style>
@font-face {
    font-family: "HelveticaNeue";
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue.eot);
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue.eot) format("embedded-opentype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue.woff) format("woff"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue.ttf) format("truetype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue.svg) format("svg");
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: "HelveticaNeue-Medium";
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Medium.eot);
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Medium.eot) format("embedded-opentype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Medium.woff) format("woff"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Medium.ttf) format("truetype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Medium.svg) format("svg");
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: "HelveticaNeue-Bold";
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Bold.eot);
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Bold.eot) format("embedded-opentype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Bold.woff) format("woff"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Bold.ttf) format("truetype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Bold.svg) format("svg");
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: "HelveticaNeue-Light";
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Light.eot);
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Light.eot) format("embedded-opentype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Light.woff) format("woff"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Light.ttf) format("truetype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-Light.svg) format("svg");
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: "HelveticaNeue-UltraLight";
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-UltraLight.eot);
    src: url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-UltraLight.eot) format("embedded-opentype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-UltraLight.woff) format("woff"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-UltraLight.ttf) format("truetype"),
         url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/fonts/HelveticaNeue-UltraLight.svg) format("svg");
    font-weight: normal;
    font-style: normal;
}
</style>
</head>
<body>
    <!-- Các trường hợp của nav_header:
        -   Mặc định ko có class nào      :   Line màu đen + ko có absolute
        -   has_header_bg                 :   Line trong suốt + có absolute
        -   has_header_bg + bg_black      :   Line đen + có absolute
    -->
    <? 
    if ($this->_aVars['display'] == 'trang_chu') {
        $sClassHeader = 'has_header_bg';
    }
    if ($this->_aVars['display'] == 'de_tai_tai_khoan') {
        $sClassHeader = 'has_header_bg';
        /*  bg_black */
    }
    ?>
    <header class="<?= $sClassHeader?>">
        <div class="nav_header">
            <div class="wrap">
                <div class="left_nav left">
                    <div class="logo item_left_nav">
                        <a href="/">
                            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/logo.png" />
                        </a>
                    </div>
                   <div class="item_left_nav home_nav">
                       <a href="/">Trang chủ</a>
                   </div>
                    <div class="item_left_nav discovery_nav">
                        <a href="#">Khám phá</a>
                    </div>
                    <!-- <div class="item_left_nav group_nav">
                        <a href="#">Nhóm</a>
                    </div> -->
                    <div class="item_left_nav buy_sell_nav">
                        <a href="#">Mua bán</a>
                    </div>
                    <div class="item_left_nav search_nav">
                        <form class="form_search_nav">
                            <input type="text" placeholder="Tìm kiếm" class="text_search_nav" autocomplete="off" id="input_search_nav"/>
                            <input type="submit" class="right submit_search_nav" value="" id="submit_search_nav"/>
                            <div class="clear"></div>
                            <div class="suggest_search">
                                <div class="list_normal_ss">
                                    <div class="item_normal_ss picture_inss">
                                        <div class="header_inss">Hình ảnh</div>
                                        <div class="list_result_inss">
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>to</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>lớn</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>bự</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>nhỏ</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>xanh</span></a>
                                        </div>
                                    </div>
                                    <div class="item_normal_ss video_inss">
                                        <div class="header_inss">Phim ảnh</div>
                                        <div class="list_result_inss">
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>to</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>lớn</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>bự</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>nhỏ</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>xanh</span></a>
                                        </div>
                                    </div>
                                    <div class="item_normal_ss post_inss">
                                        <div class="header_inss">Bài viết</div>
                                        <div class="list_result_inss">
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>to</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>lớn</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>bự</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>nhỏ</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>xanh</span></a>
                                        </div>
                                    </div>
                                    <div class="item_normal_ss group_inss">
                                        <div class="header_inss">Nhóm</div>
                                        <div class="list_result_inss">
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>to</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>lớn</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>bự</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>nhỏ</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>xanh</span></a>
                                        </div>
                                    </div>
                                    <div class="item_normal_ss status_inss">
                                        <div class="header_inss">Trạng thái</div>
                                        <div class="list_result_inss">
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>to</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>lớn</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>bự</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>nhỏ</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>xanh</span></a>
                                        </div>
                                    </div>
                                    <div class="item_normal_ss device_inss">
                                        <div class="header_inss">Thiết bị</div>
                                        <div class="list_result_inss">
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>to</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>lớn</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>bự</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>nhỏ</span></a>
                                            <a class="item_result_inss"><span class="exitst_kw">Biển</span><span>xanh</span></a>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="list_author_ss">
                                    <div class="header_author_ss">Tác giả</div>
                                    <div class="content_author_ss">
                                        <div class="item_author_ss">
                                            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/avatar.png" alt="" class="img_author_ss">
                                            <div class="info_author_ss">
                                                <div class="name_author_ss">Henry Winter</div>
                                                <div class="nick_author_ss">henrywinter</div>
                                            </div>
                                            <div class="icon_author_ss"></div>
                                            <a href="" class="link_author_ss"></a>
                                        </div>
                                        <div class="item_author_ss">
                                            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/avatar.png" alt="" class="img_author_ss">
                                            <div class="info_author_ss">
                                                <div class="name_author_ss">Henry Winter</div>
                                                <div class="nick_author_ss">henrywinter</div>
                                            </div>
                                            <div class="icon_author_ss"></div>
                                            <a href="#" class="link_author_ss"></a>
                                        </div>
                                        <div class="item_author_ss">
                                            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/avatar.png" alt="" class="img_author_ss">
                                            <div class="info_author_ss">
                                                <div class="name_author_ss">Henry Winter</div>
                                                <div class="nick_author_ss">henrywinter</div>
                                            </div>
                                            <div class="icon_author_ss"></div>
                                            <a href="#" class="link_author_ss"></a>
                                        </div>
                                        <div class="item_author_ss">
                                            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/avatar.png" alt="" class="img_author_ss">
                                            <div class="info_author_ss">
                                                <div class="name_author_ss">Henry Winter</div>
                                                <div class="nick_author_ss">henrywinter</div>
                                            </div>
                                            <div class="icon_author_ss"></div>
                                            <a href="#" class="link_author_ss"></a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="content_author_ss none_exist none">
                                        <span>Dùng</span>
                                        <span class="bold_text">@Tên-Tác-giả</span>
                                        <span>để tìm kiếm</span>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="clear"></div>
                </div> <!-- left nav -->
                <div class="right_nav right">
                    <!-- <div class="item_right_nav login_nav">
                        <a href="#">Đăng nhập</a>
                    </div>
                    <div class="item_right_nav regis_nav">
                        <a href="#">Đăng ký</a>
                    </div> -->
                    <div class="item_right_nav bell_nav">
                        <a href="#"></a>
                    </div>
                    <div class="item_right_nav user_nav">
                        <a href="#">
                            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/avatar.png" alt="" class="user_avatar">
                            <span class="user_name">Mikado</span>
                        </a>
                    </div>
                    <div class="item_right_nav upload_nav">
                        <a href="#">Tải ảnh</a>
                    </div>
                    <div class="item_right_nav setting_nav">
                        <a href="#"></a>
                        <div class="list_setting_nav lv_1_stn">
                            <div class="group_stn">
                                <div class="item_stn"><a href="#">Quản lý</a></div>
                                <div class="item_stn"><a href="#">Sự kiện</a>
                                    <div class="list_setting_nav lv_2_stn">
                                        <div class="group_stn">
                                            <div class="item_stn"><a href="#">Tạo mới</a></div>
                                            <div class="item_stn"><a href="#">Đã tạo</a></div>
                                            <div class="item_stn"><a href="#">Lưu nháp</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="group_stn">
                                <div class="item_stn"><a href="#">Nâng cấp</a></div>
                                <div class="item_stn"><a href="#">Cài đặt</a></div>
                            </div>
                            <div class="group_stn">
                                <div class="item_stn"><a href="#">Tìm bạn bè</a></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- right nav -->
                <div class="clear"></div>
            </div> <!-- wrap -->
        </div> <!-- nav header -->
    </header>