<?php

namespace KF\Lib\View\Html;

class InputTextMoney extends InputText {

    public function __construct($name, $label = null, $value = null, $options = []) {
        try {
            parent::__construct($name, $label, $value, $options);
            $this->addClass('money');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
