<?php

namespace Main\Controller;

Class Index extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function index() {
        try {
            $service = new \Admin\Service\User();
            $serviceUG = new \Admin\Service\UserGroup();
            x($service->findOneByEmail('guilherme@fontenele.net'));
            x($service->findOneByStatus('1'));
            x($serviceUG->findOneByStatus('1'));
            xd('fim');
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
