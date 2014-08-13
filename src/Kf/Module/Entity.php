<?php

namespace KF\Module;

abstract class Entity extends \KF\Database\Dml {

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
     * Primary Key
     * @var string
     */
    protected $pk;

    /**
     * Service name
     * @var string
     */
    protected $serviceName;

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

    public function getServiceName() {
        return $this->serviceName;
    }

    public function getService() {
        return new $this->serviceName;
    }

    public static function createField($name) {
        $field = new \KF\Database\Field($name);
        return $field;
    }

    public function addField(\KF\Database\Field $field) {
        $this->fields[$field->getName()] = $field;
    }

    /**
     * @param string $field
     * @return \KF\Database\Field
     */
    public function getField($field) {
        return isset($this->fields[$field]) ? $this->fields[$field] : null;
    }

    public function getFields() {
        return $this->fields;
    }

    public function setValues($row) {
        foreach ($row as $field => $value) {
            if ($this->getField($field)) {
                $this->getField($field)->setValue($value);
            }
        }
    }

    public function setServiceName($serviceName) {
        $this->serviceName = $serviceName;
    }

}
