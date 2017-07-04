<div class="container page-marketing page-marketing-add">
    <div class="content-box panel-shadow">
        <div class="box-title">
            <?= $this->_aVars['aPage']['title']?>
        </div>
        <div class="box-inner">
            <?php if($iStatus == 3):?>
            <div class="row30 padtb10 ">
                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
            </div>
            <div class="row30 padtop10">
                <div class="col3 padright10">
                    <div class="button-blue" onclick="window.location='/user/field/add/';">
                        <?= Core::getPhrase('language_them')?>
                    </div>
                </div>
                <div class="col3 padright10">
                    <div class="button-blue" onclick="window.location='/user/field/';">
                        <?= Core::getPhrase('language_quan-ly')?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <form action="#" method="post" name="frm_dang_ky" id="frm_add" class="box style width100" onsubmit="return sbm_frm()">
                 <?php if(!empty($this->_aVars['aErrors'])):?>
                    <div class="row30 padtb10 ">
                        <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
                    </div>
                    <div class="row30 padtb10">
                        <?php foreach($this->_aVars['aErrors'] as $error):?>
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
                        <span id="div_ten_kiem_tra_name_code"></span>
                    </div>
                    <div class="col7">
                        <input type="text" id="name" name="val[name]" value="<?= $this->_aVars['aData']['name']?>" class="default-input" onblur="kiem_tra_name_code()"/>
                        <input type="hidden" name="val[id]" value="<?= $this->_aVars['iId']?>">
                    </div>
                </div>
                <div class="row30 line-bottom padbot10 mgbt10">
                    <div class="col5">
                        <label for="name_code" class="sub-black-title">
                            <?= Core::getPhrase('language_ma-ten')?>:
                            <a href="javascript:" onclick="return btn_cap_nhat_name_code()" style="margin-left: 10px; font-size:12px; font-family: HelveticaNeue; color: #999; font-weght: 200">(<?= Core::getPhrase('language_cap-nhat-tu-dong')?>)</a>
                        </label>
                    </div>
                    <div class="col7">
                        <input type="text" id="name_code" name="val[name_code]" value="<?= $this->_aVars['aData']['name_code']?>" onblur="kiem_tra_name_code()" class="default-input"/>
                    </div>
                </div>
                <div class="row30 mgbt10 padbot10 line-bottom">
                    <div class="col5">
                        <label  class="sub-black-title">
                            <input type="checkbox" name="val[display_in_register]" value="1" <?if ($this->_aVars['aData']['display_in_register']):?> checked="checked" <? endif?> >
                            Hiển thị khi đăng ký
                        </label>
                    </div>
                    <div class="col6">
                        <label class="sub-black-title">
                            <input type="checkbox" name="val[is_require]" value="1" <?if ($this->_aVars['aData']['is_require']):?> checked="checked" <? endif?>>
                            Yêu cầu bắt buộc
                        </label>
                    </div>
                </div>
                <div class="row30 mgbt10 padbot10 line-bottom">
                    <div class="col5">
                        <label for="status" class="sub-black-title">Nhóm:</label>
                    </div>
                    <div class="col7">
                        <select name="val[group_id]" style="height: 30px; width:100%">
                            <option value="0">Chọn nhóm</option>
                            <? foreach ($this->_aVars['aFieldGroup'] as $aGroup) : ?>
                                <option value="<?= $aGroup['id']?>"<?php if($this->_aVars['aData']['group_id'] == $aGroup['id']):?> selected="selected"<?php endif;?>><?= $aGroup['name']?></option>
                            <? endforeach; ?>
                         </select>
                    </div>
                </div>
                <div class="row30 mgbt10 padbot10 line-bottom">
                    <div class="col5">
                        <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                    </div>
                    <div class="col7">
                        <select name="val[status]" id="status" style="height: 30px; width:100%">
                           <option value="1"<?php if($this->_aVars['aData']['status'] ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                           <option value="0"<?php if($this->_aVars['aData']['status'] ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                         </select>
                    </div>
                </div>
                <div class="row30 mgbt10 padbot10 line-bottom">
                    <div class="col5">
                        <label class="sub-black-title">Kiểu dữ liệu:</label>
                    </div>
                    <div class="col7">
                        <select name="val[type]" id="js-type-file" style="height: 30px; width:100%">
                            <? foreach ($this->_aVars['aData']['type_list'] as $sKey => $sVals):?>
                                <option value="<?= $sKey?>" <?php if($this->_aVars['aData']['type'] == $sKey):?> selected="selected"<?php endif?> ><?= $sVals?></option>
                            <? endforeach; ?>
                         </select>
                    </div>
                </div>
                <div id="js-block-list">
                    <? if ($this->_aVars['aData']['type'] != 'text'):?>
                    <div class="row30 mgbt10" id="js-field-value-list" data-count="<?= count($this->_aVars['aData']['option'])?>">
                        <div class="row30 mgbt10">
                            <div class="col3">
                                <label for="status" class="sub-black-title">Danh sách giá trị:</label>
                            </div>
                            <div class="col2">
                                <div id="js-add-value" class="button-blue"> Thêm giá trị mới </div>
                            </div>
                        </div>
                        <? foreach ($this->_aVars['aData']['option'] as $sKey => $aVals):?>
                            <div class="row30 js-field-value" id="js-object-<?= ($sKey+1)?>">
                                <div class="col1">
                                    Giá trị:
                                </div>
                                <div class="col4">
                                    <input type="text" name="val[list_name][]" value="<?= $aVals['name']?>" />
                                </div>
                                <div class="col2 padleft20">
                                    Mã code:
                                </div>
                                <div class="col4">
                                    <input type="text" name="val[list_code][]" value="<?= $aVals['name_code']?>" />
                                </div>
                                    <input type="hidden" name="val[list_id][]" value="<?= $aVals['id']?>">
                                <div class="col1">
                                    <span class="fa fa-close right icon-wh js-delete-value" data-id="<?= ($sKey+1)?>"></span>
                                </div>
                            </div>
                        <? endforeach?>
                    </div>
                    <? endif?>
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
                        <div class="button-blue" onclick="window.location = '/user/group/'">
                            <?= Core::getPhrase('language_quan-ly')?>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif?>
        </div>
    </div>
</div>
<script type="text/javascript">
var domain_name = '<?= Core::getDomainName();?>';
var group_id = '<?= $id?>';
</script>
