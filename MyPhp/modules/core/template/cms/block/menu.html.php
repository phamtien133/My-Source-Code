<?php $oSession = Core::getLib('session');?>
<div class="snap-drawers unselectable">
    <div class="snap-drawer snap-drawer-left">
        <section id="left-menu" class="js-scroll">
            <? if($this->_aVars['aSessionUser']['priority_group'] < 0):?>
            <?php if(Core::getParam('core.main_server') != 'cms.'):?>
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Phản hồi</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/response/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách phản hồi</span>
                            </div>
                        </md-button>
                    </div>
                </div>

                <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                <!--Nhà cung cấp-->
                <? if($oSession->getArray('session-permission', 'manage_extend')):?>
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Nhà cung cấp</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/vendor/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_tao-moi')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/vendor/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>
                <? endif?>

                <?
                    $tmp = $oSession->getArray('session-permission', 'edit_category');
                    if(empty($tmp)) $tmp = $oSession->getArray('session-permission', 'create_category');
                ?>
                <? if(!empty($tmp)):?>
                <!--Danh mục đề tài-->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Danh mục</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/category/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_tao-de-tai')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/category/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly-de-tai')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>
                <?
                    $tmp = $oSession->getArray('session-permission', 'edit_article');
                    if(empty($tmp)) $tmp = $oSession->getArray('session-permission', 'create_article');
                ?>
                <? if(!empty($tmp)):?>
                <!-- Sản phẩm -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Sản phẩm</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <? if($oSession->getArray('session-permission', 'create_article')):?>
                        <md-button class="mn1" href="/article/add/?type=product">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_tao-moi')?></span>
                            </div>
                        </md-button>
                        <? endif?>
                        <md-button class="mn1" href="/article/?type=product">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách sản phẩm</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>

                <? if($oSession->getArray('session-permission', 'manage_extend') && ( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace' )):?>
                <!-- Danh sách đơn hàng, giao dịch -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Đơn hàng</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/shop/order/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_xem-dat-hang')?></span>
                            </div>
                        </md-button>
                        <!--<md-button class="mn1" href="/shop/config/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_thiet-lap')?></span>
                            </div>
                        </md-button>-->
                        <md-button class="mn1" href="/shop/setting">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Q.L Giờ đặt hàng</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Cộng đồng</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/community/support/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Góc chia sẻ</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/community/food/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Món ngon</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/community/cart/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Giỏ hàng gợi ý</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>

                <? if($oSession->getArray('session-permission', 'manage_extend')):?>
                <!-- Trích lọc -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span><?= Core::getPhrase('language_trich-loc')?></span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/filter/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_tao-moi')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/filter/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/filter/general/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?=  Core::getPhrase('language_tao-trich-loc-tong')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/filter/general/index/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly-trich-loc-tong')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>

                <!--Quản lý hiển thị-->
                <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                <? if($oSession->getArray('session-permission', 'manage_extend')): ?>
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Quản lý hiển thị</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/display/home/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Trang chủ</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/display/category/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh mục</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/display/vendor/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Siêu thị</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif; ?>
                <? endif; ?>
                <?
                    $tmp = $oSession->getArray('session-permission', 'edit_user');
                    if(empty($tmp)) $tmp = $oSession->getArray('session-permission', 'create_user');
                    if(empty($tmp)) $tmp = $oSession->getArray('session-permission', 'permission_user');
                ?>
                <? if(!empty($tmp)):?>
                <!-- Thành viên, khách hàng -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Thành viên</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/user/index/?type=member">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách thành viên</span>
                            </div>
                        </md-button>
                        <?
                            // check phân quyền nhó, user sẽ có thể có quyền quản lý thành viên nhưng không có quyền quản lý nhóm.
                        ?>
                        <md-button class="mn1" href="/user/group/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Nhóm thành viên</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/user/field/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Custom field</span>
                            </div>
                        </md-button>
                    </div>
                </div>

                <? if($oSession->getArray('session-permission', 'edit_setting')):?>
                <!-- Người dùng -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Quản trị viên</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/user/add/?type=user">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thêm mới</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/user/index/?type=user">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách quản trị</span>
                            </div>
                        </md-button>
                        <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                        <md-button class="mn1" href="/user/confirm">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Duyệt nạp tiền</span>
                            </div>
                        </md-button>
                        <? endif; ?>
                    </div>
                </div>
                <? endif; ?>
                <? endif;?>

                <? if($oSession->getArray('session-permission', 'edit_setting')):?>
                <!-- Quảng cáo -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Quảng cáo</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/advertise/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách chiến dịch</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/advertise/add">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Tạo chiến dịch</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/advertise/unit/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Đơn vị quảng cáo</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/advertise/position/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Vị trí quảng cáo</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/advertise/confirm/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Duyệt quảng cáo</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                <!-- Marketing -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Marketing</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/marketing/add">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Tạo chiến dịch</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/marketing/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách chiến dịch</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/marketing/email/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Danh sách nhóm</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/marketing/smshistory/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Lịch sử gởi sms</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/marketing/emailhistory/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Lịch sử gởi email</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/marketing/setting/sms">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thiết lập</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif; ?>
                <? endif; ?>
                <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                <?php $aStorePermission = $oSession->get('session-store_permission');?>
                <?php if (isset($aStorePermission) && !empty($aStorePermission)):?>
                <!-- Warehouse -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Quản lý kho</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/store/import">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Tạo phiếu nhập</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/store/export">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Tạo phiếu xuất</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/store/diaryentry/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Nhật ký nhập</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/store/diaryexport">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Nhật ký xuất</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/store/inventory">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Báo cáo nhập xuất tồn</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/store/card">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thẻ kho</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <?php endif;?>
                <?php endif;?>
                <!-- Hệ thống -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span><?= Core::getPhrase('language_he-thong')?></span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <!--<md-button class="mn1" href="#">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Khuyến mại</span>
                            </div>
                        </md-button>-->
                        <md-button class="mn1" href="/article/?type=news">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Tin tức</span>
                            </div>
                        </md-button>
                        <!--<md-button class="mn1" href="#">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Quảng cáo</span>
                            </div>
                        </md-button>-->
                        <md-button class="mn1" href="/system/log/admin/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Log system</span>
                            </div>
                        </md-button>
                        <!--<md-button class="mn1" href="/system/template/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thiết lập hiển thị</span>
                            </div>
                        </md-button>-->
                        <!--<md-button class="mn1" href="#">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thiết lập Phí DV</span>
                            </div>
                        </md-button>-->
                       <!-- <md-button class="mn1" href="/discount/index/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thiết lập khuyến mại HD</span>
                            </div>
                        </md-button>-->

                        <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                        <md-button class="mn1" href="/discount/index/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Quản lý Voucher</span>
                            </div>
                        </md-button>
                        <?php endif?>
                        <?php if( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'):?>
                        <md-button class="mn1" href="/unit/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Quản lý đơn vị tính</span>
                            </div>
                        </md-button>
                        <?php endif?>
                        <md-button class="mn1" href="/area/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Quản lý khu vực</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/system/language/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_ngon-ngu')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/system/log/access/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_thong-ke')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/system/config/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_thiet-lap')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>

                <? if($oSession->getArray('session-permission', 'manage_extend')):?>
                <? if($oSession->getArray('session-permission', 'manage_extend') && ( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace' ) ):?>
                <!-- Thống kê -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Thống kê</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/report/?key=san_pham&w=view">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Sản phẩm được xem</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/report/?key=san_pham&w=buy">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Sản phẩm được mua</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/report/?key=san_pham&w=buy_sku">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>SKU được mua</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/report/cat/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Đề tài được xem</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>
                <? endif?>

                <?
                    $tmp = $oSession->getArray('session-permission', 'create_menu');
                    if(empty($tmp)) $tmp = $oSession->getArray('session-permission', 'edit_menu');
                ?>
                <? if(!empty($tmp)):?>
                <!-- Menu -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span><?= Core::getPhrase('language_menu')?></span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/menu/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_them-menu')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/menu/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly-menu')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>

                <? if($oSession->getArray('session-permission', 'online_support') && ( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace' )):?>
                <!-- Hỗ trợ trực tuyến -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span><?= Core::getPhrase('language_ho-tro-truc-tuyen')?></span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/support/index/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_vao-trang-ho-tro')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/support/config/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_thiet-lap')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>
                <? if($oSession->getArray('session-permission', 'manage_extend')):?>
                <!-- Thẻ nội dung -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Thẻ nội dung</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/tab/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_tao-moi')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/tab/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>

                <? if($oSession->getArray('session-permission', 'manage_extend')):?>
                <!-- Hình ảnh -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Nội dụng mở rộng</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/imageextend/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_tao-moi')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/imageextend/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/imageextend/general/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Mở rộng tổng</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <? endif?>

                <? if($oSession->getArray('session-permission', 'manage_extend') || $oSession->getArray('session-permission', 'setting_main_slide')):?>
                <!-- Slide -->
                <!--<div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span><?= Core::getPhrase('language_slide')?></span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/slide/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_them-slide')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/slide/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_quan-ly-slide')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>-->
                <? endif?>

                <!-- Newsletter -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Newsletter</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/newsletter/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_them-email')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/newsletter/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_danh-sach')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/newsletter/group/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_nhom')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <!-- Chuyển trang -->
                <!--<div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span><?= Core::getPhrase('language_chuyen-trang')?></span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/system/redirect/add/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_them')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/system/redirect/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_thiet-lap')?></span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/system/redirect/log/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span><?= Core::getPhrase('language_thong-ke')?></span>
                            </div>
                        </md-button>
                    </div>
                </div>-->

                <? if($oSession->getArray('session-permission', 'manage_extend') && ( Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace' ) ):?>
                <!-- App Giao vận -->
                <div class="mn1">
                    <md-button class="mn11 js-open-menu">
                        <div class="mn2 fa fa-list"></div>
                        <div class="mn3">
                            <span>Quản lý Giao Vận</span>
                            <span class="mn6 fa fa-angle-down"></span>
                        </div>
                    </md-button>
                    <div class="mn4">
                        <md-button class="mn1" href="/app/activity/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Hoạt động</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/order/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Vận đơn</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/shopper/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Người mua hộ</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/shipper/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Người vận chuyển</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/permission/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Quản lý đối tác</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/assign/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Manual Assign</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/payment/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Yêu cầu thanh toán</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/setting/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Thiết lập</span>
                            </div>
                        </md-button>
                        <md-button class="mn1" href="/app/report/shopper/">
                            <div class="mn2 fa fa-angle-right"></div>
                            <div class="mn3">
                                <span>Báo cáo</span>
                            </div>
                        </md-button>
                    </div>
                </div>
                <?endif?>
                <!-- Slide -->
            <?php elseif (Core::getParam('core.main_server') == 'sup.'):?>
            <!--<div class="mn1">
                <md-button class="mn11 js-open-menu">
                    <div class="mn2 fa fa-list"></div>
                    <div class="mn3">
                        <span>Phản hồi</span>
                        <span class="mn6 fa fa-angle-down"></span>
                    </div>
                </md-button>
                <div class="mn4">
                    <md-button class="mn1" href="/response/index/">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Danh sách phản hồi</span>
                        </div>
                    </md-button>
                </div>
            </div>-->
            <div class="mn1">
                <md-button class="mn11 js-open-menu">
                    <div class="mn2 fa fa-list"></div>
                    <div class="mn3">
                        <span>Đơn hàng</span>
                        <span class="mn6 fa fa-angle-down"></span>
                    </div>
                </md-button>
                <div class="mn4">
                    <md-button class="mn1" href="/shop/order/">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Danh sách đơn hàng</span>
                        </div>
                    </md-button>
                </div>
            </div>
            <div class="mn1">
                <md-button class="mn11 js-open-menu">
                    <div class="mn2 fa fa-list"></div>
                    <div class="mn3">
                        <span>Sản phẩm</span>
                        <span class="mn6 fa fa-angle-down"></span>
                    </div>
                </md-button>
                <div class="mn4">
                    <md-button class="mn1" href="/article/?type=product">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Danh sách sản phẩm</span>
                        </div>
                    </md-button>
                    <md-button class="mn1" href="/article/add/?type=product">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Bổ sung sản phẩm mới</span>
                        </div>
                    </md-button>
                </div>
            </div>
            <div class="mn1">
                <md-button class="mn11 js-open-menu">
                    <div class="mn2 fa fa-list"></div>
                    <div class="mn3">
                        <span>Người dùng</span>
                        <span class="mn6 fa fa-angle-down"></span>
                    </div>
                </md-button>
                <div class="mn4">
                    <md-button class="mn1" href="/user/index/?type=user">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Danh sách người dùng</span>
                        </div>
                    </md-button>
                </div>
            </div>
            <!-- Quảng cáo -->
            <!--<div class="mn1">
                <md-button class="mn11 js-open-menu">
                    <div class="mn2 fa fa-list"></div>
                    <div class="mn3">
                        <span>Quảng cáo</span>
                        <span class="mn6 fa fa-angle-down"></span>
                    </div>
                </md-button>
                <div class="mn4">
                    <md-button class="mn1" href="/ads/">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Danh sách quảng cáo</span>
                        </div>
                    </md-button>
                    <md-button class="mn1" href="/ads/add">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Thêm quảng cáo</span>
                        </div>
                    </md-button>
                </div>
            </div>-->
            <div class="mn1">
                <md-button class="mn11 js-open-menu">
                    <div class="mn2 fa fa-list"></div>
                    <div class="mn3">
                        <span>Quản lý Giao Vận</span>
                        <span class="mn6 fa fa-angle-down"></span>
                    </div>
                </md-button>
                <div class="mn4">
                    <md-button class="mn1" href="/app/permission/">
                        <div class="mn2 fa fa-angle-right"></div>
                        <div class="mn3">
                            <span>Quản lý đối tác</span>
                        </div>
                    </md-button>
                </div>
            </div>
            <?php endif?>
            <?php endif?>
        </section>
        <div class="js-toggle-menu <? if($_COOKIE['toggleMenu'] == 0):?> fa-angle-double-right<? endif;?> <? if($_COOKIE['toggleMenu'] == 1):?> fa-angle-double-left<? endif;?> toggle-menu fa "></div>
    </div>
    <div class="snap-drawer snap-drawer-right"></div>
</div>