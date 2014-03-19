<?php

namespace KF\Lib\System;

class Session extends ArrayObject {

    public $container;

    public function __construct($container) {
        $this->container = $container;
        if (!isset($_SESSION[$container])) {
            $this->clear();
        } else {
            parent::__construct($_SESSION[$container]);
        }
    }

    public function __get($name) {
        return $this->offsetExists($name) ? $this->offsetGet($name) : '';
    }

    public function __set($name, $value = null) {
        $this->offsetSet($name, $value);
    }
    
    public function offsetSet($name, $value = null) {
        $_SESSION[$this->container][$name] = serialize($value);
        parent::offsetSet($name, $value);
    }
    
    public function offsetGet($index) {
        return unserialize(parent::offsetGet($index));
    }
    
    public function clear() {
        $_SESSION[$this->container] = [];
    }

}
