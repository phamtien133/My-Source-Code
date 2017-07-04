<section class="cbo-mk">
    <div class="cbo-mk-head">
        <div class="mk-nm">
            <?= $this->_aVars['sDefault']; ?>
        </div>
        <div class="mk-txt-sl">
            Chọn siêu thị
        </div>
        <div class="mk-ico fa fa-caret-down"></div>
    </div> <!-- cbo-mk-head -->
    <div class="pnl-mk">
        <div class="cl fa fa-close"></div>
        <div class="in-pnl-mk">
            <div class="pnl-mk-head">
                Hàng nghìn sản phẩm, thực phẩm tươi ngon
            </div>
            <div class="pnl-mk-des">
                Giúp bạn mua sắm thuận tiện hơn, đơn giản hơn, tiết kiệm thời gian hơn.
                <br>
                Giao hàng nội thành miễn phí
            </div> <!-- pnl-mk-des -->
            <form action="" class="frm-sl-mk" name="frm-sl-mk">
                <input class="ip-sl-mk none" type="text">
                <div class="cbo-sl-mk">
                    <div class="sl-mk-head">
                        <span class="vl">
                            Chọn Siêu thị
                        </span>
                        <span class="fa fa-caret-down"></span>
                    </div>
                    <div class="sl-mk-list is-mn">
                        <div class="sl-mk-itm" data-mk="0">
                            Tất Cả
                        </div>
                        <?php foreach($this->_aVars['aVendors'] as $aVendor): ?>
                            <div class="sl-mk-itm" data-mk="<?= $aVendor['id']?>">
                                <?= $aVendor['name']?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div> <!-- cbo-sl-mk -->
                <div class="sl-mk-btn">
                    Đi nhé
                </div>
            </form>
        </div>
    </div> <!-- pnl-mk -->
</section> <!-- cbo-mk -->
<section class="cbo-srch is-hs-hdl" data-hs="cbo-srh" data-valueEnter="60" data-valueLeave="60" data-typeAttr="top">
    <form action="" class="frm-srch" name="frm-srch">
        <input class=" id-search none" type="hidden" name="vendor">
        <input class="ip-srch" type="text" name="q" placeholder="Tìm kiếm....">
        <button class="smt-srch fa fa-search" type="submit"></button>
    </form>
    <div class="sgst-srch is-hs-obj" data-hs="cbo-srh">
        <!-- content suggest -->
    </div>
</section>