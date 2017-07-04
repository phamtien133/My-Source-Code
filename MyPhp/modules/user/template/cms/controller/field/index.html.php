<?php
    $aMapStatusIcon =array(
        0 => 'fa-close',
        1 => 'fa-check'
    );
?>
<section class="container dskh wrap">
    <section class="overview-statistic-bar statistic-bar" style="margin: 0 0 10px;">
        <form method="GET" id="frm" action="#">
            <div class="rb right">
                <div class="ctb left mxClrAft">
                        <div class="tt left">
                            <?= Core::getPhrase('language_tu-khoa')?> : 
                        </div>
                        <input type="text" name="q" value="<?= $tu_khoa?>" id="tu_khoa" class="product-srch-input left tt tt1" placeholder="Nội dung tìm kiếm">
                    </div>
                <div class="ctb left mxClrAft product-srch-bt">
                    <button class="btn gr button-blue">Tìm</button>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </form>
    </section>
    <div class="content-box panel-shadow-hover">
        <div class="box-title pad0 list-ads-position">
            <div class="col-md-1 js-sort-by" data-sort=1>
                ID
            </div>
            <div class="col-md-4">
                Tên
            </div>
            <div class="col-md-2">
                Loại dữ liệu
            </div>
            <div class="col-md-5">
                
            </div>
        </div>
        <div class="box-inner pad0 js-scroll list-pos-inner">
            <?php if (count($this->_aVars['aData']) > 0):?>
            <?php foreach ($this->_aVars['aData'] as $aList):?>
            <div class="row50 line-bottom" id="js-group-<?= $aList['id']?>">
                <div class="col-md-1"><?= $aList['id']?></div>
                <div class="col-md-4">
                    <?= $aList['name'] ?>
                </div>
                <div class="col-md-2">
                    <?= $aList['type_txt'] ?>
                </div>
                <div class="col-md-5 div-action">
                    <div class="fleft">
                        <a href="javascript:void(0);" class="js-group-status" title="" data-status="<?= $aList['status']?>" data-id="<?= $aList['id']?>">
                            <span class="fa <?= $aMapStatusIcon[$aList['status']]?>"></span>
                        </a>
                    </div>
                    <div class="fleft">
                        <a href="/user/field/add/?id=<?= $aList['id']?>" title="Chỉnh sửa">
                            <span class="fa fa-pencil"></span>
                        </a>
                    </div>
                    <div class="fleft">
                        <a href="javascript:void(0);" class="js-delete-group" data-id="<?= $aList['id']?>" title="Xóa nhóm này">
                            <span class="fa fa-trash"></span>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else:?>
            <div class="row50 line-bottom pad10">
                Không có dữ liệu.
            </div>
            <?php endif?>
        </div>
        <?= Core::getService('core.tools')->paginate($this->_aVars['iTotalPage'], $this->_aVars['iPage'], $this->_aVars['sPaginationUrl'].'&page=::PAGE::', $this->_aVars['sPaginationUrl'], '', '')?>
    </div>
    <div class="ri">
        <div class="lst">
            <div class="add bg" id="js-add-new-field" title="Tạo mới"> </div>
        </div>
    </div>
</section>