<? if(Core::isUser()):?>
    <form action="" method="get" name="frm_tim_kiem" onsubmit="return CheckSearch(this)" class="sf left inhd" style="display: block">
      <div class="sic bg left" id="js-btn-search"></div>
      <input placeholder="Tìm kiếm..." class="sb left" id="js-ctn-search" value="">
      <div class="clear" ></div>
    </form>
<? endif; ?>