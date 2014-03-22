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
    public $input = [];

    /**
     * @var \KF\Lib\Module\Model
     */
    public $model;
    public $aliases = [];
    public $aliasesFromFields = [];

    /**
     * @param \KF\Lib\Module\Model $model
     * @throws \KF\Lib\Database\Exception
     */
    public function __construct($model = null) {
        try {
            $this->model = $model;
            $alias = 'j' . (count($this->aliases) + 1);
            $this->aliases[$this->model->_table] = $alias;

            foreach ($this->model->_joins as $field => $join) {
                $alias = 'j' . (count($this->aliases) + 1);
                $this->aliases[$join['model']->_table] = $alias;
                $this->aliasesFromFields[$field] = $alias;
            }
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
    public function select($fields = []) {
        try {
            $this->query.= 'SELECT ';
            if ($fields) {
                foreach ($fields as $alias => $field) {
                    $alias = is_integer($alias) ? '' : ' as ' . $alias;
                    $this->query.= "{$field}{$alias}, ";
                }
                $this->query = substr($this->query, 0, -2) . ' ';
            } elseif (count($this->aliases)) {
                foreach ($this->model->fields() as $field => $fieldAttr) {
                    $alias = $this->aliases[$this->model->_table];
                    $this->query.= "{$alias}.{$field}, ";
                }

                foreach ($this->aliases as $table => $alias) {
                    if (in_array($alias, $this->aliasesFromFields) && isset($this->model->_joins[array_search($alias, $this->aliasesFromFields)])) {
                        $model = $this->model->_joins[array_search($alias, $this->aliasesFromFields)]['model'];
                        $_table = explode('.', $table);
                        foreach ($model->fields() as $field => $fieldAttrs) {
                            $this->query.= "{$alias}.{$field} as {$_table[1]}_{$field}, ";
                        }
                    }
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
                $alias = $alias ? $alias : $this->aliases[$table];
                $alias = $alias ? ' as ' . $alias : '';
                $this->query.= "{$table}{$alias} ";
            }

            foreach ($this->model->_joins as $field => $join) {
                $this->join($field, $join['type'], $table, $join['model']->_table, $this->aliases[$join['model']->_table], $join['fk']);
            }

            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function join($field, $type, $tableFrom, $tableFk, $aliasFk, $fieldFk) {
        try {
            switch ($type) {
                case \KF\Lib\Module\Model::JOIN_INNER:
                    $this->query.= 'INNER JOIN ';
                    break;
                case \KF\Lib\Module\Model::JOIN_LEFT:
                    $this->query.= 'LEFT JOIN ';
                    break;
                case \KF\Lib\Module\Model::JOIN_RIGHT:
                    $this->query.= 'RIGHT JOIN ';
                    break;
                case \KF\Lib\Module\Model::JOIN_FULL:
                    $this->query.= 'FULL JOIN ';
                    break;
            }


            $this->query.= "{$tableFk} {$aliasFk} ON ({$aliasFk}.{$fieldFk} = {$this->aliases[$tableFrom]}.{$field}) ";
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
    public function where($where = []) {
        try {
            $this->query.= "WHERE 1=1 ";
            foreach ($where as $field => $value) {
                if ($this->model->field($field)) {
                    $alias = $this->aliases[$this->model->_table];
                    $this->query.= "AND {$alias}.{$field} = ? ";
                } else {
                    $alias = $this->aliases[$this->model->_table];
                    $this->query.= "AND {$field} = ? ";
                }
                $this->input[] = $value;
            }
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function insert($row) {
        try {
            $this->query = "INSERT INTO {$this->model->_table} (";
            $values = '';
            if(isset($row[$this->model->_pk])) {
                unset($row[$this->model->_pk]);
            }
            foreach ($row as $field => $value) {
                $this->query.= "{$field}, ";
                $values.= "?, ";
                $this->input[] = $value;
            }
            $values = substr($values, 0, -2);
            $this->query = substr($this->query, 0, -2) . ") VALUES ({$values})";
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update($where = []) {
        try {
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
