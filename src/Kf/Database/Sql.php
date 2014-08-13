<?php

namespace KF\Database;

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
     * @var \KF\Module\Model
     */
    public $model;

    /**
     * @var array
     */
    public $aliases = [];

    /**
     * @var array
     */
    public $aliasesFromFields = [];

    /**
     * @var boolean
     */
    public static $debug = false;

    /**
     * @param \KF\Module\Model $model
     * @throws \KF\Database\Exception
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
     * @return \KF\Database\Sql
     * @throws \KF\Database\Exception
     */
    public function select($fields = [], $paginator = false) {
        try {
            $this->query.= 'SELECT ';
            if ($paginator) {
                $this->query.= "(SELECT count(1) FROM {$this->model->_table} %s) as kf_dg_total, ";
            }
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
     * @return \KF\Database\Sql
     * @throws \KF\Database\Exception
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
                case \KF\Module\Model::JOIN_INNER:
                    $this->query.= 'INNER JOIN ';
                    break;
                case \KF\Module\Model::JOIN_LEFT:
                    $this->query.= 'LEFT JOIN ';
                    break;
                case \KF\Module\Model::JOIN_RIGHT:
                    $this->query.= 'RIGHT JOIN ';
                    break;
                case \KF\Module\Model::JOIN_FULL:
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
     * @param integer $rowsPerPage
     * @param integer $numPage
     * @param array $whereConditions
     * @param array $orderBy
     * @return \KF\Database\Sql
     * @throws \KF\Database\Exception
     */
    public function where($where = [], $rowsPerPage = null, $numPage = 1, $whereConditions = [], $orderBy = []) {
        try {
            $this->query.= "WHERE 1=1 ";

            $_where = '';

            foreach ($where as $field => $value) {
                $_conditionOperator = \KF\View\Html\Datagrid::CRITERIA_CONDITION_EQUAL;
                if (isset($whereConditions[$field]) && $whereConditions[$field]) {
                    $_conditionOperator = $whereConditions[$field];
                }

                if ($this->model->field($field)) {
                    $alias = $this->aliases[$this->model->_table];

                    $this->query.= "AND {$this->parseWhereFieldValue("{$alias}.{$field}", $value, $_conditionOperator)}";
                    $_where.= "AND {$this->parseWhereFieldValue($field, $value, $_conditionOperator)}";
                } elseif (strstr($field, '.')) {
                    $field = explode('.', $field);
                    $alias = $this->aliasesFromFields[array_shift($field)];
                    $field = array_shift($field);

                    $this->query.= "AND {$this->parseWhereFieldValue("{$alias}.{$field}", $value, $_conditionOperator)}";
                    $_where.= "AND {$this->parseWhereFieldValue("{$alias}.{$field}", $value, $_conditionOperator)}";
                } else {
                    $this->query.= "AND {$this->parseWhereFieldValue($field, $value, $_conditionOperator)}";
                    $_where.= "AND {$this->parseWhereFieldValue($field, $value, $_conditionOperator)}";
                }
            }

            $this->query = sprintf($this->query, $rowsPerPage ? ('WHERE 1=1 ' . sprintf($_where)) : '');

            if ($rowsPerPage) {
                // Order By
                if ($orderBy) {
                    foreach ($orderBy as $field => $orderType) {
                        $field = explode('.', $field);
                        $alias = $this->aliasesFromFields[$field[0]];
                        $this->query.= "ORDER BY {$alias}.{$field[1]} {$orderType} ";
                    }
                } else {
                    $this->query.= "ORDER BY {$this->aliases[$this->model->_table]}.{$this->model->_pk} ";
                }

                $offset = 0;
                $rowsPerPage = $rowsPerPage ? $rowsPerPage : \KF\Kernel::$config['system']['view']['datagrid']['rowsPerPage'];
                if ($numPage > 1) {
                    $offset = $rowsPerPage * ($numPage - 1);
                }
                $this->query.= "LIMIT {$rowsPerPage} OFFSET {$offset}";
            } elseif (substr($this->query, 0, 6) != 'DELETE') {
                if ($orderBy) {
                    foreach ($orderBy as $field => $orderType) {
                        $field = explode('.', $field);
                        $alias = $this->aliasesFromFields[$field[0]];
                        $this->query.= "ORDER BY {$alias}.{$field[1]} {$orderType} ";
                    }
                }
            }

            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    protected function parseWhereFieldValue($field, $value, $criteriaCondition = \KF\View\Html\Datagrid::CRITERIA_CONDITION_EQUAL) {
        try {
            switch ($criteriaCondition) {
                case \KF\View\Html\Datagrid::CRITERIA_CONDITION_EQUAL:
                    return "{$field} = '{$value}' ";
                case \KF\View\Html\Datagrid::CRITERIA_CONDITION_LIKE:
                    $value = strtoupper($value);
                    return "UPPER({$field}) LIKE '%%{$value}%%' ";
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    protected function parseFieldValue($field, &$value) {
        try {
            switch ($this->model->field($field)['type']) {
                case \KF\Module\Model::TYPE_BOOLEAN:
                    if(!$value) {
                        $value = 0;
                    }
                    break;
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function insert($row) {
        try {
            $this->query = "INSERT INTO {$this->model->_table} (";
            $values = '';
            if (isset($row[$this->model->_pk])) {
                unset($row[$this->model->_pk]);
            }
            foreach ($row as $field => $value) {
                $this->query.= "{$field}, ";
                $values.= "?, ";
                $this->parseFieldValue($field, $value);
                $this->input[] = $value;
            }
            $values = substr($values, 0, -2);
            $this->query = substr($this->query, 0, -2) . ") VALUES ({$values})";
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update($row, $where = []) {
        try {
            $_where = ' WHERE 1=1 ';
            $this->query = "UPDATE {$this->model->_table} SET ";
            $pk = isset($row[$this->model->_pk]) ? $row[$this->model->_pk] : null;
            if($pk) {
                $_where.= " AND {$this->model->_pk}=?";
                unset($row[$this->model->_pk]);
            }
            foreach ($row as $field => $value) {
                $this->query.= "{$field}=?, ";
                $this->parseFieldValue($field, $value);
                $this->input[] = $value;
            }
            foreach($where as $param => $val) {
                $_where.= " AND {$param}=?";
                $this->input[] = $val;
            }
            if($pk) {
                $this->query = substr($this->query, 0, -2);
                $this->input[] = $pk;
            }
            $this->query.= $_where;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function delete($where) {
        try {
            $alias = isset($this->aliases[$this->model->_table]) ? $this->aliases[$this->model->_table] : '';
            $this->query = "DELETE FROM {$this->model->_table} as {$alias} ";
            $this->where($where);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function createTable() {
        try {
            $schemeTable = explode('.', $this->model->_table);
            $scheme = array_shift($schemeTable);
            $table = array_shift($schemeTable);
            $pk = $this->model->_pk;
            $seq = $this->model->_sequence;
            $joins = $this->model->_joins;

            // Table
            $this->query = "CREATE TABLE {$scheme}.{$table} (";

            $i = 1;
            foreach ($this->model->_fields as $field => $dataField) {
                $this->query.= "{$field} ";
                switch ($dataField['type']) {
                    case \KF\Module\Model::TYPE_INTEGER:
                        $this->query.= "integer";
                        break;
                    case \KF\Module\Model::TYPE_VARCHAR:
                        $this->query.= "character varying" . ($dataField['length'] ? "({$dataField['length']})" : '');
                        break;
                    case \KF\Module\Model::TYPE_DATE:
                        $this->query.= "date without time zone";
                        break;
                    case \KF\Module\Model::TYPE_TIME:
                        $this->query.= "time without time zone";
                        break;
                    case \KF\Module\Model::TYPE_DATETIME:
                        $this->query.= "datetime without time zone";
                        break;
                    case \KF\Module\Model::TYPE_MONEY:
                        $this->query.= "money";
                        break;
                    case \KF\Module\Model::TYPE_BOOLEAN:
                        $this->query.= "boolean";
                        break;
                }

                if ($field == $pk) {
                    $this->query.= " NOT NULL";
                }

                if ($i != count($this->model->_fields)) {
                    $this->query.= ", ";
                }
                $i++;
            }

            $this->query.= ");";

            // Sequence
            $this->query.= "\nCREATE SEQUENCE {$seq} START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;";

            // Attach sequence to primary key
            $this->query.= "\nALTER SEQUENCE {$seq} OWNED BY {$scheme}.{$table}.{$pk};";

            $this->query.= "\nALTER TABLE ONLY {$scheme}.{$table} ALTER COLUMN {$pk} SET DEFAULT nextval('{$seq}'::regclass);";
            $this->query.= "\nALTER TABLE ONLY {$scheme}.{$table} ADD CONSTRAINT {$table}_pk PRIMARY KEY ({$pk});";

            if (count($joins)) {
                foreach ($joins as $fieldJoin => $dataField) {
                    $tableJoin = $dataField['model']->_table;
                    $pkJoin = $dataField['model']->_pk;
                    $this->query.= "\nALTER TABLE ONLY {$scheme}.{$table} ADD CONSTRAINT {$table}_{$fieldJoin}_fk FOREIGN KEY ({$fieldJoin}) REFERENCES {$tableJoin}({$pkJoin});";
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Return the SQL query
     * @return string
     * @throws \KF\Database\Exception
     */
    public function getQuery() {
        try {
            $sql = $this->query;
            foreach ($this->input as $input) {
                $posInput = strpos($sql, '?');
                if ($posInput) {
                    $left = str_replace('?', $input, substr($sql, 0, $posInput + 1));
                    $sql = substr_replace($sql, $left, 0, $posInput + 1);
                }
            }
            return $sql;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
