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
        <div class="main-info-page mxClrAft">
            <div class="left-info-page">
                <?php Core::getBlock('user.info.chargemoney');?>
            </div>
            <div class="right-info-page">
                <?php Core::getBlock('user.info.personal');?>
                <?php //Core::getBlock('user.info.cart');?>
            </div>
        </div>
    </div>
</section>