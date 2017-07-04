<div class="wrap_login <?if($this->_aVars['sAct'] == 'noinclude'):?>popup_login<? else:?>normal_login<? endif?>">
    <div class="err_list_login hide">
        <div class="content_err">
        </div>
        <div class="close_err_login"></div>
    </div>
    <div class="border_wrap_login">
        <div class="regis_form">
            <h2 class="tieu_de_dang_nhap clearfix"><?= Core::getPhrase('language_dang-ky')?></h2>
            <div class="sub_title_dang_nhap">Theo dõi những gì bạn yêu thích trên...</div>
            <form method="post" class="dang_ky" name="frm_dang_ky" <? if($this->_aVars['iAcp']):?>autocomplete="off"<? endif?> >
                <div class="err_list_login_popup"></div>
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2" style="padding: 0">
                                <div class="login_social">
                                    <center>
                                        <?php if (Core::getParam('setting_openid_login') == 1):?>
                                        <? if (count(Core::getParam('core.login_facebook'))):?><a class="Facebook_button_log" href="javascript:void(0)" onclick="return moPopup('http://<?= Core::getParam('core.login_domain')?>/tools/loginopenid.php?login&sid=<?= session_id()?>&p=2&refer=<?= $this->_aVars['sReferEncode']?>&ghi_nho=' + (($('#ghi_nho:checked').val() == 1) ? 1 : 0), function(){},{'type' : 'popup', 'width' : 800, 'height' : 560})"><span class="icon_Facebook_log"></span><span class="text_log">Đăng ký với Facebook</span></a><? endif?>
                                        <? if (count(Core::getParam('core.login_twitter'))):?><a class="Twitter_button_log" href="javascript:void(0)" onclick="return moPopup('http://<?= Core::getParam('core.login_domain')?>/tools/loginopenid.php?login&sid=<?= session_id()?>&p=3&refer=<?= $this->_aVars['sReferEncode']?>&ghi_nho=' + (($('#ghi_nho:checked').val() == 1) ? 1 : 0), function(){},{'type' : 'popup', 'width' : 800, 'height' : 560})"><span class="icon_Twitter_log"></span><span class="text_log">Đăng ký với Twitter</span></a><? endif?>
                                        <a class="Google_button_log" href="javascript:void(0)" onclick="return moPopup('http://<?= Core::getParam('core.login_domain')?>/tools/loginopenid.php?login&sid=<?= session_id()?>&p=0&refer=<?= $this->_aVars['sReferEncode']?>&ghi_nho=' + (($('#ghi_nho:checked').val() == 1) ? 1 : 0), function(){},{'type' : 'popup', 'width' : 800, 'height' : 560})"><span class="icon_Google_log"></span><span class="text_log">Đăng ký với Google</span></a>
                                        <a class="Yahoo_button_log" href="javascript:void(0)" onclick="return moPopup('http://<?= Core::getParam('core.login_domain')?>/tools/loginopenid.php?login&sid=<?= session_id()?>&p=1&refer=<?= $this->_aVars['sReferEncode']?>&ghi_nho=' + (($('#ghi_nho:checked').val() == 1) ? 1 : 0), function(){},{'type' : 'popup', 'width' : 800, 'height' : 560})"><span class="icon_Yahoo_log"></span><span class="text_log">Đăng ký với Yahoo</span></a>
                                        <?php endif?>
                                    </center>
                                </div>
                                <div class="login_account">
                                    <div class="row_login_acc outer_check">
                                        <input type="text" placeholder="<?= Core::getPhrase('language_hop-thu')?>" name="val[email]" autocomplete="off" id="hop_thu" class="inputbox input_log" />
                                    </div>
                                    <div class="row_login_acc">
                                        <input type="password" placeholder="<?= Core::getPhrase('language_mat-khau')?>" name="val[password]" autocomplete="off" id="mat_khau" class="inputbox input_log" />
                                    </div>
                                    <div class="row_login_acc">
                                        <a class="dong_y_dieu_khoan check">Đồng ý với quy định và điều khoản</a>
                                    </div>
                                    <div class="row_login_acc">
                                        <button class="button_submit_log button_next" type="submit"><?= Core::getPhrase('language_tiep-tuc')?></button>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <? if($this->_aVars['sAct'] != 'noinclude'):?>
             <div class="footer_login">
                <div class="left_link_footer">
                    <a class="footer_login_link" href="/dang_nhap.html">Đăng nhập</a>
                </div>
                <div class="right_link_footer">
                </div>
                <div class="clear"></div>
            </div>
            <? endif?>
        </div>
        <div class="info_user_form none">
            <form method="post" name="submit_dang_ky" id="submit_dang_ky">
                <div class="header_user_info">Thông tin tài khoản</div>
                <div class="avatar_user none">
                    <img src="" alt="" class="image_avatar">
                </div>
                <div class="outer_check check_ho_ten">
                    <input type="text" name="val[fullname]" autocomplete="off" id="ho_va_ten" placeholder="Họ và tên của bạn" class="inputbox input_log" />
                </div>
                <div class="group_acc">
                    <div class="link_web"></div>
                    <input type="text" name="val[username]" autocomplete="off" id="ten_truy_cap" placeholder="Tài khoản" class="input_acc" />
                </div>
                <div class="group_goi_y">
                    <div class="header_goi_y none">Gợi ý tài khoản</div>
                    <div class="list_goi_y none"></div>
                </div>
                <div id="div_ma_kich_hoat" class="group_captcha">
                    <span id="hinh_anh"><img /></span>
                    <a class="lay_hinh_moi" href="javascript:" onclick="lay_hinh_moi<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>()">
                        <img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/refresh.png" />
                    </a>
                </div>
                <input placeholder="<?= Core::getPhrase('language_ma-xac-nhan')?>" type="text" name="val[captcha]" class="input_log inputbox ma_xac_nhan"  autocomplete="off" />
                <button class="button_submit_log button_next"><?= Core::getPhrase('language_dang-ky')?></button>
            </form>
        </div>
        <? if($this->_aVars['sAct'] != 'noinclude'):?>
            <div class="loading_form none">
                <form name='loading' bgcolor=#ffffff>
                    <div class="combo_progress">
                        <div class='text_chuyen_trang'><?= Core::getPhrase('language_he-thong-dang-chuyen-trang')?></div>
                        <div class='text_nhap_vao'>
                            <a href='<?= $this->_aVars['sRefer']?>'>[ <?= Core::getPhrase('language_nhap-vao-day-neu-ban-khong-muon-doi')?> ]</a>
                        </div>
                        <div class="progress_bar">
                            <div class="propress_bar_bg"></div>
                            <div class="progress_bar_value"></div>
                        </div>
                    </div>
                </form>
            </div>
        <? endif?>
    </div>
</div>
<script type="text/javascript">
    var hop_thu, mat_khau, ten_truy_cap, ho_va_ten, ma_xac_nhan;
    /*  Tuần tự các bước xử lý đăng ký 
        Đăng ký qua 2 bước:
        1. Nhập hộp thư và mật khẩu
        2. Nhập Họ tên và username + mã captcha xác nhận*/
    function change_step<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        var timeout_kpr_hvt;
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').keypress(function(){
            /*  Khi đăng nhập hộp thư, tiến hành check time out để kiểm tra hộp thư nhập có đúng cấu trúc hay ko 
                - Nếu sai báo lỗi
                - Nếu đúng thì báo dấu ok */
            if (timeout_kpr_hvt) {  
                clearTimeout(timeout_kpr_hvt);
            }
            timeout_kpr_hvt = setTimeout(function() {
                var pr_obj = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').parent('.outer_check');
                var temp_ht = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').val();
                var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (testEmail.test(temp_ht) == true){
                    pr_obj.find('.check_x').remove();
                    pr_obj.find('.check_ok').remove();
                    pr_obj.append('<div class="check_ok"></div>');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
                }else{
                    pr_obj.find('.check_x').remove();
                    pr_obj.find('.check_ok').remove();
                    pr_obj.append('<div class="check_x"></div>');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Hộp thư không đúng cấu trúc');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                }
            }, 1000);
        });
        
        /*  Submit Form ở bước 1 */
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> form.dang_ky').unbind('submit').submit(function(){
            hop_thu = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').val();
            mat_khau = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #mat_khau').val();
            /*  Kiểm tra hộp thư đã nhập chưa */
            if(hop_thu == ''){
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền Hộp thư');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').focus();
                return false;
            }
            /*  Kiểm tra tính chính xác của hộp thư một lần nữa trước khi submit */
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            if (testEmail.test(hop_thu) == false){
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Hộp thư không chính xác!');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').focus();
                return false;
            }
            /*  Kiểm tra mật khẩu đã nhập chưa */
            if(mat_khau == ''){
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền mật khẩu');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #mat_khau').focus();
                return false;
            }
            /*  Kiểm tra đồng ý điều khoản - Mặc định là đồng ý sẵn*/
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .dong_y_dieu_khoan').hasClass('un_check')){
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng đồng ý với điều khoản');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;   
            }
            
            /* Kiểm tra email đã tồn tại hay không*/
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.checkExistEmail';
            sParams += '&val[email]=' +  hop_thu;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "GET",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'json',
                error: function(jqXHR, status, errorThrown){
                    
                } ,
                success: function (data) {
                   returnCheckEmail(data);
                }
            });
            return false;
        });
    }
    /*  Hàm kiểm tra trong chuỗi có các ký tự đặc biệt vào số hay không 
        Mặc định chỉ kiểm tra ký tự đặc biệt, muốn kiểm tra số nào thì thêm số đó vào number*/
    function check_ky_tu<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(str, number){
        if (typeof(number) == 'undefined') {
            number = '';
        }
        var specialChars = "<>@!#$%^&*()_+[]{}?:;|'\"\\,./~`-=" + number;
        for(i = 0; i < specialChars.length;i++){
            if(str.indexOf(specialChars[i]) > -1){
                return true;
            }
        }
        return false;
    };
    /*  Hàm gọi ý tài khoản 
        - Lấy từ họ tên đầy đủ xóa bỏ khoảng trắng từ hoa thường
        - Kết hợp vài số ngẫu nhiên để tạo gợi ý

        Danh sách được hiển thị dưới input username*/
    function goi_y_tai_khoan<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>()
    {
        var timeout_ttc_hvt;
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').keypress(function(e){
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .list_goi_y').fadeIn(200);
            var danh_sach = [];
            /*  Một số thao tác xử lý chuỗi để tạo tên */
            danh_sach.push($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').val());
            var ten_day_du = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').val();
            
            ten_day_du = ten_day_du.stripViet();

            ten_day_du = ten_day_du.replaceAll('  ','');
            ten_day_du = $.trim(ten_day_du);
            ten_day_du = ten_day_du.replaceAll(' ','');
            ten_day_du = ten_day_du.toLowerCase();
            danh_sach.push(ten_day_du);
            var random = Math.floor((Math.random() * 1000)); 
            danh_sach.push(ten_day_du + random);
            random = Math.floor((Math. random() * 1000)); 
            danh_sach.push(ten_day_du + random);

            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .list_goi_y').html('');
            $.each(danh_sach, function(index, value){
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .list_goi_y').append('<div class="item_goi_y">' + value + '</div>');
            });

            /*  Khi chọn 1 gợi ý thì đẩy vào username */
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .list_goi_y .item_goi_y').unbind('click').click(function(){
                var item = $(this).html();
        
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').val(item);
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .header_goi_y').fadeOut(200);
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .list_goi_y').fadeOut(200);

            });
            $('html').unbind('click').click(function (e) {
                if (e.target.id != 'ten_truy_cap' && e.target.id != 'group_goi_y') {
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .list_goi_y').fadeOut(200);
                }
            });
            if (timeout_ttc_hvt) {  
                clearTimeout(timeout_ttc_hvt);
            }
            /*  Trong khi gõ user name sẽ kiểm tra tính hợp lệ 
                username ko được chứa ký tự đặc biệt*/
            var pr_obj = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').parent('.group_acc');
            timeout_ttc_hvt = setTimeout(function() {
                if (check_ky_tu<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>($('#ten_truy_cap').val()) == true) {
                    pr_obj.removeClass('.check_ok');
                    pr_obj.removeClass('.check_x');
                    pr_obj.addClass('check_x');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Tên truy cập không được chứa ký tự đặc biệt');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                    return ;
                };
            }, 1000);
        });
    }

    function showCheckUserLoading(value_loading){

    }
    function showCheckUser(check_username)
    {
        if (typeof(check_username) == 'undefined') {
            check_username = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').val();
        }else{
            check_username = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #' + check_username).val();
        }

        var ket_qua = dataSearchProduct['check_user'][0][check_username]['exists'];
        var obj = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .group_acc');
        if(ket_qua == false){
            obj.addClass('check_ok');
            obj.removeClass('check_x');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
        }else{
            obj.addClass('check_x');
            obj.removeClass('check_ok');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Tài khoản đã tồn tại, vui lòng chọn tài khoản khác');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
    }

    String.prototype.replaceAll = function(strTarget,  strSubString) {
        var strText = this;
        var intIndexOfMatch = strText.indexOf( strTarget );
         
        while (intIndexOfMatch != -1){
            strText = strText.replace( strTarget, strSubString ); 
            intIndexOfMatch = strText.indexOf( strTarget );
        }
        return strText;
    }﻿;

    function submit_dang_ky<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>()
    {
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> form#submit_dang_ky').unbind('submit').submit(function(){
            ten_truy_cap = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').val();
            ho_va_ten = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').val();
            if(ten_truy_cap == ''){
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền Tên truy cập');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ten_truy_cap').focus();

                return false;
            }
            ho_va_ten = ho_va_ten.replace('  ',' ');
            ho_va_ten = $.trim(ho_va_ten);
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').val(ho_va_ten);
            if (ho_va_ten.indexOf(' ') < 0) {
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Họ và tên không thích hợp! Họ và tên phải có 2 từ trở lên.');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').focus();
                return false;
            }
            ma_xac_nhan = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .ma_xac_nhan').val();
            var data =  {       'hop_thu'       :   hop_thu, 
                                'mat_khau'      :   mat_khau, 
                                'ten_truy_cap'  :   ten_truy_cap, 
                                'ho_va_ten'     :   ho_va_ten,
                                'ma_kich_hoat'   :   ma_xac_nhan  
                            };
            
            var aParam = new Array();
            aParam['val[email]'] = hop_thu;
            aParam['val[password]'] = md5(mat_khau);
            aParam['val[username]'] = ten_truy_cap;
            aParam['val[fullname]'] = ho_va_ten;
            aParam['val[captcha]'] = ma_xac_nhan;
            aParam['val[type]'] = 'register',
            addRequest(aParam, 'user.addUser', 'POST', 'json', 'returnAddUser()');
            return false;
        });
    }
    /*  Hàm lấy hình mới */
    function lay_hinh_moi<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        var url = setupPathCode(setupPath('www') + '/tools/image.php?id=<?= $this->_aVars['sTypeAct']?>' + '&_=' + Math.random());
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hinh_anh').html('<img src="' + url +'" />');
    };
    /*  Dùng để chạy thanh tiến trình sau khi đăng nhập hay đăng xuất thành công 
        Set time out để chạy thanh tiến trình
    */
    var progress_bar=0;
    function progress_bar_login(){
        progress_bar = progress_bar + 2;
        $('.combo_progress .progress_bar_value').width(progress_bar * 4);
        $('.combo_progress .percent_progress').html(progress_bar + '%');
        if (progress_bar < 99){
            setTimeout('progress_bar_login()',20);
        }else{
            window.location = '<?= ($this->_aVars['sRefer']==''?'/':$this->_aVars['sRefer']); ?>';
        }
    }
    
    /* Bổ sung */
    function setupPathCode(path)
    {
        if(path.indexOf('?') == -1) path += '?';
        path += '&code=' + readCookie('code');
        return path;
    }
    
    function setupPath(subdomain)
    {
        var path = window.location.hostname;
        
        if(window.location.port == '8080')
        {
            subdomain = '';
            path += ':' + window.location.port;
        }
        if(subdomain != '')
        {
            subdomain = subdomain + '.';
            if(subdomain == path.substring(0, subdomain.length)) subdomain = '';
        }
        path = document.location.protocol + '//' + subdomain + path;
        return path;
    };
    
    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    
    String.prototype.stripViet = function() {
        var replaceChr = String.prototype.stripViet.arguments[0];
        var stripped_str = this;
        var viet = [];
        i = 0;
        viet[i++] = new Array('a', "/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/g");
        viet[i++] = new Array('o', "/ó|ò|ỏ|õ|ọ|ơ|ớ|ờ|ở|ỡ|ợ|ô|ố|ồ|ổ|ỗ|ộ/g");
        viet[i++] = new Array('e', "/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/g");
        viet[i++] = new Array('u', "/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/g");
        viet[i++] = new Array('i', "/í|ì|ỉ|ĩ|ị/g");
        viet[i++] = new Array('y', "/ý|ỳ|ỷ|ỹ|ỵ/g");
        viet[i++] = new Array('d', "/đ/g");
        for (var i = 0; i < viet.length; i++) {
            stripped_str = stripped_str.replace(eval(viet[i][1]), viet[i][0]);
            stripped_str = stripped_str.replace(eval(viet[i][1].toUpperCase().replace('G', 'g')), viet[i][0].toUpperCase());
        }
        if (replaceChr) {
            return stripped_str.replace(/[\W]|_/g, replaceChr).replace(/\s/g, replaceChr).replace(/^\-+|\-+$/g, replaceChr);
        } else {
            return stripped_str;
        }
    };
    
    function returnAddUser()
    {
        if(result.status == 'error'){
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html(result.data.error);
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ma_xac_nhan').focus();
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
        else if (result.status == 'success') {
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .info_user_form').hide();
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .loading_form').fadeIn(200);
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .border_wrap_login').addClass('no_border');
            complete_task();
        }
        else {
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Đã có lỗi xảy ra. Vui lòng thử lại');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
        return false;
    }
    
    function returnCheckEmail(result)
    {
        if(result.status == 'error'){
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Hộp thư đã tồn tại!');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').focus();
        }
        else if (result.status == 'success') {
            /*  Ẩn danh sách lỗi và chuyển Form qua bước 2 */
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .regis_form').hide();
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .info_user_form').fadeIn();
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .border_wrap_login').addClass('border_user_info');
            /*  Lấy tên miền web để điền vào trước username */
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .group_acc .link_web').html(global['domain']);
            var w = 233 - $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .group_acc .link_web').width();
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .group_acc #ten_truy_cap').width(w);
            lay_hinh_moi<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
            goi_y_tai_khoan<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
            var timeout_kpr_hvt;

            /*  Khi đang gõ họ tên tiến hành kiểm tra tính chính xác:
                1. Có ít nhất 2 từ
                2. Không có số và ký tự đặc biệt */
             $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').keypress(function(){
                if (timeout_kpr_hvt) {  
                    clearTimeout(timeout_kpr_hvt);
                }
                timeout_kpr_hvt = setTimeout(function() {
                     var pr_obj = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').parent('.outer_check');
                    var temp_ht = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ho_va_ten').val();
                    temp_ht = temp_ht.replace('  ',' ');
                    temp_ht = $.trim(temp_ht);
                    if(check_ky_tu<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(temp_ht, "0123456789") == true){
                        pr_obj.find('.check_x').remove();
                        pr_obj.find('.check_ok').remove();
                        pr_obj.append('<div class="check_x"></div>');
                        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Họ và tên không được chứa số và các ký tự đặc biệt.');
                        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                        return ;
                    };
                    if (temp_ht.indexOf(' ') < 0) {
                        pr_obj.find('.check_x').remove();
                        pr_obj.find('.check_ok').remove();
                        pr_obj.append('<div class="check_x"></div>');
                        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Họ và tên không thích hợp! Họ và tên phải có 2 từ trở lên.');
                        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                        return ;
                    }
                    pr_obj.find('.check_x').remove();
                    pr_obj.find('.check_ok').remove();
                    pr_obj.append('<div class="check_ok"></div>');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
                }, 1000);
            });
            submit_dang_ky<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
            return false;
        }
        else {
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Đã có lỗi xảy ra. Vui lòng thử lại');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
        return false;
    }
    
    
/*    Complete dùng khi hoàn thành thao tác ở đăng nhập / đăng xuất
    Dùng biến $('#popup_dang_nhap').hasClass('open') để xác định là 
    đăng nhập / đăng xuất ở popup hay bình thường
    - true         :         Dùng popup         ->         Close popup
    - false        :         Bình thường        ->        Chạy progress bar
*/
function complete_task(){
    if ($('#popup_dang_nhap').hasClass('open') == true) {
        /*    Trước khi đóng popup sẽ lấy thông tin người dùng để trả về call back */
        /*
        $.ajaxCall({
            url: setupPathCode(setupPath('s') + '/tools/thong_tin_nguoi_dung.php'),
            type: 'POST',
            timeout: 8000,
            callback: function(data){
                if(typeof(callback_close_popup) == 'function') callback_close_popup(data);
            }
        });
        */
        /*    Giả lập sự kiện click vào điểm đóng popup */
        $('.close_popup').trigger('click');
    }else{
        setTimeout('progress_bar_login()', 1000);
    }
}
</script>