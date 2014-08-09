<?php

namespace KF\Lib\View\Html;

class Button extends Tag {

    /**
     * @param string $name
     * @param string $label
     * @param array $options
     * @throws \Exception
     */
    public function __construct($name, $label = null, $options = []) {
        try {
            parent::__construct('button', $name, $label);
            $this->content = $label;
            $this->class[] = 'btn';
            $this->removeClass('form-control');
            $this->name = '';
            if (isset($options['class'])) {
                $this->class[] = $options['class'];
            }
            $this->title = $label;
            $this->closeTagAfter = true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $options
     * @return Button
     */
    public static function create($name, $label = null, $options = []) {
        $obj = new self($name, $label, $options);
        return $obj;
    }

    public function formGroup($class = null, $labelClass = null, $componentClass = null) {
        try {
            $return = "<div class='form-group {$class}'>";
            $return.= $this->label($labelClass, true);
            $return.= "<div class='{$componentClass}'>";
            $return.= $this->render();
            $return.= "</div>";
            $return.= "</div>";
            return $return;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
