<div class="container overview unselectable" ng-controller="overview-ctrl">
    	<section class="overview-statistic-bar">
    	    <div class="lb left mxClrAft js-tab" data-call-back="">
    	    	<div class="d atv js-nav-tab">
                    1 Tháng
                </div>
    	    </div>
    	    <div class="rb right">
    	    	<div class="ctb left mxClrAft">
    	    	    <div class="tt left">
                        Từ
                    </div>
    	    	    <input type="text" class="js-date-time left tt tt1" id="date-time-from" placeholder="Chọn ngày">
    	    	</div>
    	    	<div class="ctb left mxClrAft">
    	    	    <div class="tt left">
                        Đến
                    </div>
    	    	    <input type="text" class="js-date-time left tt tt2" id="date-time-to" placeholder="Chọn ngày">
    	    	</div>
    	    	<div class="clear"></div>
    	    </div>
    	    <div class="clear"></div>
    	</section>
    	<div class="overview-scrollbody wrap">
            <div>
                <input type="hidden" id="js-chart-sales" value='<?= json_encode($this->_aVars['data']['doanh_so']);?>' />
                <?php if(Core::getParam('core.main_server') == 'cms.'):?>
                <input type="hidden" id="js-chart-access" value='<?= json_encode($this->_aVars['data']['thong_ke_truy_cap']);?>' />
                <?php endif?>
            </div> 
    	    <section class="bigchart js-tab js-chart-overview" ng-controller="ChartCtrl">
    	        <div class="ch">
                    <? if (Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'): ?>
                    <div class="js-cnt-tab ch1 atv" data-id="chart-1">
                        <canvas id="line-ds" class="chart chart-line" data="dataDs" labels="labelsDs">
                        </canvas> 
                    </div>
                    <div class="js-cnt-tab ch1" data-id="chart-2">
                        <canvas id="line-gd" class="chart chart-line" data="dataGd" labels="labelsGd">
                        </canvas> 
                    </div>
                    <? endif; ?>
                    <div class="js-cnt-tab ch1" data-id="chart-3">
                        <canvas id="line-tc" class="chart chart-line" data="dataTc" labels="labelsTc">
                        </canvas> 
                    </div>   
                </div>
    	        <div class="mn">
                <? if (Core::getParam('setting_page_type') == 'shopping' || Core::getParam('setting_page_type') == 'marketplace'): ?>
                 <div class="m atv js-nav-tab" data-id="chart-1">
                    Doanh số
                </div>
                 <div class="m js-nav-tab" data-id="chart-2" ng-click="initChartGd()">
                    Giao dịch
                </div>
                <? endif; ?>
                <?php if(Core::getParam('core.main_server') == 'cms.'):?>
                 <div class="m js-nav-tab" data-id="chart-3" ng-click="initChartTc()">
                    Khách truy cập
                </div>
                <?php endif?>
                 <div class="clear"></div>
                </div>
    	    </section>
    	    <section class="box-space">
                <!--giao dịch trung bình-->
                <div class="b box1">
                    <div class="bhb">
                        <div class="hb">
                            Giá trị giao dịch trung bình
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="n">
                        <?= Core::getService('core.currency')->formatMoney(array('money' => round($this->_aVars['data']['doanh_so_tong_tien'] / $this->_aVars['data']['tong_giao_dich']) )); ?>
                        <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                            <u>
                                <?= $this->_aVars['aSettings']['currency']['symbol']?>
                            </u>
                        </sup>
                    </div>
                    <div class="tt">
                        Tổng số giao dịch
                    </div>
                    <div class="n">
                        <?= $this->_aVars['data']['tong_giao_dich'] ?>
                    </div>
                    <div class="tk">
                        <div class="ic bg" ng-class="box1.checkUpDown"></div>
                        <div class="ttk">
                            
                        </div>
                    </div>
                </div>
                <!--tổng doanh số-->
                <!--<div class="b box2">
                    <div class="bhb">
                        <div class="hb">
                            Tổng doanh số
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="bd">
                        <div class="ch">
                            <img src="style/images/temp/chart.png">
                            <div class="nch">
                                100k
                            </div>
                        </div>
                        <div class="ttch">
                            <div class="l">
                                <div class="lcl gr"></div>
                                <div class="t">
                                    {{line.cash}} Tiền mặt
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="l">
                                <div class="lcl pi"></div>
                                <div class="t">
                                    {{line.credit}} Thẻ tín dụng
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="l">
                                <div class="lcl blu"></div>
                                <div class="t">
                                    {{line.discount}} Thẻ giảm giá
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="l">
                                <div class="lcl gra"></div>
                                <div class="t">
                                    {{line.other}} Khác
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>-->
                <!--sản phẩm bán chạy-->
                <div class="b box3">
                    <div class="bhb">
                        <div class="hb">
                            Sản phẩm bán chạy
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb js-scroll">
                    <?php if (isset($this->_aVars['data']['san_pham_ban_chay']) && !empty($this->_aVars['data']['san_pham_ban_chay'])): ?>
                        <? foreach($this->_aVars['data']['san_pham_ban_chay'] as $aProduct):?>
                        <div class="r f">
                            <div class="ler">
                                <?= $aProduct['name']?>
                            </div>
                            <div class="rir">
                                <?= Core::getService('core.currency')->formatMoney(array('money' => $aProduct['price_sell']))?>
                                <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                    <u>
                                        <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                    </u>
                                </sup>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <? endforeach?>
                    <?php else:?>
                        <div class="dialog-empty">
                            Chưa có thông tin.
                        </div>
                    <?php endif?>
                    </div>
                </div>
                <!--điểm thưởng-->
                <!--<div class="b box4">
                    <div class="bhb">
                        <div class="hb">
                            Điểm thưởng
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="n">
                        {{box4.num1}}
                    </div>
                    <div class="tt">
                        {{box4.smalltitle}}
                    </div>
                    <div class="gd">
                        <div class="ma">
                            {{box4.male}} Nam 
                        </div>
                        <div class="fma"> 
                            {{box4.female}} Nữ
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="tk">
                        <div class="ic bg" ng-class="box4.checkUpDown"></div>
                        <div class="ttk">
                            {{box4.info}}
                        </div>
                    </div>
                </div>-->
                <!--phần thưởng quy đổi-->
                <!--<div class="b box3">
                    <div class="bhb">
                        <div class="hb">
                            phần thưởng quy đổi
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb js-scroll">
                        <div class="r f">
                            <div class="ler">
                                Thẻ quà tặng 50k
                            </div>
                            <div class="rir">
                                1.000.000đ
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Thẻ quà tặng 50k
                            </div>
                            <div class="rir">
                                1.000.000đ
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Thẻ quà tặng 50k
                            </div>
                            <div class="rir">
                                1.000.000đ
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Thẻ quà tặng 50k
                            </div>
                            <div class="rir">
                                1.000.000đ
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Thẻ quà tặng 50k
                            </div>
                            <div class="rir">
                                1.000.000đ
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>-->
                <!--giờ bán chạy hàng-->
                <div class="b box3">
                    <div class="bhb">
                        <div class="hb">
                            giờ bán chạy hàng
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb js-scroll">
                    <?php if(isset($this->_aVars['data']['gio_vang']) && !empty($this->_aVars['data']['gio_vang'])):?>
                        <?php foreach($this->_aVars['data']['gio_vang'] as $aHour):?>
                        <div class="r f">
                            <div class="ler">
                                <?= $aHour['hour'].' ('.$aHour['total_product'].'SP)'?>
                                <!--08:00 21/02/2015 (200SP)-->
                            </div>
                            <div class="rir">
                                <?= Core::getService('core.currency')->formatMoney(array('money' =>$aHour['total_amount']))?>
                                <sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['name_code']?>">
                                    <u>
                                        <?= $this->_aVars['aSettings']['currency']['symbol']?>
                                    </u>
                                </sup>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php endforeach?>
                    <?php else:?>
                        <div class="dialog-empty">
                            Chưa có thông tin.
                        </div>
                    <?php endif?>
                    </div>
                </div>
                <!--khách truy cập-->
                <?php if(Core::getParam('core.main_server') == 'cms.'):?>
                <div class="b box1">
                    <div class="bhb">
                        <div class="hb">
                            khách truy cập
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="n">
                        <?= $this->_aVars['data']['tong_truy_cap'] ?>
                    </div>
                    <div class="tt">
                        Lượt xem trang
                    </div>
                    <div class="n">
                        <?= $this->_aVars['data']['tong_xem_trang'] ?>
                    </div>
                    <!--<div class="tk">
                        <div class="ic bg" ng-class="box1.checkUpDown"></div>
                        <div class="ttk">
                            {{box1.info}}
                        </div>
                    </div>-->
                </div>
                <?php endif?>
                <!--địa điểm truy cập-->
                <!--<div class="b box3">
                    <div class="bhb">
                        <div class="hb">
                            địa điểm truy cập
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb js-scroll">
                        <div class="r f">
                            <div class="ler">
                                TP Hồ Chí Minh
                            </div>
                            <div class="rir">
                                900
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Hà Nội
                            </div>
                            <div class="rir">
                                700
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Đà Lạt
                            </div>
                            <div class="rir">
                                650
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Đà Nẵng
                            </div>
                            <div class="rir">
                                500
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>-->
                <!--nguồn truy cập-->
                <!--<div class="b box3">
                    <div class="bhb">
                        <div class="hb">
                            nguồn truy cập
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb js-scroll">
                        <div class="r f">
                            <div class="ler">
                                Trực tiếp
                            </div>
                            <div class="rir">
                                30%
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Gián tiếp
                            </div>
                            <div class="rir">
                                20%
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r">
                            <div class="ler">
                                Facebook
                            </div>
                            <div class="rir">
                                50%
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>-->
                <!--khách hàng thân thiết-->
                <div class="b box5">
                    <div class="bhb">
                        <div class="hb vol">
                            khách hàng thân thiết
                        </div>
                        <div class="hr">
                            <div class="smr vol"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb5 js-scroll">
                    <?php if (isset($this->_aVars['data']['khach_hang_than_thiet']) && !empty($this->_aVars['data']['khach_hang_than_thiet'])): 
                        $iCnt = 0;
                    ?>
                    <?php foreach( $this->_aVars['data']['khach_hang_than_thiet'] as $aCustomer):?>
                    <?php $iCnt++;?>
                        <div class="rb5">
                            <div class="num vol">
                                <?= $iCnt?>
                            </div>
                            <div class="na">
                                <?= $aCustomer['fullname']?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach?>
                    <?php else:?>
                        <div class="dialog-empty">
                            Chưa có thông tin.
                        </div>
                    <?php endif?>
                    </div>
                    <div class="more vol">
                        Xem thêm
                    </div>
                </div>
                <!--nhân viên chăm chỉ-->
                <!--<div class="b box5">
                    <div class="bhb">
                        <div class="hb" ng-class="box5.color">
                            Nhân viên chăm chỉ
                        </div>
                        <div class="hr">
                            <div class="smr" ng-class="box5.color"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="scrb5 js-scrolll">
                        <? for($i=1; $i<=10; $i ++):?>
                          <div class="rb5">
                              <div class="num" ng-class="box5.color">
                                  1
                              </div>
                              <div class="na">
                                  Nguyễn Văn A
                              </div>
                              <div class="mon">
                                  1.000.000
                              </div>
                              <div class="clear"></div>
                          </div>
                        <? endfor?>
                    </div>
                    <div class="more" ng-class="box5.color">
                        Xem thêm
                    </div>
                </div>-->
                <!--hành động mua sắm-->
                <!--<div class="b box6">
                    <div class="bhb">
                        <div class="hb">
                            {{box6.head}}
                        </div>
                        <div class="hr">
                            <div class="smr"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="bd">
                        <div class="r">
                            <div class="ft">
                                Thêm sản phẩm vào giỏ hàng
                            </div>
                            <div class="st">
                                {{box6.add}}
                            </div>
                            <div class="rou">
                                <div class="inrou"></div>
                            </div>
                            <div class="bar"></div>
                        </div>
                        <div class="r">
                            <div class="ft">
                                Thực hiện thanh toán
                            </div>
                            <div class="st">
                                {{box6.pay}}
                            </div>
                            <div class="rou">
                                <div class="inrou"></div>
                            </div>
                            <div class="bar"></div>
                        </div>
                        <div class="r">
                            <div class="ft">
                                Hoàn thành thanh toán
                            </div>
                            <div class="st">
                                {{box6.fin}}
                            </div>
                            <div class="rou">
                                <div class="inrou"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>-->
                <div class="clear"></div>
    	    </section>
    	</div>
    </div>