<section class="container">
    <div class="panel-box">
   	    <section class="grid-block">
            <div class="grid-box grid-h">
                <div class="module mod-box">
                    <div class="content-box panel-shadow">
                        <h3 class="box-title">Hình ảnh mở rộng</h3>
                        <div class="box-inner">
                        <? if($this->_aVars['aData']['data']['status'] == 3):?>
                            <div class="row30 padtop10">
                                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                            </div>
                            <div class="row30 padtop10">
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/imageextend/add/';">
                                        <?= Core::getPhrase('language_them')?>
                                    </div>
                                </div>
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/imageextend/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            //redirect page
                            redirectPage();
                            function redirectPage()
                            {
                                window.location = '/imageextend/';
                            }
                            </script>
                        <? else :?>
                            <form action="#" method="post" name="frm_add" id="frm_add" class="box style width100" onsubmit="return sbm_frm()">
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
                                        <label for="categories" class="sub-black-title"><?= Core::getPhrase('language_loai')?>:</label>
                                    </div>
                                    <div class="col7">
                                        <select name="categories" id="categories" class="inputbox" style="height: 30px; width:150px">
                                            <? foreach ($this->_aVars['aData']['data']['categories'] as $aVals):?><option value="<?= $aVals['name_code']?>"<? if($loai == $aVals['name_code']) {?> selected="selected"<? }?> title="<?= $aVals['ghi_chu']?>"><?= $aVals['name']?></option>
                                            <? endforeach?>
                                         </select>
                                    </div>
                                </div>
                                <div class="row30 line-bottom padbot10 mgbt10">
                                    <div class="col5">
                                        <label for="categories" class="sub-black-title">Kế thừa:</label>
                                    </div>
                                    <div class="col7">
                                        <select name="influence" id="influence" class="inputbox" style="height: 30px; width:150px">
                                            <? foreach ($this->_aVars['aData']['data']['influence'] as $aVals):?>
                                            <option value="<?= $aVals['name_code']?>"<?php if($influence == $aVals['name_code']) {?> selected="selected"<?php }?> title="<?= $aVals['ghi_chu']?>"><?= $aVals['name']?></option>
                                            <? endforeach?>
                                         </select>
                                    </div>
                                </div>
                                <div class="row30 line-bottom padbot10 mgbt10">
                                    <div class="col5">
                                        <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                                    </div>
                                    <div class="col7">
                                        <select name="status" id="status" class="inputbox" style="height: 30px; width:150px">
                                           <option value="1"<? if($status == 1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                           <option value="0"<? if($status == 0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
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
                                        <div class="button-blue" onclick="window.location = '/imageextend/';">
                                            <?= Core::getPhrase('language_quan-ly')?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script>
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
                        }
                        function kiem_tra_ma_ten() {
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
		                        lay_ma_ten_tu_dong($('#name').val());
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
                        <?php endif?>
                    </div>
	            </div>
            </div>
        </section>
    </div>
</section>