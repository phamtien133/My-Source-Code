<section class="combo-product is-tab">
    <div class="d1 mxClrAft">
        <div class="d2">
            <div class="d4 is-nav-tab atv" data-id="cp01">
                Sản phẩm đã mua
            </div>
            <div class="d4 is-nav-tab" data-id="cp02">
                Sản phẩm đã xem
            </div>
            <div class="d4 is-nav-tab" data-id="cp03">
                Sản phẩm đã thích
            </div>
        </div> <!-- d2 -->
        <a class="d3">
            Xem tiếp
            <span class="fa fa-caret-right"></span>
        </a>
    </div> <!-- d1 -->
    <div class="d5">
        <div class="d6 is-cnt-tab atv" data-id="cp01">
            <?php if(!empty($this->_aVars['aPurchaseProducts']['data'])):?>
            <?php $iCount = 0;?>
            <?php foreach ($this->_aVars['aPurchaseProducts']['data'] as $aProduct):?>
            <?php $iCount++; if ($iCount > 5 ) { break; } ?>
                <div class="cq">
                    <a href="<?= $aProduct['path'] ?>" class="cr">
                        <img src="<?= $aProduct['image_path'] ?>" alt="<?= $aProduct['name_html'] ?>">
                    </a>
                    <div class="cs">
                        <a href="<?= $aProduct['path'] ?>" class="ct">
                            <?= $aProduct['name'] ?>
                        </a>
                        <!--<div class="cu">
                            500Gr
                        </div>-->
                        <div class="cv">
                            <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['amount'])) ?>
                            <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                <u>
                                    <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                </u>
                            </sup>
                        </div>
                        <div class="cw">
                            <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['price_sell'])) ?>
                            <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                <u>
                                    <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                </u>
                            </sup>
                        </div>
                    </div>
                </div>
            <? endforeach?>
            <?php else:?>
                <div class="cq">
                    Chưa có thông tin sản phẩm nào.
                </div>
            <?php endif?>
        </div> <!-- d6 -->
        <div class="d6 is-cnt-tab none" data-id="cp02">
            <?php if(!empty($this->_aVars['aViewProducts']['data'])):?>
            <?php $iCount = 0;?>
            <?php foreach ($this->_aVars['aViewProducts']['data'] as $aProduct):?>
            <?php $iCount++; if ($iCount > 5 ) { break; } ?>
                <div class="cq">
                    <a href="<?= $aProduct['path'] ?>" class="cr">
                        <img src="<?= $aProduct['image_path'] ?>" alt="<?= $aProduct['name_html'] ?>">
                    </a>
                    <div class="cs">
                        <a href="<?= $aProduct['path'] ?>" class="ct">
                            <?= $aProduct['name'] ?>
                        </a>
                        <!--<div class="cu">
                            500Gr
                        </div>-->
                        <div class="cv">
                            <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['amount'])) ?>
                            <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                <u>
                                    <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                </u>
                            </sup>
                        </div>
                        <div class="cw">
                            <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['price_sell'])) ?>
                            <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                <u>
                                    <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                </u>
                            </sup>
                        </div>
                    </div>
                </div>
            <? endforeach?>
            <?php else:?>
                <div class="cq">
                    Chưa có thông tin sản phẩm nào.
                </div>
            <?php endif?>
        </div> <!-- d6 -->
        <div class="d6 is-cnt-tab none" data-id="cp03">
            <?php if(!empty($this->_aVars['aLikeProducts']['data'])):?>
            <?php $iCount = 0;?>
            <?php foreach ($this->_aVars['aLikeProducts']['data'] as $aProduct):?>
            <?php $iCount++; if ($iCount > 5 ) { break; } ?>
                <div class="cq">
                    <a href="<?= $aProduct['path'] ?>" class="cr">
                        <img src="<?= $aProduct['image_path'] ?>" alt="<?= $aProduct['name_html'] ?>">
                    </a>
                    <div class="cs">
                        <a href="<?= $aProduct['path'] ?>" class="ct">
                            <?= $aProduct['name'] ?>
                        </a>
                        <!--<div class="cu">
                            500Gr
                        </div>-->
                        <div class="cv">
                            <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['amount'])) ?>
                            <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                <u>
                                    <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                </u>
                            </sup>
                        </div>
                        <div class="cw">
                            <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['price_sell'])) ?>
                            <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                <u>
                                    <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                </u>
                            </sup>
                        </div>
                    </div>
                </div>
            <? endforeach?>
            <?php else:?>
                <div class="cq">
                    Chưa có thông tin sản phẩm nào.
                </div>
            <?php endif?>
        </div> <!-- d6 -->
    </div>
</section> <!-- combo-product -->