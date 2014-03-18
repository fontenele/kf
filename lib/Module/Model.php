<?php

namespace KF\Lib\Module;

abstract class Model {

    public $_table;
    public $_sequence;
    public $_pk;
    public $_fields;
    public $_joins = [self::JOIN_INNER => [], self::JOIN_LEFT => [], self::JOIN_RIGHT => [], self::JOIN_FULL => []];
    private $_totalJoins = 0;

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
                    return call_user_func_array(array($this, "findBy"), array(array(strtolower(substr($name, 6)) => $arguments[0])));
                case substr($name, 0, 9) == 'findOneBy':
                    return call_user_func_array(array($this, "findOneBy"), array(array(strtolower(substr($name, 9)) => $arguments[0])));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addField($name, $type, $length = null, $join = null, $joinTable = null, $joinForeign = null) {
        try {
            $this->_fields[$name] = ['type' => $type, 'length' => $length, 'join' => $join];
            if ($join && $joinTable && $joinForeign) {
                switch($join) {
                    case self::JOIN_INNER:
                        $alias = ++$this->_totalJoins;
                        $this->_joins[$join][$name] = "INNER JOIN {$joinTable} j{$alias} ON (j{$alias}.{$joinForeign} = {$name})";
                        break;
                    case self::JOIN_LEFT:
                        $alias = ++$this->_totalJoins;
                        $this->_joins[$join][$name] = "LEFT JOIN {$joinTable} j{$alias} ON (j{$alias}.{$joinForeign} = {$name})";
                        break;
                    case self::JOIN_RIGHT:
                        $alias = ++$this->_totalJoins;
                        $this->_joins[$join][$name] = "RIGHT JOIN {$joinTable} j{$alias} ON (j{$alias}.{$joinForeign} = {$name})";
                        break;
                    case self::JOIN_FULL:
                        $alias = ++$this->_totalJoins;
                        $this->_joins[$join][$name] = "FULL JOIN {$joinTable} j{$alias} ON (j{$alias}.{$joinForeign} = {$name})";
                        break;
                }
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

    protected function fetchAllBySql($dml, $input = array()) {
        try {
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute($input);
            return $stmt->fetchAll();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    protected function fetchBySql($dml, $input = array()) {
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
    protected function fetchAll($sql) {
        try {
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

    public function findBy($where, $selectNames = array()) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->select($selectNames)->from($this->_table)->where($where);
            return $this->fetchAll($sql);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function findOneBy($where, $selectNames = array()) {
        try {
            xd($this);
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->select($selectNames)->from($this->_table)->where($where);
            return $this->fetch($sql);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
