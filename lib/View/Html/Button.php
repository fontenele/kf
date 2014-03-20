<?php

namespace KF\Lib\View\Html;

class Button extends Tag {

    public function __construct($name, $label) {
        try {
            parent::__construct('button', $name, $label);
            $this->closeTagAfter = true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
