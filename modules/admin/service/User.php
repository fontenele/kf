<?php

namespace Admin\Service;

class User extends \KF\Lib\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\User';
    }

}
