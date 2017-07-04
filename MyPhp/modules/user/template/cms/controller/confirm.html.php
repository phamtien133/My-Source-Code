<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<div class="container crm-wrap page-ads-position">
    <div class="col12">
        <div class="content-box panel-shadow-hover filter-ads-position mgbt20">
            <div class="item-filter-ads">
                Tất cả
                <span><?= $tong_cong?></span>
            </div>
            <!--<div class="item-filter-ads">
                Đang chạy
                <span>10</span>
            </div>
            <div class="item-filter-ads">
                Chờ duyệt
                <span>10</span>
            </div>
            <div class="item-filter-ads">
                Sắp kết thúc
                <span>10</span>
            </div>
            <div class="item-filter-ads">
                Hoàn thành
                <span>10</span>
            </div>-->
            <div class="clear"></div>
        </div>
        <div class="content-box panel-shadow-hover">
            <div class="box-title pad0 list-ads-position">
                <!--<div class="col-ads-1"></div>-->
                <div class="padlr10 col-ads-2 js-sort-by" data-sort=1>
                    Mã số
                    <span class="<?php if(!isset($sap_xep) ||$sap_xep == 0):?>fa fa-angle-down <?php elseif ($sap_xep == 1):?>fa fa-angle-up <?php endif?> js-icon-sort"></span>
                </div>
                <div class="col-ads-3 js-sort-by" data-sort=3>
                    Tên thành viên
                    <span class="<?php if(isset($sap_xep) &&$sap_xep == 2):?>fa fa-angle-down <?php elseif (isset($sap_xep) &&$sap_xep == 3):?>fa fa-angle-up <?php endif?> js-icon-sort"></span>
                </div>
                <div class="col-ads-4 js-sort-by" data-sort=5>
                    Người nạp
                    <span class="<?php if(isset($sap_xep) &&$sap_xep == 4):?>fa fa-angle-down <?php elseif (isset($sap_xep) &&$sap_xep == 5):?>fa fa-angle-up <?php endif?> js-icon-sort"></span>
                </div>
                <div class="col-ads-5">
                    <span>Tổng tiền</span>
                    <span>Siêu xu</span>
                </div>
                <div class="col-ads-4">
                    Ghi chú
                    <span class=""></span>
                </div>
                <div class="col-ads-4">
                    Thời gian tạo
                    <span class=""></span>
                </div>
                <div class="col-ads-6">
                    Thao tác
                    <span class=""></span>
                </div>
            </div>
            <div class="box-inner pad0">
                <? if (count($aData['id']) > 0):?>
                <? for($i=0; $i < count($aData['id']); $i++):?>
                    <div class="row50 list-ads-position line-bottom" id="tr_object_<?= $aData['id'][$i]?>">
                        <div class="padlr10 col-ads-2"><?= $aData['id'][$i]?></div>
                        <div class="col-ads-3"><?= $aData['user_fullname'][$i]?></div>
                        <div class="col-ads-4"><?= $aData['user_recharge_name'][$i]?></div>
                        <div class="col-ads-5">
                            <span><?= Core::getService('core.currency')->formatMoney(array('money' => $aData['total_money'][$i]))?>đ</span>
                            <span><?= Core::getService('core.currency')->formatMoney(array('money' => $aData['total_coin'][$i]))?>xu</span>
                        </div>
                        <div class="col-ads-4"><?= $aData['note'][$i]?></div>
                        <div class="col-ads-4"><?= $aData['create_time'][$i]?></div>
                        <div class="col-ads-6" id="js-action-<?= $aData['id'][$i]?>">
                            <div class="fa fa-check-square js-confirm-recharge" data-id="<?= $aData['id'][$i]?>" title="Duyệt"></div>
                            <div class="fa fa-close js-cancel-recharge" data-id="<?= $aData['id'][$i]?>" title="Hủy"></div>
                        </div>
                    </div>
                <? endfor?>
                <? else:?>
                <div class="row50 list-ads-position line-bottom">
                    Không có đối tượng nào cần duyệt.
                </div>
                <? endif?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var sort_path = '<?= $sLinkSort?>';
function initStatus()
{
    
}
</script>