<div class="container page-marketing page-marketing-add">
    <div class="content-box panel-shadow">
        <div class="box-title">
            <? if ($this->_aVars['aData']['id']): ?>
                <h3 class="box-title">'Thông tin chi tiết' <?= $this->_aVars['aData']['id']; ?></h3>
            <? else: ?>
                <h3 class="box-title">Tạo mã giảm giá</h3>
            <? endif; ?>   
        </div>
        <div class="box-inner">
            <? if($this->_aVars['aData']['data']['global_status'] == 3):?>
            <div class="row30 padtb10 ">
                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
            </div>
            <div class="row30 padtop10">
                <div class="col3 padright10">
                    <div class="button-blue" onclick="window.location='/discount/add/';">
                        <?= Core::getPhrase('language_them')?>
                    </div>
                </div>
                <div class="col3 padright10">
                    <div class="button-blue" onclick="window.location='/discount/';">
                        <?= Core::getPhrase('language_quan-ly')?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <form action="#" method="post" id="frm_add" onsubmit="return submitForm();">
                <div class="row30">
                    <div class="col12 padright10">
                        <? if($this->_aVars['aData']['status'] == 'error'):?>
                            <div class="row30 mgbt20">
                                <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                            </div>
                            <div class="row30 mgbt20">
                                <?= $this->_aVars['aData']['message']?>
                            </div>
                        <? endif?>
                        <div class="row30 mgbt20">
                            <div class="row30 sub-blue-title">Loại chương trình</div>
                            <select name="val[program_type]" id="js-program-type" class="<? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<?endif?>">
                                <? foreach($this->_aVars['aData']['data']['program_type'] as $sKey => $sVal):?>
                                <option value="<?= $sKey?>" <?php if($sKey == $this->_aVars['aData']['data']['list']['program_type']):?>selected="selected"<?php endif?>><?= $sVal?></option>
                                <?php endforeach?>
                            </select>
                        </div>
                        <div class="row30 mgbt20 <? if($this->_aVars['aData']['data']['list']['program_type'] != 1):?> none <? endif?>" id="js-vendor-list">
                            <div class="row30 sub-blue-title">Nhà cung cấp</div>
                            <select name="val[vendor_id]" id="select-list-vendor" class="<? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<?endif?>">
                                <option value="-1">Chọn nhà cung cấp</option>
                                <?php foreach($this->_aVars['aData']['data']['vendor_value'] as $sKey => $aVal):?>
                                <option value="<?= $aVal['id']?>" <?php if($aVal['id'] == $this->_aVars['aData']['data']['list']['vendor_id']):?>selected="selected"<?php endif?>><?= $aVal['name']?></option>
                                <?php endforeach?>
                            </select>
                        </div>
                        <div class="row30 mgbt20">
                            <div class="row30 sub-blue-title">Tên</div>
                            <input type="text" name="val[name]" value="<?= $this->_aVars['aData']['data']['list']['name']?>" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?> placeholder="Tên mã giảm giá ...">
                        </div>
                        <div class="row30">
                            <div class="row30 sub-blue-title">Mã số</div>
                            <div class="row30">
                                <div class="col6 padright10">
                                    <div class="row30">Mã (Tiền tố)</div>
                                    <input type="text" name="val[name_code]" value="<?= $this->_aVars['aData']['data']['list']['name_code']?>" placeholder="Tiền tố mã giảm giá ..." <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?>>
                                </div>
                                <div class="col6 padleft10">
                                    <div class="row30">Số lượng mã</div>
                                    <input type="text" name="val[total_item]" value="<?= $this->_aVars['aData']['data']['list']['total_item']?>" placeholder="Số lượng ..." <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?>>
                                    <!--<div class="row30">Mã</div>
                                    <input type="text" name="val[name_code]" value="<?= $this->_aVars['aData']['data']['list']['name_code']?>" placeholder="Mã số">-->
                                </div>
                            </div>
                        </div>
                        <div class="row30 mgbt20">
                            <div class="col3 padright10">
                                <div class="row30 sub-blue-title">Loại giảm giá</div>
                                <select name="val[type]" id="" class="<? if($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<? endif?>">
                                    <option value="-1">- <?= Core::getPhrase('language_chon')?> -</option>
                                    <? for($i = 0; $i < count($this->_aVars['aData']['data']['value_type']); $i++):?>
                                    <option value="<?= $i?>"<? if($this->_aVars['aData']['data']['list']['type'] == $i):?> selected="selected"<?php endif?>>
                                    <?= $this->_aVars['aData']['data']['value_type'][$i]?>
                                    </option>
                                    <? endfor?>
                                </select>
                            </div>
                            <div class="col6 padright10 padleft10">
                                <div class="row30 sub-blue-title">Giá trị</div>
                                <input type="text" name="val[value]" value="<?= $this->_aVars['aData']['data']['list']['value']?>" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?> placeholder="Giá trị theo loại giảm giá">
                            </div>
                            <div class="col3 padleft10">
                                <div class="row30 sub-blue-title">Số lần sử dụng</div>
                                <input type="text" name="val[times_to_use]" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?> value="<?= $this->_aVars['aData']['data']['list']['times_to_use']?>" placeholder="Số lần sử dụng">
                            </div>
                        </div>
                        <div class="row30 mgbt20">
                            <div class="col6 padright10">
                                <div class="row30 sub-blue-title">Ngày bắt đầu</div>
                                <input name="val[start_time]" value="<?= $this->_aVars['aData']['data']['list']['start_time']?>" id="js-start-time" placeholder="Chọn ngày..." class="js-date-time <? if($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<? endif?>" type="text">
                            </div>
                            <div class="col6 padleft10">
                                <div class="row30 sub-blue-title">Ngày kết thúc</div>
                                <input name="val[end_time]" value="<?= $this->_aVars['aData']['data']['list']['end_time']?>" id="js-end-time" placeholder="Chọn ngày..." class="js-date-time <? if($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<? endif?>" type="text">
                            </div>
                        </div>
                        <div class="row30 mgbt20">
                            <div class="col6 padright10">
                                <div class="row30 sub-blue-title">Loại áp dụng</div>
                                <select name="val[apply]" id="js-select-obj-apply" class="<? if($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<? endif?>">
                                    <?php foreach($this->_aVars['aData']['data']['apply'] as $sKey => $sVal):?>
                                    <option value="<?= $sKey?>" <?php if($sKey == $this->_aVars['aData']['data']['apply']):?>selected="selected"<?php endif?>><?= $sVal?></option>
                                    <?php endforeach?>
                                </select>
                            </div>
                            <div class="col6 padleft10">
                                <div class="row30 sub-blue-title">Trạng thái</div>
                                <select name="val[status]" class="<? if($this->_aVars['aData']['data']['list']['id'] > 0):?>disable-pointer<? endif?>">
                                    <option value="1"<?php if($this->_aVars['aData']['data']['list']['status'] ==1):?> selected="selected"<?php endif?>>
                                    <?= Core::getPhrase('language_kich-hoat')?>
                                    </option>
                                    <option value="0"<?php if($this->_aVars['aData']['data']['list']['status'] ==0):?> selected="selected"<?php endif?>>
                                    <?= Core::getPhrase('language_chua-kich-hoat')?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row30 mgbt20 <? if($this->_aVars['aData']['data']['list']['apply'] != 0):?> none <? endif?>" id="js-conds-order">
                            <div class="row30 sub-blue-title">Điều kiện áp dụng</div>
                            <div class="row30">
                                <div class="col12">
                                    <label class="label-custom" for="js-conds-all-order">
                                        <input type="checkbox" name="val[conds_select][order][all]" <?php if($this->_aVars['aData']['data']['conds_select']['order']['all'] == 'on'):?> checked="checked"<?php endif?> id="js-conds-all-order" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>disabled="disabled"<?endif?>>
                                        Cho tất cả hóa đơn
                                    </label>
                                </div>
                            </div>
                            <div class="row30 mgbt20">
                                <div class="col3">
                                    <label class="label-custom" for="js-conds-price-order">
                                        <input type="checkbox" name="val[conds_select][order][price]" <?php if($this->_aVars['aData']['data']['conds_select']['order']['price'] == 'on'):?> checked="checked"<?php endif?> id="js-conds-price-order" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>disabled="disabled"<?endif?>>
                                        Tổng tiền hóa đơn trên :
                                    </label>
                                </div>
                                <div class="col9">
                                    <input type="text" name="val[conds][order][price][from]" value="<?= $this->_aVars['aData']['data']['list']['conds']['order']['price']['from']?>" placeholder="Tổng tiền tối thiếu của hóa đơn" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?>>
                                </div>
                            </div>
                        </div>
                        <div class="row30 mgbt20 <? if($this->_aVars['aData']['data']['list']['apply'] != 1):?> none <? endif?>" id="js-conds-product">
                            <div class="row30 sub-blue-title">Điều kiện áp dụng</div>
                            <div class="row30 mgbt20">
                                <div class="col3">
                                    <label class="label-custom" for="">
                                        <input type="checkbox" name="val[conds_select][product][price]" <?php if($this->_aVars['aData']['data']['conds_select']['product']['price'] == 'on'):?> checked="checked"<?php endif?> <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>disabled="disabled"<?endif?>>
                                        Sản phẩm có giá trị từ:
                                    </label>
                                </div>
                                <div class="col4">
                                    <input type="text" name="val[conds][product][price][from]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['price']['from']?>" placeholder="Giá tiền từ" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?>>
                                </div>
                                <div class="col1 txt-center">
                                    Đến:
                                </div>
                                <div class="col4">
                                    <input type="text" name="val[conds][product][price][to]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['price']['to']?>" placeholder="Giá tiền đến" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?>>
                                </div>
                            </div>
                            <div class="row30 mgbt20">
                                <div class="col3">
                                    <label class="label-custom">
                                        <input type="checkbox" name="val[conds_select][product][list]" <?php if($this->_aVars['aData']['data']['conds_select']['product']['list'] == 'on'):?> checked="checked"<?php endif?> <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>disabled="disabled"<?endif?>>
                                        Chọn sản phẩm áp dụng:
                                    </label>
                                </div>
                                <div class="col9 suggest-marketing">
                                    <input type="text" class="js-search-product" data-type="product" placeholder="Tìm kiếm" <? if ($this->_aVars['aData']['data']['list']['id'] > 0):?>readonly="readonly"<?endif?>>
                                    <div class="list-suggest js-scroll none">
                                        <div class="item-suggest">Sản phẩm</div>
                                        <div class="item-suggest">Sản phẩm</div>
                                        <div class="item-suggest">Sản phẩm</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row30 mgbt20" id="js-product-list">
                                <div class="row30 sub-blue-title">
                                    Danh sách sản phẩm áp dụng
                                </div>
                                <? if ($this->_aVars['aData']['data']['conds_select']['product']['list'] == 'on' && !empty($this->_aVars['aData']['data']['list']['conds']['product']['list']['id'])):?>
                                <? for ($i = 0; $i < count($this->_aVars['aData']['data']['list']['conds']['product']['list']['id']); $i++):?>
                                <div class="row30 mgbt20 js-dis-product" id="js-object-<?= $i?>">
                                     <div class="col2">
                                        Tên sản phẩm:
                                     </div>
                                     <div class="col4">
                                        <label class="label-custom"><?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['name'][$i]?></label>
                                     </div>
                                     <div class="col1">
                                        SKU:
                                     </div>
                                     <div class="col4">
                                        <label class="label-custom"><?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['sku'][$i]?></label>
                                     </div>
                                     <input type="hidden" name="val[conds][product][list][id][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['id'][$i]?>">
                                     <input type="hidden" name="val[conds][product][list][influence_id][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['influence_id'][$i]?>">
                                     <input type="hidden" name="val[conds][product][list][name][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['name'][$i]?>">
                                     <input type="hidden" name="val[conds][product][list][sku][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['sku'][$i]?>">
                                     <input type="hidden" name="val[conds][product][list][category_id][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['category_id'][$i]?>">
                                     <input type="hidden" name="val[conds][product][list][unit_id][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['unit_id'][$i]?>">
                                     <input type="hidden" name="val[conds][product][list][unit_name][]" value="<?= $this->_aVars['aData']['data']['list']['conds']['product']['list']['unit_name'][$i]?>">
                                     <div class="col1">
                                        <? if ($this->_aVars['aData']['data']['list']['id'] == 0):?>
                                        <span class="fa fa-close right icon-wh js-delete-product" data-id="<?= $i?>"></span>
                                        <?endif?>
                                     </div>
                                </div>
                                <? endfor?>
                                <? endif?>
                            </div>
                        </div>
                        <div class="row30">
                            <div class="col6"></div>
                            <div class="col3 padleft20">
                                <!--<div class="button-default">Hủy</div>-->
                            </div>
                            <div class="col3 padleft20">
                            <? if ($this->_aVars['aData']['data']['list']['id'] == 0):?>
                                <div class="button-blue" id="js-btn-submit">Hoàn thành</div>
                            <? endif?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif?>
        </div>
    </div>
</div>
