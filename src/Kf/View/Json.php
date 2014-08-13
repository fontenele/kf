<?php

namespace KF\View;

class Json extends \KF\System\ArrayObject {

    public function __construct($vars = array()) {
        parent::__construct($vars);
    }
    
    public function render() {
        return json_encode($this->getArrayCopy());
    }
    
    public function __get($name) {
        return $this->offsetExists($name) ? $this->offsetGet($name) : '';
    }
    
    public function __set($name, $value = null) {
        $this->offsetSet($name, $value);
    }

}
