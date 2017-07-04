<meta http-equiv="content-type" content="text/html; charset=<?= Core::getPhrase('language_kieu_charset'); ?>" />
<meta http-equiv="Content-Language" content="<?= Core::getPhrase('kieu_ngon_ngu');?>"/>
<meta property="og:title" content="<?= Core::getLib('template')->getTitle();?>" />
<?php  if (!empty($this->_aVars['sKeyword'])):?><meta name="keywords" content="<?= $this->_aVars['sKeyword']; ?>" /><?php endif;?>
<?php if(!empty($this->_aVars['description']) ):?>
<meta name="description" content="<?= $this->_aVars['description']?>" />
<meta property="og:description" content="<?= $this->_aVars['description']?>" />
<?php endif?>
<?php if($this->_aVars['refresh_time']>10) {?><meta http-equiv="REFRESH" content="<?= $this->_aVars['refresh_time']?>" /><?php }?>

<?php if(!empty($this->_aVars['sArticleImagePath'])):?><meta property="og:image" content="<?= $this->_aVars['sArticleImagePath']?>" /><?php endif;?>

<meta content="all" name="robots" />
<?php if($this->_aVars['aMeta']['index']):?>
<meta name="robots" content="index,all" />
<meta name="googlebot" content="all, index, follow" />
<?php else:?>
<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">

<?php endif?>
<?
// rel link prev and next for SEO in cat with filter
if(!empty($this->_aVars['prev_link'])) echo '<link rel="prev" title="'.$this->_aVars['prev_link']['title'].'" href="'.$this->_aVars['prev_link']['href'].'" />';
if(!empty($this->_aVars['next_link'])) echo '<link rel="next" title="'.$this->_aVars['next_link']['title'].'" href="'.$this->_aVars['next_link']['href'].'" />';
?>

<meta name="revisit-after" content="1 days" />
<meta name="pagerank™" content="10" />
<meta name="serps" content="1, 2, 3, 10, 11, 12, 13, ATF" />
<meta name="author" content="fi.ai" />
<meta content="<?= $this->_aVars['aSettings']['template'];?>" name="template" />
<base href="http://<?= Core::getParam('core.main_server').$this->_aVars['sDomainName']?>" />
<link rel="canonical" href="http://<?= $this->_aVars['sDomainName'].$_SERVER['REQUEST_URI']?>" />

<link rel="alternate" type="application/rss+xml" href="/feed.rss" />
<link rel="alternate" type="application/atom+xml" href="/feed.rss" />
<link rel="icon" type="image/png" href="<?= $this->_aVars['aSettings']['avatar']['favicon']?>" />
<script>
<?php if(empty($this->_aVars['sMobile'])): ?> 
    var mobileversion = '';
<?php else:?>
    var mobileversion = <?= $this->_aVars['sMobile'] ?>;
<?php endif; ?>
var global = {};
global['domain'] = '<?= $this->_aVars['sDomainName'];?>';
</script>
<script type='text/javascript' src='http://img.<?= $this->_aVars['sDomainName'];?>/styles/web/global/js/default.js?v=<?= $this->_aVars['versionExFile']?>'></script>
<?=
  Core::getService('core.tools')->getMinifyName('css', 
    array('global/css/default.css', '1018/css/button.css', 'global/js/anytime/anytime.css','global/js/fancybox/jquery.fancybox.css'),
    '

@font-face {
    font-family: "HelveticaNeue";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue.eot?v='.$this->_aVars['versionExFile'].'-#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue.svg?v='.$this->_aVars['versionExFile'].'#OpenSansRegular") format("svg");
    font-weight: normal;
    font-style: normal;

}
@font-face {
    font-family: "HelveticaNeue-Medium";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Medium.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Medium.eot?v='.$this->_aVars['versionExFile'].'-#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Medium.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Medium.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Medium.svg?v='.$this->_aVars['versionExFile'].'#OpenSansRegular") format("svg");
    font-weight: normal;
    font-style: normal;

}
@font-face {
    font-family: "HelveticaNeue-Light";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Light.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Light.eot?v='.$this->_aVars['versionExFile'].'-#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Light.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Light.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Light.svg?v='.$this->_aVars['versionExFile'].'#OpenSansRegular") format("svg");
    font-weight: normal;
    font-style: normal;

}
@font-face {
    font-family: "HelveticaNeue-Bold";
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Bold.eot?v='.$this->_aVars['versionExFile'].'");
    src: url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Bold.eot?v='.$this->_aVars['versionExFile'].'-#iefix") format("embedded-opentype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Bold.woff?v='.$this->_aVars['versionExFile'].'") format("woff"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Bold.ttf?v='.$this->_aVars['versionExFile'].'") format("truetype"),
         url("http://img.'.$this->_aVars['sDomainName'].'/styles/web/global/font/HelveticaNeue-Bold.svg?v='.$this->_aVars['versionExFile'].'#OpenSansRegular") format("svg");
    font-weight: normal;
    font-style: normal;

}
')
?>

<?= $this->_aVars['setting']['column_0']?>
<?php if($this->_aVars['setting']['google_analytics']):?>
<script>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?= $this->_aVars['setting']['google_analytics']?>']);
  _gaq.push(['_trackPageview']);
  <?php if($this->_aVars['sDomainName'] != $_SERVER['HTTP_HOST']):?>
  _gaq.push(['_setDomainName', 'none']);
  _gaq.push(['_setAllowLinker', true]);
  <?php endif?>
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js?v=<?= $this->_aVars['versionExFile']?>';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php endif?>