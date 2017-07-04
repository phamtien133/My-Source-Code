<?php
class Core_Component_Controller_Build extends Component
{
    public function process()
    {
         $this->template()->setHeader(array(
            'search1.css' => 'site_css'
        ));
    }
}
?>
