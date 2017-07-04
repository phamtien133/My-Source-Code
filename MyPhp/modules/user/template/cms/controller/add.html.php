<section class="container">
<div class="panel-box">
<section class="grid-block">
    <div class="grid-box grid-h">
        <div class="module mod-box">
            <div class="content-box panel-shadow">
                <h3 class="box-title"><?= Core::getPhrase('language_chuc-nang')?></h3>
                <div class="box-inner">
                <?php if($this->_aVars['iStatus'] != 2):?>
                <form action="#"  method="post" name="frm_dang_ky" id="frm_add">
                    <?php if(!empty($this->_aVars['aError'])):?>
                        <div class="row30 padtb10 ">
                            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                        </div>
                        <div class="row30 padtb10">
                            <?php foreach($this->_aVars['aError'] as $error):?>
                                <div class="row30">
                                    <?= $error?>
                                </div>
                            <?php endforeach?>
                        </div>
                    <?php endif?>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col5">
                            <label for="fullname" class="sub-black-title" style="width: 100%">
                                Họ và tên: (<span style="color: rgb(255, 0, 0);">*</span>)
                            </label>
                            <!--<span id="div_ten_kiem_tra_ma_ten"></span>-->
                        </div>
                        <div class="col7">
                            <input type="text" id="fullname" name="fullname" value="<?= $this->_aVars['aVals']['fullname']?>" class="default-input"/>
                        </div>
                    </div>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col5">
                            <label for="username" class="sub-black-title" style="width: 100%">
                                <?= Core::getPhrase('language_ten-truy-cap')?>: (<span style="color: rgb(255, 0, 0);">*</span>)
                            </label>
                        </div>
                        <div class="col7">
                            <input type="text" id="username" name="username" value="<?= $this->_aVars['aVals']['username']?>" class="default-input"/>
                        </div>
                    </div>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col5">
                            <label for="password" class="sub-black-title" style="width: 100%">
                                <?= Core::getPhrase('language_mat-khau')?>: (<span style="color: rgb(255, 0, 0);">*</span>)
                            </label>
                        </div>
                        <div class="col7">
                            <input id="password" name="passwd" value="" type="password" autocomplete="off" class="default-input"/>
                        </div>
                    </div>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col5">
                            <label for="email" class="sub-black-title" style="width: 100%">
                                <?= Core::getPhrase('language_hop-thu')?>: (<span style="color: rgb(255, 0, 0);">*</span>)
                            </label>
                        </div>
                        <div class="col7">
                            <input type="text" id="email" name="email" value="<?= $this->_aVars['aVals']['email']?>" class="default-input"/>
                        </div>
                    </div>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col5">
                            <label for="birthday" class="sub-black-title" style="width: 100%">
                                <?= Core::getPhrase('language_ngay-sinh')?>: (<span style="color: rgb(255, 0, 0);">*</span>)
                            </label>
                        </div>
                        <div class="col7">
                            <input type="text" id="birthday" name="birthday" value="<?= $this->_aVars['aVals']['birthday']?>" class="default-input js-date-time tt tt2"/>
                        </div>
                    </div>
                    <div class="row30 mgbt10">
                        <div class="col5">
                            <label for="email" class="sub-black-title" style="width: 100%">
                                <?= Core::getPhrase('language_gioi-tinh')?>: (<span style="color: rgb(255, 0, 0);">*</span>)
                            </label>
                        </div>
                        <div class="col7">
                            <select style="height: 30px; width:245px;" class="inputbox" name="sex">
                                <option value="1"<?php if($this->_aVars['aVals']['sex']==1){?> selected="selected"<?php }?>>
                                <?= Core::getPhrase('language_gioi-tinh-nam')?>
                                </option>
                                <option value="2"<?php if($this->_aVars['aVals']['sex']==2){?> selected="selected"<?php }?>>
                                <?= Core::getPhrase('language_gioi-tinh-nu')?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <div class="row30"> 
                        <div class="col1">
                            <div class="button-blue" type="submit" name="submit" id="js-btn-submit">
                                <?= Core::getPhrase('language_hoan-tat')?>
                            </div>
                            <input type="hidden" name="is_submit" id="js-submit" value=0>
                        </div>
                        <div class="col10"></div>
                        <div class="col1">
                            <div class="button-blue" onclick="window.location = '/user/<?= $this->_aVars['sType']?>'">
                                <?= Core::getPhrase('language_quan-ly')?>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <script>
                        document.getElementById("fullname").focus();
                        $('#js-btn-submit').unbind('click').click(function(){
                            $(this).unbind('click');
                            $('#js-submit').val(1);
                            $('#frm_add').submit();
                        });
                    </script>
                </form>
                <?php else :?>
                    <div class="row30 padtop10">
                        <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                    </div>
                    <div class="row30 padtop10">
                        <div class="col3 padright10">
                            <div class="button-blue" onclick="window.location='/user/add/<?= $this->_aVars['sType']?>';">
                                <?= Core::getPhrase('language_them')?>
                            </div>
                        </div>
                        <div class="col3 padright10">
                            <div class="button-blue" onclick="window.location='/user/<?= $this->_aVars['sType']?>';">
                                <?= Core::getPhrase('language_quan-ly')?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                    //redirect page
                    redirectPage();
                    function redirectPage()
                    {
                        window.location = '/user/<?= $this->_aVars['sType']?>';
                    }
                    </script>
                <?php endif?>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
</section>