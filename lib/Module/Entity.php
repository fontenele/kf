<?php

namespace KF\Lib\Module;

abstract class Entity extends \KF\Lib\Database\Dml {

    /**
     * Table name
     * @var string
     */
    protected $table;

    /**
     * PK sequence
     * @var string
     */
    protected $sequence;

    /**
     * PK
     * @var string
     */
    protected $pk;

    /**
     * Table Fields
     * @var array
     */
    protected $fields = [];

    public function __construct($recursive = true) {
        $this->configure($recursive);
        if ($this->getTable()) {
            $this->aliases[$this->getTable()] = 'j' . (count($this->aliases) + 1);
            foreach ($this->getFields() as $field => $fieldData) {
                if ($fieldData->getFkEntity()) {
                    $this->aliases[$fieldData->getFkEntity()->getTable()] = 'j' . (count($this->aliases) + 1);
                }
            }
        }
    }

    abstract public function configure($recursive);

    public function setTable($table) {
        $this->table = $table;
    }

    public function setSequence($sequence) {
        $this->sequence = $sequence;
    }

    public function setPrimaryKey($pk) {
        $this->pk = $pk;
    }

    public function getTable() {
        return $this->table;
    }

    public function getSequence() {
        return $this->sequence;
    }

    public function getPrimaryKey() {
        return $this->pk;
    }

    public static function createField($name) {
        $field = new \KF\Lib\Database\Field($name);
        return $field;
    }

    public function addField(\KF\Lib\Database\Field $field) {
        $this->fields[$field->getName()] = $field;
    }

    public function getField($field) {
        return isset($this->fields[$field]) ? $this->fields[$field] : null;
    }

    public function getFields() {
        return $this->fields;
    }

}
