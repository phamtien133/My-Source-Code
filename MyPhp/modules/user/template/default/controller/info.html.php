<section class="wrap info-page">
    <div class="ctnr">
        <section class="cbo-brc">
            <div class="brc-lst">
                <a class="next" href="#" title="title">
                    Trang chủ
                </a>
            </div>
            <a class="atc-nm" href="/trang_ca_nhan.html" title="title">
                Trang cá nhân
            </a>
        </section> <!-- cbo-brc -->
        <div class="main-info-page mxClrAft" id="information">
            <div class="left-info-page">
                <div id="product">
                    <?php Core::getBlock('user.info.product');?>
                </div>
                <div id="order">
                    <?php Core::getBlock('user.order');?>
                </div>
                <div id="profile">
                    <?php Core::getBlock('user.info.profile');?>
                </div>
            </div>
            <div class="right-info-page">
                <div id="personal">
                    <?php Core::getBlock('user.info.personal');?>
                    <?php Core::getBlock('user.top');?>
                </div>
                <div id="cart">
                    <?php //Core::getBlock('user.info.cart');?>
                    <?php Core::getBlock('user.top');?>
                </div>
            </div>
        </div>
    </div>
</section>