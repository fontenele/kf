<?php

namespace KF\Lib\Database;

class Sql {

    /**
     * @var string
     */
    public $query = '';

    /**
     * @var array
     */
    public $input = array();

    /**
     * @var \KF\Lib\Module\Model
     */
    public $model;

    /**
     * @param \KF\Lib\Module\Model $model
     * @throws \KF\Lib\Database\Exception
     */
    public function __construct($model = null) {
        try {
            $this->model = $model;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Create SELECT Statement
     * @param array $fields
     * @return \KF\Lib\Database\Sql
     * @throws \KF\Lib\Database\Exception
     */
    public function select($fields = array()) {
        try {
            $this->query.= 'SELECT ';
            if ($fields) {
                foreach ($fields as $alias => $field) {
                    $alias = is_integer($alias) ? '' : ' as ' . $alias;
                    $this->query.= "{$field}{$alias}, ";
                }
                $this->query = substr($this->query, 0, -2) . ' ';
            } else {
                $this->query.= '* ';
            }
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Set FROM Statement
     * @param string $table
     * @return \KF\Lib\Database\Sql
     * @throws \KF\Lib\Database\Exception
     */
    public function from($table = null, $alias = null) {
        try {
            $this->query.= 'FROM ';
            if ($table) {
                $alias = $alias ? ' as ' . $alias : '';
                $this->query.= "{$table}{$alias} ";
            }
            xd($this->model);
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function join($type, $table, $alias, $where = array()) {
        try {
            
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Set WHERE Clausules
     * @param array $where
     * @return \KF\Lib\Database\Sql
     * @throws \KF\Lib\Database\Exception
     */
    public function where($where = array()) {
        try {
            $this->query.= "WHERE 1=1 ";
            foreach ($where as $field => $value) {
                $this->query.= "AND {$field} = ? ";
                $this->input[] = $value;
            }
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
