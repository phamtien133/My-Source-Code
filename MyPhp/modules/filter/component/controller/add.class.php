<?php
class Filter_Component_Controller_Add extends Component
{
    public function process()
    {
        $aVals = Core::getLib('request')->getRequests();
        /*
            Phần Đề tài mối quan hệ
                Điểm khác biệt cơ bản so với các phần có mối quan hệ khác, như môi quan hệ Đề tài, menu,..Ở trích lọc, những giá trị của trích lọc này phụ thuộc vào những giá trị khác.
                Để hiển thị ngoài bài viết, nếu cha_trich_loc_stt > 0, mặc định ẩn, khi check vào giá trị 1 trích lọc, foreach all trich loc khac, neu cha_trich_loc_stt =, thì cho hiển thị

                Trich loc cha stt là 1 list, như vậy cần 1 bảng, để mốc mối liên hẹ trich_loc_gt, rel_trich_loc_gt_phu_thuoc

        */
        $page['title'] = Core::getPhrase('language_tao-trich-loc');

        if (!empty($_POST)) {
            if (isset($aVals['id'])) {
                $_POST['id'] = $aVals['id'];
            }
            $aParam = $_POST;
            $aData = Core::getService('filter')->create($aParam);
            if ($aData['status'] == 'success') {
                //re-direct page
                $sDir = $_SERVER['REQUEST_URI'];
                $aTmps = explode('/', $sDir, 3);
                $sDir = '/'.$aTmps[1].'/';
                header('Location: '.$sDir);
            }            
        } else {
            if (isset($aVals['id'])) {
                $aParam['id'] = $aVals['id'];
            }
            $aData = Core::getService('filter')->initCreate($aParam); 

        }
        $this->template()->setTitle($page['title']);

        $this->template()->assign(array(
            'aData' => $aData
        ));
    }
}
?>