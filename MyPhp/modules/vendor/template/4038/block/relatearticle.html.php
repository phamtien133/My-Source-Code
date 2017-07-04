<section class="cbo-rlt-mrt">
    <div class="rlt-mrt-head">Sản phẩm cùng siêu thị</div>
    <div class="rlt-mrt-lst">
        <? for($i=1; $i<=5; $i++):?>
        <div class="rlt-mrt-itm mxClrAft">
            <a href="#" title="tiêu đề">
                <img src="http://img.<?= $_SESSION['session-ten_mien']['ten']?>/styles/web/4038/images/demo/bn3.jpg" alt="">
                <span class="pc-pr">
                    <span>-10</span>
                </span>
            </a>
            <div class="rlt-mrt-ifo">
                <a class="mrt-nm" href="#" title="Thịt gà">
                    Thịt gà xông khói
                </a>
                <div class="sp-pr">
                    125.000 đ/kg
                </div>
            </div> <!-- rlt-mrt--ifo -->
        </div> <!-- rlt-mrt-lst-itm -->
        <? endfor?>
    </div> <!-- rlt-mrt-lst -->
</section> <!-- cbo-rlt-mrt -->