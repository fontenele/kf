<?php

namespace Admin\Controller;

class Menu extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function listItems() {
        try {
            
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
