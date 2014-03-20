<?php

namespace KF\Lib\System;

class ArrayObject extends \ArrayObject {

    public function offsetGet($index) {
        return $this->offsetExists($index) ? parent::offsetGet($index) : null;
    }

    public function __get($name) {
        return $this->offsetExists($name) ? $this->offsetGet($name) : '';
    }

    public function __set($name, $value = null) {
        $this->offsetSet($name, $value);
    }

}
