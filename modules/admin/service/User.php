<?php

namespace Admin\Service;

class User extends \KF\Lib\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\User';
    }

    public static function identity() {
        try {
            $session = new \KF\Lib\System\Session('system');
            return $session->identity;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
