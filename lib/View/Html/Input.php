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
            $this->setType($type);
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
        $obj = new Input($name, $type, $label, $options);
        return $obj;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getType() {
        return $this->type;
    }

    public function getRequired() {
        return $this->required;
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function getValue() {
        return $this->value;
    }

    /**
     * @param string $title
     * @return \KF\Lib\View\Html\Input
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $type
     * @return \KF\Lib\View\Html\Input
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @param bool $required
     * @return \KF\Lib\View\Html\Input
     */
    public function setRequired($required) {
        $this->required = $required;
        return $this;
    }

    /**
     * @param string $placeholder
     * @return \KF\Lib\View\Html\Input
     */
    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param string $value
     * @return \KF\Lib\View\Html\Input
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

}
