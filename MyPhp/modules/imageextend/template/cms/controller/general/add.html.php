<section class="container">
    <div class="panel-box">
        <section class="grid-block">
            <div class="grid-box grid-h">
                <div class="module mod-box">
                    <div class="content-box panel-shadow">
                        <? if(!empty($this->_aVars['aData']['data']['name'])):?>
                            <h3 class="box-title">Cập nhật nội dung mở rộng tổng</h3>
                        <? else :?>
                            <h3 class="box-title">Tạo nội dung mở rộng tổng</h3>
                        <? endif ?>
                        <div class="box-inner">
                            <? if( $this->_aVars['aData']['status_global'] == 1):?>
                            <div class="row30 padtop10">
                                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                            </div>
                            <div class="row30 padtop10">
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/imageextend/general/add/';">
                                        <?= Core::getPhrase('language_them')?>
                                    </div>
                                </div>
                                <div class="col3 padright10">
                                    <div class="button-blue" onclick="window.location='/imageextend/general/';">
                                        <?= Core::getPhrase('language_quan-ly')?>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                            //redirect page
                            redirectPage();
                            function redirectPage()
                            {
                                window.location = '/imageextend/general/';
                            }
                            </script>
                            <?php else :?>
                            <form action="#" method="post" id="frm_add" name="frm_dang_ky" class="box style width100" onsubmit="return sbm_frm()">
                                <? if($this->_aVars['aData']['data']['status'] == 'error'):?>
                                <div class="row30 padtb10 ">
                                    <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                                </div>
                                <div class="row30 padtb10">
                                        <div class="row30">
                                            <?= $this->_aVars['aData']['data']['message']?>
                                        </div>
                                </div>
                                <? endif?>
                                <div class="row30 line-bottom padbot10 mgbt10">
                                    <div class="col5">
                                        <label for="ten" class="sub-black-title" style="width: 50px">
                                            <?= Core::getPhrase('language_ten')?>:
                                        </label>
                                    </div>
                                    <div class="col7">
                                        <input type="text" id="ten" name="val[name]" value="<?= $this->_aVars['aData']['data']['name']?>" class="default-input"/>
                                        <input type="hidden" id="general_id" name="val[id]" value="<?= $this->_aVars['aData']['data']['id']?>" class="default-input"/>
                                    </div>
                                </div>
                                <div class="row30  line-bottom padbot10 mgbt10">
                                    <div class="col5">
                                        <label for="trang_thai" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                                    </div>
                                    <div class="col7">
                                        <select name="val[status]" id="trang_thai" style="height: 30px; width:100%">
                                           <option value="1"<?php if($this->_aVars['aData']['data']['status'] ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                                           <option value="0"<?php if($this->_aVars['aData']['data']['status'] ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
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
                                        <div class="button-blue" onclick="window.location = '/imageextend/general/';">
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
