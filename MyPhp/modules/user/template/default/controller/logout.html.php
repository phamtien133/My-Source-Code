<div class="wrap_login">
    <form name='loading' bgcolor=#ffffff>
        <div class="combo_progress">
            <div class='text_chuyen_trang'><?= Core::getPhrase('language_he-thong-dang-chuyen-trang')?></div>
            <div class='text_nhap_vao'>
                <a href='<?= $refer?>'>[ <?= Core::getPhrase('language_nhap-vao-day-neu-ban-khong-muon-doi')?> ]</a>
            </div>
            <div class="progress_bar">
                <div class="propress_bar_bg"></div>
                <div class="progress_bar_value"></div>
            </div>
        </div>
        <script>
            $(function(){
                localStorage.clear();
                progress_bar_login();
            });
            /*  Dùng để chạy thanh tiến trình sau khi đăng nhập hay đăng xuất thành công 
                Set time out để chạy thanh tiến trình
            */
            var progress_bar=0;
            function progress_bar_login(){
                progress_bar = progress_bar + 2;
                $('.combo_progress .progress_bar_value').width(progress_bar * 4);
                $('.combo_progress .percent_progress').html(progress_bar + '%');
                if (progress_bar < 99){
                    setTimeout('progress_bar_login()',20);
                }else{
                    window.location = '<?= ($refer==''?'/':$refer); ?>';
                }
            }
        </script>
    </form>
</div>
