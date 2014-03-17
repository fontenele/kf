<?php

namespace Kuestions\Service;

class Perguntas {
    
    public function fetchAll($paginator) {
        $model = new \Kuestions\Model\Perguntas();
        return $model->fetchAll($paginator);
    }

    public function save(&$row) {
        $model = new \Kuestions\Model\Perguntas();
        return $model->save($row);
    }
    
    public function removerCategorias($categoria) {
        $model = new \Kuestions\Model\Perguntas();
        return $model->removerCategorias($categoria);
    }

}
