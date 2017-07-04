<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery-1.9.1.min.js?v=<?= $this->_aVars['versionExFile']?>"></script>
<script src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/jquery.nouislider.min.js?v=<?= $this->_aVars['versionExFile']?>"></script>

<script type="text/javascript" src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/js/anytime/anytime.js?v=<?= $this->_aVars['versionExFile']?>"></script>

<?=
    Core::getService('core.tools')->getMinifyName('js', array(
        'global/js/main.js',
        'global/js/trich_loc.js'
    ))
?>
<script>var configGlobal = [];configGlobal['getShopTotal'] = -1;</script>