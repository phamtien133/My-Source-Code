<?php
class Discount_Component_Controller_Index extends Component
{
    public function process()
    {
        //Lấy dữ liệu method get
        $aVals = Core::getLib('request')->getRequests();

        //Chuyển trang
        if (isset($aVals['req2']) && !empty($aVals['req2'])) {
            if ($aVals['req2'] == 'add' || $aVals['req2'] == 'edit') {
                return Core::getLib('module')->setController('discount.add');
            }
            else if ($aVals['req2'] == 'list'){
                return Core::getLib('module')->setController('discount.list');
            }
        }
        
        //Biến title
        $aPage['title'] = Core::getPhrase('language_quan-ly-shop-custom');
        
        //Lấy dữ liệu discount
        $aData = array();
        $aData = Core::getService('discount')->getDiscount($aVals);
        
        $this->template()->setHeader(array(
            'sanpham.css' => 'site_css',
        ));
        
        $this->template()->assign(array(
            'aData' => $aData,
        ));
        
        $this->template()->setTitle('Mã giảm giá');
    }
}
?>