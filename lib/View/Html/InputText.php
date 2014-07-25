<?php

namespace KF\Lib\View\Html;

class InputText extends Input {

    public function __construct($name, $label = null, $value = null, $options = []) {
        try {
            parent::__construct($name, $label, 'text', $options);
            $this->value = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
