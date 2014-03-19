<?php

namespace Admin\Service;

class UserGroup extends \KF\Lib\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\UserGroup';
    }

}
