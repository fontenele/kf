<?php

namespace Kuestions\Service;

class Usuarios {

    public function auth($email, $senha) {
        $model = new \Kuestions\Model\Usuarios();
        return $model->auth($email, $senha);
    }

}
