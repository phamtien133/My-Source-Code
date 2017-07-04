<div id="footer">
<div class="leftbnr_wing">
    <?php Core::getBlock('slide.left-top');?>
    <?php Core::getBlock('slide.left-bottom');?>
    <div class="chinh_sua" id="cot_4"><?= $this->_aVars['aSettings']['column_4']?></div>
</div>
     
<div class="skyscraper mainon not_dept ">
    <button class="btn_sky_mini"><span class="blind">Minimize</span></button>
    <div>
        <div class="cart nogoods">
            <strong class="s_title"><a href="/shop_chi_tiet.html" class="sp_dpm h_wish" title="<?= Core::getPhrase('language_gio-hang')?>"><?= Core::getPhrase('language_gio-hang')?></a><span><em class="front">0</em><em></em></span></strong>
            <div id="div_gio_hang" class="float_div_cartwish">
                <h4 class="widgettitle"><?= Core::getPhrase('language_gio-hang')?></h4>
                <div style="float:left;width:30%">
                    <div style="line-height:30px;color:#66CCCC;font-weight:bold;font-size:40px;text-align:center" id="div_gio_hang_so_luong">0</div>
                    <div style="float:right;color:#CCC;">
                        <?= Core::getPhrase('language_san-pham')?>
                    </div>
                </div>
                <div style="float:right;width:70%;padding-top:8px">
                    <div style="color:red;font-weight:bold;font-size:20px;text-align:right" id="div_gio_hang_tong_tien">0</div>
                    <div style="float:right">
                        <?= $this->_aVars['aSettings']['currency']['name']?>
                    </div>
                </div>
                <div style="clear:both;padding-bottom:20px"></div>
                <div style="float:left;width:50%;" id="div_cap_nhat_gio_hang"><a href="#" onclick="return capNhatGioHang()"><?= $lang['cap-nhat']?></a></div>
                <div style="float:right;text-align:right;width:50%;">
                    <a href="/shop_chi_tiet.html"><?= Core::getPhrase('language_xem-chi-tiet')?></a></div>
                <div style="clear:both;padding-bottom:20px"></div>
                <div class="div_content"></div>
            </div>
        </div>
        <div class="wish nogoods">
            <strong class="s_title"><a href="#" class="sp_dpm h_wish">Wishlist</a><span><em class="front">0</em><em></em></span></strong>
            <div id="div_ua_thich" class="float_div_cartwish"></div>
        </div>
        <div class="recent nogoods">
            <strong class="s_title"><a href="#" class="sp_dpm h_recent">Wishlist</a><span><em class="front">0</em><em></em></span></strong>
            <div id="div_san_pham_vua_xem" class="float_div_cartwish"></div>
        </div>
        <div class="chinh_sua" id="cot_10"><?= $this->_aVars['aSettings']['column_10']?></div>
    </div>
    <p class="btn_top"><a href="#" class="sp_dpm">TOP</a></p>
</div>
    <div class="footer_wrap">
    <div class="footer_content">
    <div class="chinh_sua" id="cot_6"><?= $this->_aVars['aSettings']['column_6']?></div>
</div><!-- //wrap -->
<script>
configGlobal['getShopTotal'] = -1;
function capNhatGioHang_callback(data)
{
    if(data[0] < 1) return;
    
    $('.skyscraper .cart .front').html(data[0]);
    $('.global_mn .cart em').html(data[0]);
    
    var content = '';
    if(typeof(data['san_pham']['stt']) == 'undefined') data['san_pham']['stt'] = [];
    for(i in data['san_pham']['stt'])
    {
        content += '<div><h3><a target="_blank" href="' + data['san_pham']['duong_dan'][i] + '">' + data['san_pham']['ten'][i] + '</a></h3><div>Giá bán: ' + numberFormat(data['san_pham']['gia_ban'][i]) + '</div></div>';
    }
    $('#div_gio_hang .div_content').html(content);
    
    if(data['auto']) return;
    
    $('.skyscraper .cart').trigger('mouseenter');
    
    setTimeout(
        function(){
            $('.skyscraper .cart').trigger('mouseleave');
        }, 1000
    );
}
$(function(){
    $('.h_wish, .h_recent').mouseover(function(e) {
        var obj = '#div_ua_thich';
        var query = setupPath('s') + '/tools/like.php?type=bai_viet&act=get&n=10';
        if($(this).hasClass('h_recent'))
        {
            obj = '#div_san_pham_vua_xem';
            query = setupPath('s') + '/tools/recent.php?type=bai_viet&act=get&n=10';
        }
        
        if($(obj).html() != '')
        {
            return ;
        }
        $(obj).html('<a href="javascript:void(this);" onclick=""><img src="http://img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" title="Waiting" /></a>');
        $.ajaxCall({
            url: query,
            timeout: 15000,
            cache:false,
            callback: function(data){
                if(typeof(data) == 'object' && data.type == 'error')
                {
                    $(obj).html('');
                    return false;
                }
                
                if(data == null)
                {
                    var data = [{
                        'san_pham': {}
                    }];
                }
                else if(typeof(data['san_pham']) == 'undefined')
                {
                    data['tong'] = 0;
                    data['san_pham'] = {};
                }
                var content = '<div class="content-list">';
                if(obj == '#div_ua_thich') content += '<div>Bạn đã thích ' + data['tong'] + ' sản phẩm</div>';
                
                if(typeof(data['san_pham']['stt']) == 'undefined') data['san_pham']['stt'] = [];
                for(i in data['san_pham']['stt'])
                {
                    content += '<div><h3><a target="_blank" href="' + data['san_pham']['duong_dan'][i] + '">' + data['san_pham']['ten'][i] + '</a></h3><div>Giá bán: ' + numberFormat(data['san_pham']['gia_ban'][i]) + '</div></div>';
                }
                content += '</div>';
                $(obj).html(content);
            }
        });
    });
});
</script>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.loadTemplate-1.4.1.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/js/idangerous.swiper-2.1.min.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/js/idangerous.swiper.scrollbar-2.1.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/js/integration.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
<div class="chinh_sua" id="cot_12"><?= $this->_aVars['aSettings']['column_12']?></div>
<?= 
   Core::getService('core.tools')->getMinifyName('js', 
        array(
            '1062/js/Jquery.hashchange.js',
        ),
        '
    
    $(function() {
        $(".btn_left .quick").hover(function(){
            $(".btn_quick").show();
        }, function() {
            $(".btn_quick").hide();
        });
        $(".category .cate_all").click(function(){
            $(".category .inner").toggleClass("dpn");
        });
        $(".btm .close").click(function(){
            $(".category .inner").addClass("dpn");  
        });
        $(".depart > .dep2 > .in > .lst > ul > li a ").hover(function(){
            var $img = $(this).children();
            $img.attr("src",$img.attr("src").replace(".GIF","on.GIF"));
        }, function(){
            var $img = $(this).children();
            $img.attr("src",$img.attr("src").replace("on.GIF",".GIF"));
        });
        
        $(".mn_group li").css("z-index","1");
        $(".mn_group li").each(function(){      
            $(this).hover(
            function(){ 
                $(this).children(".dep2").show();
                $(this).css("z-index","900");
            }, 
            function() {
                $(this).children(".dep2").hide();
                $(this).css("z-index","1");
            }); 
        });.js
    });'
    );
?>
</body>
</html>