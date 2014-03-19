<?php

namespace Admin\Controller;

class Index extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function error404() {
        try {
            $this->view->template = 'public/themes/' . \KF\Kernel::$config['system']['view']['theme'] . '/view/' . \KF\Kernel::$config['system']['view']['error404'];
            \KF\Kernel::$layout->userLogged = false;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
