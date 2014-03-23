<?php

namespace KF\Lib\View\Html;

class Input extends Tag {

    public function __construct($name, $label = null, $type) {
        try {
            parent::__construct('input', $name, $label);
            $this->type = $type;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function setValue($value = null) {
        try {
            $this->value = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
