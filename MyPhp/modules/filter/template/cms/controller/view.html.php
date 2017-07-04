<section class="container product unselectable page-list-vendor">
    <section class="sanpham fthtab">
        <section class="statistic-bar" ng-class="showlist">
            <div class="hb">
              <div class="tab hasab atv">
                <div class="tt or">
                  <?= Core::getPhrase('language_danh-sach')?>
                </div>
                <div class="ab">
                  <?= $this->_aVars['aData']['data']['total']?>
                </div>
                <div class="clear"></div>
                <div class="hv"></div>
              </div>
              <div class="clear"></div>
            </div>
        </section>
        <section class="search-bar content-box mgbt20 ">
            <div class="mgbt20">
                <div class="col2 row30" style="margin-top: 5px">
                    <div class="button-blue js-add-filter-value">
                        Thêm giá trị
                    </div>
                </div>
                <form method="GET" id="frm" action="#">
                    <div class="row20">
                        <div class="col5"></div>
                        <div class="col1">
                            <div class="sub-black-title mgtop10"><?= Core::getPhrase('language_tu-khoa')?> : </div>
                        </div>
                        <div class="col3 padright10">
                            <input type="text" name="q" value="<?= $tu_khoa?>" id="tu_khoa" class="default-input product-srch-input tt tt1" placeholder="Nội dung tìm kiếm" style="margin-top: 5px">
                        </div>
                        <div class="col1 product-srch-bt">
                            <button class="btn gr button-blue" style="margin-top: 5px; height: 30px ! important; line-height: 30px ! important; padding: 0px 10px;border: medium none;">Tìm</button>
                        </div>
                    </div>
                    <div class="clear"></div>
                form>
            </div>
        </section>
        <section class="product-list-box content-box panel-shadow mgbt20" ng-class="showlist">
            <div class="title_list">
                <div class="col1 cl js-sort-by" data-link="<?= $sLinkSort?>" data-sort=1>
                    <div class="tt or">
                    Mã số
                    </div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="col3 cl js-sort-by" data-link="<?= $sLinkSort.'&sap_xep=1'?>" data-sort=3>
                    <div class="tt or">
                    <?= Core::getPhrase('language_ten')?>
                    </div>
                    <div class="<?php if(isset($sap_xep) && $sap_xep == 2):?>ic ic1 <?php elseif (isset($sap_xep) && $sap_xep == 3):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="col3 cl">
                    <div class="tt or">
                    Kế thừa từ
                    </div>
                    <div class="bg"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="col5 cl">
                    <div class="tt or">
                    Thao tác
                    </div>
                    <div class="bg"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="clear"></div>
            </div>
            <? if (isset($this->_aVars['aData']['data']) && !empty($this->_aVars['aData']['data'])):?>
            <? foreach ($this->_aVars['aData']['data']['list'] as $aVals):?>
            <div class="r" id="tr_object_<?=$aList['id']?>">
                <div class="col1 cl atv">
                    <div class="tt or">
                        #<?= $aVals['id']?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="col3 cl">
                    <div class="tt or">
                    <?= $aVals['name']?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="col3 cl">
                    <div class="tt or">
                        <? if (count($aVals['parent_filter_value_list'])): ?>
                            <? foreach ($aVals['parent_filter_value_list'] as $aParentList): ?>

                            <? endforeach;?>
                        <? else: ?>
                            Không có dữ liệu
                        <? endif; ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="col5 cl list-icon">
                    <div class="fleft js-activity mgright10" title="<? if ($aList['status']): ?> Chọn để hủy kích hoạt <? else: ?> Chọn để kích hoạt <? endif;?>" data-id="<?= $aList['id']?>" data-status="<? if ($aList['status']): ?>1<? else: ?>0<? endif;?>">
                        <a href="javascript:void(0);">
                            <span class="fa icon-medium <? if ($aList['status']): ?> fa-eye <? else: ?> fa-eye-slash<? endif;?>"></span>
                        </a>
                    </div>
                    <div class="fleft js-edit mgright10" title="<?= Core::getPhrase('language_sua')?>" data-id="<?= $shop_custom['id'][$i]?>">
                        <a href="javascript:void(0);">
                            <span class="fa icon-medium fa-pencil"></span>
                        </a>
                    </div>
                    <div class="fleft mgright10" >
                        <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $aList['id']?>, 1)">
                            <span class="fa icon-medium fa-chevron-up"></span>
                        </a>
                    </div>
                    <div class="fleft mgright10">
                        <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $aList['id']?>, 0)">
                            <span class="fa icon-medium fa-chevron-down"></span>
                        </a>
                    </div>
                    <div class="fleft mgright10 js-delete" title="<?= Core::getPhrase('language_xoa')?>" data-id="<?= $shop_custom['id'][$i]?>">
                        <a href="javascript:void(0);">
                            <span class="fa icon-medium fa-trash"></span>
                        </a>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <? endforeach;?>
            <? else:?>
                <div class="r">
                    Không tìm thấy trích lọc giá trị nào.
                </div>
            <? endif?>
        </section>
    </section>
</section>