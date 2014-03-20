<?php

namespace KF\Lib\View\Html;

class Select extends Tag {

    public $options = [];
    public $defaultItemValue;
    public $defaultItemLabel = 'Selecione';

    public function __construct($name, $label, $options = []) {
        try {
            parent::__construct('select', $name, $label);
            $this->setOptions($options);
            $this->closeTagAfter = true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function setSelected($value = null) {
        try {
            $this->content = '';
            $selected = $value == $this->defaultItemValue ? "selected='selected'" : '';

            if ($this->defaultItemLabel) {
                $this->content.= "<option {$selected} value='{$this->defaultItemValue}'>{$this->defaultItemLabel}</option>";
            }
            $selected = '';
            foreach ($this->options as $_value => $_label) {
                $selected = $value == $_value ? "selected='selected'" : '';
                $this->content.= "<option {$selected} value='{$_value}'>{$_label}</option>";
            }
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function setOptions($options = []) {
        try {
            $this->content = '';
            $this->options = $options;

            if ($this->defaultItemLabel) {
                $this->content.= "<option value='{$this->defaultItemValue}'>{$this->defaultItemLabel}</option>";
            }
            foreach ($options as $value => $label) {
                $this->content.= "<option value='{$value}'>{$label}</option>";
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
