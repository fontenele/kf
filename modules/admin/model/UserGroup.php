<?php

namespace Admin\Model;

class UserGroup extends \KF\Lib\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\UserGroup);
    }

}
