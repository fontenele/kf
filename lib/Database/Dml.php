<?php

namespace KF\Lib\Database;

class Dml {

    /**
     * @var array
     */
    public $aliases = [];

    /**
     * @var array
     */
    public $aliasesFromFields = [];

    /**
     * @var string
     */
    public $query = '';

    /**
     * @var array
     */
    public $input = [];

    /**
     * Debug toggle
     * @var boolean
     */
    public static $debug = false;

    /**
     * @return Dml
     */
    public static function createDml() {
        $dml = new $this;
        return $dml;
    }

    public function select($fields = [], $paginator = false) {
        $this->query.= 'SELECT ';
        if ($paginator) {
            $this->query.= "(SELECT count(1) FROM {$this->getTable()} %s) as kf_dg_total, ";
        }
        if ($fields) {
            foreach ($fields as $alias => $field) {
                $alias = is_integer($alias) ? '' : " as {$alias}";
                $this->query.= "{$field}{$alias}, ";
            }
        } elseif ($this->getFields()) {
            foreach ($this->getFields() as $field => $fieldData) {
                $alias = isset($this->aliases[$this->getTable()]) ? "{$this->aliases[$this->getTable()]}." : '';
                $this->query.= "{$alias}{$fieldData->getDbName()}, ";
                if ($fieldData->getFkEntity()) {
                    foreach ($fieldData->getFkEntity()->getFields() as $fkField => $fkFieldData) {
                        $fkTable = $fieldData->getFkEntity()->getTable();
                        $fkAlias = isset($this->aliases[$fkTable]) ? "{$this->aliases[$fkTable]}." : '';
                        $this->query.= "{$fkAlias}{$fkField} as {$fieldData->getDbName()}_{$fkField}, ";
                    }
                }
            }
        } else {
            $alias = $this->getTable() && isset($this->aliases[$this->getTable()]) ? "{$this->aliases[$this->getTable()]}." : '';
            $this->query.= "{$alias}*, ";
        }

        $this->query = substr($this->query, 0, -2) . ' ';
        return $this;
    }

    public function from($table = null, $alias = null) {
        $this->query.= 'FROM ';
        $alias = $alias ? " as {$alias}" : '';
        $table = '';
        if ($table) {
            $alias = !$alias && isset($this->aliases[$table]) ? " as {$this->aliases[$table]} " : '';
            $table = $table;
        } elseif ($this->getTable()) {
            $alias = !$alias && $this->getTable() && isset($this->aliases[$this->getTable()]) ? " as {$this->aliases[$this->getTable()]} " : '';
            $table = $this->getTable();
        }
        $this->query.= "{$table}{$alias}";

        foreach ($this->getFields() as $field => $fieldData) {
            if ($fieldData->getFkEntity()) {
                $this->join($fieldData->getFkEntityJoinType(), $fieldData->getFkEntity()->getTable(), $fieldData->getFkEntityField(), $fieldData->getDbName());
            }
        }

        return $this;
    }

    public function join($type, $tableFk, $fieldFk, $fieldLocal) {
        switch ($type) {
            case Field::DB_JOIN_INNER:
                $this->query.= 'INNER JOIN ';
                break;
            case Field::DB_JOIN_LEFT:
                $this->query.= 'LEFT JOIN ';
                break;
            case Field::DB_JOIN_RIGHT:
                $this->query.= 'RIGHT JOIN ';
                break;
            case Field::DB_JOIN_FULL:
                $this->query.= 'FULL JOIN ';
                break;
        }
        $aliasFk = isset($this->aliases[$tableFk]) ? $this->aliases[$tableFk] : '';
        $aliasLocal = $this->getTable() && isset($this->aliases[$this->getTable()]) ? $this->aliases[$this->getTable()] : '';
        $this->query.= "{$tableFk} {$aliasFk} ON ({$aliasFk}.{$fieldFk} = {$aliasLocal}.{$fieldLocal})";
        return $this;
    }

    public function where($where = [], $paginator = false) {
        $_where = 'WHERE 1=1 ';
        if ($where) {
            foreach ($where as $field => $value) {
                if ($this->getField($field) && $value) {
                    if ($this->getField($field)->getSearchCriteria()) {
                        $upper = $this->getField($field)->getSearchCriteria()->getUpper();
                        switch ($this->getField($field)->getSearchCriteria()->getType()) {
                            case Criteria::CONDITION_EQUAL:
                                $_field = $upper ? "UPPER({$this->aliases[$this->getTable()]}.{$field})" : "{$this->aliases[$this->getTable()]}.{$field}";
                                $_value = $upper ? "UPPER({$this->parseFieldValue($field, $value)})" : $this->parseFieldValue($field, $value);
                                $_where.= "AND {$_field} = {$_value} ";
                                break;
                            case Criteria::CONDITION_LIKE:
                                $_field = $upper ? "UPPER({$this->aliases[$this->getTable()]}.{$field})" : "{$this->aliases[$this->getTable()]}.{$field}";
                                $_value = $upper ? "UPPER('%{$value}%')" : "'%{$value}%'";
                                $_where.= "AND {$_field} LIKE {$_value} ";
                                break;
                            case Criteria::CONDITION_BETWEEN:
                                /**
                                 * @todo
                                 */
                                xd('@TODO');
                                $_where.= "AND {$this->aliases[$this->getTable()]}.{$field} BETWEEN {$this->parseFieldValue($field, $value)} AND {???otherValue???} ";
                                break;
                        }
                    } else {
                        $_where.= "AND {$this->aliases[$this->getTable()]}.{$field} = {$this->parseFieldValue($field, $value)} ";
                    }
                }
            }
        }
        if ($paginator) {
            $this->query = sprintf($this->query, $_where);
        }

        $this->query.= $_where;
        return $this;
    }

    public function orderBy($fields = []) {
        if ($fields) {
            $this->query.= "ORDER BY ";
            $_orderBy = '';
            foreach ($fields as $field => $sortType) {
                if ($this->getField($field)) {
                    $_orderBy.= "{$this->aliases[$this->getTable()]}.{$field} {$sortType}, ";
                }
            }
            $this->query.= substr($_orderBy, 0, -2) . ' ';
        } elseif ($this instanceof \KF\Lib\Module\Entity) {
            $orderBy = [];
            $_orderBy = '';
            foreach ($this->getFields() as $field => $fieldData) {
                if ($fieldData->getDbOrderBySequence()) {
                    $orderBy[$fieldData->getDbOrderBySequence()] = [$field, $fieldData->getDbOrderBySortType()];
                }
            }
            if (count($orderBy)) {
                $this->query.= "ORDER BY ";
                foreach ($orderBy as $fieldData) {
                    $field = array_shift($fieldData);
                    $sortType = array_shift($fieldData);
                    if ($this->getField($field)) {
                        $_orderBy.= "{$this->aliases[$this->getTable()]}.{$field} {$sortType}, ";
                    }
                }
                $this->query.= substr($_orderBy, 0, -2) . ' ';
            }
        }
        return $this;
    }

    public function paginate($numPage, $rowsPerPage) {
        if (!$rowsPerPage) {
            return $this;
        }
        $offset = 0;
        if ($numPage > 1) {
            $offset = $rowsPerPage * ($numPage - 1);
        }
        $this->query.= "LIMIT {$rowsPerPage} OFFSET {$offset} ";
        return $this;
    }

    public function insert($row) {
        try {
            $this->query = "INSERT INTO {$this->getTable()} (";
            $values = '';
            if (isset($row[$this->getPrimaryKey()])) {
                unset($row[$this->getPrimaryKey()]);
            }
            foreach ($row as $field => $value) {
                $this->query.= "{$field}, ";
                $values.= "?, ";
                $value = $this->parseFieldValue($field, $value);
                $this->input[] = $value;
            }
            $values = substr($values, 0, -2);
            $this->query = substr($this->query, 0, -2) . ") VALUES ({$values})";
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function update($row, $where = []) {
        try {
            $_where = ' WHERE 1=1 ';
            $this->query = "UPDATE {$this->getTable()} SET ";
            $pk = isset($row[$this->getPrimaryKey()]) ? $row[$this->getPrimaryKey()] : null;
            if ($pk) {
                $_where.= " AND {$this->getPrimaryKey()}=?";
                unset($row[$this->getPrimaryKey()]);
            }
            foreach ($row as $field => $value) {
                $this->query.= "{$field}=?, ";
                $value = $this->parseFieldValue($field, $value);
                $this->input[] = $value;
            }
            foreach ($where as $param => $val) {
                $_where.= " AND {$param}=?";
                $this->input[] = $val;
            }
            if ($pk) {
                $this->query = substr($this->query, 0, -2);
                $this->input[] = $pk;
            }
            $this->query.= $_where;
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function delete($where) {
        try {
            $alias = isset($this->aliases[$this->getTable()]) ? $this->aliases[$this->getTable()] : '';
            $this->query = "DELETE FROM {$this->getTable()} as {$alias} ";
            $this->where($where);
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    protected function parseFieldValue($field, &$value) {
        try {
            switch ($this->getField($field)->getDbType()) {
                case Field::DB_TYPE_INTEGER:
                    $value = (int) $value;
                    break;
                case Field::DB_TYPE_VARCHAR:
                    if (!$value) {
                        $value = '';
                    }
                    //$value = "'{$value}'";
                    break;
                case Field::DB_TYPE_BOOLEAN:
                    if (!$value) {
                        $value = 0;
                    }
                    break;
            }
            return $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
