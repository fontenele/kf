<?php

namespace KF\Lib\View\Html;

class Input extends Tag {

    public function __construct($name, $label = null, $type, $options = []) {
        try {
            parent::__construct('input', $name, $label);
            $this->type = $type;
            if (isset($options['required'])) {
                $this->required = $options['required'];
            }
            if (isset($options['placeholder'])) {
                $this->placeholder = $options['placeholder'];
            }
            if (isset($options['class'])) {
                $this->class[] = $options['class'];
            }
            $this->title = $label;
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
