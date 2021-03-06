<?php

namespace Kf\View\Html;

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
    /*public static function create($name, $type, $label = null, $options = []) {
        $obj = new Input($name, $type, $label, $options);
        return $obj;
    }*/

    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     * @return \Kf\View\Html\Input
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

}
