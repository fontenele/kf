<?php

namespace KF\Lib\View\Html;

class InputText extends Input {

    public function __construct($name, $label = null, $value = null, $options = []) {
        try {
            parent::__construct($name, $label, 'text');
            if (isset($options['required'])) {
                $this->required = $options['required'];
            }
            if (isset($options['placeholder'])) {
                $this->placeholder = $options['placeholder'];
            }
            $this->value = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
