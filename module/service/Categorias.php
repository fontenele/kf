<?php

namespace Kuestions\Service;

class Categorias {

    public function fetchAll($paginator = null, $parsed = true) {
        $categorias = array();
        $model = new \Kuestions\Model\Categorias();
        $result = $model->fetchAll($paginator);
        if ($parsed) {
            foreach ($result as $row) {
                if ($row['pai'] && isset($categorias[$row['pai']])) {
                    if (!isset($categorias[$row['pai']]['filhos'])) {
                        $categorias[$row['pai']]['filhos'] = array();
                    }
                    $categorias[$row['pai']]['filhos'][$row['cod']] = $row;
                } elseif (!isset($categorias[$row['cod']])) {
                    $categorias[$row['cod']] = $row;
                    $categorias[$row['cod']]['filhos'] = array();
                }
            }
            return $categorias;
        } else {
            return $result;
        }
    }

    public function save(&$row) {
        $model = new \Kuestions\Model\Categorias();
        return $model->save($row);
    }

    public function delete($cod) {
        $modelPerguntas = new \Kuestions\Model\Perguntas();
        if ($modelPerguntas->removerCategorias($cod)) {

            $model = new \Kuestions\Model\Categorias();
            return $model->delete($cod);
        }
    }

}
