<section class="info-user-table">
    <div class="f1">
        <a href="#" class="f2">
            <?php if(isset($this->_aVars['aProfile']['profile_image']) && !empty($this->_aVars['aProfile']['profile_image']) ): ?>
            <img src="<?= $this->_aVars['aProfile']['profile_image'] ?>"  alt="<?= $this->_aVars['aProfile']['fullname']?>">
            <?php else:?>
            <img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/4038/images/demo/mini-ava.jpg" alt="Avatar mặc định">
            <?php endif?>
        </a>
        <div class="f3">
            <a class="f4" href="#">
                <?= $this->_aVars['aProfile']['fullname']?>
            </a>
            <div id="friend">
                <?php if ($this->_aVars['aProfile']['follow'] < 0):?>
                <a class="f5" href="#" onclick="processFollow('<?= $this->_aVars['aProfile']['id']?>', 'add'); return false;" >
                    <span class="fa fa-user-plus"></span>
                    Kết bạn
                </a>
                <?php elseif ($this->_aVars['aProfile']['follow'] > 0):?>
                <a class="f5" href="#"  onclick="processFollow('<?= $this->_aVars['aProfile']['follow']?>', 'delete'); return false;">
                    <span class="fa fa-user-times"></span>
                    Bỏ kết bạn
                </a>
                <?php endif?>
            </div>
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