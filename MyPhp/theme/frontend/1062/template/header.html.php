<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?=
    Core::getService('core.tools')->getMinifyName('css', 
        array(
            '1062/css/neopubl.css',
            '1062/css/fakeselect.css',
            '1062/css/device.css',
            '1062/css/popup.css',
        ),
        '
/*font.css*/

@font-face {
    font-family: "NhomMuaBold";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-bold-webfont.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-bold-webfont.eot?v='.$this->_aVars['versionExFile'].'#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-bold-webfont.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-bold-webfont.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-bold-webfont.svg?v='.$this->_aVars['versionExFile'].'#NhomMuaBold") format("svg");
    font-weight: bold;
    font-style: normal;

}
@font-face {
    font-family: "NhomMuaMedium";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-md-webfont.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-md-webfont.eot?v='.$this->_aVars['versionExFile'].'#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-md-webfont.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-md-webfont.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-md-webfont.svg?v='.$this->_aVars['versionExFile'].'#NhomMuaMedium") format("svg");
    font-weight: normal;
    font-style: normal;

}
@font-face {
    font-family: "NhomMuaSmBd";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-sb-webfont.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-sb-webfont.eot?v='.$this->_aVars['versionExFile'].'#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-sb-webfont.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-sb-webfont.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-sb-webfont.svg?v='.$this->_aVars['versionExFile'].'#NhomMuaSmBd") format("svg");
    font-weight: normal;
    font-style: normal;

}
@font-face {
    font-family: "NhomMuaRegular";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-rg-webfont.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-rg-webfont.eot?v='.$this->_aVars['versionExFile'].'#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-rg-webfont.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-rg-webfont.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/2044/css/font/nhom_mua-rg-webfont.svg?v='.$this->_aVars['versionExFile'].'#NhomMuaRegular") format("svg");
    font-weight: normal;
    font-style: normal;

}'
    );
?>
    
        <link href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/css/main_1303.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css" rel="stylesheet" />
        <link href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/css/category.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css" rel="stylesheet" />
        <link href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/css/goods.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css" rel="stylesheet" />
        
    <link href="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/1062/css/common_1303.css?v=<?= $this->_aVars['versionExFile']?>" type="text/css" rel="stylesheet" />
</head>                   
<script>
$(function() {
    $('.bnr_header .iosSlider').imagesLoaded(function() {
        $('.bnr_header .iosSlider').iosSlider({
            snapToChildren: true,
            desktopClickDrag: true,
            snapSlideCenter: true,
            autoSlide: true,
        });
    });
    /* setup top of menu */
    var height = 0, heightParent = $('#catecontent').height();
    
    height = $('#catecontent > div:first').height();
    
    $('.cate_mn > li').each(function(index, element) {
        $(this).find('.dep_cate_mn').css('top', height*-1);
        
        height += $(this).height() + 6;
        
        if($(this).find('.dep_cate_mn').height() < heightParent)
        {
            $(this).find('.dep_cate_mn').height(heightParent);
        }
    });
});
</script>