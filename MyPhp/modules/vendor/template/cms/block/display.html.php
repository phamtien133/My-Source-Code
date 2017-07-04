<section class="cbo-lst-mrt mxClrAft">
    <?php foreach($this->_aVars['aSuppliers'] as $iKey => $aSupplier) :?>
        <?php if($iKey % 9 == 0): ?><div class="rw-lst-mrt mxClrAft"> <?endif;?>
        <a href="<?= $aSupplier['detail_path'];?>" class="itm-lst-mrt" title="<?= $aSupplier['name'] ?>">
            <img class="itm-lst-mrt-acti" src="<?= $aSupplier['image_path']?>" alt="">
            <span class="ab">
                <span>
                    <?= $aSupplier['name']?>
                </span>
                <span>
                     Thành lập năm <?= $aSupplier['display_found_time']?>
                </span>
                <span>
                    Tổng sản phẩm <?= $aSupplier['total_product']?>
                </span>
                <span>
                    
                </span>
            </span>
        </a>
        <?php if($iKey % 9 == 8): ?></div> <?endif;?>
    <?php endforeach; ?>
    <?php if($this->_aVars['bIsViewMore']): ?>
    <a  href="javascript:void(0);" class="itm-lst-mrt">
        Xem tất cả
        <span class="fa fa-caret-right"></span>
    </a>
    <?php endif; ?>
</section> <!-- cbo-lst-mrt -->
<?php unset($this->_aVars['bIsViewMore']); ?>
<?php unset($this->_aVars['aSuppliers']); ?>
<?php unset($aSupplier); ?>