
  <section class="container phanquyen">
    <form id="js-frm-permssion" action="" method="post">
    <div class="tpq mxClrAft">
      <div class="inf mxClrAft">
        <div class="r mxClrAft">
          <div class="tt">
            Tên:
          </div>
          <div class="if txt-blue txt-bold">
            <?= $this->_aVars['aData']['info']['name']?>
          </div>
          <input type="hidden" id="js-obj-id" value="<?= $this->_aVars['aData']['info']['id']?>">
          <input type="hidden" id="js-obj-type" value="<?= $this->_aVars['aData']['type']?>">
        </div>
        <div class="r mxClrAft">
          <div class="tt">
            STT:
          </div>
          <div class="if txt-blue txt-bold">
            <?= $this->_aVars['aData']['info']['id']?>
          </div>
        </div>
      </div>
      <div class="up">
        <md-button class="ubt" id="js-sbm-permission">
          Cập nhật
        </md-button>
        <div class="row30 mgtop10">
            <div class="col6">Trạng thái:</div>
            <div class="col6 status-icon">
                <span class="fa fa-check icon-wh js-activity-object" data-id="3"></span>
            </div>
        </div>
      </div>
    </div>
    <div class="bigtabb mxCLrAft">
      <?php //if (!$this->_aVars['aData']['vendor_id']):?>
      <div class="bd">
        <?php if(Core::getParam('core.main_server') == 'cms.'):?>
          <div class="tab <?php if($this->_aVars['aData']['vendor_id']< 1):?>atv<?php endif?> js-tab-vendor" data-link="<?= $sLinks?>">
              <div class="tt">
                Hệ thống
              </div>
              <div class="hv"></div>
          </div>
          <?php $bHas = false;?>
          <?php if(isset($aVendorSelect) && !empty($aVendorSelect)):?>
              <?php foreach($aVendorSelect as $aTmp):?>
                <div class="tab <?php if($this->_aVars['aData']['vendor_id'] == $aTmp['id']):?>atv<?php $bHas = true; endif?> js-tab-vendor" data-link="<?= $sLinks.'&vendor_id='.$aTmp['id']?>">
                  <div class="tt">
                    <?= $aTmp['name']?>
                  </div>
                  <div class="hv"></div>
                </div>
              <?php endforeach?>
          <?php endif?>
          <?php if(!$bHas && $this->_aVars['aData']['vendor_id']> 0):?>
          <div class="tab atv js-tab-vendor" data-link="<?= $sLinks.'&vendor_id='.$this->_aVars['aData']['vendor_id']?>">
              <div class="tt">
                <?= $aVendor['name']?>
              </div>
              <div class="hv"></div>
          </div>
          <?php endif?>
          <!--<div class="tab js-tab-vendor" data-link="none">
              <div class="tt">
                Chọn nhà cung cấp
              </div>
              <div class="hv"></div>
          </div>-->
        <?php else: ?>
        <div class="tab atv">
          <div class="tt">
            <?= $aVendor['name']?>
          </div>
          <div class="hv"></div>
        </div>
        <?php endif?>
        <?php if ($this->_aVars['aData']['vendor_id'] > 0):?>
        <input type="hidden" name="vendor_id" value="<?= $this->_aVars['aData']['vendor_id']?>">
        <?php endif?>
      </div>
    </div>
    <?php if(Core::getParam('core.main_server') == 'cms.' && 1 == 2):?>
    <div class="none vendor-list panel">
        <div class="title">
            <div class="cl">
                Danh sách các nhà cung cấp
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <?php foreach($danh_sach_nha_cc as $sKey => $Tmp):?>
                <div class="cl vendor-item">
                 <a href="/user/permission/?id=<?= $id?>&vendor_id=<?= $Tmp[0]?>">
                    <img src="<?= $Tmp['image_path']?>" alt="logo" title="<?= $Tmp[1]?>" height="42" width="42">
                 </a>
                </div>
            <?php endforeach?>
            <div class="clear"></div>
        </div>
    </div>
    <? endif; ?>
    <div class="tabb mxClrAft">
      <div class="tab atv">
        Danh mục
      </div>
      <div class="tab">
        Mở rộng
      </div>
    </div>
    <section data-id="danhmuc">
      <div class="list">
        <div class="title_list mxClrAft">
          <div class="cl1">
            <div class="ck <? if ($iIsSpecial):?> atv<? endif?>" onclick="return de_tai_chon_tat_ca();" id="js-select-permission-all"></div>
          </div>
          <div class="cl2 cl">
            <div class="t">
              Tên đề tài
            </div>
          </div>
          <?php if($this->_aVars['aData']['vendor_id'] < 1):?>
          <div class="cl3 cl">
            <div class="top">
              <div class="t">
                Đề tài
              </div>
            </div>
            <div class="bt mxClrAft">
              <div class="cls cl">
                <div class="t">
                  Tạo
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('create_category')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Sửa
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('edit_category')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Xóa
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('delete_category')"></div>
              </div>
            </div>
          </div>
          <?php endif?>
          <div class="cl4 cl">
            <div class="top">
              <div class="t">
                Bài viết
              </div>
            </div>
            <div class="bt mxClrAft">
              <div class="cls cl">
                <div class="t">
                  Tạo
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('create_article')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Sửa
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('edit_article')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Sửa khác
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('edit_other_article')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Xóa
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('delete_article')"></div>
              </div>
            </div>
          </div>
          <div class="cl5 cl">
            <div class="top">
              <div class="t">
                Lời bình
              </div>
            </div>
            <div class="bt mxClrAft">
              <div class="cls cl">
                <div class="t">
                  Gửi
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('create_comment')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Sửa
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('edit_comment')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Xóa
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('delete_comment')"></div>
              </div>
            </div>
          </div>
          <div class="cl6 cl">
            <div class="top">
              <div class="t">
                Kiểm lời bình
              </div>
            </div>
            <div class="bt mxClrAft">
              <div class="cls cl">
                <div class="t">
                  Kiểm
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('approve_comment')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Bị kiểm
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('was_approved_comment')"></div>
              </div>
            </div>
          </div>
          <div class="cl7 cl">
            <div class="top">
              <div class="t">
                Kiểm bài viết
              </div>
            </div>
            <div class="bt mxClrAft">
              <div class="cls cl">
                <div class="t">
                  Kiểm
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('approve_article')"></div>
              </div>
              <div class="cls cl">
                <div class="t">
                  Bị kiểm
                </div>
                <div class="ck" onclick="return de_tai_chon_tat_ca('was_approved_article')"></div>
              </div>
            </div>
          </div>
        </div>
<?php
function Menu_gui_bai( $iVendorId,$parentid,$menu,$res = '',$sep = ''){
        foreach($menu as $v){
            if($v[2] == $parentid){
                if ($iVendorId) {
                    $re = '<div class="r mxClrAft"><div class="cl1 cl">
            <div class="ck de_tai_doi_tuong" id="hinh_anh_'.$v[0].'" class="de_tai_doi_tuong" onclick="chon_doi_tuong('.$v[0].')"></div>
          </div>
          <div class="cl2 cl">
            <div class="t">
              '.$sep.$v[1].'
            </div>
          </div>
          <div class="cls cl">
            <div class="ck create_article cl_khu_vuc" id="create_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_article cl_khu_vuc" id="edit_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_other_article cl_khu_vuc" id="edit_other_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_other_article cl_khu_vuc" id="edit_other_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck create_comment cl_khu_vuc" id="create_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_comment cl_khu_vuc" id="edit_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck delete_comment cl_khu_vuc" id="delete_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck approve_comment cl_khu_vuc" id="approve_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck was_approved_comment cl_khu_vuc" id="was_approved_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck approve_article cl_khu_vuc" id="approve_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck was_approved_article cl_khu_vuc" id="was_approved_article_'.$v[0].'"></div>
          </div></div>';
                }
                else {
                    $re = '<div class="r mxClrAft"><div class="cl1 cl">
            <div class="ck de_tai_doi_tuong" id="hinh_anh_'.$v[0].'" class="de_tai_doi_tuong" onclick="chon_doi_tuong('.$v[0].')"></div>
          </div>
          <div class="cl2 cl">
            <div class="t">
              '.$sep.$v[1].'
            </div>
          </div>
           <div class="cls cl">
            <div class="ck create_category cl_khu_vuc" id="create_category_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_category cl_khu_vuc" id="edit_category_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck delete_category cl_khu_vuc" id="delete_category_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck create_article cl_khu_vuc" id="create_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_article cl_khu_vuc" id="edit_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_other_article cl_khu_vuc" id="edit_other_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck delete_article cl_khu_vuc" id="delete_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck create_comment cl_khu_vuc" id="create_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck edit_comment cl_khu_vuc" id="edit_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck delete_comment cl_khu_vuc" id="delete_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck approve_comment cl_khu_vuc" id="approve_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck was_approved_comment cl_khu_vuc" id="was_approved_comment_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck approve_article cl_khu_vuc" id="approve_article_'.$v[0].'"></div>
          </div>
          <div class="cls cl">
            <div class="ck was_approved_article cl_khu_vuc" id="was_approved_article_'.$v[0].'"></div>
          </div></div>';
                }
                $res.=Menu_gui_bai($iVendorId, $v[0],$menu,$re,$sep."---");
            }
        }
        return $res;
    } 
    $sMenu = Menu_gui_bai($this->_aVars['aData']['vendor_id'],-1,$this->_aVars['aData']['category_list']);
    echo $sMenu;
?>
      </div>
    </section>
    <section data-id="morong" class="morong mxClrAft">
        <div class="row30">
            <div class="r mxClrAft">
                <div class="t mgright20 txt-blue txt-bold">
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
                        <md-switch class="sw js-child-per <?if(isset($this->_aVars['aData']['extend'][$sVal]) && $this->_aVars['aData']['extend'][$sVal] > 0):?> md-checked <? endif?>" data-value="<?= $sVal?>" data-id="<?= $iCnt?>" data-field="<?= $sFeild?>"></md-switch>
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
    
    <input type="hidden" name="create_category" id="create_category" value="" />
    <input type="hidden" name="edit_category" id="edit_category" value="" />
    <input type="hidden" name="delete_category" id="delete_category" value="" />
    <input type="hidden" name="create_article" id="create_article" value="" />
    <input type="hidden" name="edit_article" id="edit_article" value="" />
    <input type="hidden" name="edit_other_article" id="edit_other_article" value="" />
    <input type="hidden" name="delete_article" id="delete_article" value="" />
    <input type="hidden" name="create_comment" id="create_comment" value="" />
    <input type="hidden" name="edit_comment" id="edit_comment" value="" />
    <input type="hidden" name="delete_comment" id="delete_comment" value="" />
    <input type="hidden" name="approve_comment" id="approve_comment" value="" />
    <input type="hidden" name="was_approved_comment" id="was_approved_comment" value="" />
    <input type="hidden" name="approve_article" id="approve_article" value="" />
    <input type="hidden" name="was_approved_article" id="was_approved_article" value="" />
    </form>
  </section>
<script type="text/javascript">
var vendor_id = <?= isset($this->_aVars['aData']['vendor_id']) ? $this->_aVars['aData']['vendor_id']: 0;?>;
var a_mang_gia_tri = <?= json_encode($this->_aVars['aData']['category'])?>;
var a_mang_khu_vuc = <?= json_encode($this->_aVars['aData']['category_field'])?>;
var status = <?= isset($status) ? $status: 0;?>;
</script>
