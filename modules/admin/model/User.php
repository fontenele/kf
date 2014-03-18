<?php

namespace Admin\Model;

class User extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.user';
        $this->_sequence = 'user_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('email', self::TYPE_VARCHAR, 150);
        $this->addField('password', self::TYPE_VARCHAR, 32);
        $this->addField('name', self::TYPE_VARCHAR, 200);
        $this->addField('perfil', self::TYPE_INTEGER);
        $this->addField('status', self::TYPE_INTEGER);
    }

    /**
     * @param string $email
     * @param string $pass
     * @return array
     */
    public function auth($email, $pass) {
        try {
            $dml = <<<DML
                    SELECT count(1) as logged FROM {$this->_table} WHERE email = :email AND password = :password
DML;
            return $this->fetch($dml, array(':email' => $email, ':password' => $pass))['logged'];
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
