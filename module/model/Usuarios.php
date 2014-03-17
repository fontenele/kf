<?php

namespace Kuestions\Model;

class Usuarios {

    public $sequence = 'usuarios_cod_seq';

    /**
     * 
     * @param \Kuestions\Lib\View\Helper\Paginator $paginator
     * @return type
     */
    public function auth($email, $senha) {
        try {
            $dml = <<<DML
                    SELECT count(1) as logged FROM usuarios WHERE email = :email AND senha = :senha
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $stmt->execute(array(':email' => $email, ':senha' => $senha));
            return $stmt->fetchAll()[0];
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
