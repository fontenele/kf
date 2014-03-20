<?php

namespace KF\Lib\View\Html;

class Form extends \KF\Lib\System\ArrayObject {

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
    const TYPE_TEXT_AREA = 18;
    const TYPE_SELECT = 19;
    const TYPE_SELECT_MULTIPLE = 20;
    const TYPE_BUTTON = 21;

    /**
     * @param array $config
     * @throws \KF\Lib\View\Helper\Exception
     */
    public function __construct($config = []) {
        try {
            if ($config) {
                xd($config);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addField($name, $label, $type, $options = []) {
        try {
            $obj = null;

            switch ($type) {
                case self::TYPE_INPUT_TEXT:
                    $obj = new InputText($name, $label);
                    break;
                case self::TYPE_INPUT_HIDDEN:
                    $obj = new Input($name, $label, 'hidden');
                    break;
                case self::TYPE_SELECT:
                    $obj = new Select($name, $label, $options);
                    break;
            }

            $this->{$name} = $obj;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
