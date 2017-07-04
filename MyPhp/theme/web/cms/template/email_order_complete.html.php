<meta charset="UTF-8">
<div style="background: #e9e9e9; width: 100%;padding: 50px 0;font-family: Helvetica; margin: 0">
    <div style="width: 700px;background-color: #fff;display: block;margin: 0 auto;padding-top: 1px;">
        <div style="width: 698px;height: 90px;display: block;margin: 0 1px;background-color: #00a651;">
            <div style="float: left; padding-top: 23px; padding-left: 30px;">
                <a href="<?= Core::getDomainName()?>" style="display: block">
                    <img src="https://img.<?= Core::getDomainName()?>/logo.png" alt="logo" style="display:block;width: 200px;height: 35px;">
                </a>
                <? if (Core::getDomainName() == 'disieuthi.vn'):?>
                <div style="display: block;margin-left: 173px;padding-top: 8px;height: 18px;">
                    <img style="display: block;margin-right: 5px;height: 16px;float: left;padding-top: 2px;" src="https://img.disieuthi.vn/ma.png">
                    <div style="line-height: 18px;font-size: 12px;color:#fff;display: block;float: left;">Hà Nội</div>
                    <div style="clear: both"></div>
                </div>
                <? endif?>
            </div>
            <? if (Core::getDomainName() == 'disieuthi.vn'):?>
            <div style="float: right;padding-top: 24px;">
                <img src="https://img.disieuthi.vn/lg1.png" style="float: left;margin-right: 25px;display: block;height: 40px;">
                <img src="https://img.disieuthi.vn/lg2.png" style="float: left;margin-right: 25px;display: block;height: 40px;">
                <img src="https://img.disieuthi.vn/lg3.png" style="float: left;display: block;height: 40px;margin-right: 10px;">
                <div style="clear: both"></div>
            </div>
            <? endif?>
            <div style="clear: both"></div>
        </div>

        <div style="padding: 30px;">
            <div>
                <div style="font-size: 18px;font-weight: bold;display: block;padding-bottom: 15px;">Kính gửi quý khách: <?= $this->_aVars['sUserName']?></div>
                <a href="<?= Core::getDomainName()?>" style="line-height: 20px;font-size: 13px;display: inline-block;color: #000;text-decoration: none;"><?= Core::getDomainName()?></a> 
                <div style="line-height: 20px;font-size: 13px;display: inline-block;">xin thông báo đã hoàn thành đơn hàng #<?= $this->_aVars['sOrderCode']?> của quý khách:</div>
            </div>
            <div>
                <div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">Khách hàng:</div>
                        <a href="javascript:void()" style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;font-weight: bold;"><?= $this->_aVars['sUserName']?></a>
                        <div style="clear: both"></div>
                    </div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">Điện thoại:</div>
                        <a href="javascript:void()" style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;font-weight: bold;"><?= $this->_aVars['iPhoneNumber']?></a>
                        <div style="clear: both"></div>
                    </div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">Địa chỉ:</div>
                        <a href="javascript:void()" style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;font-weight: bold;"><?= $this->_aVars['sAddress']?></a>
                        <div style="clear: both"></div>
                    </div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">Ngày đặt hàng:</div>
                        <a href="javascript:void()" style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;font-weight: bold;"><?= $this->_aVars['sCreateTime']?></a>
                        <div style="clear: both"></div>
                    </div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">Ngày giao hàng:</div>
                        <a href="javascript:void()" style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;font-weight: bold;"><?= $this->_aVars['sDeliveryTime']?></a>
                        <div style="clear: both"></div>
                    </div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">H.T thanh toán</div>
                        <a href="javascript:void()" style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;font-weight: bold;">#<?= $this->_aVars['sPayment']?></a>
                        <div style="clear: both"></div>
                    </div>
                    <div style="display: block;padding-top: 15px;">
                        <div style="display: block;float: left;width: 144px;font-size: 13px;font-weight: bold;">Tổng tiền</div>
                        <div style="color: #00a651;float: left;font-size: 13px;display: block;text-decoration: none;"><?= $this->_aVars['sTotalAmount']?></div>
                        <div style="clear: both"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="padding: 30px">
            <div style="border-top: 1px solid #E1E1E1"> 
                <div style="display: block; padding-top: 20px; width: 635px;">
                    Quý khách có thể truy cập <a href="<?= Core::getDomainName()?>" style="line-height: 20px;display: inline-block;color: #000;text-decoration: none;"><?= Core::getDomainName();?></a> để tra cứu thông tin chi tiết đơn hàng.
                </div>
                <div style="display: block; padding-top: 5px; width: 635px;">
                    <div style="display: inline;font-size: 13px;line-height: 20px;">Mọi thắc mắc và góp ý vui lòng liên hệ với <?= Core::getDomainName()?> qua email:</div>
                    <? if (Core::getDomainName() == 'disieuthi.vn'):?>
                    <a href="javascript:void()" style="display: inline;font-size: 13px;line-height: 20px;color: #00a651;">hotro@disieuthi.vn</a>
                    <div style="display: inline;font-size: 13px;line-height: 20px;">hoặc số điện thoại</div>
                    <a href="callto:0966992221" style="display: inline;font-size: 13px;line-height: 20px;color: #00a651;">19002075</a>
                    <div style="display: inline;font-size: 13px;line-height: 20px;"> (từ 8h-19h tất cả các ngày trong tuần).</div>
                    <? else:?>
                    <a href="javascript:void()" style="display: inline;font-size: 13px;line-height: 20px;color: #00a651;">support@<?= Core::getDomainName();?></a>
                    <? endif?>
                </div>
                <div style="display: block; padding-top: 20px;">
                    <div style="font-size: 13px; line-height: 20px;">Cảm ơn Quý Khách đã sử dụng dịch vụ của <?= Core::getDomainName();?>!</div>
                    <div style="font-size: 13px; line-height: 20px;">Trân trọng!</div>
                    <div style="font-size: 13px; line-height: 20px; font-weight: bold"><?= Core::getDomainName();?></div>
                </div>
            </div>
        </div>
    </div>
</div>