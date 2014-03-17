<?php

namespace Admin\Service;

class User extends \KF\Lib\Module\Service {

    /**
     * @param string $email
     * @param string $pass
     * @return array
     */
    public function auth($email, $pass) {
        try {
            $model = new \Admin\Model\User();
            return $model->auth($email, $pass);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
