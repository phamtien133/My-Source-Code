<section class="wrap comunity-page">
    <div class="ctnr">
        <section class="cbo-brc">
            <div class="brc-lst">
                <a class="next" href="#" title="<?= Core::getPhrase('language_trang-chu') ?>">
                    <?= Core::getPhrase('language_trang-chu') ?>
                </a>
                <?
                  $iTotal = count($this->_aVars['aCategory']['breadcrumb']);
                  $sKey = -1;
                  $iCnt = 0;
                ?>
                <? foreach($this->_aVars['aCategory']['breadcrumb'] as $sKeyTmp => $aVal):?>
                    <?
                        $iCnt++;
                        if ($iCnt == $iTotal) {
                            $sKey = $sKeyTmp;
                        }
                        else {
                    ?>
                    <a class="next" href="<?= $aVal['path']?>" title="<?= $aVal['name'] ?>">
                        <?= $aVal['name'] ?>
                    </a>
                    <? if(isset($this->_aVars['aCategory']['parent_list_value'][$aVal['parent_id']])):?>
                    <ul style="display: none;">
                    <? foreach($this->_aVars['aCategory']['parent_list_value'][$aVal['parent_id']] as $aValue):?>
                        <? if ($aValue['type'] != $this->_aVars['aCategory']['type']) continue?>
                        <li><a href="<?= $aValue['detail_path']?>"><?= $aValue['name']?></a></li>
                    <? endforeach?>
                    </ul>
                    <? endif?>
                    <? }?>
                <? endforeach?>
            </div>
            <? if ($sKey != -1):?>
            <? $aTmp = $this->_aVars['aCategory']['breadcrumb'][$sKey];?>
            <a class="atc-nm" href="<?= $aTmp['path']?>" title="<?= $aTmp['name'] ?>">
                <?= $aTmp['name'] ?>
            </a>
            <? if(isset($this->_aVars['aCategory']['parent_list_value'][$aTmp['parent_id']])):?>
            <ul style="display: none;">
            <? foreach($this->_aVars['aCategory']['parent_list_value'][$aTmp['parent_id']] as $aValue):?>
                <? if ($aValue['type'] != $this->_aVars['aCategory']['type']) continue?>
                <li><a href="<?= $aValue['detail_path']?>"><?= $aValue['name']?></a></li>
            <? endforeach?>
            </ul>
            <? endif?>
            <? endif?>
        </section> <!-- cbo-brc -->
        <section class="top-comunity">
            <section class="menu-comunity">
                <!-- menu level 1 -->
                <? if(isset($this->_aVars['aCategory']['parent_list_value'][$this->_aVars['iCategoryId']])):?>
                <? foreach ($this->_aVars['aCategory']['parent_list_value'][$this->_aVars['iCategoryId']] as $sKey => $aVals): ?>
                <a href="<?= $aVals['detail_path']?>" class="k1<? if ($sKey == $this->_aVars['aCategory']['breadcrumb'][1]['id']):?> atv<? endif?>">
                    <span class="fa fa-angle-right"></span>
                    <?= $aVals['name']?>
                </a>
                <? endforeach?>
                <? endif?>
            </section>
            <section class="lasttest-comnunity">
                <div class="k2">
                    <div class="k3">
                        Cộng đồng tư vấn
                    </div>
                    <div class="k4">
                        Hiệu quả tiết kiệm
                    </div>
                    <div class="k5" onclick="return clickTest();">
                        Đặt câu hỏi nhanh
                    </div>
                </div>
                <div class="k6 is-tab">
                    <div class="k7">
                        <div class="k8 is-nav-tab atv" data-id="k71">
                            Mới nhất
                        </div>
                        <div class="k8 is-nav-tab" data-id="k72">
                            Vote nhiều nhất
                        </div>
                        <div class="k8 is-nav-tab" data-id="k73">
                            Tư vấn viên tốt nhất
                        </div>
                        <div class="k8 is-nav-tab" data-id="k74">
                            Trả lời nhiều nhất
                        </div>
                    </div>
                    <div class="k9" id="content_top">
                    
                        
                    </div>
                </div>
            </section>
            <?php Core::getBlock('core.activity.buy');?>
        </section> <!-- top-comunity -->
        <section class="bottom-comunity">
            <div class="kh">
                <section class="new-cart">
                    <div class="kj">
                        Giỏ hàng mới nhất
                    </div>
                    <div class="kk">
                        <div class="kl">
                            <a href="" class="km">
                                <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/k1.jpg" alt="Hình ảnh">
                            </a>
                            <a href="" class="kn">
                                Giỏ tào Fuji Hàn QUốc Nam
                            </a>
                            <div class="ko">
                                <div class="kp">
                                    124.000
                                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                        <u>
                                            đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                                        </u>
                                    </sup>
                                </div>
                                <div class="kq">
                                    124.000
                                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                        <u>
                                            đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                                        </u>
                                    </sup>
                                </div>
                            </div>
                            <div class="kr">
                                <div class="ks">
                                    <span class="fa fa-heart"></span>
                                    <span class="kt"></span>
                                    lượt thích
                                </div>
                                <div class="ks">
                                    <span class="fa fa-user"></span>
                                    Nguyễn Văn Nam
                                </div>
                            </div>
                        </div>
                        <div class="kv is-iosld-vtl">
                            <div class="kw sld">
                                <div class="kx">
                                    <? for($i=1; $i<3; $i++):?>
                                    <a href="" class="kx1">
                                        <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/k<?= $i?>.jpg" alt="Hình ảnh">
                                    </a>
                                    <? endfor?>
                                    <? for($i=1; $i<3; $i++):?>
                                    <a href="" class="kx1">
                                        <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/k<?= $i?>.jpg" alt="Hình ảnh">
                                    </a>
                                    <? endfor?>
                                </div>
                            </div>
                            <div class="nav-sld">
                                <div class="prv-sld fa fa-angle-up"></div>
                                <div class="nxt-sld fa fa-angle-down"></div>
                            </div>
                        </div>
                        <div class="l1">
                            <span class="fa fa-angle-double-down"></span>
                            Xem thêm sản phẩm
                        </div>
                    </div>
                </section>
                <section class="ptoduct-sugest is-tab">
                    <div class="l2">
                        <div class="l3 atv is-nav-tab" data-id="l31">
                            Sản phẩm được gợi ý
                        </div>
                        <div class="l3 is-nav-tab" data-id="l32">
                            Sản phẩm do bạn của bạn gợi ý
                        </div>
                    </div>
                    <div class="l4">
                        <div class="l5 is-iosld is-cnt-tab" data-id="l31">
                            <div class="l51 sld">
                                <div class="cp">
                                    <? for ($i=0; $i < 5; $i++):?>
                                        <div class="cq">
                                            <a href="#" class="cr">
                                                <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/s<?= $i?>.jpg" alt="banner">
                                            </a>
                                            <div class="cs">
                                                <a href="#" class="ct">
                                                    Táo Fuji Trung Quốc
                                                </a>
                                                <div class="cu">
                                                    500Gr
                                                </div>
                                                <div class="cv">
                                                    125.000
                                                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                                        <u>
                                                            đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                                                        </u>
                                                    </sup>
                                                </div>
                                                <div class="cw">
                                                    125.000
                                                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                                        <u>
                                                            đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                                                        </u>
                                                    </sup>
                                                </div>
                                            </div>
                                        </div>
                                    <? endfor?>
                                </div>
                            </div>
                        </div>
                        <div class="l5 is-iosld is-cnt-tab none" data-id="l32">
                            <div class="l51 sld">
                                <div class="cp">
                                    <? for ($i=0; $i < 5; $i++):?>
                                        <div class="cq">
                                            <a href="#" class="cr">
                                                <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/s<?= $i?>.jpg" alt="banner">
                                            </a>
                                            <div class="cs">
                                                <a href="#" class="ct">
                                                    Táo Fuji Trung Quốc
                                                </a>
                                                <div class="cu">
                                                    500Gr
                                                </div>
                                                <div class="cv">
                                                    125.000
                                                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                                        <u>
                                                            đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                                                        </u>
                                                    </sup>
                                                </div>
                                                <div class="cw">
                                                    125.000
                                                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                                        <u>
                                                            đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                                                        </u>
                                                    </sup>
                                                </div>
                                            </div>
                                        </div>
                                    <? endfor?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="l1">
                        <span class="fa fa-angle-double-down"></span>
                        Xem thêm sản phẩm
                    </div>
                </section>
            </div>
            <div class="ki">
                <section class="lastest-cart">
                    <div class="l6">
                        Giỏ hàng mới
                    </div>
                    <div class="l7 is-mn">
                        <? for($i=1; $i<=15; $i++):?>
                        <div class="l8">
                            <a href="" class="l9">
                                <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/mini-ava.jpg" alt="Hình ảnh">
                            </a>
                            <div class="la">
                                <a href="" class="lb">
                                    Nguyễn Văn Nam
                                </a>
                                <span class="lc">
                                    2 giờ trước
                                </span>
                                <div class="ld">
                                    Vừa tạo giỏ hàng
                                    <span class="le">
                                        Thực phẩm trong tuần
                                    </span>
                                    tại
                                    <span class="lf">
                                        BigGreen
                                    </span>
                                    trị giá
                                    <span class="lg">
                                        300.000
                                        <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                            <u>
                                                <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                            </u>
                                        </sup>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <? endfor?>
                    </div>
                    <div class="lh">
                        <span class="li">
                            Cộng đồng gợi ý
                        </span>
                    </div>
                    <div class="lj is-mn">
                        <? for($i=1; $i<=15; $i++):?>
                        <div class="l8">
                            <a href="" class="l9">
                                <img src="//img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/mini-ava.jpg" alt="Hình ảnh">
                            </a>
                            <div class="la">
                                <a href="" class="lb">
                                    Nguyễn Văn Nam
                                </a>
                                <span class="lc">
                                    2 giờ trước
                                </span>
                                <div class="lk">
                                    <a href="" class="lm">
                                        BigGreen
                                    </a>
                                    đang khuyến mãi cà chua cả nhà ơi
                                </div>
                            </div>
                        </div>
                        <? endfor?>
                    </div>
                </section>
            </div>
        </section>
    </div>
</section>