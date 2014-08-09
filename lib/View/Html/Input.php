<?php

namespace KF\Lib\View\Html;

class Input extends Tag {

    /**
     * @param string $name
     * @param string $type
     * @param string $label
     * @param array $options
     * @throws \Exception
     */
    public function __construct($name, $type, $label = null, $options = []) {
        try {
            parent::__construct('input', $name, $label);
            $this->type = $type;
            if (isset($options['required'])) {
                $this->required = $options['required'];
            }
            if (isset($options['placeholder'])) {
                $this->placeholder = $options['placeholder'];
            }
            if (isset($options['class'])) {
                $this->class[] = $options['class'];
            }
            $this->title = $label;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $label
     * @param array $options
     * @return Input
     */
    public static function create($name, $type, $label = null, $options = []) {
        $obj = new self($name, $type, $label, $options);
        return $obj;
    }

    public function setValue($value = null) {
        try {
            $this->value = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
