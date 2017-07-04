<div class="container pad20 page-customer page-customer-edit">
    <section class="content-box panel-shadow">
        <div class="box-title">
            <div class="left title-form">
                Thông tin chi tiết
            </div>
            <div class="right">
                <!--<span class="fa fa-eye icon-wh js-ban-user" title=""></span>-->
                <span class="fa fa-plus icon-wh js-recharge" title="Nạp tiền"></span>
                <!--<span class="fa fa-star icon-wh"></span>-->
                <span class="fa fa-phone icon-wh" title="Gọi điện"></span>
                <!--<span class="fa fa-envelope icon-wh"></span>
                <span class="fa fa-comments icon-wh"></span>-->
                
                <span class="fa fa-pencil icon-wh js-edit-user" title="Chỉnh sửa thông tin"></span>
                <span class="fa fa-close icon-wh js-close-view-user" data-type="<?= $this->_aVars['sType']?>"></span>
                <input type="hidden" value="<?= $this->_aVars['aUser']['id']?>" id="js-user-id">
            </div>
        </div>
        <div class="box-inner">
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
            <div class="row30 sub-blue-title">
                Thông tin cá nhân
            </div>
            <div class="row30 mgbt10">
                <div class="col6 padright10">
                    <div class="row20 mgbt10">
                        <div class="col4">
                            <img src="<?= $this->_aVars['aUser']['profile_image']?>" alt="" class="img-user">
                        </div>
                        <div class="col8">
                            <div class="row20 mgbt10">
                                <div class="row20 sub-blue-title">Họ và tên</div>
                                <input type="text" value="<?= $this->_aVars['aUser']['fullname']?>" placeholder="Điền thông tin...">
                            </div>
                            <div class="row20">
                                <div class="row20 sub-blue-title">Công ty</div>
                                <input type="text" placeholder="Điền thông tin..." value="<?= $this->_aVars['aUser']['company']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row20">
                        <div class="row20 mgbt10">
                            <div class="row20 sub-blue-title">Điện thoại</div>
                            <input type="text" value="<?= $this->_aVars['aUser']['phone_number']?>" placeholder="Điền thông tin...">
                        </div>
                        <div class="row20 mgbt10">
                            <div class="row20 sub-blue-title">Hộp thư</div>
                            <input type="text" value="<?= $this->_aVars['aUser']['email']?>" placeholder="Điền thông tin...">
                        </div>
                        <!--<div class="row20">
                            <div class="row20 sub-blue-title">Nhóm</div>
                            <select name="">
                                <option value="<?= $this->_aVars['aUser']['group']['id']?>"><?= $this->_aVars['aUser']['group']['name']?></option>
                            </select>
                        </div>-->
                    </div>
                </div>
                <div class="col6 padleft10">
                    <div class="row20 mgbt10">
                        <div class="col12">
                            <div class="row20">
                                <div class="row20 sub-blue-title">Số nhà & Tên đường</div>
                                <input type="text" value="<?= $this->_aVars['aUser']['street']?>" placeholder="...">
                            </div>
                        </div>
                        <!--<div class="col9 padleft10">
                            <div class="row20">
                                <div class="row20 sub-blue-title">Tên đường</div>
                                <input type="text" placeholder="...">
                            </div>
                        </div>-->
                    </div>
                    <div class="row20 mgbt10">
                        <div class="row20 sub-blue-title">Phường / Xã</div>
                        <input type="text" value="<?= $this->_aVars['aUser']['ward']?>" placeholder="Điền thông tin...">     
                    </div>
                    <div class="row20 mgbt10">
                        <div class="row20 sub-blue-title">Quận / Huyện</div>
                        <select name="">
                            <option value="<?= $this->_aVars['aUser']['district']['id']?>"><?= $this->_aVars['aUser']['district']['name']?></option>
                        </select>
                    </div>
                    <div class="row20 mgbt10">
                        <div class="row20 sub-blue-title">Tỉnh / Thành phố</div>
                        <select name="">
                            <option value="<?= $this->_aVars['aUser']['city']['id']?>"><?= $this->_aVars['aUser']['city']['name']?></option>
                        </select>
                    </div>
                    <div class="row20 mgbt10">
                        <div class="row20 sub-blue-title">Quốc gia</div>
                        <select name="">
                            <option value="<?= $this->_aVars['aUser']['country']['id']?>"><?= $this->_aVars['aUser']['country']['name']?></option>
                        </select>
                    </div>
                    <!--<div class="row30 padtb20">
                        <div class="col6 padright10">
                            <div class="button-trans">
                                Hủy...
                            </div>
                        </div>
                        <div class="col6 padleft10">
                            <div class="button-blue">
                                Lưu thông tin
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="row30  sub-blue-title">
                Ví diện tử
            </div>
            <div class="row30 mgbt10">
                <? foreach ($this->_aVars['aUserPoint'] as $aPoint): ?>
                <div class="col3">
                    <? if ($aPoint['name_code'] =='tien_mat'):?>Số tiền trong tài khoản: <?else:?><? if ($aPoint['name_code'] =='diem_thuong'):?>Điểm thưởng: <?else:?> Tài khoản <?= $aPoint['name']?> <? endif;?><? endif;?>
                </div>
                <div class="col3">
                    <?= Core::getService('core.currency')->formatMoney($aPoint['point']) .' '.$aPoint['name']?>
                </div>
                <? endforeach; ?>
            </div>
            <div class="row30 sub-blue-title">
                Thông tin giao hàng
            </div>
            <div class="row30 mgbt10">
                <? if (isset($this->_aVars['aUser']['contact_info']) && !empty($this->_aVars['aUser']['contact_info'])):?>
                <? $iCnt = 1;?>
                <? foreach ($this->_aVars['aUser']['contact_info'] as $sKey => $aVal):?>
                <div class="row30 sub-blue-title">Địa chỉ <?= $iCnt?></div>
                <div class="row30">
                    <div class="col6">
                        Người nhận:
                        <label class="label-custom"><?= $aVal['fullname']?></label>
                    </div>
                    <div class="col6">
                        Số điện thoại:
                        <label class="label-custom"><?= $aVal['phone_number']?></label>
                    </div>
                </div>
                <div class="row30">
                    Địa chỉ:
                    <label class="label-custom"><?= $aVal['address']?></label>
                </div>
                <? $iCnt++;?>
                <? endforeach?>
                <? else:?>
                <div class="row30 dialog-empty">
                    Chưa có thông tin.
                </div>
                <? endif?>
            </div>
            <div class="row30 sub-blue-title">
                Lịch sử mua hàng 
            </div>
            <div class="row30 mgbt10">
                <? if (isset($this->_aVars['aHistory']['data']) && !empty($this->_aVars['aHistory']['data'])):?>
                <div class="row30 line-bottom">
                    <div class="col2">
                        Mã
                    </div>
                    <div class="col3">
                        Thời gian
                    </div>
                    <div class="col1">
                        Số S.P
                    </div>
                    <div class="col3">
                        Trạng thái
                    </div>
                    <div class="col2">
                        Giá tiền
                    </div>
                    <div class="col1">
                        
                    </div>
                </div>
                <? foreach ($this->_aVars['aHistory']['data'] as $sKey => $aVal):?>
                <div class="row30 line-bottom">
                    <div class="col2">
                        #<?= $aVal['code']?>
                    </div>
                    <div class="col3">
                        <?= $aVal['create_time']?>
                    </div>
                    <div class="col1 txt-center">
                        <?= $aVal['total_product']?>
                    </div>
                    <div class="col3">
                        <?= $aVal['status_deliver']?>
                    </div>
                    <div class="col2">
                        <?= Core::getService('core.currency')->formatMoney(array('money' => $aVal['total_amount']))?> đ
                    </div>
                    <div class="col1">
                        
                    </div>
                </div>
                <? endforeach?>
                <? else:?>
                <div class="row30 dialog-empty">
                    Chưa có thông tin.
                </div>
                <? endif?>
            </div>
            <div class="row30 sub-blue-title">
                Lịch sử giao dịch
            </div>
            <div class="row30 mgbt10">
                <? if (isset($this->_aVars['aTransactions']['data']) && !empty($this->_aVars['aTransactions']['data'])):?>
                <div class="row30 line-bottom">
                    <div class="col2">
                        Mã
                    </div>
                    <div class="col2">
                        Thời gian
                    </div>
                    <div class="col2">
                        H.T Thanh toán
                    </div>
                    <div class="col3">
                        Nội dung
                    </div>
                    <div class="col2">
                        Giá tiền
                    </div>
                    <div class="col1">
                        
                    </div>
                </div>
                <? foreach ($this->_aVars['aTransactions']['data'] as $sKey => $aHistory):?>
                <div class="row30 line-bottom">
                    <div class="col2">
                        #T<?= date('m'). $aHistory['id']?>
                    </div>
                    <div class="col2">
                        <?= $aHistory['display_time']?>
                    </div>
                    <div class="col2 txt-center">
                        <? if($aHistory['payment_method'] == 'diem'): ?>
                            Tài khoản DST
                        <? elseif($aHistory['payment_method'] == 'cong-thanh-toan'): ?>
                            Cổng thanh toán
                        <? else: ?>
                            COD
                        <? endif; ?>
                    </div>
                    <div class="col3">
                        <?= $aHistory['note']?>
                    </div>
                    <div class="col2">
                        <span>
                            <? if($aHistory['type'] == 'purchase'): ?>
                                -
                            <? else:?>
                                +
                            <? endif; ?>
                            <?= Core::getService('core.currency')->formatMoney(array( 'money' => $aHistory['value']['tien_mat']))?>
                            đ
                        </span>
                        </br>
                        <span>
                            <? if($aHistory['type'] == 'purchase'): ?>
                                -
                            <? else:?>
                                +
                            <? endif; ?>
                            <?= Core::getService('core.currency')->formatMoney(array( 'money' => $aHistory['value']['tien_xu']))?>
                            xu
                        </span>
                    </div>
                    <div class="col1">
                        
                    </div>
                </div>
                <? endforeach?>
                <? else:?>
                <div class="row30 dialog-empty">
                    Chưa có thông tin.
                </div>
                <? endif?>
            </div>
        </div>
    </section>
</div>
