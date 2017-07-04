<div class="wrap_login <?if($this->_aVars['sAct'] == 'noinclude'):?>popup_login<? else:?>normal_login<? endif?>">
    <div class="err_list_login hide">
        <div class="content_err">
        </div>
        <div class="close_err_login"></div>
    </div>
    <div class="border_wrap_login  border_user_info">
        <div class="form_input_info">
            <form id="jForm" class="quen_mk" action="" method="post">
                <h2 class="header_user_info"><?= Core::getPhrase('language_quen-mat-khau')?></h2>
                <div class="err_list_login_popup"></div>
                <div class="main_quen_mk bg_qmk">
                    <div class="header_quen_mk">
                        Vui lòng nhập email tài khoản để lấy lại mật khẩu của bạn. Bạn sẽ nhận được email hướng dẫn. Nếu bạn đang gặp vấn đề với ghi nhớ tên đăng nhập hoặc email của bạn, xin vui lòng gửi cho chúng tôi thông tin phản hồi hoặc gửi email cho chúng tôi để được hỗ trợ nhanh nhất
                    </div>
                    <div class="outer_check check_ht_qmk">
                        <input type="text" placeholder="<?= Core::getPhrase('language_hop-thu')?>" name="hop_thu" autocomplete="off" class="input_log hop_thu_quen_mk" id="hop_thu" class="inputbox" />
                    </div>
                    <div class="clear"></div>
                    <div id="div_ma_kich_hoat" class="group_captcha">
                        <span id="hinh_anh" class="left img_captcha_qmk"><img /></span>
                        <a href="javascript:" class="lay_hinh_moi" onclick="lay_hinh_moi<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>()">
                            <img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/refresh.png" />
                        </a>
                        <div class="clear"></div>
                    </div>
                    <div style="clear:both"></div>
                    <input type="text" name="ma_kich_hoat" placeholder="Nhập mã xác nhận" class="input_log input_captcha_qmk inputbox" autocomplete="off" />
                      <input type="hidden" name="gui_len" value="1" />
                      <input type="hidden" name="type" value="quen_mat_khau" />
                    <button type="submit" class="button_submit_log btn_sm_input_form"  name="submit"><?= Core::getPhrase('language_tiep-tuc')?></button>
                </div>
            </form>
        </div>
        <div class="form_set_info none">
            <form class="dat_lai_mk" id="jForm" action="" method="post">
                <div class="header_user_info">Thiết lập mật khẩu</div>
                <div class="err_list_login_popup"><?= $error?></div>
                <div class="main_quen_mk">
                    <div class="header_reset_pass">
                    </div>
                    <div class="content_quen_mk">
                        <input type="text" placeholder="<?= Core::getPhrase('language_ma-kich-hoat')?>" name="ma_kich_hoat" id="ma_kich_hoat" value="<?= $ma_kich_hoat?>" class="input_log inputbox" />
                        <input type="password" placeholder="<?= Core::getPhrase('language_mat-khau-moi')?>" name="mat_khau" id="mat_khau" value="" class="input_log inputbox" />
                        <div class="outer_check check_repass">
                            <input type="password" placeholder="<?= Core::getPhrase('language_mat-khau-moi-nhap-lai')?>" name="mat_khau_nhap_lai" id="mat_khau_nhap_lai" value="" class="input_log inputbox" autocomplete="off" />
                        </div>
                        <div id="div_ma_kich_hoat" class="group_captcha">
                            <span id="hinh_anh_2"><img /></span>
                            <a class="lay_hinh_moi" href="javascript:" onclick="lay_hinh_moi_buoc_2<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>()">
                                <img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/refresh.png" />
                            </a>
                        </div>
                        <input placeholder="<?= Core::getPhrase('language_ma-xac-nhan')?>" type="text" name="ma_xac_nhan" class="input_log inputbox ma_xac_nhan"  autocomplete="off" />
                        <input type="hidden" name="type" value="quen_mat_khau" />
                        <button class="button_submit_log submit_reset_pass" type="submit" name="submit"><?= Core::getPhrase('language_tiep-tuc')?></button>
                    </div>
                </div>
            </form>    
        </div>
        <div class="form_success_reset none">
            <div class="header_form_success">Mật khẩu đã được đổi thành công!</div>
            <a href="dang_nhap.html" class="button_submit_log btn_form_succes"> Đăng nhập</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        init_qmk<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();        
    });
    function init_qmk<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        var data_hash_tag = get_hash_tag();
        var ma_kich_hoat = data_hash_tag['ma_kich_hoat'];
        if (typeof(ma_kich_hoat) != 'undefined') {
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_input_info').hide();
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info').fadeIn(200);
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .border_wrap_login').addClass('border_user_info').removeClass('no_border');
            submit_form_reset<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
            lay_hinh_moi_buoc_2<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();

            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #ma_kich_hoat').val(ma_kich_hoat);
            return;
        }
        lay_hinh_moi<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
        var timeout_kpr_hvt;
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').keypress(function(){
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
        submit_form_qmk<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
    };

    function submit_form_qmk<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #jForm.quen_mk').unbind('submit').submit(function(){
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .hop_thu_quen_mk').val() == '')
            { 
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền hộp thư');   
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .hop_thu_quen_mk').focus();
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;
            }
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .input_captcha_qmk').val() == '')
            {
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền mã xác nhận');   
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .input_captcha_qmk').focus();
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;
            }

            var data = {'hop_thu'        :     $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').val(),
                        'ma_kich_hoat'     :     $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .input_captcha_qmk').val()};
            var aParam = new Array();
            aParam['val[email]'] = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hop_thu').val();
            aParam['val[captcha]'] = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .input_captcha_qmk').val();
            addRequest(aParam, 'user.forgetPassword', 'POST', 'json', 'resultForgot()');
            return false;
        });
    };

    function submit_form_reset<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        lay_hinh_moi_buoc_2<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
        var timeout_repass;
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .check_repass #mat_khau_nhap_lai').keypress(function(){
            if (timeout_repass) {
                clearTimeout(timeout_repass);
            }
            timeout_repass = setTimeout(function() {
                var pr_obj = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .check_repass #mat_khau_nhap_lai').parent('.outer_check');
                var temp_ht = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .check_repass #mat_khau_nhap_lai').val();
                var mk = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #mat_khau').val();
                
                if (temp_ht == mk){
                    pr_obj.find('.check_x').remove();
                    pr_obj.find('.check_ok').remove();
                    pr_obj.append('<div class="check_ok"></div>');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
                }else{
                    pr_obj.find('.check_x').remove();
                    pr_obj.find('.check_ok').remove();
                    pr_obj.append('<div class="check_x"></div>');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Mật khẩu nhập lại không khớp');
                    $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                }
            }, 1000);
        });

        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> form.dat_lai_mk').unbind('submit').submit(function(){
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #ma_kich_hoat').val() == '')
            { 
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền mã kích hoạt');   
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #ma_kich_hoat').focus();
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;
            }
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau').val() == '')
            {
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền mật khẩu mới');   
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau').focus();
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;
            }
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau').val() != $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau_nhap_lai').val())
            {
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Mật khẩu nhập lại không khớp');   
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau_nhap_lai').focus();
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;
            }
            if($('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info .ma_xac_nhan').val() == '')
            {
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Vui lòng điền mã xác nhận');   
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info .ma_xac_nhan').focus();
                $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
                return false;
            }
            var aParam = new Array();
            aParam['val[active_code]'] = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #ma_kich_hoat').val();
            aParam['val[password]'] = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau').val();
            aParam['val[re_password]'] = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info #mat_khau_nhap_lai').val();
            aParam['val[captcha]'] = $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info .ma_xac_nhan').val();
            addRequest(aParam, 'user.forgetPassword', 'POST', 'json', 'resultReset()');
            return false;
        });
    };

    /*  Hàm lấy hình mới */
    function lay_hinh_moi<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        var url = setupPathCode(setupPath('s') + '/tools/hinh_anh.php?id=<?= $type?>' + '&_=' + Math.random());
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hinh_anh').html('<img src="' + url +'" />');
    };
    function lay_hinh_moi_buoc_2<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>(){
        var url = setupPathCode(setupPath('s') + '/tools/hinh_anh.php?id=<?= $type?>' + '&_=' + Math.random());
        $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> #hinh_anh_2').html('<img src="' + url +'" />');
    };
    
    function resultForgot()
    {
        if(result.status == 'error'){
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html(result.data.error);
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
        else if (result.status == 'success') {
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .border_wrap_login').addClass('border_user_info').removeClass('no_border');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_input_info').hide();
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info').fadeIn(200);
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .header_reset_pass').html('Mã kích hoạt đã được gửi tới email: ' + $('#hop_thu').val());
            lay_hinh_moi_buoc_2<? if($act == 'noinclude'):?>_popup<? endif?>();
            submit_form_reset<? if($act == 'noinclude'):?>_popup<? endif?>();
        }
        else {
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Đã có lỗi xảy ra. Vui lòng thử lại');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
        return false;
    }
    
    function resultReset()
    {
        if(result.status == 'error'){
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html(result.data.error);
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
            lay_hinh_moi_buoc_2<? if($this->_aVars['sAct'] == 'noinclude'):?>_popup<? endif?>();
        }
        else if (result.status == 'success') {
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').addClass('hide');
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_set_info').hide();
            $('<?if($this->_aVars['sAct'] == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .form_success_reset').fadeIn(200);
        }
        else {
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .content_err').html('Đã có lỗi xảy ra. Vui lòng thử lại');
            $('<?if($act == 'noinclude'):?>.popup_login<? else:?>.normal_login<? endif?> .err_list_login').removeClass('hide');
        }
        return false;
    }
    
    /* Bổ sung */
    function get_hash_tag(){
        var data = {};
        $.each(window.location.hash.replace("#", "").split("&"), function (i, value) {
            value = value.split("=");
            data[value[0]]= value[1]; 
        });
        return data; 
    }
    
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
    
</script>
