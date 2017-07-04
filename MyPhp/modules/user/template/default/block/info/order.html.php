<section class="list-order">
    <div class="dc">
        Các đơn hàng bạn đã mua
    </div> <!-- dc -->
    <div class="dd">
        <div class="de">
            <div class="df">
                <div class="df1">
                    Ngày
                </div>
                <div class="df2">
                    Số sản phẩm
                </div>
                <div class="df3">
                    Trạng thái
                </div>
                <div class="df4">
                    Giá tiền
                </div>
            </div> <!-- df -->
            <?php if(!empty($this->_aVars['aOrders']['data'])): ?>
            <? foreach($this->_aVars['aOrders']['data'] as $aOrder):?>
            <a href="#" class="df">
                <span class="df1">
                    <?= $aOrder['create_time']?>
                </span>
                <span class="df2">
                    <?= $aOrder['total_product']?>
                </span>
                <span class="df3">
                    <?= $aOrder['status_deliver']?>
                </span>
                <span class="df4">
                    <?= Core::getService('core.currency')->formatMoney(array( 'money' => $aOrder['total_amount']))?>
                    <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                        <u>
                            <?= $this->_aVars['aSettings']['currency']['symbol']?>
                        </u>
                    </sup>
                </span>
                <span class="df5">
                    <span class="fa fa-eye"></span>
                    Xem đơn hàng
                </span>
                <span class="df6">
                    <span class="fa fa-share-square-o"></span>
                    Mua lại
                </span>
            </a> <!-- df -->
            <? endforeach?>
            <?php else:?>
            <div class="df">
                Bạn chưa thực hiện đơn hàng nào.
            </div>
            <?php endif?>
        </div> <!-- de -->
    </div> <!-- dd -->
</section> <!-- list-order -->