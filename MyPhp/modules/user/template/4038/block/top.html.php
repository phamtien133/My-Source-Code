<section class="side-bar-hp">
    <div class="side-bar-head">
        <span class="inhead">Top thành viên</span>
    </div>
    <div class="side-bar-content">
        <?php if (isset($this->_aVars['aTopUsers']['data']) && !empty($this->_aVars['aTopUsers']['data'])): ?>
        <?php $iCount = 0;?>
        <? foreach ($this->_aVars['aTopUsers']['data'] as $aTop) :?>
            <?php $iCount ++;?>
            <div class="bl">
                <a href="#" class="bm">
                    <img src="<?= $aTop['user']['profile_image']?>" alt="<?= $aTop['user']['fullname']?>">
                    <span>
                        <?= $iCount?>
                    </span>
                </a>
                <div class="bn">
                    <div class="bo">
                        <?= $aTop['user']['fullname']?>
                    </div>
                    <div class="bp">
                        Điểm uy tín:
                        <span>
                            1/10
                        </span>
                    </div>
                    <div class="bq">
                        Đã mua:
                        <span><?= $aTop['total_buy']?></span>
                        sản phẩm
                    </div>
                    <div class="bs">
                        Đã tạo:
                        <span><?= $aTop['total_cart']?></span>
                        giỏ hàng
                    </div>
                </div>
            </div>
        <? endforeach?>
        <?php else:?>
            <div class="empty-content">
                Chưa có thông tin.
            </div>
        <?php endif?>
    </div>
</section> <!-- side-bar-hp -->