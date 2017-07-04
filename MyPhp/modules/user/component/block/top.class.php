<?php
class User_Component_Block_Top extends Component
{
    public function process()
    {
        $aTopUsers = Core::getService('user')->getTop(array(
            'page' => 1,
            'paze_size' => 3,
        ));
        
        $this->template()->assign(array(
            'aTopUsers' => $aTopUsers,
        ));
    }
}
?>
