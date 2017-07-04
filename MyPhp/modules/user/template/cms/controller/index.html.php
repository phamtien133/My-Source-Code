<?php
    $aMapStatusIcon =array(
        0 => 'fa-check',
        1 => 'fa-check',
        3 => 'fa-close'
    );
?>
<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<?php
if($status==1)
{
?>

<section class="idkh smwrap" style="display: none">
    <div class="smcl">
      <?php
        $author_word = array("a", "b", "c", "d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
        foreach ($author_word as $value) :
      ?>
      <section class="nav">
        <md-subheader class="author_word"><?php echo $value; ?></md-subheader>
        <md-list class="author_list">
          <md-list-item class="author">
            <div class="author_name">
              An Yên
            </div>
          </md-list-item>
          <md-list-item class="author">
            <div class="author_name">
              An Dĩ Mạch
            </div>
          </md-list-item>
          <md-list-item class="author">
            <div class="author_name last">
              An Lạc Phong
            </div>
          </md-list-item>
        </md-list>
      </section>
      <?php endforeach; ?>
      <div class="scroll_word">
        <?php
          $word = array("a", "b", "c", "d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","#");
          foreach ($word as $value) :
        ?>
        <md-button class="word">
          <?php echo $value; ?>
        </md-button>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="bicl">
      <div class="le">
        <div class="idb">
          <div class="av bg">
            <img src="" alt="">
          </div>
          <div class="dt">
            <input type="text" placeholder="Họ và tên" class="n">
            <input type="text" placeholder="Công ty" class="n">
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
        </div>
        <div class="dtb">
          <div class="b">
            <div class="bsh">
              <md-button class="ad bg"></md-button>
              <div class="tad">
                Điện thoại
              </div>
              <div class="clear"></div>
            </div>
            <div class="bhi">
              <div class="blt">
                Điện thoại
              </div>
              <input type="text" placeholder="Nhập số điện thoại" class="n">
            </div>
          </div>
          <div class="b">
            <div class="bsh">
              <md-button class="ad bg"></md-button>
              <div class="tad">
                Hộp thư
              </div>
              <div class="clear"></div>
            </div>
            <div class="bhi">
              <div class="blt">
                Hộp thư
              </div>
              <input type="text" placeholder="Nhập email" class="n">
            </div>
          </div>
          <div class="bs">
            <div class="blt">
              Địa chỉ
            </div>
            <div class="r">
              <input type="text" placeholder="Số nhà" class="sm t">
              <input type="text" placeholder="Tên đường" class="bi t">
              <div class="clear"></div>
            </div>
            <div class="r">
              <input type="text" placeholder="Phường/Xã" class="t">
            </div>
            <div class="r">
              <input type="text" placeholder="Quận/ Huyện" class="t">
            </div>
            <div class="r">
              <input type="text" placeholder="Tỉnh/ Thành Phố" class="t">
            </div>
            <div class="r">
              <input type="text" placeholder="Quốc gia" class="t">
            </div>
          </div>
          <div class="bsl">
            <div class="sbl">
              <div class="tsl">
                Nhóm KH
              </div>
              <div class="slb">
                <div class="slr">
                  Mặc định
                </div>
                <div class="slr">
                  VIP
                </div>
              </div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
        <md-button class="ed">
          Chỉnh sửa
        </md-button>
      </div>
      <div class="ri">
        <div class="ch">
          <div class="tt">
            <div class="ttt">
              Mua hàng
            </div>
            <div class="ttt">
              Xem hàng
            </div>
            <div class="ttt">
              Hành vi
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <div class="b">
          <div class="ttb">
            cấp độ thành viên
          </div>
          <div class="nb">
            20.000 Điểm
          </div>
          <div class="tb">
            thành viên cấp 1
          </div>
          <div class="bor">
            <div class="bo"></div>
          </div>
        </div>
        <div class="b">
          <div class="ttb">
            điểm thưởng
          </div>
          <div class="nb">
            100 điểm
          </div>
          <div class="bor"></div>
        </div>
        <div class="b">
          <div class="ttb">
            ví điện tử
          </div>
          <div class="nb">
            1.000.000.000 VNĐ
          </div>
          <div class="bor"></div>
        </div>
        <div class="b">
          <div class="ttb">
            khuyến mãi đã áp dụng
          </div>
          <div class="nb">
            Giảm 10%
          </div>
          <div class="bor"></div>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </section>
  <section class="container dskh wrap">
  <?php if(Core::getParam('core.main_server') == 'admin1.'):?>
    <div class="le">
      <div class="r">
        <div class="tr">
          Tất cả
        </div>
        <div class="ir bg"></div>
        <div class="clear"></div>
        <div class="bor"></div>
      </div>
      <div class="r">
        <div class="tr">
          Gần đây
        </div>
        <div class="ir bg"></div>
        <div class="clear"></div>
        <div class="bor"></div>
      </div>
      <div class="r">
        <div class="tr">
          Nổi bật
        </div>
        <div class="ir bg"></div>
        <div class="clear"></div>
        <div class="bor"></div>
      </div>
      <div class="r sh">
        <div class="tr">
          Nhóm
        </div>
        <div class="ir bg"></div>
        <div class="clear"></div>
        <div class="bor"></div>
        <div class="onr">
          <div class="r atv">
            <div class="li bg"></div>
            <div class="tr">
              Nhóm con
            </div>
            <div class="clear"></div>
            <div class="bor"></div>
          </div>
          <div class="r">
            <div class="li bg"></div>
            <div class="tr">
              Nhóm con
            </div>
            <div class="clear"></div>
            <div class="bor"></div>
          </div>
          <div class="r">
            <div class="tr b">
              Tạo nhóm mới
            </div>
          </div>
        </div>
      </div>
      <div class="r sh">
        <div class="tr">
          Mở rộng
        </div>
        <div class="ir bg"></div>
        <div class="clear"></div>
        <div class="bor"></div>
          <div class="inr">
            <div class="r">
              <div class="tr">
                Cài đặt quyền hạn
              </div>
            </div>
            <div class="r">
              <div class="tr">
                Nhập danh sách KH
              </div>
            </div>
            <div class="r">
              <div class="tr">
                Xuất danh sách KH
              </div>
            </div>
          </div>
      </div>
    </div>
  <?php endif?>
    <div class="ri">
        <section class="overview-statistic-bar statistic-bar" style="margin: 0 0 10px;">
          <form method="GET" id="frm" action="#">
            <input type="hidden" name="type" value="<?= $sType?>" />
            <div class="rb right">
                <div class="ctb left mxClrAft">
                        <div class="tt left">
                            <?= Core::getPhrase('language_tu-khoa')?> :
                        </div>
                        <input type="text" name="q" value="<?= $tu_khoa?>" id="tu_khoa" class="product-srch-input left tt tt1" placeholder="Nội dung tìm kiếm">
                    </div>
                <div class="ctb left mxClrAft product-srch-bt">
                    <button class="btn gr button-blue">Tìm</button>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
          </form>
      </section>
      <div class="bpop" style="display: none">
        <div class="popad">
          <form action="" class="ad">
            <div class="lb">
              Họ tên *
            </div>
            <input type="text" placeholder="Điền thông tin" class="ip">
          </form>
          <form action="" class="ad">
            <div class="lb">
              Công ty
            </div>
            <input type="text" placeholder="Điền thông tin" class="ip">
          </form>
          <form action="" class="ad">
            <div class="lb">
              Điện thoại
            </div>
            <input type="text" placeholder="Điền thông tin" class="ip">
          </form>
          <form action="" class="ad">
            <div class="lb">
              Hộp thư *
            </div>
            <input type="text" placeholder="Điền thông tin" class="ip">
          </form>
          <form action="" class="ad">
            <div class="lb">
              Tài khoản *
            </div>
            <input type="text" placeholder="Điền thông tin" class="ip">
          </form>
          <form action="" class="ad">
            <div class="lb">
              Mật khẩu *
            </div>
            <input type="password" placeholder="Điền thông tin" class="ip">
          </form>
          <form action="" class="ad">
            <div class="lb">
              Nhóm thành viên
            </div>
            <div class="ip">
              <div class="ic bg"></div>
              <div class="g">
                Nhóm 1,
              </div>
              <div class="gip">
                <input type="text" placeholder="Thêm vào nhóm" class="iip">
                <div class="bip">
                  <div class="r">
                    <div class="c"></div>
                    <div class="t">
                      Nhóm 1
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="r">
                    <div class="c"></div>
                    <div class="t">
                      Nhóm 1
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="r">
                    <div class="c"></div>
                    <div class="t">
                      Nhóm 1
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="adr">
                    Tạo nhóm mới
                  </div>
                </div>
              </div>
              <div class="clear"></div>
            </div>
          </form>
          <div class="ctrl">
            <div class="bt w">
              TẠO
            </div>
            <div class="bt">
              hủy
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
      <div class="rg">
        <div class="left">
          <div class="gi bg"></div>
          <div class="tg">
            Nhóm con (6)
          </div>
          <div class="clear"></div>
        </div>
        <div class="right">
          <div class="ed bg"></div>
          <div class="mn bg"></div>
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="lst">
        <div class="ttls">
          <div class="col-md-2 js-sort-by" data-sort=0>
            <div class="ttcl">
              Tài khoản
            </div>
            <div class="<?php if(!isset($sap_xep) ||$sap_xep == 0):?>i3 <?php elseif ($sap_xep == 1):?>i2 <?php endif?> bg js-icon-sort"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <?php if(Core::getParam('core.main_server') == 'admin1.'):?>
          <div class="cl3 atv js-sort-by" data-sort=1>
            <div class="ttcl">
              Mã số KH
            </div>
            <div class="i2 bg js-icon-sort"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <?php endif?>
          <div class="col-md-3 js-sort-by" data-sort=2>
            <div class="ttcl">
              Hộp thư
            </div>
            <div class="<?php if(isset($sap_xep) && $sap_xep == 2):?>i3 <?php elseif (isset($sap_xep) && $sap_xep == 3):?>i2 <?php endif?> bg js-icon-sort"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="col-md-2 js-sort-by" data-sort=4>
            <div class="ttcl">
              Số Điện Thoại
            </div>
            <div class="<?php if(isset($sap_xep) && $sap_xep == 4):?>i3 <?php elseif (isset($sap_xep) && $sap_xep == 5):?>i2 <?php endif?> bg js-icon-sort"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="col-md-2 js-sort-by" data-sort=6>
            <div class="sb">
              <div class="ttcl">
                Truy cập gần nhất
              </div>
              <div class="<?php if(isset($sap_xep) && $sap_xep == 6):?>i3 <?php elseif (isset($sap_xep) && $sap_xep == 7):?>i2 <?php endif?> bg js-icon-sort"></div>
              <div class="clear"></div>
              <div class="hv"></div>
            </div>
            <div class="hb">
              <div class="ed bg"></div>
              <div class="ad bg"></div>
              <div class="mn bg"></div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="col-md-3 js-sort-by" data-sort=4>
            <div class="ttcl">
              Trạng thái / Thao tác
            </div>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
        </div>
        <?php if (count($stt) > 0):?>
        <?php   for ($i = 0; $i < count($stt);$i++): ?>
          <div class="line-bottom row50">
            <div class="col-md-2">
               <div class="tcl or <?php if(Core::getParam('core.main_server') == 'cms.'):?> js-view-user <?php endif?>" data-id="<?= $stt[$i]?>">
                <?= $ten[$i]?>
              </div>
            </div>
            <div class="col-md-3">
              <div class="tcl or <?php if(Core::getParam('core.main_server') == 'cms.'):?> js-view-user <?php endif?>" data-id="<?= $stt[$i]?>">
                <?= $hop_thu[$i]?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="tcl or <?php if(Core::getParam('core.main_server') == 'cms.'):?> js-view-user <?php endif?>" data-id="<?= $stt[$i]?>">
                <?= $sdt[$i]?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="sb">
                <div class="tcl">
                  <?= $last_visit[$i]?>
                </div>
              </div>
              <div class="hb">
                <div class="ed bg"></div>
                <div class="ad bg"></div>
                <div class="mn bg"></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="col-md-3 div-action">
                <div class="fleft">
                    <a href="javascript:void(0);" title="<?= $trang_thai_text[$i] ?>" data-status="<?= $trang_thai[$i]?>" data-id="<?= $stt[$i]?>">
                        <span class="fa <?= $aMapStatusIcon[$trang_thai[$i]]?>"></span>
                    </a>
                </div>
                <div class="fleft">
                    <a href="/user/view/?type=member&id=<?= $stt[$i]?>" title="Xem thông tin chi tiết">
                        <span class="fa fa-list"></span>
                    </a>
                </div>
                <div class="fleft">
                    <a href="/user/permission/?type=member&id=<?= $stt[$i]?>" title="Thiết lập phân quyền">
                        <span class="fa fa-lock"></span>
                    </a>
                </div>
                <div class="fleft">
                    <a href="javascript:void(0);" class="js-delete-member" data-id="<?= $stt[$i]?>" title="Xóa thành viên này">
                        <span class="fa fa-trash"></span>
                    </a>
                </div>
            </div>
            <div class="clear"></div>
          </div>
        <?php endfor; ?>
        <?php else:?>
            Không tìm thấy thành viên nào.
        <?php endif?>
        <div class="add bg" id="js-add-pers-user"></div>
        <?= Core::getService('core.tools')->paginate($tong_trang, $trang_hien_tai, $duong_dan_phan_trang.'&page=::PAGE::', $duong_dan_phan_trang, '', '')?>
      </div>
    </div>
    <div class="clear"></div>
  </section>
  <script type="text/javascript">
    var search_path = '<?= $sLinkSearch?>';
    var sort_path = '<?= $sLinkSort?>';
    var type = '<?= $sType?>';
  </script>

<?php
}
else if ($status == 4)
{
?>
<section class="dskh wrap">
<p>
    <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
    <br />
    <? foreach($errors as $error):?>
        <?= $error.'<br/>'?>
    <? endforeach?>
</p>
<p class="buttonarea">
    <button type="button" onclick="window.location='/user/index/?type=<?= $sType?>';"><span class="round"><span>
    <?= Core::getPhrase('language_quan-ly-thanh-vien')?>
    </span></span></button>
</p>
</section>
<?php
}
?>