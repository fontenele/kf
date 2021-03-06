<?php

namespace Kf\Module;

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
     * @throws \Kf\Module\Exception
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
     * @throws \Kf\Module\Exception
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
     * @throws \Kf\Module\Exception
     */
    protected function fetchAllBySql($dml, $input = []) {
        try {
//            if (self::$debug) {
//                x(__METHOD__, $dml, $input);
//            }
            $stmt = \Kf\Kernel::$db->prepare($dml);
            $stmt->execute($input);

//            if ($stmt->errorInfo()[2]) {
//                \Kf\System\Logger::database($sql->getQuery(), $stmt->errorInfo()[2], $stmt->errorCode());
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
     * @throws \Kf\Module\Exception
     */
    protected function fetchBySql($dml, $input = []) {
        try {
//            if (self::$debug) {
//                x(__METHOD__, $dml, $input);
//            }
            $stmt = \Kf\Kernel::$db->prepare($dml);
            $stmt->execute($input);

            if ($stmt->errorInfo()[2]) {
                \Kf\System\Logger::database($dml, $stmt->errorInfo()[2], $stmt->errorCode());
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
     * @throws \Kf\Module\Exception
     */
    public function fetchAll($where = [], $rowsPerPage = null, $numPage = 0, $selectNames = [], $whereConditions = [], $orderBy = []) {
        try {
            $dml = $this->getEntity()
                    ->select($selectNames, $rowsPerPage ? true : false)
                    ->from()
                    ->where($where, $rowsPerPage ? true : false)
                    ->orderBy($orderBy)
                    ->paginate($numPage, $rowsPerPage);

            if (\Kf\Database\Dml::$debug) {
                x($dml->query, $dml->input);
            }

            //xd($dml, $this->fetchAllBySql($dml->query, $dml->input));
            //$sql = new \Kf\Database\Sql($this);
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
     * @throws \Kf\Module\Exception
     */
    public function fetch($where = [], $selectNames = []) {
        try {
            $dml = $this->getEntity()
                    ->select($selectNames)
                    ->from()
                    ->where($where);

            //xd($where, $selectNames);
//            $sql = new \Kf\Database\Sql($this);
//            $sql->select($selectNames)->from($this->_table)->where($where);
//            if (self::$debug) {
//                x(__METHOD__, $sql->query, $sql->input, $sql);
//            }
            return $this->fetchBySql($dml->query, $dml->input);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $where
     * @param array $selectNames
     * @return array
     * @throws \Kf\Module\Exception
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
     * @throws \Kf\Module\Exception
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
     * @throws \Kf\Module\Exception
     */
    public function save(&$row) {
        try {
            $pk = $this->getEntity() ? $this->getEntity()->getPrimaryKey() : null;
            if ($pk && isset($row[$pk]) && $row[$pk]) {
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
            $dml = $this->getEntity()->insert($row);

//            xd($row, $dml->query, $dml->input, $dml);
//            $sql = new \Kf\Database\Sql($this);
//            $sql->insert($row);
//            if (self::$debug) {
//                x(__METHOD__, $dml->query, $dml->input, $dml);
//            }

            $stmt = \Kf\Kernel::$db->prepare($dml->query);
            $success = $stmt->execute($dml->input);

            if ($stmt->errorInfo()[2]) {
                \Kf\System\Logger::database($dml->query, $stmt->errorInfo()[2], $stmt->errorCode());
            }

            if ($success) {
                $row[$this->getEntity()->getPrimaryKey()] = \Kf\Kernel::$db->lastInsertId($this->getEntity()->getSequence());
            }

            if ($stmt->errorInfo()[2]) {
                \Kf\System\Logger::database($dml->query, $stmt->errorInfo()[2], $stmt->errorCode());
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
            $dml = $this->getEntity()->update($row, $where);
//            $sql = new \Kf\Database\Sql($this);
//            $sql->update($row, $where);
//
//            if (self::$debug) {
//                x(__METHOD__, $sql->query, $sql->input, $sql);
//            }

            $stmt = \Kf\Kernel::$db->prepare($dml->query);
            $success = $stmt->execute($dml->input);

            if ($stmt->errorInfo()[2]) {
                \Kf\System\Logger::database($dml->query, $stmt->errorInfo()[2], $stmt->errorCode());
            }

            return $success;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function delete($where = []) {
        try {
            $dml = $this->getEntity()->delete($where);
//            $sql = new \Kf\Database\Sql($this);
//            $sql->delete($where);
//
//            if (self::$debug) {
//                x(__METHOD__, $sql->query, $sql->input, $sql);
//            }
            $stmt = \Kf\Kernel::$db->prepare($dml->query);
            $success = $stmt->execute($dml->input);

            if ($stmt->errorInfo()[2]) {
                \Kf\System\Logger::database($dml->query, $stmt->errorInfo()[2], $stmt->errorCode());
            }

            return $success;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function deleteBySql($sql, $input) {
        $stmt = \Kf\Kernel::$db->prepare($sql);
        $success = $stmt->execute($input);

        if ($stmt->errorInfo()[2]) {
            \Kf\System\Logger::database($sql, $stmt->errorInfo()[2], $stmt->errorCode());
        }

        return $success;
    }

    public function createTable() {
        try {
            $sql = new \Kf\Database\Sql($this);
            $sql->createTable();

            if (self::$debug) {
                x(__METHOD__, $sql->query, $sql);
            }

            $success = \Kf\Kernel::$db->exec($sql->query);

            if (\Kf\Kernel::$db->errorInfo()[2]) {
                \Kf\System\Logger::database($sql->getQuery(), \Kf\Kernel::$db->errorInfo()[2], \Kf\Kernel::$db->errorCode());
            }

            return $success;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
