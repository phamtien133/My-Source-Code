<header id="header" class="header unselectable">
  <div class="leftheader left">
  	<div class="ttpage left inhd">
	  <md-button class="mnic bg left" id="js-menu-toggle" ng-class="setMenuIcon"></md-button>
	  <div class="tt left">
      <? if (Core::getParam('core.main_server') == 'sup.'): ?>
        Hệ quản trị nội dung DISIEUTHI.VN <? if (!empty($this->_aVars['sVendorName'])): ?> - <?= $this->_aVars['sVendorName']?> <? endif; ?>
      <? else: ?>
        <?= Core::getLib('template')->getTitle();?>
      <? endif; ?>
    </div>
	  <div class="bor left"></div>
	  <div class="clear"></div>
	</div>
    <? core::getBlock('core.search'); ?>
	<div class="more left inhd" style="display: none">
		Thêm sản
		<div class="hv"></div>
	</div>
	<div class="clear"></div>
  </div>
  <div class="rightheader right">
    <? if (Core::getParam('core.main_server') == 'cms.' && Core::isUser()): ?>
    <div class="back-state left has-message close-crm" id="js-crm-state">
    </div>
    <? endif; ?>
    <?php if(Core::getUserId() > 0):?>
  	    <div class="stinf left inhd" id="js-user-profile">
  	      <div class="na left">
  	  	    <?= Core::getUserName()?>
  	      </div>
  	      <div class="ava left">
  	  	    <img src="//img.disieuthi.vn/styles/web/global/images/noimage/male.png">
  	      </div>
  	      <div class="clear"></div>
  	      <div class="hv"></div>
          <div class="user-proflie">
                <a href="/user/logout" id="js-logout">Đăng xuất</a>
            </div>
  	    </div>
    <?php endif?>
  	<div class="clear"></div>
  </div>
  <div class="clear"></div>
</header>
<div class="clearheader"></div>