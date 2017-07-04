<?php  foreach($this->_aVars['aAds']['index'] as $sKey => $sValue):?>
<div class="div_quang_cao vi_tri_<?= $sValue?> close" id="div_quang_cao_<?= $sKey?>">
    <div class="div_quang_cao_tieu_de">
    <?= Core::getPhrase('language_quang-cao')?><div class="div_quang_cao_dong" onClick="tat_quang_cao(<?= $sKey?>)">x</div><div class="div_quang_cao_thu_nho" id="div_quang_cao_thu_nho_<?= $sKey ?>" onClick="thu_nho_quang_cao(<?= $sKey ?>)">^</div>
    </div>
    <div class="div_quang_cao_noi_dung" id="div_quang_cao_noi_dung"><?= htmlentities($this->_aVars['aAds']['content'][$sKey], ENT_QUOTES, "UTF-8")?></div>
</div>
<?php endforeach?>
<div class="div_quang_cao vi_tri_5 close" id="div_chat">
    <div class="div_quang_cao_tieu_de">
        <span class="text-header"><?= Core::getPhrase('language_ho-tro')?></span>
        <div class="div_quang_cao_dong" onClick="tat_chat()"></div>
        <div class="div_quang_cao_thu_nho" id="div_chat_thu_nho" onClick="thu_nho_chat()"></div>
    </div>
    <div class="div_quang_cao_noi_dung" style="background:#FFF;">
        <div id="div_chat_noi_dung">
        </div>
        <div class="control-chat">
            <form method="post" onSubmit="return luu_chat()">
                <input type="text" autocomplete="off" id="txt_chat" />
                <input type="submit" id="submit_chat" value="<?= Core::getPhrase('language_gui')?>" />
             </form>
        </div>
    </div>
    <div id="div_chat_loi_chao" class="none"><?= $this->_aVars['sSettings']['setting_support']['welcome']?></div>
    <div id="div_chat_thong_bao_lan_dau" class="none"><?= $this->_aVars['sSettings']['setting_support']['first_announce']?></div>
</div>

<?php if($adminSite && $_SESSION['session-quyen']['sua_thiet_lap'] ==1):?>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/tiny_mce/tinymce.min.js?v=<?= $this->_aVars['versionExFile']?>"></script>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/tinymce_tuy_chinh.js?v=<?= $this->_aVars['versionExFile']?>"></script>
<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/admin.js?v=<?= $this->_aVars['versionExFile']?>" ></script>
<div class="div_quang_cao vi_tri_9" id="cot_form" style="top:50px;left:500px">
    <div class="div_quang_cao_tieu_de" id="div_cot_tieu_de"></div>
    <div class="div_quang_cao_noi_dung" id="div_cot_noi_dung" style="background:#FFF;"></div>
</div>
<?php endif?>

<script>
var arr_mua_nhanh = [];
<?php if($this->_aVars['aSettings']['page_type'] == 'shopping'):?>
if (typeof(build_temp_buy_quick) != 'function'){
  function build_temp_buy_quick(arr, data) {
  var content = '', i = 0;
    if (data['price_discount'][i] > 0)  {
      phan_tram = Math.ceil( (data['price_discount'][i] / data['price_sell'][i]) * 100 );
      data['price_sell'][i] = numberFormat(data['price_sell'][i]);
      data['price_discount'][i] = numberFormat(data['price_discount'][i]);
      content = '<div class="tooltip_saleoff">Giá chính hãng: <span><span class="bai_viet_gia_ban">' + data['price_sell'][i] + '</span><sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['code_name']?>"><u><?= $this->_aVars['aSettings']['currency']['symbol']?></u></sup></span></div><div class="tooltip_percent">Giảm <span><span class="bai_viet_phan_tram">' + phan_tram + '</span>%</span></div>';
    }
  content = '<div class="infoMuaNhanh"><div class="fleft"><div>Bạn đang xem nhanh:<br><h3><a href="' + data['detail_path'][i] + '">' + data['title'][i] + '</a></h3></div>' + content + '<div class="tooltip_price">Giá bán: <span><span class="bai_viet_thanh_tien">' + arr['amount'] + '</span><sup itemprop="priceCurrency" content="<?= $this->_aVars['aSettings']['currency']['code_name']?>"><u><?= $this->_aVars['aSettings']['currency']['symbol']?></u></sup></span></div><div style="border-bottom:1px solid #CCC;margin:7px;"></div>' + data['filter'][i] + '<table align="center"><tr><td width="90px">Số lượng:</td><td><select name="so_luong" id="so_luong"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select></td></tr></table><a class="btn btnMua AddCartBT_' + arr['id'] + '" href="#"></a><div style="border-bottom:1px solid #CCC;margin:7px;"></div><div style="color:#000;font-weight:bold"><?= Core::getPhrase('language_dac-tinh-noi-bat')?></div><div style="height:95px;overflow:auto;">' + data['mo_rong_ghi_chu'][i] + '</div><div style="text-align: center; position: absolute; bottom: 0px; margin: 0px auto; font-weight: bold; font-size: 20px; font-style: italic; left: 50px;"><a href="' + data['detail_path'][i] + '" title="' + data['title'][i] + '"><?= Core::getPhrase('language_xem-chi-tiet')?> Sản phẩm</a></div></div><div class="fright">';
  content += '<div><div id="nivoslider-' + i +'" class="nivoSlider nivoSliderBanner">';
  for (var j =0; j < data['image_extra'][i]['hinh-slide-lon'].length; j++) {
    content += '<a href="' + data['image_extra'][i]['hinh-slide-lon'][j] + '" rel="thumbsImg"><img src="' + data['image_extra'][i]['hinh-slide-lon'][j] + '" /></a>';
  }
  content += '</div></div>';  
  return content;
}}
<?php endif?>

var time_thu_nho = [], time_tat_quang_cao = [], popup_quang_cao = [];
$(function() {
<?php foreach($this->_aVars['aAds']['index'] as $sKey => $sValue):?>
    <?php
    if($this->_aVars['aAds']['position'][$sKey] == 10)
    {
        ?>
        popup_quang_cao[<?= $sKey?>] = {
            width : <?= $this->_aVars['aAds']['width'][$sKey]?>,
            height: <?= $this->_aVars['aAds']['height'][$sKey]?>,
            content: htmlEntities($('#div_quang_cao_<?= $sKey?> .div_quang_cao_noi_dung').html())
        };
        <?php
    }
    ?>
    time_thu_nho[<?= $sKey?>] = <?= ($this->_aVars['aAds']['minimize'][$sKey]*1000)?>;
    time_tat_quang_cao[<?= $sKey?>] = <?= ($this->_aVars['aAds']['close'][$sKey]*1000)?>;
<?php endforeach?>
});

var s = document.getElementsByTagName('script')[0];
<?php if($this->_aVars['aSettings']['online_support'] == 1 && $this->_aVars['aSettings']['chat_limit']):?>
node = document.createElement('script');
node.type = 'text/javascript';
node.async = true;
node.src = 'http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/support_online_limit.js?v=<?= $this->_aVars['versionExFile']?>';
s.parentNode.insertBefore(node, s);
<?php endif?>

var generalPage = {
    id: '<?= $this->_aVars['iId']?>',
    pid: '<?php $sType = Core::getLib('module')->getModuleName(); if($sType == 'category' || $sType == 'core' || $sType == 'article'):?><?= $this->_aVars['iParentId']?><?php endif?>',
    key: '<?= $this->_aVars['sCode']?>',
    type: '<?= $sType?>',
};
<?php if($sType == 'category' && $this->_aVars['iId'] == Core::getLib('session')->get('session-category_index')):?>
(function (){
    var version = "<?= CORE_TIME?>";
    int = localStorage.getItem(generalPage.key + '|' + generalPage.type);
})();
<?php endif?>
<?php $sAdsServer = Core::getParam('core.ads_server'); if(!empty($sAdsServer)):?>
if(_abd == null)
{
    var _abd = [];
    _abd.push(["<?= Core::getLib('session')->getArray('session-domain', 'code')?>", "phai-duoi", "300x250"]);
}

var node = document.createElement('script');
node.type = 'text/javascript';
node.async = true;
node.src = '//<?= $sAdsServer?>/ads.js?v=<?= $this->_aVars['versionExFile']?>';

s.parentNode.insertBefore(node, s);
<?php endif?>
</script>

<div id="MooDialog" style="opacity: 1;"><div id="div_dang_mua"></div></div><div id="overlay"></div><a href="?ban=1" rel="nofollow" style="display:none"></a>