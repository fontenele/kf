<?php

namespace KF\Lib\Module;

abstract class Model {

    public $_table;
    public $_sequence;
    public $_pk;
    public $_fields;

    const TYPE_INTEGER = 1;
    const TYPE_VARCHAR = 2;

    public function addField($name, $type, $length = null) {
        $this->_fields[$name] = ['type' => $type, 'length' => $length];
    }

    public function fetchAll($dml, $input = array()) {
        $stmt = \KF\Kernel::$db->prepare($dml);
        $stmt->execute($input);
        return $stmt->fetchAll();
    }

    public function fetch($dml, $input = array()) {
        $stmt = \KF\Kernel::$db->prepare($dml);
        $stmt->execute($input);
        return $stmt->fetch();
    }

}
