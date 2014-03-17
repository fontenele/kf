<?php

namespace KF\Lib\System;

class Session extends ArrayObject {

    public $container;

    public function __construct($container) {
        $this->container = $container;
        if (!isset($_SESSION[$container])) {
            $_SESSION[$container] = array();
        } else {
            parent::__construct($_SESSION[$container]);
        }
    }

    public function __get($name) {
        return $this->offsetExists($name) ? $this->offsetGet($name) : '';
    }

    public function __set($name, $value = null) {
        $_SESSION[$this->container][$name] = $value;
        $this->offsetSet($name, $value);
    }

}
