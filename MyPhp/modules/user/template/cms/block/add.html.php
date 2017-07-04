<div class="container pad20 page-customer page-customer-edit">
    <section class="content-box panel-shadow">
        <div class="box-title">
            <div class="left title-form">
                <?= $this->_aVars['aPage']['title']?>
            </div>
            <div class="right">
                <!--<span class="fa fa-star icon-wh"></span>
                <span class="fa fa-phone icon-wh"></span>
                <span class="fa fa-envelope icon-wh"></span>
                <span class="fa fa-comments icon-wh"></span>-->
                <span class="fa fa-close icon-wh js-cancel-user-edit"></span>
            </div>
        </div>
        <div class="box-inner">
            <div class="row30 mgbt10">
                <form action="#" method="post" id="frm_add_user">
                    <div id="js-error-popup">
                    </div>
                    <?php if(!empty($this->_aVars['aErrors'])):?>
                    <div class="row30 dialog-err">
                        <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                    </div>
                    <?php foreach($this->_aVars['aErrors'] as $sError):?>
                    <div class="row30">
                        <?= $sError?>
                    </div>
                    <?php endforeach?>
                    <?php endif?>
                    <div class="col6 padright10">
                        <div class="row20 mgbt10">
                            <div class="col4">
                                <img src="<?= $this->_aVars['aUser']['profile_image']?>" alt="" class="img-user">
                            </div>
                            <div class="col8">
                                <div class="row20 mgbt10">
                                    <div class="row20 sub-blue-title">Họ và tên</div>
                                    <input type="text" name="val[fullname]" value="<?= $this->_aVars['aUser']['fullname']?>" placeholder="Điền thông tin...">
                                    <input type="hidden" name="val[id]" value="<?= $this->_aVars['aUser']['id']?>">
                                    <input type="hidden" name="val[iAcp]" value="1">
                                </div>
                                <div class="row20">
                                    <div class="row20 sub-blue-title">Công ty</div>
                                    <input type="text" name="val[company]" value="<?= $this->_aVars['aUser']['company']?>" placeholder="Điền thông tin...">
                                </div>
                            </div>
                        </div>
                        <div class="row20">
                            <div class="row20 mgbt10">
                                <div class="row20 sub-blue-title">Điện thoại</div>
                                <input type="text" name="val[phone_number]" value="<?= $this->_aVars['aUser']['phone_number']?>" placeholder="Điền thông tin...">
                            </div>
                            <div class="row20 mgbt10">
                                <div class="row20 sub-blue-title">Hộp thư</div>
                                <input type="text" name="val[email]" value="<?= $this->_aVars['aUser']['email']?>" placeholder="Điền thông tin...">
                            </div>
                            <!--<div class="row20">
                                <div class="row20 sub-blue-title">Nhóm</div>
                                <select name="val[user_group]">
                                    <option value="-1">Chọn Nhóm thành viên</option>
                                    <? $iFlag = false;?>
                                    <? foreach ($this->_aVars['aUser']['group_list'] as $sKey => $aVal):?>
                                    <option value="<?= $aVal['id']?>" <? if($this->_aVars['aUser']['group']['id'] == $aVal['id']):?> selected="selected" <? $iFlag = true; endif?> ><?= $aVal['name']?></option>
                                    <? endforeach?>
                                    <? if (!$iFlag && $this->_aVars['aUser']['group']['id'] > -1):?>
                                    <option value="<?= $this->_aVars['aUser']['group']['id']?>" selected="selected"><?= $this->_aVars['aUser']['group']['name']?></option>
                                    <? endif?>
                                </select>
                            </div>-->
                        </div>
                    </div>
                    <div class="col6 padleft10">
                        <div class="row20 mgbt10">
                            <div class="row20 sub-blue-title">Quốc gia</div>
                            <select name="val[country]" id="js-country">
                                <option value="-1">Chọn Quốc gia</option>
                                <? $iFlag = false;?>
                                <? foreach ($this->_aVars['aUser']['country_list'] as $sKey => $aVal):?>
                                <option value="<?= $aVal['id']?>" <? if($this->_aVars['aUser']['country']['id'] == $aVal['id']):?> selected="selected" <? $iFlag = true; endif?> ><?= $aVal['name']?></option>
                                <? endforeach?>
                                <? if (!$iFlag && $this->_aVars['aUser']['country']['id'] > 0):?>
                                <option value="<?= $this->_aVars['aUser']['country']['id']?>" selected="selected"><?= $this->_aVars['aUser']['country']['name']?></option>
                                <? endif?>
                            </select>
                        </div>
                        <div class="row20 mgbt10">
                            <div class="row20 sub-blue-title">Tỉnh / Thành phố</div>
                            <select name="val[city]" id="js-city">
                                <option value="-1">Chọn Tỉnh thành</option>
                                <? $iFlag = false;?>
                                <? foreach ($this->_aVars['aUser']['city_list'] as $sKey => $aVal):?>
                                <option value="<?= $aVal['id']?>" <? if($this->_aVars['aUser']['city']['id'] == $aVal['id']):?> selected="selected" <? $iFlag = true; endif?> ><?= $aVal['name']?></option>
                                <? endforeach?>
                                <? if (!$iFlag && $this->_aVars['aUser']['city']['id'] > 0):?>
                                <option value="<?= $this->_aVars['aUser']['city']['id']?>" selected="selected"><?= $this->_aVars['aUser']['city']['name']?></option>
                                <? endif?>
                            </select>
                        </div>
                        <div class="row20 mgbt10">
                            <div class="row20 sub-blue-title">Quận / Huyện</div>
                            <select name="val[district]" id="js-district">
                                <option value="-1">Chọn Quận Huyện</option>
                                <? $iFlag = false;?>
                                <? foreach ($this->_aVars['aUser']['district_list'] as $sKey => $aVal):?>
                                <option value="<?= $aVal['id']?>" <? if($this->_aVars['aUser']['district']['id'] == $aVal['id']):?> selected="selected" <? $iFlag = true; endif?> ><?= $aVal['name']?></option>
                                <? endforeach?>
                                <? if (!$iFlag && $this->_aVars['aUser']['district']['id'] > 0):?>
                                <option value="<?= $this->_aVars['aUser']['district']['id']?>" selected="selected"><?= $this->_aVars['aUser']['district']['name']?></option>
                                <? endif?>
                            </select>
                        </div>
                        <div class="row20 mgbt10">
                            <div class="row20 sub-blue-title">Phường / Xã</div>
                            <input type="text" name="val[ward]" value="<?= $this->_aVars['aUser']['ward']?>" placeholder="Điền thông tin..." >     
                        </div>
                        <div class="row20 mgbt10">
                            <div class="col12">
                                <div class="row20">
                                    <div class="row20 sub-blue-title">Số nhà & Tên đường</div>
                                    <input type="text" name="val[street]" value="<?= $this->_aVars['aUser']['street']?>" placeholder="...">
                                </div>
                            </div>
                        </div>
                        <div class="row30 padtb20">
                            <div class="col6 padright10">
                                <div class="button-trans js-cancel-user-edit">
                                    Hủy...
                                </div>
                            </div>
                            <div class="col6 padleft10">
                                <div class="button-blue" id="js-submit-add-user">
                                    Lưu thông tin
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
