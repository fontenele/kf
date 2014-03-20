<?php

namespace KF\Lib\View\Html;

class InputText extends Input {

    public function __construct($name, $label, $value = null) {
        try {
            parent::__construct($name, $label, 'text');
            $this->value = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
