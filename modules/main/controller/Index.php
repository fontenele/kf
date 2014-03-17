<?php

namespace Main\Controller;

Class Index extends \KF\Lib\Module\Controller {

    public function init() {
        
    }
    
    public function index() {
        try {
            xd($_SESSION);
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
