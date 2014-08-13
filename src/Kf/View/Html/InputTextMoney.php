<?php

namespace KF\View\Html;

class InputTextMoney extends InputText {

    /**
     * @param string $name
     * @param string $label
     * @param string $value
     * @param array $options
     * @throws \Exception
     */
    public function __construct($name, $label = null, $value = null, $options = []) {
        try {
            parent::__construct($name, $label, $value, $options);
            $this->addClass('money');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $value
     * @param array $options
     * @return InputTextMoney
     */
    public static function create($name, $label = null, $value = null, $options = []) {
        $obj = new self($name, $label, $value, $options);
        return $obj;
    }

}
