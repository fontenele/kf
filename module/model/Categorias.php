<?php

namespace Kuestions\Model;

class Categorias {

    public $sequence = 'categorias_cod_seq';

    public function fetchAll($paginator = null) {
        try {
            $rowsPerPage = \Kuestions\System::$config['system']['view']['datagrid']['rowsPerPage'];
            $offset = 0;

            $where = 'WHERE 1 = 1';
            if ($paginator) {
                $rowsPerPage = $paginator->rowPerPage;
                if ($paginator->active > 1) {
                    $offset = $rowsPerPage * ($paginator->active - 1);
                }
                if (count($paginator->criteria)) {
                    foreach ($paginator->criteria as $criteria => $value) {
                        if (trim($value)) {
                            if ($criteria == 'nome') {
                                $where.= " AND UPPER(c.{$criteria}) LIKE UPPER('%{$value}%')";
                            } else {
                                $where.= " AND c.{$criteria} = {$value}";
                            }
                        }
                    }
                }
            }

            $dml = <<<DML
                SELECT
                    (SELECT count(1) FROM categorias c {$where}) as total,
                    c.cod,
                    c.pai,
                    c.nome,
                    p.nome as pai_desc
                FROM
                    categorias c
                    LEFT JOIN categorias p ON (p.cod = c.pai)
                {$where}
                    ORDER BY c.pai DESC, c.cod
                    LIMIT {$rowsPerPage} OFFSET {$offset}
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function save(&$row) {
        try {
            if (isset($row['cod']) && $row['cod']) {
                return $this->update($row);
            } else {
                // Nova pergunta deve ser definida com status = 2 (Inativo)
                $row['status'] = 2;
                return $this->insert($row);
            }
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function insert(&$row) {
        try {
            $dml = <<<DML
                INSERT INTO categorias (pai, nome) VALUES (?, ?)
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $salvou = $stmt->execute(array(
                $row['pai'] == 0 ? null : $row['pai'],
                $row['nome']
            ));

            if ($salvou) {
                $row['cod'] = \Kuestions\System::$db->lastInsertId($this->sequence);
            }

            return $salvou;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function update(&$row) {
        try {
            $data = $row;
            unset($data['cod']);
            $set = '';

            foreach ($data as $dataIndex => $dataValue) {
                $set.= ",{$dataIndex}='{$dataValue}'";
            }

            $set = substr($set, 1);

            $dml = <<<DML
                    UPDATE categorias
                        SET {$set}
                    WHERE cod = ?
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            return $stmt->execute(array(
                        $row['cod']
            ));
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function delete($cod) {
        try {
            $dml = <<<DML
                DELETE FROM categorias WHERE cod = ?
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            return $stmt->execute(array($cod));
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
