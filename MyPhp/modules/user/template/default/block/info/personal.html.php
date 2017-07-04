<?php if ($this->_aVars['bIsAjax']):?>
<section class="info-user-table">
    <div class="f1">
        <a href="#" class="f2">
            <?php if(isset($this->_aVars['aProfile']['profile_image']) && !empty($this->_aVars['aProfile']['profile_image']) ): ?>
            <img src=" <?= $this->_aVars['aProfile']['profile_image'] ?>"  alt="<?= $this->_aVars['aProfile']['fullname']?>">
            <?php else:?>
            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/mini-ava.jpg" alt="Avatar mặc định">
            <?php endif?>
        </a>
        <div class="f3">
            <a class="f4" href="#">
                <?= $this->_aVars['aProfile']['fullname']?>
            </a>
            <a class="f5" href="#" <?php if (!$this->_aVars['aProfile']['follow']):?>style="display: none;" <?php endif?>>
                <span class="fa fa-user-plus"></span>
                Kết bạn
            </a>
        </div> <!-- f3 -->
    </div> <!-- f1 -->
    <div class="f6">
        <a href="#" class="f7">
            <span class="fa fa-user"></span>
            <?= $this->_aVars['aProfile']['total_follow']?> bạn
        </a>
        <a href="#" class="f7">
            <span class="fa fa-send"></span>
            follow
        </a>
        <a href="" class="f7">
            <span class="fa fa-heart"></span>
            Thích
        </a>
    </div> <!-- f6 -->
    <div class="f8">
        <a href="#" class="f9">
            Thông tin chung tài khoản
        </a>
        <a href="#" class="f9">
            Thông tin tài khoản
        </a>
        <a href="#" class="f9">
            Thông báo của tôi
        </a>
        <a href="#" class="f9">
            Đơn hàng của tôi
        </a>
        <a href="#" class="f9">
            Sản phẩm yêu thích
        </a>
        <a href="#" class="f9">
            Sản phẩm đã lưu
        </a>
        <a href="#" class="f9">
            Giỏ hàng yêu thích
        </a>
        <a href="#" class="f9">
            Giỏ hàng đã lưu
        </a>
    </div> <!-- f8 -->
</section> <!-- info-user-table -->
<?php else:?>
<section class="info-user-table">
    <div class="f1">
        <a href="#" class="f2">
            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/mini-ava.jpg" alt="abc">
        </a>
        <div class="f3">
            <a class="f4" href="#">
                Họ tên
            </a>
            <a class="f5" href="#">
                <span class="fa fa-user-plus"></span>
                Kết bạn
            </a>
        </div> <!-- f3 -->
    </div> <!-- f1 -->
    <div class="f6">
        <a href="#" class="f7">
            <span class="fa fa-user"></span>
            Bạn
        </a>
        <a href="#" class="f7">
            <span class="fa fa-send"></span>
            Follow
        </a>
        <a href="" class="f7">
            <span class="fa fa-heart"></span>
            Thích
        </a>
    </div> <!-- f6 -->
    <div class="f8">
        <a href="#" class="f9">
            Thông tin chung tài khoản
        </a>
        <a href="#" class="f9">
            Thông tin tài khoản
        </a>
        <a href="#" class="f9">
            Thông báo của tôi
        </a>
        <a href="#" class="f9">
            Đơn hàng của tôi
        </a>
        <a href="#" class="f9">
            Sản phẩm yêu thích
        </a>
        <a href="#" class="f9">
            Sản phẩm đã lưu
        </a>
        <a href="#" class="f9">
            Giỏ hàng yêu thích
        </a>
        <a href="#" class="f9">
            Giỏ hàng đã lưu
        </a>
    </div> <!-- f8 -->
</section> <!-- info-user-table -->
<?php endif?>
