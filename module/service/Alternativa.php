<?php

namespace Kuestions\Service;

class Alternativa {

    public function save(&$row) {
        $model = new \Kuestions\Model\Alternativa();
        return $model->save($row);
    }

}
