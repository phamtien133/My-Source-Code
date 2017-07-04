<section class="adminlg">
  <div class="login_form rf">
      <div class="qc1">
          <div class="tieu_de_dang_nhap tt">
              Đăng nhập
          </div>
      </div>
      <div class="err_list_login hide">
        <div class="content_err">
        </div>
      <div class="close_err_login"></div>
      </div>
      <form method="post" action="" id="jForm" class="dang_nhap form_user qc2">
          <table class="">
              <tbody>
                  <tr>
                      <td style="padding: 0" colspan="2">
                          <div id="tai_khoan_dang_nhap">
                              <div class="login_account">
                                  <div class="row_login_acc">
                                      <div class="t2">
                                          SĐT/Email
                                      </div>
                                      <input type="text" autocomplete="on" class="inputbox qti input_log" id="ten_truy_cap" name="email" placeholder="SĐT/Email">
                                  </div>
                                  <div class="row_login_acc">
                                      <div class="t2">
                                          Mật khẩu
                                      </div>
                                      <input type="password" class="inputbox qti input_log" value="" id="mat_khau" name="passwd" placeholder="Mật khẩu">
                                  </div>
                                  <div class="bbt">
                                    <button type="submit" class="button_submit_log bt2" id="js-login">
                                        Đăng nhập
                                    </button>
                                  </div>
                              </div>
                          </div>
                      </td>
                  </tr>
              </tbody>
          </table>
          <input type="hidden" value="dang_nhap" name="type">
          <input type="hidden" value="" name="refer">
      </form>
  </div>
</section>