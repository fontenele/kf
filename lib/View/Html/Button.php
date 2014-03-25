<?php

namespace KF\Lib\View\Html;

class Button extends Tag {

    public function __construct($name, $label = null, $options = []) {
        try {
            parent::__construct('button', $name, $label);
            $this->content = $label;
            $this->class = 'btn';
            $this->name = '';
            if (isset($options['class'])) {
                $this->class.= ' ' . $options['class'];
            }
            $this->title = $label;
            $this->closeTagAfter = true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
