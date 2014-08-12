<?php

namespace KF\Lib\View\Html;

class Form extends \KF\Lib\System\ArrayObject {

    /**
     * @var array
     */
    public $attrs = [];

    /**
     * @var \KF\Lib\Module\Model
     */
    public $model;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $fields;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $method;

    const TYPE_INPUT_TEXT = 1;
    const TYPE_INPUT_HIDDEN = 2;
    const TYPE_INPUT_PASSWORD = 3;
    const TYPE_INPUT_DATETIME = 4;
    const TYPE_INPUT_DATE = 5;
    const TYPE_INPUT_TIME = 6;
    const TYPE_INPUT_MONTH = 7;
    const TYPE_INPUT_WEEK = 8;
    const TYPE_INPUT_NUMBER = 9;
    const TYPE_INPUT_EMAIL = 10;
    const TYPE_INPUT_URL = 11;
    const TYPE_INPUT_SEARCH = 12;
    const TYPE_INPUT_TEL = 13;
    const TYPE_INPUT_COLOR = 14;
    const TYPE_INPUT_FILE = 15;
    const TYPE_INPUT_RADIO = 16;
    const TYPE_INPUT_CHECK = 17;
    const TYPE_INPUT_MONEY = 18;
    const TYPE_TEXT_AREA = 19;
    const TYPE_SELECT = 20;
    const TYPE_SELECT_MULTIPLE = 21;
    const TYPE_BUTTON = 22;

    /**
     * HTTP Methods
     */
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * @param array $config
     * @throws \KF\Lib\View\Helper\Exception
     */
    public function __construct($config = []) {
        try {
            $this->action = isset($config['action']) ? $config['action'] : null;
            $this->method = isset($config['method']) ? $config['method'] : self::METHOD_POST;
            if (isset($config['id'])) {
                $this->attrs['id'] = $config['id'];
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function open() {
        try {
            $html = "<form action='{$this->action}' method='{$this->method}' class='form-horizontal' role='form' ";
            foreach ($this->attrs as $attr => $value) {
                $html.= "{$attr}='{$value}' ";
            }
            $html.= '>';
            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function close() {
        try {
            return '</form>';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function setValues($row) {
        try {
            foreach ($row as $field => $value) {
                switch (true) {
                    case $this->$field instanceof Input:
                    case $this->$field instanceof Select:
                        $this->$field->setValue($value);
                        break;
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addField($name, $type, $label = null, $options = []) {
        try {
            $obj = null;

            switch ($type) {
                case self::TYPE_INPUT_TEXT:
                    $obj = new InputText($name, $label, null, $options);
                    break;
                case self::TYPE_INPUT_MONEY:
                    $obj = new InputTextMoney($name, $label, null, $options);
                    break;
                case self::TYPE_INPUT_HIDDEN:
                    $obj = new Input($name, $label, 'hidden', $options);
                    break;
                case self::TYPE_INPUT_PASSWORD:
                    $obj = new Input($name, $label, 'password', $options);
                    break;
                case self::TYPE_INPUT_EMAIL:
                    $obj = new Input($name, $label, 'email', $options);
                    break;
                case self::TYPE_SELECT:
                    $obj = new Select($name, $label, $options);
                    break;
                case self::TYPE_BUTTON:
                    $obj = new Button($name, $label, $options);
                    break;
            }

            $this->{$name} = $obj;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
