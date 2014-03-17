<?php

namespace Admin\Model;

class User extends \KF\Lib\Module\Model {

    public $_sequence = 'usuarios_cod_seq';
    
    /**
     * @param string $email
     * @param string $pass
     * @return array
     */
    public function auth($email, $pass) {
        try {
            $dml = <<<DML
                    SELECT count(1) as logged FROM usuarios WHERE email = :email AND senha = :senha
DML;
            $stmt = \KF\Kernel::$db->prepare($dml);
            $stmt->execute(array(':email' => $email, ':senha' => $pass));
            return $stmt->fetchAll()[0];
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
