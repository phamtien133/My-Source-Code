<section class="info-acc">
    <div class="dc">
        Thông tin tài khoản
    </div> <!-- dc -->
    <div class="dd">
        <div class="dz">
            <div class="dg">
                <div class="dh">
                    <div class="di">
                        Thông tin liên hệ
                    </div>
                    <div class="dj is-edit" data-id="ed01">
                        Chỉnh sửa
                    </div>
                </div> <!-- dh -->
                <div class="dk is-edited" data-id="ed01">
                    <div class="dr">
                        <div class="dl">
                            Số điện thoại
                        </div>
                        <input type="text" class="dm" value="<?= $this->_aVars['aProfile']['phone_number']?>">
                    </div> <!-- dr -->
                    <div class="dr">
                        <div class="dl">
                            Tên đăng nhập:
                        </div>
                        <input type="text" class="dm" value="<?= $this->_aVars['aProfile']['username']?>">
                    </div> <!-- dr -->
                    <div class="dr">
                        <div class="dl">
                            Email:
                        </div>
                        <input type="text" class="dm" value="<?= $this->_aVars['aProfile']['email']?>">
                    </div> <!-- dr -->
                    <div class="dn">
                        Thay đổi mật khẩu
                    </div> <!-- dn -->
                </div> <!-- dk -->
            </div> <!-- dg -->
            <div class="dg">
                <div class="dh">
                    <div class="di">
                        Thông tin liên hệ
                    </div>
                    <div class="dj is-edit" data-id="ed02">
                        Chỉnh sửa
                    </div>
                </div> <!-- dh -->
                <div class="dk is-edited" data-id="ed02">
                    <?php if (isset($this->_aVars['aProfile']['contact_info'])):?>
                    <?php $iCount = 0;?>
                    <?php foreach($this->_aVars['aProfile']['contact_info'] as $aContact):?>
                    <?php $iCount++;  ?>
                    <div class="dq">
                        <div class="dl">
                            Địa chỉ <?= $iCount?>
                        </div> <!-- dl -->
                        <textarea name="" id="" cols="30" rows="10" class="do ats">
                            <?= $aContact['address']?>
                        </textarea>
                    </div> <!-- dq -->
                    <?php endforeach?>
                    <?php endif?>
                    <div class="dn">
                        Thêm địa chỉ khác
                    </div>
                </div> <!-- dk -->
            </div> <!-- dg -->
            <div class="dg">
                <div class="dh">
                    <div class="di">
                        Thông báo từ disieuthi.vn
                    </div>
                    <div class="dj">
                        Chỉnh sửa
                    </div>
                </div> <!-- dh -->
                <div class="dk">
                    <?php if(isset($this->_aVars['aNotice']['data']) && !empty($this->_aVars['aNotice']['data'])):?>
                    <div class="dw is-mn">
                    <?php foreach($this->_aVars['aNotice']['data'] as $aNotice):?>
                        <div class="drw">
                            Thông báo: Giỏ hàng của bạn vừa được giao lúc 12h 25/05
                        </div>
                    <?php endforeach?>
                    </div>
                    <?php else:?>
                    <div class="empty-content">
                        Hiện tại bạn không đăng ký nhận bất kỳ tin tức nào tại disieuthi.vn
                    </div>
                    <?php endif?>
                </div> <!-- dk -->
            </div> <!-- dg -->
        </div> <!-- de -->
        <div class="dy">
            <div class="dg">
                <div class="dh">
                    <div class="di">
                        Hoạt động bạn bè
                    </div>
                    <div class="dj">
                        Xem tất cả
                    </div>
                </div> <!-- dh -->
                <div class="dk">
                    <div class="ds is-mn">
                    <?php if (isset($this->_aVars['aActivityFriends']['data']) && !empty($this->_aVars['aActivityFriends']['data'])): ?>
                        <?php foreach ($this->_aVars['aActivityFriends']['data'] as $aActivity): ?>
                        <div class="dtx">
                            <a href="<?= $this->_aVars['sDomainName']?>" class="dt1">
                                <img src="<?= $aActivity['user_info']['profile_image']?>" alt="<?= $aActivity['user_info']['fullname']?>">
                            </a>
                            <div class="dt2">
                                <a href="#">
                                    <?= $aActivity['user_info']['fullname']?>
                                </a>
                                <span>
                                    <?= $aActivity['content']?>
                                </span>
                            </div>
                            <div class="dt3">
                                Điểm uy tín: <span>8/10</span>
                            </div>
                        </div> <!-- dt -->
                        <? endforeach?>
                    <?php else: ?>
                        <div class="empty-content">
                            Hiện chưa có hoạt động nào.
                        </div>
                    <?php endif ?>
                    </div>
                </div> <!-- dk -->
            </div> <!-- dg -->
        </div> <!-- df -->
    </div> <!-- dd -->
</section> <!-- info-acc -->
