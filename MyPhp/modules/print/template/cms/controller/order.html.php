<style media="print">
@page {
  size: auto;
  margin: 0;
}
</style>
<div class="print-content">
    <table class="porder-header" style="width: 100%; margin-bottom: 20px">
        <tbody>
            <tr>
                <td width="25%" class="list-vendor">
                    <? if (count($this->_aVars['aContent']['vendor']) > 1): ?>
                        <? foreach ($this->_aVars['aContent']['vendor'] as $aVendor): ?>
                            <span><?= $aVendor['name']?></span>
                        <? endforeach; ?>
                    <? else: ?>
                        <img id="modMain_ctl00_imgSupplier" src="<?= $this->_aVars['aContent']['vendor'][0]['image_path'] ?>" style="height:60px;">
                    <? endif; ?>
                </td>
                <td width="45%" ><p style="text-align: center; font-size: 26px; font-family: Helvetica; font-weight: 200;"><b>Thông tin đơn hàng</b></p></td>
                <td width="30%" class="logo"><img src="http://img.disieuthi.vn/logo_dst.jpg" height="50"><br>
                    Hotline : 0966992221
                    <br>
                    Email : hotro@disieuthi.vn
                </td>
            </tr>
        </tbody>
    </table>
    <table class="porder-info">
        <tbody>
            <tr>
                <td>Mã ĐH:</td>
                <td><span style="color: red;"><?= $this->_aVars['aContent']['general']['code']?></span></td>
                 <td></td>
                 <td></td>
            </tr>
            <tr>
                <td>Ngày giờ đặt:</td>
                <td><?= $this->_aVars['aContent']['general']['time']?></td>
                <td>Ngày giờ giao:</td>
                <td><?= $this->_aVars['aContent']['general']['deliver_from_hour']?> -&gt; <?= $this->_aVars['aContent']['general']['deliver_to_hour']?> -  <?= $this->_aVars['aContent']['general']['deliver_day']?></td>
            </tr>
            <tr>
                <td>Địa chỉ giao:</td>
                <td colspan="3"><?= $this->_aVars['aContent']['general']['customer']['address']?></td>
            </tr>
            <tr>
                <td>Người nhận:</td>
                <td><?= $this->_aVars['aContent']['general']['customer']['full_name']?></td>
                <td>Số ĐT/Email:</td>
                <td><?= $this->_aVars['aContent']['general']['customer']['phone_number']?> / <?= $this->_aVars['aContent']['general']['user_email']?></td>
            </tr>
            <tr>
                <td>H.T.T.Toán:</td>
                <td colspan="3">
                    <span style="color: green;">
                        <? if($this->_aVars['aContent']['general']['payment_gateway'] == 'diem'): ?>
                            Tài khoản ĐST
                        <? elseif($this->_aVars['aContent']['general']['payment_gateway'] == 'cong-thanh-toan'): ?>
                            Cổng thanh toán
                        <? else: ?>
                            Giao hàng nhận tiền
                        <? endif; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Ghi chú:</td>
                <td colspan="3"><?= $this->_aVars['aContent']['general']['note']?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="table porder-list" id="pint" style="width: 100%;">
        <tbody>
            <tr class="header-title">
                <th style="width: 20px;">STT</th>
                <th style="width: 200px;">Sản phẩm</th>
                <th style="width: 100px; text-align: center">Số lượng</th>
                <th style="width: 100px;text-align: center">Khối lượng</th>
                <th style="text-align: right; width: 100px">Giá bán</th>
                <th style="text-align: right;width: 100px;">Thành tiền</th>
            </tr>
            <? if (count($this->_aVars['aContent']['vendor']) > 1): ?>
                <? foreach ($this->_aVars['aContent']['vendor'] as $aVendor): ?>
                    <tr>
                        <td colspan="6" class="title-vendor">
                            Siêu thị <?= $aVendor['name']?>
                        </td>
                    </tr>
                    <? $iCnt = 1; ?>
                    <? foreach ($this->_aVars['aContent']['detail']['vendor_id'] as $iKey => $iVId): ?>
                        <? if ($iVId == $aVendor['id']): ?>
                            <tr class="table-flag-blue">
                                <td style="text-align: center"><?= $iCnt; ?></td>
                                <td><?= $this->_aVars['aContent']['detail']['name'][$iKey] ?></td>
                                <td style="text-align: center"><?= $this->_aVars['aContent']['detail']['quantity'][$iKey] ?></td>
                                <td style="text-align: left"><?= $this->_aVars['aContent']['detail']['weight'][$iKey] ?> <?= $this->_aVars['aContent']['unit'][$this->_aVars['aContent']['detail']['unit_id'][$iKey]]['name'] ?> </td>
                                <td style="text-align: right"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['detail']['unit_price'][$iKey] - $this->_aVars['aContent']['detail']['price_discount'][$iKey]))  ?> đ</td>
                                <td style="text-align: right"><?=  Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['detail']['amount'][$iKey])) ?> đ</td>
                            </tr>
                            <? $iCnt++ ?>
                        <? endif; ?>
                    <? endforeach; ?>
                <? endforeach; ?>
            <? else: ?>
                <? $iCnt = 1; ?>
                <? foreach ($this->_aVars['aContent']['detail']['id'] as $iKey => $iId): ?>
                    <tr class="table-flag-blue">
                        <td style="text-align: center"><?= $iCnt; ?></td>
                        <td><?= $this->_aVars['aContent']['detail']['name'][$iKey] ?></td>
                        <td style="text-align: center"><?= $this->_aVars['aContent']['detail']['quantity'][$iKey] ?></td>
                        <td style="text-align: center"><?= $this->_aVars['aContent']['detail']['weight'][$iKey] ?> <?= $this->_aVars['aContent']['unit'][$this->_aVars['aContent']['detail']['unit_id'][$iKey]]['name'] ?></td>
                        <td style="text-align: right"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['detail']['unit_price'][$iKey] - $this->_aVars['aContent']['detail']['price_discount'][$iKey]))  ?> đ</td>
                        <td style="text-align: right"><?=  Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['detail']['amount'][$iKey])) ?> đ</td>
                    </tr>
                    <? $iCnt++ ?>
                <? endforeach; ?>
            <? endif; ?>
            <tr class="table-flag-blue">
            <tr style="font-weight: bold">
                <td colspan="2"><b>Tổng</b></td>
                <td style="text-align: center; font-weight: bold"><?= $this->_aVars['aContent']['general']['total_product']?></td>
                <td style="text-align: right; font-weight: bold" colspan="3"><?= Core::getService('core.currency')->formatMoney(array('money' => ($this->_aVars['aContent']['general']['total_amount'] - $this->_aVars['aContent']['general']['surcharge'])))?> đ</td>
            </tr>
            <tr style="font-weight: bold">
                <td colspan="5" class="not"><b>Phí dịch vụ</b></td>
                <td style="text-align: right; font-weight: bold"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['surcharge']))?> đ</td>
            </tr>
            <? if ($this->_aVars['aContent']['general']['total_discount'] > 0): ?>
            <tr>
               <td colspan="5" class="not"><b>Voucher</b></td>
               <td style="text-align: right; font-weight: bold"> <span style="color: red">- <?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['total_discount']))?> đ</span></td>
            </tr>
            <? endif; ?>
            <? if ($this->_aVars['aContent']['general']['coin_receive'] > 0): ?>
            <tr>
               <td colspan="5" class="not"><b>Khuyến mãi</b> (<span style="color: red">siêu xu</span>)</td>
               <td style="text-align: right; font-weight: bold"> <span style="color: red">- <?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['total_coin']))?> xu</span></td>
            </tr>
            <? endif; ?>
            <tr style="font-weight: bold">
                <td colspan="5" class="not"><b>Thành tiền</b></td>
                <td style="text-align: right; font-weight: bold"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['total_amount'] - $this->_aVars['aContent']['general']['total_discount'] - $this->_aVars['aContent']['general']['coin_receive']))?> đ</td>
            </tr>
            <? if ($this->_aVars['aContent']['general']['coin_receive'] > 0): ?>
            <tr style="font-weight: bold">
                <td colspan="5" class="not"><b>Số xu trả trước</b></td>
                <td style="text-align: right; font-weight: bold"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['coin_receive']))?> xu</td>
            </tr>
            <? endif; ?>
            <tr style="font-weight: bold">
                <td colspan="5" class="not"><b>Số tiền trả trước</b></td>
                <td style="text-align: right; font-weight: bold"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['money_recieve']))?> đ</td>
            </tr>
            <? if ($this->_aVars['aContent']['general']['total_amount'] >= ($this->_aVars['aContent']['general']['coin_receive'] + $this->_aVars['aContent']['general']['money_recieve'])): ?>
            <tr style="font-weight: bold">
                <td colspan="5" class="not"><b>Số tiền cần thanh toán</b></td>
                <td style="text-align: right; font-weight: bold"><?= Core::getService('core.currency')->formatMoney(array('money' => $this->_aVars['aContent']['general']['total_amount'] - $this->_aVars['aContent']['general']['total_discount'] - $this->_aVars['aContent']['general']['coin_receive'] - $this->_aVars['aContent']['general']['money_recieve']))?> đ</td>
            </tr>
            <? else: ?>
            <tr style="font-weight: bold">
                <td colspan="5" class="not"><b>Số tiền hoàn trả</b></td>
                <td style="text-align: right; font-weight: bold"><?= Core::getService('core.currency')->formatMoney(array('money' => ($this->_aVars['aContent']['general']['coin_receive'] + $this->_aVars['aContent']['general']['money_recieve']) - $this->_aVars['aContent']['general']['total_amount'] + $this->_aVars['aContent']['general']['total_discount']))?> đ</td>
            </tr>
            <? endif; ?>
        </tbody>
    </table>
    <br>
    <table style="width: 100%; text-align: center">
        <tbody>
            <tr class="ac">
                <td>Người bán hàng</td>
                <td>Người giao hàng</td>
                <td>Người mua hàng</td>
            </tr>
            <tr class="sm">
                <td>(Ký ghi rõ họ tên)</td>
                <td>(Ký ghi rõ họ tên)</td>
                <td>(Ký ghi rõ họ tên)</td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>
