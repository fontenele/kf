<?php

namespace KF\Lib\System;

class ArrayObject extends \ArrayObject {

    public function offsetGet($index) {
        return $this->offsetExists($index) ? parent::offsetGet($index) : null;
    }

}
