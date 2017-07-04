<section class="container">
    <div class="panel-box">
   	    <section class="grid-block">
            <div class="grid-box grid-h">
                <div class="module mod-box">
                    <div class="content-box panel-shadow">
                        <h3 class="box-title"><?= Core::getPhrase('language_chuc-nang')?></h3>
	                    <div class="box-inner">
                            <? if($this->_aVars['aData']['data']['status'] == 3):?>
                            <div class="row30 padtop10">
                                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                            </div>
                            <div class="row30 padtop10">
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/tab/add/';">
                                        <?= Core::getPhrase('language_them')?>
                                    </div>
                                </div>
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/tab/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            //redirect page
                            redirectPage();
                            function redirectPage()
                            {
                                window.location = '/tab/';
                            }
                            </script>s
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
                                            <a href="javascript:" onclick="return btn_cap_nhat_ma_ten()" style="margin-left: 10px; font-size:12px; font-family: HelveticaNeue; color: #999; font-weght: 200">(<?= Core::getPhrase('language_cap-nhat-tu-dong')?>)</a>
                                        </label>
                                    </div>
                                    <div class="col7">
                                        <input type="text" id="name_code" name="name_code" value="<?= $this->_aVars['aData']['data']['list']['name_code']?>" onblur="kiem_tra_ma_ten()" class="default-input"/>
                                    </div>
                                </div>
                                <div class="row30 line-bottom mgbt10">
                                    <div class="col5">
                                        <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                                    </div>
                                    <div class="col7">
                                        <select name="status" id="status" style="height: 30px; width:100%">
                                           <option value="1"<? if($this->_aVars['aData']['data']['list']['status'] == 1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                           <option value="0"<? if($this->_aVars['aData']['data']['list']['status'] == 0):?> selected="selected"<? endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                                         </select>
                                    </div>
                                </div>
                                <div class="row30 padtop10">
                                    <div class="w100 line-border" style="float: left;">
                                        <div class="button-blue" id="js-btn-submit">
                                            <?= Core::getPhrase('language_hoan-tat')?>
                                        </div>
                                    </div>
                                    <div class="w100 line-border" style="float: right;">
                                        <div class="button-blue" onclick="window.location = '/tab/';">
                                            <?= Core::getPhrase('language_quan-ly')?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script>
                        <? if($id < 1):?>
                        $('#name').keyup(function(){
                            lay_name_code_tu_dong($(this).val())
                        });
                        $('#name').change(function(){
                            lay_name_code_tu_dong($(this).val());
                            kiem_tra_name_code()
                        });
                        $('#name').blur(function(){
                            lay_name_code_tu_dong($(this).val());
                            kiem_tra_name_code();
                        });
                        <? endif?>
                        function lay_name_code_tu_dong(noi_dung, obj)
                        {
	                        if(obj == undefined) obj = 'name_code';
	                        noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
	                        noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
	                        
	                        document.getElementById(obj).value = noi_dung;
                        }
                        function kiem_tra_name_code() {
                        }
                        function btn_cap_nhat_name_code(id)
                        {
	                        if(id == undefined) id = 0;
	                        if(id == 0)
	                        {
		                        lay_name_code_tu_dong($('#name').val());
		                        kiem_tra_name_code();
	                        }
	                        else
	                        {
		                        lay_name_code_tu_dong($('#name').val());
	                        }
	                        return false;
                        }
                        function sbm_frm()
                        {
	                        return true;
                        }
                        
                        $(document).ready(function(){
                            $('#js-btn-submit').unbind('click').click(function(){
                                $(this).unbind('click');
                                $('#frm_add').submit();
                            });
                        });
                        </script>
                        <? endif?>               
		            </div>
	            </div>
            </div>
        </section>
    </div>
</section>