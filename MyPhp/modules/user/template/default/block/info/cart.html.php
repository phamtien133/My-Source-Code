<section class="my-cart">
    <div class="g1">
        <span>
            Giỏ hàng của tôi
        </span>
    </div>
    <div class="g2">
        <div class="g3">
            <? for($i = 1; $i < 4; $i++ ):?>
            <div class="g4">
                <a href="#" class="g5">
                    <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/s3.jpg" alt="">
                    <span class="g6">
                        <span>-10%</span>
                    </span>
                </a> <!-- g5 -->
                <div class="g7">
                    <a class="g8" href="#">
                        Gà ta xông khói
                    </a>
                    <div class="g9">
                        125.000
                        <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                            <u>
                                đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                            </u>
                        </sup>
                    </div>
                </div> <!-- g7 -->
            </div> <!-- g4 -->
            <? endfor?>
        </div> <!-- g3 -->
        <div class="ga">
            Có 
            <span>
                2 sản phẩm
            </span>
            trong giỏ hàng chưa thanh toán.
        </div> <!-- ga -->
        <div class="gb">
            Tổng giỏ hàng
            <span>
                235.000
                <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                    <u>
                        đ<?= $this->_aVars['aSettings']['currency']['symbol']?>
                    </u>
                </sup>
            </span>
        </div> <!-- gb -->
        <a href="#" class="gc">
            Thanh toán ngay
            <span class="fa fa-check"></span>
        </a> <!-- gc -->
    </div>
</section>