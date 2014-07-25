<?php

namespace Main\Controller;

Class Index extends \KF\Lib\Module\Controller {

    public function index() {
        try {
            
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
