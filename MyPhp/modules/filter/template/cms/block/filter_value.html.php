<? if (count($this->_aVars['aFilterValues'])): ?>
    <? $iCnt = 1; ?>
    <? foreach ($this->_aVars['aFilterValues'] as $aValue): ?>
        <? if ($iCnt%3 == 1): ?>
        <div class="row30">
        <? endif; ?>
            <div class="col4 js-filter-value-item" data-id="<?= $aValue['id']?>">
                <?= $aValue['name']?>
            </div>
        <? if ($iCnt%3 == 0 || $iCnt == count($this->_aVars['aFilterValues'])): ?>
        </div>
        <? endif; ?>
        <? $iCnt++; ?>
    <? endforeach; ?>
<? else: ?>
    <div class="row30 mgbt20">
        Không có dữ liệu trong hệ thống.
    </div>
<? endif; ?>