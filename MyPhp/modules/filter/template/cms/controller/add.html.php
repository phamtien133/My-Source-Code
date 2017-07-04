<section class="container">
<div class="panel-box">
    <section class="grid-block">
        <div class="grid-box grid-h">
            <div class="module mod-box">
                <div class="content-box panel-shadow">
                    <h3 class="box-title">Tạo trích lọc <? echo (is_array($this->_aVars['aData']['data']['list']['value'])); ?></h3>
                    <div class="box-inner">
                        <? if ($this->_aVars['aData']['data']['status'] == 3):?>
                        <div class="row30 padtop10">
                            <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                        </div>
                        <div class="row30 padtop10">
                            <div class="col3 padright10">
                                <div class="button-blue" onclick="window.location='/filter/add/';">
                                    <?= Core::getPhrase('language_them')?>
                                </div>
                            </div>
                            <div class="col3 padright10">
                                <div class="button-blue" onclick="window.location='/filter/';">
                                    <?= Core::getPhrase('language_quan-ly')?>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                        //redirect page
                        redirectPage();
                        function redirectPage()
                        {
                            window.location = '/filter/';
                        }
                        </script>
                        <? else :?>
                        <form action="#" method="post" id="frm_add" name="frm_dang_ky" class="box style width100" onsubmit="return sbm_frm()">
                            <? if($this->_aVars['aData']['status'] == 'error'):?>
                                <div class="row30 padtb10 ">
                                    <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
                                </div>
                                <div class="row30 padtb10">
                                        <div class="row30">
                                            <?= $this->_aVars['aData']['message']?>
                                        </div>
                                </div>
                            <? endif?>
                            <div class="row30 line-bottom padbot10 mgbt10">
                                <div class="col5">
                                    <label for="name" class="sub-black-title" style="width: 50px">
                                        <?= Core::getPhrase('language_ten')?>:
                                    </label>
                                    <span id="div_ten_kiem_tra_ma_ten"></span>
                                </div>
                                <div class="col7">
                                    <input type="text" id="name" name="name" value="<?= $this->_aVars['aData']['data']['list']['name']?>" class="default-input"/>
                                </div>
                            </div>
                            <div class="row30 line-bottom padbot10 mgbt10">
                                <div class="col5">
                                    <label for="name_code" class="sub-black-title">
                                        <?= Core::getPhrase('language_ma-ten')?>:
                                        <a href="javascript:" onclick="return btn_cap_nhat_ma_ten()" style="margin-left: 10px; font-size:12px; font-family: HelveticaNeue; color: #999; font-weght: 200">(<?= Core::getPhrase('language_cap-nhat-tu-dong')?>)</a><br>Lưu ý: Với hãng sản xuất, vui lòng đặt mã tên là <strong>production</strong>
                                    </label>
                                </div>
                                <div class="col7"> 
                                    <input type="text" id="name_code" name="name_code" value="<?= $this->_aVars['aData']['data']['list']['name_code']?>" onblur="kiem_tra_ma_ten()" class="default-input"/>
                                </div>
                            </div>
                            <div class="row30 line-bottom padbot10 mgbt10">
                                <div class="col5">
                                    <label for="type" class="sub-black-title"><?= Core::getPhrase('language_loai')?>:</label>
                                </div>
                                <div class="col7">
                                    <select name="type" id="type" >
                                       <? foreach ($this->_aVars['aData']['data']['list_type'] as $aVals):?>
                                        <option value="<?= $aVals['name_code']?>"<? if($this->_aVars['aData']['data']['list']['type'] == $aVals['name_code']) {?> selected="selected"<? }?> title="<?= $aVals['note']?>"><?= $aVals['name']?></option>
                                        <? endforeach?>
                                     </select>
                                </div>
                            </div>
                            <div class="row30 line-bottom padbot10 mgbt10">
                                <div class="col5">
                                    <label for="is_display" class="sub-black-title">
                                        Show in:
                                    </label>
                                </div>
                                <div class="col7">
                                    <input type="checkbox" value="1" name="is_display" id="is_display" class="inputbox"<? if($this->_aVars['aData']['data']['list']['is_display'] == 1) {?> checked<? }?>> <?= Core::getPhrase('language_trang-chu')?>
                                </div>
                            </div>
                            <div class="row30 mgbt10">
                                <div class="col5">
                                    <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                                </div>
                                <div class="col7">
                                    <select name="status" id="status">
                                       <option value="1"<? if($this->_aVars['aData']['data']['list']['status'] ==1):?> selected="selected"<? endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                       <option value="0"<? if($this->_aVars['aData']['data']['list']['status'] ==0):?> selected="selected"<? endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                                    </select>
                                </div>
                            </div>
                            <hr />
                            <!-- -->
                            <div class="row30 line-bottom mgbt10">
                                <label for="gia_tri" class="sub-black-title"><?= Core::getPhrase('language_gia-tri')?>:</label>
                            </div>
                            <div class="row30">
                                <div id="div_slide" class=" padbot10 mgbt10">
                                </div>
                                <div style="clear:both"></div>
                                <div class="row30 padtop10">
                                    <div class="col2">
                                        <select id="sel_div_slide" style="height: 30px; width:100%">
                                            <option value="0" selected="selected">
                                            <?= Core::getPhrase('language_cuoi-cung')?>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col2">
                                        <a class="button-blue" style="padding: 5px 10px; margin-left: 10px;" onclick="them_slide()">
                                            <span class="fa fa-plus"></span>
                                            Thêm
                                        </a>
                                    </div>
                                </div>
                                <div style="clear:both"></div>
                                <hr />
                                <br clear="all" />
                            </div>
                            <!-- -->
                            <div class="row30 padtop10">
                                <div class="w100 line-border" style="float: left;">
                                    <div class="button-blue" id="js-btn-submit">
                                        <?= Core::getPhrase('language_hoan-tat')?>
                                    </div>
                                </div>
                                <div class="w100 line-border" style="float: right;">
                                    <div class="button-blue" onclick="window.location = '/filter/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <script>
            var danh_sach_loai = [];
            var filters = <?= json_encode($trich_loc) ?>;
            <? foreach($trich_loc as $i => $val):?>
            danh_sach_loai.push("<?= $val['type']?>");
            <? endforeach?>

            function get_list_tl(obj)
            {
                sParams = '&'+ getParam('sGlobalTokenName') + '[call]=filter.getFilterValue';
                sParams += '&fid=' + obj.val();
                $.ajax({
                    crossDomain:true,
                    xhrFields: {
                        withCredentials: true
                    },
                    url: getParam('sJsAjax'),
                    type: "POST",
                    data: sParams,
                    timeout: 15000,
                    cache:false,
                    dataType: 'text',
                    error: function(jqXHR, status, errorThrown){
                        alert('Error');
                    },
                    success: function (data) {
                        $('#js-search-filter #filter-value-list').html(data);
                        $('#js-search-filter').removeClass('none');
                        initSelectFilterValue();
                    }
                });
            }
            function initSelectFilterValue()
            {
                $('.search-filter-box .js-filter-value-item').unbind('click').click(function(){
                    var obj = $(this);
                    var fv_select = false;
                    if (obj.hasClass('atv')) {
                        fv_select = true;
                    }
                    var fv_id = trim(obj.attr('data-id'));
                    var fv_name = trim(obj.html());
                    if (fv_select) {
                        // thực hiện bỏ chọn
                        obj.removeClass('atv');
                        removeSelectFilter(fv_id, fv_name);
                    }
                    else {
                        // thực hiện chọn.
                        obj.addClass('atv');
                        chon_trich_loc(fv_id, fv_name)
                    }
                });
            }
            function chon_trich_loc(id, name)
            {
                var obj = $('.search-filter-box #stt_bai_lien_ket_lien_quan').val();
                obj = '#' + obj;

                name = name + '-' + id;
                if ($(obj).tagExist(name))
                    return false;

                $(obj).addTag(name);

                return false;
            }
            function removeSelectFilter(id, name)
            {
                var obj = $('.search-filter-box #stt_bai_lien_ket_lien_quan').val();
                obj = '#' + obj;
                name = name + '-' + id;
                $(obj).removeTag(name);
            }
            function cap_nhat()
            {
                $('.div_menu_nhom_0').datasort({
                    datatype    : 'number',
                    sortElement : '.val_pos_0',
                    reverse     : false
                });
            }
            var hinh_slide_hien_tai = 0;

            function upHinhMoRong(arr) {
                    if(arr == undefined) arr = {};
                    if(arr.obj == undefined) arr.obj = '';
                    if(arr.type == undefined) arr.type = '1';
                    if(arr.width == undefined) arr.width = 0;
                    else arr.width *= 1;
                    if(arr.height == undefined) arr.height = 0;
                    else arr.height *= 1;

                    hinh_slide_hien_tai = arr.id;

                    function receiveMessage(e) {
                        if (e.origin !== 'http://img.' + global['domain'] + ':8080') return;
                        window.removeEventListener("message", receiveMessage, false);
                        settings = JSON.parse(e.data);
                        $('#' + settings['id']).val(settings['value']);
                        $('#' + settings['id']).trigger(settings['trigger']);

                        $.modal.close();
                        $.fancybox.close();
                    }
                    window.addEventListener('message', receiveMessage);

                moPopup(document.location.protocol + '//img.' + global['domain'] + ':8080/dialog.php?type=1&field_id=' + arr.obj +'&height=' + arr.height + '&width=' + arr.width + '&sid=<?= session_id()?>',
                    function(){
                        $('.duong_dan_hinh_mo_rong').change(function(e) {
                            btn_cap_nhat_ma_ten(id);
                        });
                    },
                    600,
                    600
                );
            }
            $(document).ready(function(){
                $('#js-btn-submit').unbind('click').click(function(){
                    $(this).unbind('click');
                    $('#frm_add').submit();
                });

                $('#type').change(function(e) {
                    //if($(this).val().indexOf('co-dinh') == -1) return ;

                    if($(this).val().indexOf('khong-co-dinh') == -1 && $(this).val().indexOf('khoang-gia-tri') == -1)
                    {
                        $('#div_slide').fadeIn('fast');

                        if($(this).val().indexOf('-hinh-anh') == -1)
                            $('#div_slide').find('button').fadeOut('fast');
                        else
                            $('#div_slide').find('button').fadeIn('fast');
                    }
                    else
                    {
                        $('#div_slide').fadeOut('fast');
                    }

                });
                $('#type').change();
                <? if($cap_nhat_vi_tri):?>
                cap_nhat();
                <? endif?>
            });
            function doi_vi_tri(classname, stt_can, stt_thay)
            {
            var obj = ' .val_pos_' + classname;
            var gia_tri_can = $('#div_slide_' + stt_can + obj).html();

            $('#div_slide_' + stt_can + obj).html($('#div_slide_' + stt_thay + obj).html());
            $('#div_slide_' + stt_thay + obj).html(gia_tri_can);

            cap_nhat();
            }
            function tang_giam_vi_tri(classname, stt, loai)
            {
                var objs = $('.div_menu_nhom_' + classname),  i = 0, stt_sau = '', obj;
                if(loai == 1)
                {
                    for(i = 0;i<objs.length;i++)
                    {
                        obj = $(objs[i+1]);
                        if(obj.attr('id') == undefined)
                        {
                            stt_sau = $(objs[objs.length-1]).attr('id').replace('div_slide_', '');
                            doi_vi_tri(classname, stt_sau, stt);
                            break;
                        }
                        else
                        {
                            stt_sau = obj.attr('id').replace('div_slide_', '');
                            if(stt_sau == stt)
                            {
                                stt = $(objs[i]).attr('id').replace('div_slide_', '');
                                doi_vi_tri(classname, stt, stt_sau);
                                break;
                            }
                        }
                    }
                }
                else if(loai == 0)
                {
                    for(i = objs.length-1;i>=0;i--)
                    {
                        obj = $(objs[i-1]);
                        if(obj.attr('id') == undefined)
                        {
                            stt_sau = $(objs[0]).attr('id').replace('div_slide_', '');
                            doi_vi_tri(classname, stt_sau, stt);
                            break;
                        }
                        else
                        {
                            stt_sau = obj.attr('id').replace('div_slide_', '');
                            if(stt_sau == stt)
                            {
                                stt = $(objs[i]).attr('id').replace('div_slide_', '');
                                doi_vi_tri(classname, stt, stt_sau);
                                break;
                            }
                        }
                    }
                }
                else
                {
                    var vi_tri = null;
                    while(1)
                    {
                        vi_tri = prompt("Vui lòng nhập vị trí cần chuyển tới", "1");
                        if(vi_tri == null)
                        {
                            break;
                        }
                        vi_tri *= 1;

                        if(vi_tri > 0)
                        {
                            break;
                        }
                        else alert('Không thể xác định vị trí cần chuyển tới!');
                    }
                    if(vi_tri == null) return false;

                    stt = 'div_slide_' + stt;
                    vi_tri -= 1;
                    var tong = 0, vi_tri_can = -1;
                    var ton_tai = false;
                    for(i = 0;i<objs.length;i++)
                    {
                        tong++;
                        if(i == vi_tri)
                        {
                            vi_tri_can = tong;
                            tong++;
                        }
                        if($(objs[i]).attr('id') == stt)
                        {
                            // nếu vị trí nhỏ hơn đối tượng
                            if(vi_tri_can == -1)
                            {
                                // lưu trạng thái
                                continue ;
                            }
                            ton_tai = true;
                            $('#' + $(objs[i]).attr('id') + ' .val_pos_' + classname).html(vi_tri_can);
                            continue;
                        }
                        $('#' + $(objs[i]).attr('id') + ' .val_pos_' + classname).html(tong);
                    }
                    // nếu vị trí nhỏ hơn đối tượng
                    if(!ton_tai)
                    {
                        if(vi_tri_can > 0)
                        {
                            for(i = 0;i<objs.length;i++)
                            {
                                if($(objs[i]).attr('id') == stt)
                                {
                                    $('#' + $(objs[i]).attr('id') + ' .val_pos_' + classname).html(vi_tri_can);
                                    ton_tai = true;
                                    break;
                                }
                            }
                        }
                        else
                        {
                            $('#' + stt + ' .val_pos_' + classname).html(objs.length + 1);
                        }
                    }
                    cap_nhat();
                }
                return false;
            }
            function initTagsInput()
            {
                $('.tags').each(function() {
                    var tid = $(this).attr('id');
                    if ($('#'+tid+'_tagsinput').length > 0) {}
                    else {
                        $('#'+tid).tagsInput({
                            width: 'auto',
                            delimiter: '|',
                            interactive:false,
                            onChange: function(elem, elem_tags) {
                                var n = 0;
                                $('.tags', elem_tags).each(function(){
                                    if(n % 2 === 0)
                                    {
                                        $(this).css('background-color', 'yellow');
                                    }
                                    n++;
                                });
                            }
                        });
                    }
                });
            }
            var slide = 0, lan_dau = true;
            function them_slide(arr)
            {
                console.log(arr);
                /* default data */
                var tmps = {
                    ten: [''],
                    ma_ten: [''],
                    trang_thai: [1],
                    stt: [0],
                    description: [''],
                    image_path: [''],
                    cha_trich_loc_gt: [[]],
                };

                if(arr == undefined) arr = tmps;
                for(i in tmps)
                    if(arr[i] == undefined) arr[i] = tmps[i];
                tmps = {};

                if(arr == undefined) arr = tmps;

                var vi_tri = parseInt($('#sel_div_slide').val());
                var slide_pos = 0;
                var content = '';
                // update cac node
                var objs = $('.div_menu_nhom_0'),  i = 0;

                if(vi_tri == -1)
                {
                    slide_pos = 1;
                    for(i = 0; i < objs.length; i++)
                    {
                        slide_pos += 1;
                        $('#' + $(objs[i]).attr('stt') + ' .val_pos_0').html(slide_pos);
                    }
                    slide_pos = 1;
                }
                else if(vi_tri > 0)
                {
                    slide_pos = vi_tri + 1;
                    for(i = vi_tri; i < objs.length; i++)
                    {
                        slide_pos += 1;
                        $('#' + $(objs[i]).attr('stt') + ' .val_pos_0').html(slide_pos);
                    }
                    slide_pos = vi_tri + 1;
                }
                else
                {
                    slide++;
                    slide_pos = slide;
                }
                // end

                slide--;
                for(i in arr['ten'])
                {

                    slide++;
                    if(arr['ten'].length > 1)
                    {

                        slide_pos = slide;
                    }

                    gt_cha_trich_loc = '';
                    for(j in arr['cha_trich_loc_gt'][i])
                    {
                        console.log(arr['cha_trich_loc_gt'][i]['name']);
                        gt_cha_trich_loc += arr['cha_trich_loc_gt'][i][j]['name'] + '-' + arr['cha_trich_loc_gt'][i][j]['id'] + '|';
                    }
                    /*
                    content += '<div class="div_menu div_con div_menu_nhom_0" id="div_slide_' + slide + '">\
                            <div style="float:right"> \
                                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 1)">\
                                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up.png" />\
                                </a>\
                                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 2)">\
                                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up-down-custom.png" />\
                                </a>\
                                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 0)">\
                                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/down.png" />\
                                </a>\
                                <a href="javascript:void(this)" onclick="xoa_slide(' + slide + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a>\
                            </div>\
                            <p><div style="float:left;width:180px;"><label for="ten_' + slide + '"><?= Core::getPhrase('language_ten')?></label></div><input type="text" name="gt_ten[' + slide + ']" id="ten_' + slide + '" value="' + arr['ten'][i] + '" class="inputbox" style="width:50%;" /><button type="button" onclick="upHinhMoRong({id: ' + slide + ', obj: \'ten_' + slide + '\'})"><span class="round"><span><?= Core::getPhrase('language_tai-hinh-len')?></span></span></button></p>\
                            <p><div style="float:left;width:180px;"><label for="ma_ten_' + slide + '"><?= Core::getPhrase('language_ma-ten')?></label>(<a href="javascript:" onclick="return btn_cap_nhat_ma_ten(' + slide + ')"><?= Core::getPhrase('language_cap-nhat-tu-dong')?></a>)</div><input type="text" name="gt_ma_ten[' + slide + ']" id="ma_ten_' + slide + '" value="' + arr['ma_ten'][i] + '" class="inputbox ma_ten" style="width:50%;" /></p>\
                            <p><div style="float:left;width:180px;"><label for="cha_trich_loc_gt_' + slide + '">Inherit</label>(<a href="javascript:" onclick="return them_cha_trich_loc(' + slide + ')"><?= Core::getPhrase('language_them')?></a>)</div><input name="cha_trich_loc_gt[' + slide + ']" id="cha_trich_loc_gt_' + slide + '" class="inputbox tags" style="width:50%;" value="' + gt_cha_trich_loc + '" /></p>\
                            <div style="float:left;width:180px;"><label for="trang_thai_' + slide + '"><?= Core::getPhrase('language_trang-thai')?>:</label></div><select name="gt_trang_thai[' + slide + ']" id="trang_thai_' + slide + '"><option value="1" ';
                       if(arr['trang_thai'][i] == 1) content += 'selected="selected"';
                       content += '><?= Core::getPhrase('language_kich-hoat')?></option><option value="0"';
                       if(arr['trang_thai'][i] == 0) content += 'selected="selected"';
                       content += '><?= Core::getPhrase('language_chua-kich-hoat')?></option></select>\
                 </div>\
                        <div class="hidden val_pos_0">' + slide_pos + '</div>\
                        <input type="hidden" name="gt_stt[' + slide + ']" value="' + arr['stt'][i] + '" />\
                        </div>';
                        */

                        content += '<div class="row30 line-bottom padbot10 mgbt10" id="div_slide_' + slide + '">\
                                        <div class="row30">\
                                            <div class="col3">\
                                                Tên:\
                                            </div>\
                                            <div class="col6">\
                                                <input id="ten_' + slide + '" class="default-input" type="text" value="' + arr['ten'][i] + '" name="val_name[' + slide + ']">\
                                            </div>\
                                            <div class="col3">\
                                                <a class="del-trich-loc" onclick="xoa_slide(' + slide + ')" href="javascript:void(this)">\
                                                    <span class="fa fa-trash" alt="Xóa"></span>\
                                                </a>\
                                            </div>\
                                        </div>\
                                        <div class="row30">\
                                            <div class="col3">\
                                                Mã tên:\
                                                (<a onclick="return btn_cap_nhat_ma_ten(' + slide + ')" href="javascript:">Cập nhât tự động</a>)\
                                            </div>\
                                            <div class="col6">\
                                                <input id="ma_ten_' + slide + '" class="default-input ma_ten" type="text" value="' + arr['ma_ten'][i] + '" name="val_name_code[' + slide + ']">\
                                            </div>\
                                            <div class="col3">\
                                            </div>\
                                        </div>\
                                        <div class="row30">\
                                            <div class="col3">\
                                                Kế thừa:\
                                                (<a onclick="return them_cha_trich_loc(' + slide + ')" href="javascript:">Thêm</a>)\
                                            </div>\
                                            <div class="col6">\
                                                <input name="parent_filter_value_list[' + slide + ']" id="cha_trich_loc_gt_' + slide + '" class="inputbox tags" style="width:100%;height: 100px;" value="' + gt_cha_trich_loc + '" />\
                                            </div>\
                                            <div class="col3">\
                                            </div>\
                                        </div>\
                                        <div class="row30">\
                                            <div class="col3">\
                                                Mô tả:\
                                            </div>\
                                            <div class="col6">\
                                                <textarea name="val_description[' + slide + ']" cols="" rows="" class="inputbox"" style="width:100%;height: 100px;">' + arr['description'][i] + '</textarea>\
                                            </div>\
                                            <div class="col3">\
                                            </div>\
                                        </div>\
                                        <div class="row30">\
                                            <div class="col3">\
                                                Ảnh đại diện:\
                                            </div>\
                                            <div class="col5">\
                                                <input id="image_path_' + slide + '" class="inputbox text-input default-input" type="text" value="' + arr['image_path'][i] + '" name="val_image_path[' + slide + ']">\
                                            </div>\
                                            <div class="col1 padlr10">\
                                                <div class="button-blue" type="button" onclick="opHinhAnh(' + slide + ')">Upload</div>\
                                            </div>\
                                            <div class="col3">\
                                            </div>\
                                        </div>\
                                        <div class="row30">\
                                            <div class="col3">\
                                                Trạng thái:\
                                            </div>\
                                            <div class="col6">\
                                                <select id="trang_thai_' + slide + '" name="val_status[' + slide + ']">\
                                                    <option selected="selected" value="1"';
                                                    if(arr['trang_thai'][i] == 1) content += 'selected="selected"';
                           content += '><?= Core::getPhrase('language_kich-hoat')?></option><option value="0"';
                           if(arr['trang_thai'][i] == 0) content += 'selected="selected"';
                           content += '><?= Core::getPhrase('language_chua-kich-hoat')?></option>\
                                                </select>\
                                            </div>\
                                            <div class="col3">\
                                                <input type="hidden" name="val_id[' + slide + ']" value="' + arr['stt'][i] + '" />\
                                            </div>\
                                        </div>\
                                    </div>';

                }
                /*
                if(vi_tri == -1)
                {
                    $('#div_slide').first().before(content);
                }
                else if(vi_tri > 0)
                {
                    $('#div_slide_' + vi_tri).after(content);
                }
                else
                {
                    $('#div_slide').append(content);
                }
                */
                if(arr['ten'].length > 1) document.getElementById('div_slide').innerHTML = content;
                else $('#div_slide').append(content);
                cap_nhat();
                $('#type').change();
                initTagsInput();
                if(!lan_dau) $('html, body').animate({scrollTop: $('#div_slide_' + slide).offset().top}, 800);
            }
            function cap_nhat_slide()
            {
                // cập nhật select box
                var val = '', stt = 0;
                $('#sel_div_slide').html('<option value="0" selected="selected"><?= Core::getPhrase('language_cuoi-cung')?></option><option value="-1"><?= Core::getPhrase('language_tren-dau')?></option>');
                //$('#sel_div_slide').append(new Option(val, 'Slide ' + val, true, true));
                //alert($('#div_slide').html());
                $('#div_slide .div_con').each(function(index, element) {
                    stt = $(this).attr('stt').replace('div_slide_', '');
                    val = $('#ten_' + stt).val();
                    $('#sel_div_slide').append('<option value="' + stt + '">' + val + '</option>');
                    //$('#sel_div_slide').append(new Option(val, 'Slide ' + val, true, true));
                });
            }
            function them_cha_trich_loc(slide)
            {
                sParams = '&'+ getParam('sGlobalTokenName') + '[call]=filter.showSearchFilterBox';
                sParams += '&value=' + slide;
                $.ajax({
                    crossDomain:true,
                    xhrFields: {
                        withCredentials: true
                    },
                    url: getParam('sJsAjax'),
                    type: "POST",
                    data: sParams,
                    timeout: 15000,
                    cache:false,
                    dataType: 'text',
                    error: function(jqXHR, status, errorThrown){
                        alert('Error');
                    },
                    success: function (data) {
                        insertPopupCrm(data, ['.js-cancel-add-parent-filter'], '.search-filter-box', true);

                    }
                });
            }
            $('#sel_div_slide').focus(function(e) {
                cap_nhat_slide();
            });
            function xoa_slide(stt)
            {
                if(!confirm('<?=Core::getPhrase('language_ban-co-chac-muon-xoa-slide-nay')?>')) return false;
                $('#div_slide_' + stt).remove();
                cap_nhat();
            }

            function opHinhAnh(id) {
                upHinhMoRong({obj: 'image_path_' + id});
            }
            <?php

            if( 1 == 1 && is_array($this->_aVars['aData']['data']['list']['value']))
            {
                ?>
                var tmps = {
                    ten: [],
                    ma_ten: [],
                    trang_thai: [],
                    stt: [],
                    description: [],
                    image_path: [],
                    cha_trich_loc_gt: [],
                };
                <?
                for($i=0;$i<count($this->_aVars['aData']['data']['list']['value']);$i++)
                {
                    $tmp_ten = str_replace("'", "\'", $this->_aVars['aData']['data']['list']['value'][$i]['name']);
                    $tmp_ma_ten = str_replace("'", "\'", $this->_aVars['aData']['data']['list']['value'][$i]['name_code']);
                    $tmp_trang_thai = str_replace("'", "\'", $this->_aVars['aData']['data']['list']['value'][$i]['status']);
                    $tmp_des = str_replace("'", "\'", $this->_aVars['aData']['data']['list']['value'][$i]['description']);
                    $tmp_img = str_replace("'", "\'", $this->_aVars['aData']['data']['list']['value'][$i]['image_path']);
                    $tmp_stt = str_replace("'", "\'", $this->_aVars['aData']['data']['list']['value'][$i]['id']);
                    $tmp_cha_trich_loc_gt_chon = json_encode($this->_aVars['aData']['data']['list']['value'][$i]['parent_filter_value_list']);
                ?>
                tmps['ten'].push('<?= $tmp_ten?>');
                tmps['ma_ten'].push('<?= $tmp_ma_ten?>');
                tmps['trang_thai'].push('<?= $tmp_trang_thai?>');
                tmps['stt'].push('<?= $tmp_stt?>');
                tmps['description'].push('<?= $tmp_des?>');
                tmps['image_path'].push('<?= $tmp_img?>');
                tmps['cha_trich_loc_gt'].push(<?= $tmp_cha_trich_loc_gt_chon?>);
                <?
                }
                ?>
                them_slide(tmps);
                <?
            }
            else
            {
            ?>
                them_slide();
            <?
            }
            ?>
            lan_dau = false;
            <? if($id < 1):?>
            $('#name').keyup(function(){
                lay_ma_ten_tu_dong($(this).val())
            });
            $('#name').change(function(){
                lay_ma_ten_tu_dong($(this).val());
                kiem_tra_ma_ten()
            });
            $('#name').blur(function(){
                lay_ma_ten_tu_dong($(this).val());
                kiem_tra_ma_ten();
            });
            <? endif?>
            function lay_ma_ten_tu_dong(noi_dung, obj)
            {
                if(obj == undefined) obj = 'name_code';
                noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
                noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');

                document.getElementById(obj).value = noi_dung;
                if(obj == 'name_code' && ma_ten_truoc != noi_dung)
                {
                    var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
                    if(obj_ten.innerHTML != '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">' )
                    {
                        obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
                    }
                }
            }
            var ma_ten_truoc = document.getElementById("name_code").value;
            function kiem_tra_ma_ten() {
                var noi_dung = document.getElementById("name_code").value;
                var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
                if(ma_ten_truoc != noi_dung)
                {
                    obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
                    http.open('POST', '/includes/ajax.php?=&core[call]=core.checkNameCode', true);
                    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
                    http.onreadystatechange = function () {
                        if(http.readyState == 4){
                            ma_ten_truoc = noi_dung;
                            if( http.responseText == 1)
                            {
                                obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png">';
                            }
                            else
                            {
                                obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png">';
                            }
                        }
                    };
                    http.send('val[id]=<?= $id?>&val[type]=filter&val[name_code]='+unescape(noi_dung));
                }
            }

            function btn_cap_nhat_ma_ten(id)
            {
                if(id == undefined) id = 0;
                if(id == 0)
                {
                    lay_ma_ten_tu_dong($('#name').val());
                    kiem_tra_ma_ten();
                }
                else
                {
                    var tmp = $('#ten_' + id).val();

                    if($('#type').val().indexOf('-hinh-anh') != -1)
                    {
                        var start, end;

                        start = tmp.lastIndexOf('/');
                        end = tmp.indexOf('?', start);
                        if(end != -1) tmp = tmp.substr(0, end - start);

                        end = tmp.indexOf('.', start);
                        if(end == -1) end = tmp.length;

                        tmp = tmp.substr(start, end - start);

                        if(tmp == '') tmp = Math.floor((Math.random()*100000)+1);
                    }

                    lay_ma_ten_tu_dong(tmp, 'ma_ten_' + id);

                    // check các giá trị trước đó
                    $('#div_slide .ma_ten').each(function(index, element) {
                        if( ($(this).attr('id') == 'ma_ten_' + id) || $(this).val() != $('#ma_ten_' + id).val()) return true;
                        $('#ma_ten_' + id).val( $('#ma_ten_' + id).val() + '-' + Math.floor((Math.random()*100000)+1) );
                        return false;
                    });

                }
                return false;
            }
            function sbm_frm()
            {
                // xử lý cha_trich_loc_gt
                $('.tags').each(function(index, element) {
                    var tmps = $(this).val().split('|');
                    var content = '', tmp = '';
                    for(i in tmps)
                    {
                        tmp = tmps[i];
                        if(tmp == '') continue ;
                        pos = tmp.lastIndexOf('-') + 1;
                        tmp = tmp.substr(pos, tmp.length - pos);
                        tmp *= 1;
                        if(tmp < 1) continue ;
                        content += tmp + '|';
                    }
                    if(content != '')
                    {
                        content = content.substr(0, content.length - 1);
                        $(this).val(content);
                    }
                    else
                    {
                        $(this).remove();
                    }
                });
                return true;
            }
            </script>
                        <? endif?>


                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</section>
