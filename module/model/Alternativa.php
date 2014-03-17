<?php

namespace Kuestions\Model;

class Alternativa {

    public $sequence = 'alternativa_cod_seq';

    public function save(&$row) {
        try {
            if (isset($row['cod']) && $row['cod']) {
                return $this->update($row);
            } else {
                return $this->insert($row);
            }
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function insert(&$row) {
        try {
            $dml = <<<DML
                INSERT INTO alternativa (descricao) VALUES (?)
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $salvou = $stmt->execute(array(
                $row['descricao']
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
                    UPDATE alternativa
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

}
