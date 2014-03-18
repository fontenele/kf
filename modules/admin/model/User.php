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
        $this->addField('user_group', self::TYPE_INTEGER, null, self::JOIN_INNER, 'user_group', 'cod');
        $this->addField('status', self::TYPE_INTEGER);
    }

}
