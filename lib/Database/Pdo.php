<?php

namespace KF\Lib\Database;

class Pdo extends \PDO {

    public function __construct($config) {
        parent::__construct($config['dsn'], $config['username'], $config['password']);
        $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

}
