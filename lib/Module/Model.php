<?php

namespace KF\Lib\Module;

/**
 * @package Module
 * @abstract
 */
abstract class Model {

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * Table name
     * @var string
     */
    //public $_table;

    /**
     * PK sequence
     * @var string
     */
    //public $_sequence;

    /**
     * PK
     * @var string
     */
    //public $_pk;

    /**
     * Table Fields
     * @var array
     */
    //public $_fields = [];

    /**
     * Table joins
     * @var array
     */
    //public $_joins = [];

    /**
     * Debug toggle
     * @var boolean
     */
    //public static $debug = false;

    /**
     * Fields types
     */
//    const TYPE_INTEGER = 1;
//    const TYPE_VARCHAR = 2;
//    const TYPE_DATE = 3;
//    const TYPE_TIME = 4;
//    const TYPE_DATETIME = 5;
//    const TYPE_MONEY = 6;
//    const TYPE_BOOLEAN = 7;

    /**
     * Joins types
     */
//    const JOIN_INNER = 1;
//    const JOIN_LEFT = 2;
//    const JOIN_RIGHT = 3;
//    const JOIN_FULL = 4;

    public function __construct() {
        $this->configure();
    }

    abstract public function configure();

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

    public function setEntity(Entity $entity) {
        try {
            $this->entity = $entity;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return Entity
     */
    public function getEntity() {
        return $this->entity;
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
     * Return the field on _fields attribute array
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
//            if (self::$debug) {
//                x(__METHOD__, $dml, $input);
//            }
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute($input);

//            if ($stmt->errorInfo()[2]) {
//                \KF\Lib\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
//            }

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
            if (self::$debug) {
                x(__METHOD__, $dml, $input);
            }
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute($input);

            if ($stmt->errorInfo()[2]) {
                \KF\Lib\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
            }

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
     * @param array $whereConditions
     * @param array $orderBy
     * @return array
     * @throws \KF\Lib\Module\Exception
     */
    public function fetchAll($where = [], $rowsPerPage = null, $numPage = 0, $selectNames = [], $whereConditions = [], $orderBy = []) {
        try {
            $dml = $this->getEntity()
                    ->select($selectNames, $rowsPerPage ? true : false)
                    ->from()
                    ->where($where, $rowsPerPage ? true : false)
                    ->orderBy($orderBy)
                    ->paginate($numPage, $rowsPerPage);
            //xd($dml, $this->fetchAllBySql($dml->query, $dml->input));


            //$sql = new \KF\Lib\Database\Sql($this);
            //$sql->select($selectNames, $rowsPerPage ? true : false)->from($this->_table)->where($where, $rowsPerPage, $numPage, $whereConditions, $orderBy);
            //xd($sql);
//            if (self::$debug) {
//                x(__METHOD__, $sql->query, $sql->input, $sql);
//            }

            return $this->fetchAllBySql($dml->query, $dml->input);
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
            if (self::$debug) {
                x(__METHOD__, $sql->query, $sql->input, $sql);
            }
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

            if (self::$debug) {
                x(__METHOD__, $sql->query, $sql->input, $sql);
            }

            $stmt = \KF\Kernel::$db->prepare($sql->query);
            $success = $stmt->execute($sql->input);

            if ($stmt->errorInfo()[2]) {
                \KF\Lib\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
            }

            if ($success) {
                $row[$this->_pk] = \KF\Kernel::$db->lastInsertId($this->_sequence);
            }

            if ($stmt->errorInfo()[2]) {
                \KF\Lib\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
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
    public function update(&$row, $where = []) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->update($row, $where);

            if (self::$debug) {
                x(__METHOD__, $sql->query, $sql->input, $sql);
            }

            $stmt = \KF\Kernel::$db->prepare($sql->query);
            $success = $stmt->execute($sql->input);

            if ($stmt->errorInfo()[2]) {
                \KF\Lib\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
            }

            return $success;
        } catch (\Exception $ex) {
            xd($ex);
            throw $ex;
        }
    }

    public function delete($where = []) {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->delete($where);

            if (self::$debug) {
                x(__METHOD__, $sql->query, $sql->input, $sql);
            }
            $stmt = \KF\Kernel::$db->prepare($sql->query);
            $success = $stmt->execute($sql->input);

            if ($stmt->errorInfo()[2]) {
                \KF\Lib\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
            }

            return $success;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function createTable() {
        try {
            $sql = new \KF\Lib\Database\Sql($this);
            $sql->createTable();

            if (self::$debug) {
                x(__METHOD__, $sql->query, $sql);
            }

            $success = \KF\Kernel::$db->exec($sql->query);

            if (\KF\Kernel::$db->errorInfo()[2]) {
                \KF\Lib\System\Logger::database($sql->getQuery(), \KF\Kernel::$db->errorInfo()[2], \KF\Kernel::$db->errorCode());
            }

            return $success;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
