<section class="container">
    <div class="panel-box">
        <section class="grid-block">
            <div class="grid-box grid-h">
                <div class="module mod-box">
                    <div class="content-box panel-shadow">
                        <h3 class="box-title"><?= $this->_aVars['aPage']['title']?></h3>
                        <div class="box-inner">
                            <? if(!empty($this->_aVars['sError'])):?>
                                <div class="row30 mgbt20 dialog-err">
                                    <?= $this->_aVars['sError']?>
                                </div>
                            <? endif?>
                            <? if(!empty($this->_aVars['sNotice'])):?>
                                <div class="row30 mgbt20 dialog-success">
                                    <?= $this->_aVars['sNotice']?>
                                </div>
                            <? endif?>
                            <form action="#" method="post" name="frm_create" id="frm_add" class="box style width100" onsubmit="return sbm_frm()">
                                <input type="hidden" name="val[id]" value="<?= $this->_aVars['aData']['data']['id']?>">
                                <div class="row30 line-bottom padbot10 mgbt10">
                                    <div class="col3">
                                        <label for="ten" class="sub-black-title">
                                            Nhà cung cấp:
                                        </label>
                                    </div>
                                    <div class="col9">
                                        <input type="hidden" name="val[vendor_id]" value="<?= $this->_aVars['aData']['data']['vendor_id']?>" id="js-vendor-id">
                                        <input type="text" name="val[vendor_name]" value="<?= $this->_aVars['aData']['data']['vendor_name']?>" class="inputbox text-input default-input disable-pointer"/>
                                    </div>
                                </div>
                                <div class="row30 line-bottom padbot10 mgbt10">
                                    <div class="col3">
                                        <label for="ten" class="sub-black-title">
                                            Tên kho hàng:
                                        </label>
                                    </div>
                                    <div class="col9">
                                        <input type="text" name="val[name]" value="<?= $this->_aVars['aData']['data']['name']?>" class="inputbox text-input default-input " id="js-name-store" />
                                    </div>
                                </div>
                                <div class="row30 padbot10 mgbt10 info-address-map-store">
                                    <div class="row30">
                                        <label for="ten" class="sub-black-title">
                                            Địa điểm:
                                        </label>
                                    </div>
                                    <input type="text" class="search-address" name="val[address]" value="<?= $this->_aVars['aData']['data']['address']?>" id="pac-input" placeholder="Nhập địa chỉ kho hàng">
                                    <div class="row30">
                                        <div class="col9">
                                            <div id="js-map-vendor-store" class="map-vendor-store"></div>
                                        </div>
                                        <div class="col3">
                                            <div class="row30 line-bottom padleft20">
                                                Lưu ý:
                                            </div>
                                            <div class="row30 padleft20">
                                                <label for="ten" class="sub-blue-title">
                                                    Vị trí được chọn:
                                                </label>
                                            </div>
                                            <div class="row30 padleft20">
                                                Latitude:
                                            </div>
                                            <div class="row30 padleft20">
                                                <input class="disable-pointer default-input" type="text" name="val[lat]" value="<?= $this->_aVars['aData']['data']['lat']?>" id="js-lat-location">
                                            </div>
                                            <div class="row30 padleft20">
                                                Longtitude:
                                            </div>
                                            <div class="row30 padleft20">
                                                <input class="disable-pointer default-input" type="text" name="val[lng]" value="<?= $this->_aVars['aData']['data']['lng']?>" id="js-lng-location">
                                            </div>
                                        </div>
                                    </div>
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
                                        <div class="button-blue" onclick="window.location = '<?= $this->_aVars['sBackLink']?>'">
                                            <?= Core::getPhrase('language_quan-ly')?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
<script src="http://maps.googleapis.com/maps/api/js?key=<?= $this->_aVars['sApiKey']?>&language=vi&libraries=places"></script>