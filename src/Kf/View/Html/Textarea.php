<?php

namespace Kf\View\Html;

class Textarea extends Tag {

    public function __construct($name, $label) {
        try {
            parent::__construct('textarea', $name, $label);
            $this->closeTagAfter = true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
