<?php

namespace Admin\Service;

class User extends \KF\Lib\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\User';
    }

    /**
     * User system auth
     * @param string $email
     * @param string $pass
     * @return array|null
     */
    public function auth($email, $password) {
        $user = $this->model()->auth($email, md5($password));
        if ($user) {
            unset($user['password']);
        }
        return $user;
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
