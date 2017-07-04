<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<div class="container page-marketing page-marketing-add">
    <div class="content-box panel-shadow">
        <div class="box-title">
            <?= $aPage['title']?>
        </div>
        <div class="box-inner">
            <?php if($iStatus == 3):?>
            <div class="row30 padtb10 ">
                <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
            </div>
            <div class="row30 padtop10">
                <div class="col3 padright10">
                    <div class="button-blue" onclick="window.location='/user/group/add/';">
                        <?= Core::getPhrase('language_them')?>
                    </div>
                </div>
                <div class="col3 padright10">
                    <div class="button-blue" onclick="window.location='/user/group/';">
                        <?= Core::getPhrase('language_quan-ly')?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <form action="#" method="post" name="frm_dang_ky" id="frm_add" class="box style width100" onsubmit="return sbm_frm()">
                 <?php if(!empty($errors)):?>
                    <div class="row30 padtb10 ">
                        <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                    </div>
                    <div class="row30 padtb10">
                        <?php foreach($errors as $error):?>
                            <div class="row30">
                                <?= $error?>
                            </div>
                        <?php endforeach?>
                    </div>
                <?php endif?>
                <div class="row30 line-bottom padbot10 mgbt10">
                    <div class="col5">
                        <label for="name" class="sub-black-title" style="width: 50px">
                            <?= Core::getPhrase('language_ten')?>:
                        </label>
                        <span id="div_ten_kiem_tra_name_code"></span>
                    </div>
                    <div class="col7">
                        <input type="text" id="name" name="name" value="<?= $data_arr['name']?>" class="default-input" onblur="kiem_tra_name_code()"/>
                    </div>
                </div>
                <div class="row30 line-bottom padbot10 mgbt10">
                    <div class="col5">
                        <label for="name_code" class="sub-black-title">
                            <?= Core::getPhrase('language_ma-ten')?>:
                            <a href="javascript:" onclick="return btn_cap_nhat_name_code()" style="margin-left: 10px; font-size:12px; font-family: HelveticaNeue; color: #999; font-weght: 200">(<?= Core::getPhrase('language_cap-nhat-tu-dong')?>)</a>
                        </label>
                    </div>
                    <div class="col7">
                        <input type="text" id="name_code" name="name_code" value="<?= $data_arr['name_code']?>" onblur="kiem_tra_name_code()" class="default-input"/>
                    </div>
                </div>
                <div class="row30 mgbt10 padbot10 line-bottom">
                    <div class="col5">
                        <label for="status" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                    </div>
                    <div class="col7">
                        <select name="status" id="status" style="height: 30px; width:100%">
                           <option value="1"<?php if($data_arr['status'] ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                           <option value="0"<?php if($data_arr['status'] ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                         </select>
                    </div>
                </div>
                <div class="row30 mgbt10"> 
                    <div class="col5">
                        <label for="status" class="sub-black-title">Thêm thành viên:</label>
                    </div>
                    <div class="col7 suggest-marketing">
                        <input type="text" class="js-search-user default-input" data-type="product" placeholder="Tìm kiếm">
                    <div class="list-suggest js-scroll none">
                        <div class="item-suggest">Sản phẩm</div>
                        <div class="item-suggest">Sản phẩm</div>
                        <div class="item-suggest">Sản phẩm</div>
                    </div>
                    </div>
                </div>
                <div class="row30 mgbt10" id="js-product-list">
                    <div class="row30 mgbt10">
                        <label for="status" class="sub-black-title">Danh sách thành viên:</label>
                    </div>
                    <? for ($i = 0; $i < count($data_arr['list_id']); $i++):?>
                        <div class="row30 mgbt20 js-dis-product" id="js-object-<?= $i?>">
                             <div class="col2">
                                Tên thành viên:
                             </div>
                             <div class="col4">
                                <label class="label-custom"><?= $data_arr['list_name'][$i]?></label>
                             </div>
                             <div class="col1">
                                Email:
                             </div>
                             <div class="col4">
                                <label class="label-custom"><?= $data_arr['list_email'][$i]?></label>
                             </div>
                             <input type="hidden" name="list_id[]" value="<?= $data_arr['list_id'][$i]?>">
                             <input type="hidden" name="list_name[]" value="<?= $data_arr['list_name'][$i]?>">
                             <input type="hidden" name="list_email[]" value="<?= $data_arr['list_email'][$i]?>">
                             <div class="col1">
                                <span class="fa fa-close right icon-wh js-delete-user" data-id="<?= $i?>"></span>
                             </div>
                        </div>
                    <? endfor?>
                </div>
                <hr />
                <div class="row30"> 
                    <div class="col1">
                        <div class="button-blue" type="submit" name="submit" id="js-btn-submit">
                            <?= Core::getPhrase('language_hoan-tat')?>
                        </div>
                    </div>
                    <div class="col10"></div>
                    <div class="col1">
                        <div class="button-blue" onclick="window.location = '/user/group/'">
                            <?= Core::getPhrase('language_quan-ly')?>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif?>
        </div>
    </div>
</div>
<script type="text/javascript">
var domain_name = '<?= Core::getDomainName();?>';
var group_id = '<?= $id?>';
</script>
