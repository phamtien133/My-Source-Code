<div class="container crm-wrap page-marketing">
    <div class="col12">
        <section class="statistic-bar mgbt20" ng-class="showlist">
            <div class="hb">
                <a class="tab hasab atv" data-link="">
                    <span class="tt or">
                          Tất cả
                    </span>
                    <span class="ab">
                        <?= $this->_aVars['aData']['data']['total']?>
                    </span>
                    <span class="clear"></span>
                </a>
                <div class="clear"></div>
            </div>
        </section>
        <!-- <section class="overview-statistic-bar statistic-bar content-box" style="margin: 0 0 10px;">
          <form method="GET" id="frm" action="#">
            <div class="row20">
                <div class="col2 row30" style="margin-top: 5px">
                    <div class="button-blue" onclick="window.location='/notice/add/';">
                        Tạo thông báo
                    </div>
                </div>
            </div>

            <div class="ctb left mxClrAft">
                <div class="tt left">

                </div>

            </div>
            <div class="ctb left mxClrAft ">

            </div>
            <div class="clear"></div>
          </form>
        </section> -->
        <div class="content-box panel-shadow-hover table-marketing table-marketing-1">
            <div class="box-title pad0 list-ads-position">
                <div class="col-mk-1">
                    Mã số
                </div>
                <div class="col-mk-3">
                    Họ tên
                </div>
                <div class="col-mk-5">
                    Email
                </div>
                <div class="col-mk-5">
                    Số điện thoại
                </div>
                <div class="col-mk-5" style="width: 200px;">
                    Ghi chú
                </div>
                <div class="col-mk-3">
                    Thời gian
                    <span class="<?php if(!isset($sap_xep) ||$sap_xep == 0):?>fa fa-angle-down <?php elseif ($sap_xep == 1):?>fa fa-angle-up <?php endif?> js-icon-sort"></span>
                </div>
                <div class="col-mk-5">
                    Thao tác
                </div>
            </div>
            <div class="box-inner pad0 js-scroll list-pos-inner">
                <? if (isset($this->_aVars['aData']['data']) && !empty($this->_aVars['aData']['data'])):?>
                <? foreach ($this->_aVars['aData']['data']['list'] as $aVals):?>
                    <div class="row30 list-ads-position line-bottom row-mk padtb10" id="tr_object_<?= $aVals['id']?>">
                        <div class="col-mk-1">#<?= $aVals['id']?></div>
                        <div class="col-mk-3" ><?= $aVals['fullname']?></div>
                        <div class="col-mk-5"><?= $aVals['email']?></div>
                        <div class="col-mk-5"><?= $aVals['phone_number']?></div>                        
                        <div class="col-mk-5" style="width: 200px;">
                            <?= $aVals['note']?>
                        </div>
                        <div class="col-mk-3"><?= $aVals['create_time_txt']?></div>
                        <div class="col-mk-5">
                            <span class="fa <?php if($aVals['status'] == 0):?> fa-close <? else:?>  fa-check<?php endif?> icon-wh js-activity-object" data-id="<?= $aVals['id']?>" data-status="<?= $aVals['status']?>" title="<?php if($aVals['status'] == 0):?>Đã liên hệ<? else:?>Chưa liên hệ<?php endif?>"></span>
                            <span class="fa fa-pencil icon-wh js-edit-object" data-id="<?= $aVals['id']?>" onclick="window.location='/contact/edit/?id=<?= $aVals['id']?>';"></span>
                        </div>
                    </div>
                <? endforeach?>
                <?= Core::getService('core.tools')->paginate(ceil($this->_aVars['aData']['data']['total']/$this->_aVars['aData']['data']['page_size']), $this->_aVars['aData']['data']['page'], '/contact/?'.'&page=::PAGE::', '/contact/?', '', '')?>
                <? else:?>
                <div class="row30 list-ads-position line-bottom row-mk padtb10">
                    Chưa có dữ liệu
                </div>
                <? endif?>
            </div>
        </div>
    </div>
</div>
    <!--  <span class="fa fa-pencil icon-wh js-edit-object" data-id="<?= $aVals['id']?>" onclick="window.location='/notice/add/?id=<?= $aVals['id']?>';"></span>  -->
<!--<span class="fa fa-trash icon-wh js-delete-object" data-id="<?= $aVals['id']?>"></span>-->