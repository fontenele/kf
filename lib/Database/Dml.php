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
            $table = "{$table}{$alias}";
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
                // @todo
            }
        }
        if ($paginator) {
            $this->query = sprintf($this->query, $_where);
        }
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
        $offset = 0;
        if ($numPage > 1) {
            $offset = $rowsPerPage * ($numPage - 1);
        }
        $this->query.= "LIMIT {$rowsPerPage} OFFSET {$offset} ";
        return $this;
    }

}
