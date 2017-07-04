<section class="combo-category">
    <div class="cm">
        <a href="#" class="cn">
            Danh sách sản phẩm
        </a>
    </div>
    <div class="cp">
        <? foreach ($this->_aVars['aArticles'] as $aArticle): ?>
            <div class="cq" data-id="<?= $aArticle['id'];?>">
                <a href="//<?= $this->_aVars['sDomainName']. $aArticle['detail_path'] ?>?vendor=<?= $this->_aVars['aVendor']['id']?>" class="cr">
                    <img src="<?= $aArticle['image_path']?>" alt="<?= $aArticle['title']?>">
                </a>
                <div class="cs">
                    <a href="//<?= $this->_aVars['sDomainName']. $aArticle['detail_path'] ?>?vendor=<?= $this->_aVars['aVendor']['id']?>" class="ct">
                        <?= $aArticle['title']?>
                    </a>
                    <!--<div class="cu">
                        500Gr
                    </div>-->
                    <div class="cv">
                        <?= $aArticle['price_sell'] - $aArticle['price_discount']?>
                        <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                            <u>
                                <?= $this->_aVars['aSettings']['currency']['symbol']?>
                            </u>
                        </sup>
                    </div>
                    <div class="cw">
                        <? if($aArticle['price_discount'] != 0): ?>
                        <?= $aArticle['price_sell']?>
                        <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                            <u>
                                <?= $this->_aVars['aSettings']['currency']['symbol']?>
                            </u>
                        </sup>
                        <? endif; ?>
                    </div>
                    <div>
                        <input type="hidden" class="js-sku" value="<?= $aArticle['sku']?>" />
                        <input type="hidden" class="js-price-sell" value="<?= $aArticle['price_sell']?>" />
                        <input type="hidden" class="js-html-name" value="<?= $aArticle['html_name']?>" />
                        <input type="hidden" class="js-image-path" value="<?= $aArticle['image_path']?>" />
                        <input type="hidden" class="js-price-discount" value="<?= $aArticle['price_discount']?>" />
                        <input type="hidden" class="js-quantity" value="1" />
                    </div>
                    <a href="javascript:void(0);" class="b90 js_quick_purchase">
                        <span class="fa fa-shopping-cart"></span>
                        Cho vào giỏ hàng
                    </a>
                </div>
            </div>
        <? endforeach;?>
    </div>
</section>