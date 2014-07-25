<?php

namespace Admin\Controller;

class Index extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function index() {
        try {
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function error404() {
        try {
            $this->view->template = 'public/themes/' . \KF\Kernel::$config['system']['view']['theme'] . '/view/' . \KF\Kernel::$config['system']['view']['error404'];
            $this->view->config = \KF\Kernel::$config;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function errorDefault() {
        try {
            $this->view->template = 'public/themes/' . \KF\Kernel::$config['system']['view']['theme'] . '/view/' . \KF\Kernel::$config['system']['view']['errorDefault'];
            $this->view->config = \KF\Kernel::$config;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
