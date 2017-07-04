<div class="sl-lc is-hs-hdl" data-hs="sl-lc">
    <div class="sl-lc-head trs_ln">
        <span class="fa fa-map-marker"></span>
        <?= $this->_aVars['sZone']; ?>
    </div>
    <div class="sl-lc-lst is-hs-obj" data-hs="sl-lc">
        <?php foreach($this->_aVars['aZones'] as $aZone): ?>
            <div class="lc-itm trs_ln">
                <a href="/<?= $aZone['name_code']; ?>/" >
                    <?= $aZone['name']; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div> <!-- location-list-->
</div> <!-- select-location -->
<?php unset($this->_aVars['sZone']); ?>
<?php unset($this->_aVars['aZones']); ?>