<?php

namespace KF\Database;

abstract class Pdo extends \PDO {

    public function __construct($config) {
        try {
            parent::__construct($config['dsn'], $config['username'], $config['password'], array());
            $this->setDefaults();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public abstract function setDefaults();
}
