<?php if(!count($this->_aVars['aArticleVendor'])): ?>
    <section class="ifo-mrt is-wait">
        <a class="mrt-head">
        </a>
        <div class="mrt-ft">
        </div>
        <div class="ce">
            Vui lòng chọn Siêu thị
        </div>
    </section>
<?php else: ?>
<section class="ifo-mrt">
    <a class="mrt-head">
        <img src="<?= $this->_aVars['aArticleVendor']['image_path']?>" alt="<?= $this->_aVars['aArticleVendor']['title']?>">
        <span class="v1">
            <?= $this->_aVars['aArticleVendor']['name'] ?>
        </span>
        <span class="rating is-rating">
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>★</span>
            <span>★</span>
        </span>
    </a>
    <div class="mrt-dtl">
        <div class="rw-mrt">
            <span class="rw-mrt-tl">
                Số siêu thị:
            </span>
            <span class="rw-mrt-ct">
                <?= $this->_aVars['aArticleVendor']['total_child'] ?>
            </span>
            <div class="fa fa-question-circle q-ifo"></div>
        </div>
        <div class="rw-mrt">
            <span class="rw-mrt-tl">
                Số lượt mua:
            </span>
            <span class="rw-mrt-ct">
                <?= $this->_aVars['aArticleVendor']['total_buy'] ?>
            </span>
            <div class="fa fa-question-circle q-ifo"></div>
        </div>
        <div class="rw-mrt">
            <span class="rw-mrt-tl">
                Số người mua:
            </span>
            <span class="rw-mrt-ct">
                <?= $this->_aVars['aArticleVendor']['total_customer'] ?>
            </span>
            <div class="fa fa-question-circle q-ifo"></div>
        </div>
        <div class="rw-mrt">
            <span class="rw-mrt-tl">
                Số sản phẩm:
            </span>
            <span class="rw-mrt-ct">
                <?= $this->_aVars['aArticleVendor']['total_product'] ?>
            </span>
            <div class="fa fa-question-circle q-ifo"></div>
        </div>
        <div class="rw-mrt">
            <span class="rw-mrt-tl">
                Số giỏ hàng:
            </span>
            <span class="rw-mrt-ct">
                <?= $this->_aVars['aArticleVendor']['total_cart'] ?>
            </span>
            <div class="fa fa-question-circle q-ifo"></div>
        </div>
    </div> <!-- mrt-dtl -->
    <div class="mrt-ft">
        <div class="ft-dt">
            Hotline: <?= $this->_aVars['aArticleVendor']['hotline'] ?>
        </div>
        <div class="rw-btn-ft">
            <div class="btn-mrtft">
                <span class="fa fa-heart"></span>
                Yêu thích
            </div>
            <div class="btn-mrtft">
                <span class="fa fa-sign-out"></span>
                <a href="<?= Core::getParam('core.path'). 'vendor' . $this->_aVars['aArticleVendor']['detail_path']?>" title="Xem thêm">Xem thêm</a>
            </div>
        </div>
    </div> <!-- mrt-ft -->
</section> <!-- ifo-mrt -->
<section class="cat-mrt is-list-cat">
    <div class="w1">
        <span>
            Danh mục của siêu thị
        </span>
    </div>
    <div class="w2">
        <?=
            Core::getService('core.tools')->showMultiMenu(array(
                'parent_id' => -1,
                'menu' => $this->_aVars['aMenus']['main-menu']['value'],
                'result' => '',
                'sep' => '',
                'degree' => 0,
                'param' => array(
                    '0' => array(
                        'open'      => '{sep}<div id="menu-{v[0]}" class="w3"><a href="{v[4]}">{v[1]}</a>',
                        'close'     => '</div>',
                        'opendad'   => '{sep}<div id="menu-{v[0]}" class="w3"><a href="{v[4]}">{v[1]}</a><div class="w4">',
                        'closedad'  => '</div></div>',
                    ),
                    '1' => array(
                        'open' => '{sep}<a href="{v[4]}">{v[1]}',
                        'close' => '</a>',
                    )
                )
            ));
        ?>
        <!-- <div class="w3 <? if($i==1) echo 'atv'?>">
            <a href="/1">
                Danh mục cấp 
            </a>
            <div class="w4">
                <a href="/2" class="atv">
                    Danh mục cấp 1
                </a>
                <a href="/2">
                    Danh mục cấp 2
                </a>
                <a href="#">
                    Danh mục cấp 3
                </a>
                <a href="#">
                    Danh mục cấp 4
                </a>
                <a href="#">
                    Danh mục cấp 5
                </a>
                <a href="#">
                    Danh mục cấp 6
                </a>
                <a href="#">
                    Danh mục cấp 7
                </a>
            </div>  w4
        </div> w3 -->
    </div> <!-- w2 -->
</section> <!-- cat-mrt -->
<?php endif; ?>
