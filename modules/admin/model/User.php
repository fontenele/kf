<?php

namespace Admin\Model;

class User extends \KF\Lib\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\User);
    }

}
