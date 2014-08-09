<?php

namespace KF\Lib\View\Html;

class Select extends Tag {

    public $selected;
    public $options = [];
    public $defaultItemValue;
    public $defaultItemLabel = 'Selecione';

    /**
     * @param string $name
     * @param string $label
     * @param array $options
     * @param string $selected
     * @throws \Exception
     */
    public function __construct($name, $label = null, $options = [], $selected = null) {
        try {
            parent::__construct('select', $name, $label);
            if (isset($options['defaultItemLabel'])) {
                $this->defaultItemLabel = $options['defaultItemLabel'];
            }
            $this->setOptions(isset($options['options']) ? $options['options'] : []);
            if (isset($options['required'])) {
                $this->required = $options['required'];
            }
            if (isset($options['class'])) {
                $this->class[] = $options['class'];
            }
            if ($selected) {
                $this->setSelected($selected);
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
     * @param string $selected
     * @return Select
     */
    public static function create($name, $label = null, $options = [], $selected = null) {
        $obj = new self($name, $label, $options, $selected);
        return $obj;
    }

    public function setSelected($value = null) {
        try {
            $this->content = '';
            $this->selected = $value;

            if ($this->defaultItemLabel) {
                $selected = $value == $this->defaultItemValue ? "selected='selected'" : '';
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

    public function setValue($value = null) {
        try {
            return $this->setSelected($value);
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

    public static function rows2options($rows, $codName = 'cod', $valueName = 'name') {
        try {
            $options = [];
            foreach ($rows as $row) {
                $options[$row[$codName]] = $row[$valueName];
            }
            return $options;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
