    <footer>
        <div class="content_footer">
            <div class="background_footer">
                <div class="div_bg" style="background-image:url(http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/bg_footer_blur.jpg)"></div>
            </div>
            <div class="overlay_bg"></div>
            <div class="wrap">
                <a href="" class="title_footer">Cộng đồng Yêu nhiếp ảnh Việt Nam</a>
                <a href="" class="link_upload_footer">Tải Ảnh của bạn</a>
                <div class="list_menu_footer">
                    <ul class="menu_footer">
                        <li><a href="">Giới thiệu</a></li>
                        <li><a href="">Điều khoản</a></li>
                        <li><a href="">Quy định</a></li>
                        <li><a href="">Báo chí</a></li>
                        <li><a href="">Blog</a></li>
                        <li><a href="">Nhà phát triển</a></li>
                        <li><a href="">Hỗ trợ</a></li>
                        <li><a href="">Tuyển dụng</a></li>
                    </ul>
                </div>
               </div>
        </div>    
    </footer>
    <div class="frame_popup none">
        <div class="inner_frame_popup">
            <div class="content_popup popup_list_user">
                <div class="header_plu">
                    <div class="title_plu">Người yêu thích</div>
                    <div class="count_plu">88</div>
                    <div class="close_popup_frame"></div>
                </div>
                <div class="container_plu">
                    <div class="list_plu">
                        <? for ($i=0; $i < 6; $i++):?>
                            <div class="item_plu">
                                <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/avatar.png" alt="" class="avatar_plu">
                                <div class="info_plu">
                                    <div class="user_plu">Mikado AJ</div>
                                    <div class="count_folow">Người theo dõi <span class="value_cf_plu">88</span></div>
                                    <div class="count_like">Cảm tình <span class="value_like_plu">125</span></div>
                                </div>
                                <div class="btn btn_folow_popup">Theo dõi</div>
                            </div>
                        <? endfor?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="frame_view_full none">
        <div class="header_vf">
            <div class="control_left_vf">
                <div class="close_vf"></div>
                <div class="full_screen_vf"></div>
            </div>
            <div class="control_right_icis">
                <div class="right_ctrl_icis">
                    <div class="item_ctrl_icis reply_item_ctrl_icis"></div>
                    <div class="item_ctrl_icis like_item_ctrl_icis"></div>
                    <div class="item_control_bcis share_control_bcis"></div>
                    <div class="item_ctrl_icis more_item_ctrl_icis">
                        <div class="list_more_control">
                            <div class="icon_lmc report_icon_lmc">Báo cáo</div>
                            <div class="icon_lmc delete_icon_lmc">Xóa</div>
                            <div class="icon_lmc favorite_icon_lmc">Yêu thích</div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="inner_view_full">
            <div class="image_view_full">
                <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/images/demo/15.jpg" alt="" class="main_img_vf">
                <div class="navi_left_img_vf"></div>
                <div class="navi_right_img_vf"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="group_js">
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/alertify.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.selectbox-0.2.js"></script>
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/jquery.exif.js"></script>
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/vague.js?v=<?= time()?>?v=<?= time()?>" ></script>
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/jquery.autosize.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.ellipsis.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/main.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <? if($giao_dien == 'de_tai_dang_anh') :?>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/alertify.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/shortcuts_v1.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDKw1I9ZlI-piCBp2zXSuviBDVRjju-aYI&sensor=true&libraries=adsense"></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/addres_gmap.js"></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.tagsinput.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.Jcrop.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/profile/jquery-ui.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/profile/jquery.fileupload.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/profile/jquery.iframe-transport.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/upload.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <? endif?>
        <? if($giao_dien == 'bai_viet_xem_anh') :?>
            <!--script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/addres_gmap.js"></script-->
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/shortcuts_v1.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.iosslider-vertical.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/view_image.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <? endif?>
        <? if($giao_dien == 'de_tai_tai_khoan') :?>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.tagsinput.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/profile/jquery-ui.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/profile/jquery.fileupload.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/profile/jquery.iframe-transport.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/profile.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/moment.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/livestamp.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>            
        <? endif?>
        <? if($giao_dien == 'de_tai_tim_kiem') :?>
            <script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4024/js/search.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
        <? endif?>            
    </div>
</body>
</html>
