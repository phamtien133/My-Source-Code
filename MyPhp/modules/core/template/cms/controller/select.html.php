<div class="container order unselectable"  ng-controller="order-ctrl">
    <div class="scrollbody bigwrap ">
        <section class="panel panel-default" style="margin-bottom:8px">
        <div class="title">
            <div class="cl"> Chọn siêu thị </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <div class="alert alert-block alert-danger fade in none"></div>
            <div class="alert alert-success fade in none"></div>
            <? if (!count($this->_aVars['aVendorLists'])): ?>
            <div class="alert alert-danger">
                Bạn không có quyền truy cập.
            </div>
            <? else: ?>
            <div class="alert dialog-warn">
                <?= $this->_aVars['sMessage'] ?>
            </div>
            <div class="form-group">
                <div class="col-md-2 txt">
                    Siêu thị:
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="js-select-vendor">
                        <? foreach ($this->_aVars['aVendorLists'] as $aVendor): ?>
                        <option value="<?= $aVendor['id']; ?>"><?= $aVendor['name']; ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"></div>
                <div class="clear"></div>
            </div>
            <div class="form-group">
                <div class="col-md-2">
                </div>
                <div class="col-md-2">
                    <button id="js-select-vendor-bt" class="btn btn-success gr" type="submit">Xác nhận</button>
                </div>
                <div class="col-md-8"></div>
                <div class="clear"></div>
            </div>
            <?endif;?>
        </div>
    </section>
    </div>
</div>