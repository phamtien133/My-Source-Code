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
                <span class="fa fa-close icon-wh js-cancel-recharge" id="js-close-recharge"></span>
            </div>
        </div>
        <div class="box-inner">
            <div class="row30 mgbt10">
                <form action="#" method="post" id="frm_recharge">
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
                    <div class="col12 padright10">
                        <div class="row20 line-bottom padbot10 mgbt10">
                            <div class="col2">
                                <img src="<?= $this->_aVars['aUser']['profile_image']?>" alt="" class="img-user">
                            </div>
                            <div class="col6">
                                <div class="row20">
                                    Họ tên: <?= $this->_aVars['aUser']['fullname']?>
                                </div>
                                <div class="row20">
                                    Hộp thư : <?= $this->_aVars['aUser']['email']?>
                                </div>
                                <div class="row20">
                                    Số điện thoại: <?= $this->_aVars['aUser']['phone_number']?>
                                </div>
                                <input type="hidden" name="val[id]" value="<?= $this->_aVars['aUser']['id']?>">
                                <input type="hidden" name="val[iAcp]" value="1">
                            </div>
                            <div class="col4">
                                <? foreach ($this->_aVars['aUserPoint'] as $aPoint): ?>
                                <div class="row20">
                                    <? if ($aPoint['name_code'] =='tien_mat'):?>Số tiền trong tài khoản <?else:?> Tài khoản <?= $aPoint['name']?> <? endif;?> : <?= $aPoint['point'].' '.$aPoint['name']?>
                                </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                        <div class="row20 padbot10 mgbt10">
                            <div class="row20 mgbt10">
                                <div class="col2 padleft10">
                                    <label for="money" class="sub-black-title">
                                        Số tiền:
                                    </label>
                                </div>
                                <div class="col4">
                                    <input type="text" id="money" name="val[money]" value="0" class="default-input js-input-number"/>
                                </div>
                                <div class="col2 padleft10">
                                    <label for="coin" class="sub-black-title">
                                        Số Xu:
                                    </label>
                                </div>
                                <div class="col4">
                                    <input type="text" id="coin" name="val[coin]" value="0" class="default-input js-input-number"/>
                                </div>
                            </div>
                            <div class="row20 mgbt10">
                                <div class="col2 padleft10">
                                    <label for="note" class="sub-black-title">
                                        Ghi chú:
                                    </label>
                                </div>
                                <div class="col10">
                                <textarea id="note" name="val[note]" class="default-input"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row20 clear right">
                            <div class="col6"></div>
                            <div class="col3 padright10">
                                <div class="button-default js-cancel-recharge">Hủy</div>    
                            </div>
                            <div class="col3 padleft10">
                                <div class="button-blue col4 js-submit-adsedit" id="js-submit-recharge">Nạp tiền</div>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </section>
</div>