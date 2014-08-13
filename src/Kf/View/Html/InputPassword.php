<?php

namespace KF\View\Html;

class InputPassword extends Input {

    /**
     * @param string $name
     * @param string $label
     * @param string $value
     * @param array $options
     * @throws \Exception
     */
    public function __construct($name, $label = null, $value = null, $options = []) {
        try {
            parent::__construct($name, 'password', $label, $options);
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
     * @return InputPassword
     */
    public static function create($name, $label = null, $value = null, $options = []) {
        $obj = new self($name, $label, $value, $options);
        return $obj;
    }

}
