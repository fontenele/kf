<?php

namespace KF\Lib\Module;

/**
 * @package Module
 * @abstract
 */
abstract class Model {

    /**
     * Table name
     * @var string
     */
    public $_table;

    /**
     * PK sequence
     * @var string
     */
    public $_sequence;

    /**
     * PK
     * @var string
     */
    public $_pk;

    /**
     * Table Fields
     * @var array
     */
    public $_fields = [];

    /**
     * Table joins
     * @var array
     */
    public $_joins = [];

    /**
     * Debug toggle
     * @var boolean
     */
    public static $debug = false;

    /**
     * Fields types
     */
    const TYPE_INTEGER = 1;
    const TYPE_VARCHAR = 2;

    /**
     * Joins types
     */
    const JOIN_INNER = 1;
    const JOIN_LEFT = 2;
    const JOIN_RIGHT = 3;
    const JOIN_FULL = 4;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \KF\Lib\Module\Exception
     */
    public function __call($name, $arguments) {
        try {
            switch (true) {
                case substr($name, 0, 6) == 'findBy':
                    return call_user_func_array([$this, "findBy"], [[strtolower(substr($name, 6)) => $arguments[0]]]);
                case substr($name, 0, 9) == 'findOneBy':
                    return call_user_func_array([$this, "findOneBy"], [[strtolower(substr($name, 9)) => $arguments[0]]]);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $name
     * @param integer $type
     * @param integer $length
     * @param integer $join
     * @param Model $joinModel
     * @param string $joinForeign
     * @throws \KF\Lib\Module\Exception
     */
    public function addField($name, $type, $length = null, $join = null, $joinModel = null, $joinForeign = null) {
        try {
            $this->_fields[$name] = ['type' => $type, 'length' => $length, 'join' => $join];
            if ($join && $joinModel && $joinForeign) {
                $this->_joins[$name] = ['model' => $joinModel, 'fk' => $joinForeign, 'type' => $join];
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return array
     */
    public function fields() {
        return $this->_fields;
    }

    /**
     * @param string $field
     * @return array
     */
    public function field($field) {
        return isset($this->_fields[$field]) ? $this->_fields[$field] : null;
    }

    /**
     * @param string $dml
     * @param array $input
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    protected function fetchAllBySql($dml, $input = []) {
        try {
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute($input);
            return $stmt->fetchAll();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $dml
     * @param array $input
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    protected function fetchBySql($dml, $input = []) {
        try {
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute($input);
            return $stmt->fetch();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $where
     * @param integer $rowsPerPage
     * @param integer $numPage
     * @param array $selectNames
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    public function fetchAll($where = [], $rowsPerPage = null, $numPage = 0, $selectNames = [], $whereConditions = []) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->select($selectNames, $rowsPerPage ? true : false)->from($this->_table)->where($where, $rowsPerPage, $numPage, $whereConditions);
            return $this->fetchAllBySql($sql->query, $sql->input);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $where
     * @param array $selectNames
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    public function fetch($where = [], $selectNames = []) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->select($selectNames)->from($this->_table)->where($where);
            return $this->fetchBySql($sql->query, $sql->input);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $where
     * @param array $selectNames
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    public function findBy($where, $selectNames = []) {
        try {
            return $this->fetchAll($where, $selectNames);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     *
     * @param array $where
     * @param array $selectNames
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    public function findOneBy($where, $selectNames = []) {
        try {
            return $this->fetch($where, $selectNames);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $row
     * @return boolean
     * @throws \KF\Lib\Module\Exception
     */
    public function save(&$row) {
        try {
            if ($this->_pk && isset($row[$this->_pk]) && $row[$this->_pk]) {
                return $this->update($row);
            } else {
                return $this->insert($row);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $row
     * @return boolean
     */
    public function insert(&$row) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->insert($row);

            $stmt = \KF\Kernel::$db->prepare($sql->query);
            $success = $stmt->execute($sql->input);

            if ($success) {
                $row[$this->_pk] = \KF\Kernel::$db->lastInsertId($this->_sequence);
            }

            return $success;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $row
     * @return boolean
     */
    public function update(&$row) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->update($row);

            $stmt = \KF\Kernel::$db->prepare($sql->query);
            return $stmt->execute($sql->input);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
