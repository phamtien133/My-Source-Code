<div class="container order unselectable home-list-display" ng-controller="order-ctrl">
    <section class="bfstatistic-bar">
        <div class="tab atv">
            <div class="tt">
                <a href="javascript:void(0);">Danh sách kho hàng</a>
              </div>
        </div>
    </section>
    <div class="scrollbody content-box ">
        <section class="order-list-box mgbt20 panel">
            <div class="title_list r">
                <md-button class="col-md-1 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort']?>" data-sort=1>
                    <div class="tt">STT</div>
                    <div class="<?php if(!isset($sort) ||$sort == 0):?>ic ic1 <?php elseif ($sort == 1):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
                </md-button>
                <md-button class="col-md-2 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort'].'&sort=1'?>" data-sort=3>
                    <div class="tt">Tên kho</div>
                    <div class="<?php if(isset($sort) && $sort == 2):?>ic ic1 <?php elseif (isset($sort) && $sort == 3):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
                </md-button>
                <md-button class="col-md-3 cl">
                    <div class="tt">Địa chỉ</div>
                </md-button>
                <md-button class="col-md-3 cl" >
                    <div class="tt">Ngày tạo</div>
                </md-button>
                <md-button class="col-md-3 cl" >
                    <div class="tt">Thao tác</div>
                </md-button>
                <div class="clear"></div>
            </div>
                <div class="">
                    <? if (count($this->_aVars['aData']['data']['list'])): ?>
                    <input type="hidden" id="js-current-vendor" value="<?= $this->_aVars['aData']['data']['list'][0]['vendor_id']?>">
                    <ul>
                    <? foreach($this->_aVars['aData']['data']['list'] as $aVals): ?>
                    <li class="r js-row-order" id="js-row-obj-<?= $aVals['id']?>">
                        <div class="col1 cl atv">
                            <div class="tt or">
                                <?= $aVals['id'] ?>
                            </div>
                        </div>
                        <div class="col-md-2 cl">
                            <div class="tt">
                                <?= $aVals['name'] ?>
                            </div>
                        </div>
                        <div class="col-md-3 cl">
                            <div class="tt">
                                <?= $aVals['address'] ?>
                            </div>
                        </div>
                        <div class="col-md-3 cl">
                            <div class="tt item-position">
                                <?= $aVals['create_time_txt'] ?>
                            </div>
                        </div>
                        <div class="col-md-3 cl">
                            <div class="tt">
                            <?php if($aVals['status'] == 1):?>
                                <div class="bg icon icon-status-on js-display-item" id="js-status-object-<?= $aVals['id']?>" data-id="<?= $aVals['id']?>" data-status="0">
                                  <div class="sp">
                                    <div class="p">
                                      Chọn để hủy kích hoạt
                                    </div>
                                  </div>
                                </div>
                                <?php else: ?>
                                <div class="bg icon icon-status-off js-display-item" id="js-status-object-<?= $aVals['id']?>" data-id="<?= $aVals['id']?>" data-status="1">
                                  <div class="sp">
                                    <div class="p">
                                      Chọn để kích hoạt
                                    </div>
                                  </div>
                                </div>
                                <?php endif?>
                                <div class="bg icon icon-status-view js-obj-view" data-id="<?= $aVals['id']?>">
                                  <div class="sp">
                                    <div class="p">
                                      Xem chi tiết
                                    </div>
                                  </div>
                                </div>
                                <div class="bg icon icon-status-del js-obj-del" data-id="<?= $aVals['id']?>">
                                  <div class="sp">
                                    <div class="p">
                                      Xóa
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <? endforeach; ?>
                    </ul>
                    <? else: ?>
                        <div class="dialog-empty">
                            Chưa có thông tin kho hàng nào
                        </div>
                    <? endif; ?>
                    <!-- Phân trang -->
                    <?= Core::getService('core.tools')->paginate(ceil($this->_aVars['aData']['data']['total']/$this->_aVars['aData']['data']['page_size']), $this->_aVars['aData']['data']['page'], '/vendor/store/?&vendor_id='.$this->_aVars['aData']['data']['list'][0]['vendor_id'].'&page=::PAGE::', '/vendor/store/?&vendor_id='.$this->_aVars['aData']['data']['list'][0]['vendor_id'], '', '')?>
                </div>
            <div class="r row30">
                <div class="col-md-3 pad10">
                    <div class="button-blue" id="js-add-vendor-store" data-id="<?= $this->_aVars['aData']['data']['list'][0]['vendor_id']; ?>">Thêm kho hàng</div>
                </div>
            </div>
        </section>
    </div>
</div>
<script type="text/javascript">


function initSort()
{
    var sort_path = '<?= $this->_aVars['aData']['data']['link_sort']?>';
    $('.js-sort-by').each(function(){
      
       $(this).unbind('click').click(function(){
        alert('111');
           var sort = $(this).data('sort');
           alert('12323');
           if (typeof(sort) == 'string' && typeof(sort_path) != 'undefined') {
               var tag = $(this).find('div.js-icon-sort');
               var hasSort = tag.hasClass('ic3');
               if (hasSort) {
                   sort = sort - 1;
               }
               sort_link = sort_path +'&sort=' + sort;
               window.location = sort_link;
           }
           return false;
       });
    });
}

function init()
{
    initSort();
}

$(function(){
    init();
});

</script>