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
  <section class="dskh wrap">
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
    <div class="ri">
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
          <div class="cl1">
            <div class="ck"></div>
          </div>
          <div class="cl2">
            <div class="ttcl">
              Khách hàng
            </div>
            <div class="i1 bg"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="cl3 atv">
            <div class="ttcl">
              Mã số KH
            </div>
            <div class="i2 bg"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="cl4">
            <div class="ttcl">
              Địa điểm
            </div>
            <div class="i1 bg"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="cl5">
            <div class="ttcl">
              Loại TK
            </div>
            <div class="i1 bg"></div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="cl6">
            <div class="sb">
              <div class="ttcl">
                Hoạt động
              </div>
              <div class="i1 bg"></div>
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
          <div class="clear"></div>
        </div>
        <?php   for ($i = 1; $i < 5;$i++): ?>
          <div class="rls">
            <div class="cl1">
              <div class="av">
                <img src="style/images/temp/ava.jpg" alt="">
                <div class="ck"></div>
              </div>
            </div>
            <div class="cl2">
              <div class="tcl or">
                Phạm Diễm
              </div>
            </div>
            <div class="cl3">
              <div class="tcl">
                ABC-123
              </div>
            </div>
            <div class="cl4">
              <div class="tcl or">
                TP. Hồ Chí Minh
              </div>
            </div>
            <div class="cl5">
              <div class="tcl or">
                Hộp thư
              </div>
            </div>
            <div class="cl6">
              <div class="sb">
                <div class="tcl">
                  01/04/2015
                </div>
              </div>
              <div class="hb">
                <div class="ed bg"></div>
                <div class="ad bg"></div>
                <div class="mn bg"></div>
                <div class="clear"></div>
              </div>
            </div>
            <div class="clear"></div>
          </div>
        <?php endfor; ?>
        <div class="add bg"></div>
      </div>
    </div>
    <div class="clear"></div>
  </section>