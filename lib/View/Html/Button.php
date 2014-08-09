<?php

namespace KF\Lib\View\Html;

class Button extends Tag {

    /**
     * @param string $id
     * @param string $label
     * @param array $options
     * @throws \Exception
     */
    public function __construct($id, $label = null, $options = []) {
        try {
            parent::__construct('button', $id, $label);
            $this->content = $label;
            $this->addClass('btn');
            $this->removeClass('form-control');
            $this->id = $this->name;
            $this->name = '';
            if (isset($options['class'])) {
                $this->addClass($options['class']);
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
     * @return \KF\Lib\View\Html\Button
     */
    public static function create($name, $label = null, $options = []) {
        $obj = new Button($name, $label, $options);
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
