<?php

namespace KF\Lib\Module;

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

    const TYPE_INTEGER = 1;
    const TYPE_VARCHAR = 2;
    const JOIN_INNER = 1;
    const JOIN_LEFT = 2;
    const JOIN_RIGHT = 3;
    const JOIN_FULL = 4;

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

    public function fields() {
        return $this->_fields;
    }

    public function field($field) {
        return isset($this->_fields[$field]) ? $this->_fields[$field] : null;
    }

    protected function fetchAllBySql($dml, $input = []) {
        try {
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute($input);
            return $stmt->fetchAll();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

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
     * Fetch All
     * @param \KF\Lib\Database\Sql $sql
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    public function fetchAll($sql = null, $where = [], $paginator = false, $rowsPerPage = null, $numPage = 1) {
        try {
            if (!$sql) {
                $sql = new \KF\Lib\Database\Sql($this);
                $sql->select(null, $paginator)->from($this->_table)->where($where, $paginator, $rowsPerPage, $numPage);
            }
            return $this->fetchAllBySql($sql->query, $sql->input);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    protected function fetch($sql) {
        try {
            return $this->fetchBySql($sql->query, $sql->input);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function findBy($where, $selectNames = []) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->select($selectNames)->from($this->_table)->where($where);
            return $this->fetchAll($sql);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function findOneBy($where, $selectNames = []) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->select($selectNames)->from($this->_table)->where($where);
            return $this->fetch($sql);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

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
            xd($ex);
        }
    }

    public function update(&$row) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->update($row);

            $stmt = \KF\Kernel::$db->prepare($sql->query);
            return $stmt->execute($sql->input);
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
