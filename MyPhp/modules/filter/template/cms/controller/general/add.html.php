<section class="container">
    <div class="panel-box">
        <section class="grid-block">
            <div class="grid-box grid-h">
                <div class="module mod-box">
                    <div class="content-box panel-shadow">
                        <h3 class="box-title"><?= Core::getPhrase('language_chuc-nang')?></h3>
                        <div class="box-inner">
                            <? if($this->_aVars['aData']['data']['global_status'] == 3):?>
                            <div class="row30 padtop10">
                                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                            </div>
                            <div class="row30 padtop10">
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/filter/general/add/';">
                                        <?= Core::getPhrase('language_them')?>
                                    </div>
                                </div>
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/filter/general/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            //redirect page
                            redirectPage();
                            function redirectPage()
                            {
                                window.location = '/filter/general/';
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
                                    </div>
                                    <div class="col7">
                                        <input type="text" id="name" name="name" value="<?= $this->_aVars['aData']['data']['list']['name']?>" class="default-input"/>
                                    </div>
                                </div>
                                <div class="row30  line-bottom padbot10 mgbt10">
                                    <div class="col5">
                                        <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                                    </div>
                                    <div class="col7">
                                        <select name="status" id="status" style="height: 30px; width:100%">
                                           <option value="1"<? if($this->_aVars['aData']['data']['list']['name'] == 1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                           <option value="0"<? if($this->_aVars['aData']['data']['list']['name'] == 0):?> selected="selected"<? endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                                         </select>
                                    </div>
                                </div>
                                <div class="row30 line-bottom padbot10 mgbt10">
                                     <?= Core::getPhrase('language_gia-tri')?>:
                                </div>
                                <div class="row30 line-bottom padbot10 mgbt10">
                                 <? for($i=0;$i<count($this->_aVars['aData']['data']['list']['val_name']);$i++):?>
                                    <div class="fleft width30">
                                        <input type="checkbox" <? if(in_array($this->_aVars['aData']['data']['list']['val_id'][$i], $this->_aVars['aData']['data']['list']['val_select'])):?> checked="checked"<? endif?> value="<?= $this->_aVars['aData']['data']['list']['val_id'][$i]?>" name="val[<?= $i?>]" id="val_<?= $i?>" />
                                        <label for="val_<?= $i?>"><?= $this->_aVars['aData']['data']['list']['val_name'][$i]?></label>
                                    </div>
                                    <? endfor?>
                                    <div style="clear:both"></div>
                                </div>
                                <div class="row30 padtop10">
                                    <div class="w100 line-border" style="float: left;">
                                        <div class="button-blue" id="js-btn-submit">
                                            <?= Core::getPhrase('language_hoan-tat')?>
                                        </div>
                                    </div>
                                    <div class="w100 line-border" style="float: right;">
                                        <div class="button-blue" onclick="window.location = '/filter/general/';">
                                            <?= Core::getPhrase('language_quan-ly')?>
                                        </div>
                                    </div>
                                </div>
                            <!--<div>
                                <div style="float:left;width:180px;"><label for="ten"><?= Core::getPhrase('language_ten')?>:</label><span id="div_ten_kiem_tra_ma_ten"></span></div>
                                <input type="text" id="ten" name="ten" value="<?= $ten?>" />
                            </div>
                            
                            <div>
                                <div style="float:left;width:180px;"><label for="trang_thai"><?= Core::getPhrase('language_trang-thai')?>:</label></div>
                                <select name="trang_thai" id="trang_thai">
                                   <option value="1"<?php if($trang_thai ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                   <option value="0"<?php if($trang_thai ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                                   </select>
                             </div>-->
                        
                        <!--<br clear="all" />
                            <div class="w100 line-border" style="float: left;">
                                <md-button class="button-blue" type="submit" name="submit">
                                    <?= Core::getPhrase('language_hoan-tat')?>
                                </md-button>
                            </div>
                            <div class="w100 line-border" style="float: right;">
                                <div class="button-blue" onclick="window.location = '/filter/general/index/'">
                                    <?= Core::getPhrase('language_quan-ly')?>
                                </div>
                            </div>-->
                            </form>
                        </div>
                        <script>
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