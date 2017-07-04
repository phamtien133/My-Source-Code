<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<section class="container">
    <div class="panel-box">
        <section class="grid-block">
            <div class="grid-box grid-h">
                <div class="module mod-box">
                    <div class="content-box panel-shadow">
                        <h3 class="box-title"><?= Core::getPhrase('language_chuc-nang')?></h3>
                        <div class="box-inner">
                        <?php if($status == 3):?>
                            <div class="row30 padtop10">
                                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                            </div>
                            <div class="row30 padtop10">
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/menu/add/';">
                                        <?= Core::getPhrase('language_them')?>
                                    </div>
                                </div>
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/menu/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            //redirect page
                            redirectPage();
                            function redirectPage()
                            {
                                window.location = '/unit/';
                            }
                            </script>
                        <?php elseif($status == 2) :?>
                            <div class="row30 padtb10 ">
                                <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                            </div>
                            <div class="row30 padtb10">
                                <?php foreach($errors as $error):?>
                                    <div class="row30">
                                        <?= $error?>
                                    </div>
                                <?php endforeach?>
                            </div>
                            <div class="row30 padtop10">
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/menu/add/';">
                                        <?= Core::getPhrase('language_them')?>
                                    </div>
                                </div>
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/menu/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                        <?php else :?>
                        <form action="#" method="post" name="frm_dang_ky" id="frm_add" class="box style width100" onsubmit="return sbm_frm()">
                            <?php if(!empty($errors)):?>
                                <div class="row30 padtb10 ">
                                    <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                                </div>
                                <div class="row30 padtb10">
                                    <?php foreach($errors as $error):?>
                                        <div class="row30">
                                            <?= $error?>
                                        </div>
                                    <?php endforeach?>
                                </div>
                            <?php endif?>
                            <div class="row30 line-bottom padbot10 mgbt10">
                                <div class="col5">
                                    <label for="name" class="sub-black-title" style="width: 50px">
                                        <?= Core::getPhrase('language_ten')?>:
                                    </label>
                                    <span id="div_ten_kiem_tra_ma_ten"></span>
                                </div>
                                <div class="col7">
                                    <input type="text" id="name" name="name" value="<?= $ten?>" class="default-input"/>
                                </div>
                            </div>
                            <div class="row30 line-bottom padbot10 mgbt10">
                                <div class="col5">
                                    <label for="name_code" class="sub-black-title">
                                        <?= Core::getPhrase('language_ma-ten')?>:
                                        <a href="javascript:" onclick="return btn_cap_nhat_ma_ten()" style="margin-left: 10px; font-size:12px; font-family: HelveticaNeue; color: #999; font-weght: 200">(<?= Core::getPhrase('language_cap-nhat-tu-dong')?>)</a>
                                    </label>
                                </div>
                                <div class="col7">
                                    <input type="text" id="name_code" name="name_code" value="<?= $ma_ten?>" onblur="kiem_tra_ma_ten()" class="default-input"/>
                                </div>
                            </div>
                            <div class="row30 mgbt10">
                                <div class="col5">
                                    <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                                </div>
                                <div class="col7">
                                    <select name="status" id="status" style="height: 30px; width:100%">
                                       <option value="1"<?php if($trang_thai ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                       <option value="0"<?php if($trang_thai ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                                     </select>
                                </div>
                            </div>
                            <hr />
                            <div class="row30"> 
                                <div class="col1">
                                    <div class="button-blue" type="submit" name="submit" id="js-btn-submit">
                                        <?= Core::getPhrase('language_hoan-tat')?>
                                    </div>
                                </div>
                                <div class="col10"></div>
                                <div class="col1">
                                    <div class="button-blue" onclick="window.location = '/menu/'">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </form>
                        <script>
                            $('#js-btn-submit').unbind('click').click(function(){
                                $(this).unbind('click');
                                $('#frm_add').submit();
                            });
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
                                    http.send('val[type]=menu&val[name_code]='+unescape(noi_dung));
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
                                    lay_ma_ten_tu_dong($('#ten_' + id).val(), 'ma_ten_' + id);
                                }
                                return false;
                            }
                            function sbm_frm()
                            {
                                return true;
                            }
                        </script>
                        <?php endif?>
                        <div> </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
