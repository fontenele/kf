<?php

namespace Main\Controller;

Class Index extends \KF\Lib\Module\Controller {

    public function init() {

    }

    public function index() {
        try {
            $service = new \Admin\Service\User();
            $user = $service->findOneByEmail('guilherme@fontenele.net');
            xd($user);
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
