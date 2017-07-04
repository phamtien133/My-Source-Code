<section class="container phanquyen">
    <? if (!empty($this->_aVars['sError'])):?>
        <div class="tpq mxClrAft">
          <div class="inf mxClrAft">
            <div class="padlr20 mgbt20">
                <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
                <br />
                <?= $this->_aVars['sError']?>
            </div>
          </div>
          <div class="up">
            <md-button class="ubt" id="js-back-manage" onclick="window.location = '/user/group/'">
              Về trang quản lý
            </md-button>
          </div>
        </div>
    <? else:?>
    <div class="tpq mxClrAft">
      <div class="inf mxClrAft">
        <div class="r mxClrAft">
          <div class="tt">
            Tên nhóm:
          </div>
          <input type="hidden" id="js-obj-id" value="<?= $this->_aVars['aData']['info']['id']?>">
          <div class="if txt-blue txt-bold">
            <?= $this->_aVars['aData']['info']['name']?>
          </div>
        </div>
      </div>
      <div class="up">
        <div class="row30">
            <md-button class="ubt" id="js-sbm-permission">
                Hoàn thành
            </md-button>
        </div>
        <div class="row30 mgtop10">
            <md-button class="ubtp" data-obj="<?= $this->_aVars['aData']['info']['id']?>" id="js-set-pri-permission">
                Quyền chi tiết
            </md-button>
        </div>
      </div>
    </div>
    <section data-id="morong" class="morong mxClrAft">
        <div class="row30">
            <div class="r mxClrAft">
                <div class="t mgright20">
                  <?= Core::getPhrase('language_uu-tien')?>:
                </div>
                <select class="" name="priority" id="priority" style="width: 40px;">
                    <?php for($i=0;$i<10;$i++):?>
                    <option value="<?= $i?>" <?php if($this->_aVars['aData']['priority'] == $i):?>selected="selected"<?php endif?>><?= $i?></option>
                    <?php endfor?>
                </select>
              </div>
        </div>
        <div class="row30">
            <? $iCnt = 0;?>
            <? foreach ($this->_aVars['aData']['group_permission'] as $sFeild => $aPermission):?>
            
            <? $iCnt++;?>
            <? if ($iCnt%2 != 0):?>
                <div class="row30">
            <? endif?>
            <div class="col6">
                <div class="">
                    <div class="r mxClrAft">
                        <md-switch class="sw js-parent-per" data-id="<?= $iCnt?>" data-field="<?= $sFeild?>"></md-switch>
                        <div class="t txt-blue txt-bold">
                            <?= $this->_aVars['aData']['field_permission'][$sFeild]?>
                        </div>
                    </div>
                </div>
                <div class="padleft20">
                    <? foreach ($aPermission as $sVal):?>
                    <div class="r mxClrAft">
                        <md-switch class="sw js-child-per <?if(isset($this->_aVars['aData']['permission'][$sVal]) && $this->_aVars['aData']['permission'][$sVal] > 0):?> md-checked <? endif?>" data-value="<?= $sVal?>" data-id="<?= $iCnt?>" data-field="<?= $sFeild?>"></md-switch>
                        <div class="t">
                            <?= $this->_aVars['aData']['field_permission'][$sVal]?>
                        </div>
                    </div>
                    <? endforeach?>
                </div>
            </div>
            <? if ($iCnt%2 == 0):?>
            </div>
            <? endif?>
            <? endforeach?>
        </div>
    </section>
    <? endif?>
</section>