<div class="container pad20 search-filter-box">
    <div class="content-box mgbt20 panel-shadow">
        <div class="box-title">Trích lọc ảnh hưởng</div>
        <div class="box-inner">
            <div>
                <input type="hidden" id="stt_bai_lien_ket_lien_quan" value="cha_trich_loc_gt_<?= $this->_aVars['iFilterValue']?>" />
                <input type="hidden" id="class_bai_lien_ket_lien_quan" value="0" />
            </div>
            <div class="row30 mgbt20">
                <div class="col4">
                    Chọn trích lọc
                </div>
                <div class="col6">
                    <select onChange="get_list_tl($(this))">
                        <option value="0">Chọn</option>
                        <? foreach ($this->_aVars['aFilters'] as $aFilter): ?>
                            <option value="<?= $aFilter['id']?>>"><?= $aFilter['name']; ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
            </div>
            <div id="js-search-filter" class="row30 mgbt20 none">
                <div class="row30 mgbt10">
                    Danh sách giá trị
                </div>
                <div class="mgbt10" id="filter-value-list"></div>
                <div class="row20 mgbt10">
                    <span style="color: #FF8080;">Click vào các giá trị để chọn và click lần nữa để hủy chọn.</span>
                </div>
            </div>
            <div class="row30 mgbt20">
                <div class="col4"></div>
                <div class="col3 padright20">
                    <div class="button-default js-cancel-add-parent-filter">Đóng</div>
                </div>
                
            </div>
        </div>
    </div>
</div>