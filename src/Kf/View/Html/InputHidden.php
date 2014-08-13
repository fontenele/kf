<?php

namespace Kf\View\Html;

class InputHidden extends Input {

    /**
     * @param string $name
     * @param string $label
     * @param string $value
     * @param array $options
     * @throws \Exception
     */
    public function __construct($name, $value = null, $options = []) {
        try {
            parent::__construct($name, 'hidden', null, $options);
            $this->value = $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $value
     * @param array $options
     * @return InputHidden
     */
    public static function create($name, $value = null, $options = []) {
        $obj = new self($name, $value, $options);
        return $obj;
    }

}
