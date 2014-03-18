<?php

namespace Main\Controller;

Class Index extends \KF\Lib\Module\Controller {

    public function init() {
        
    }
    
    public function index() {
        try {
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
