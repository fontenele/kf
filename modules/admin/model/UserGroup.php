<?php

namespace Admin\Model;

class UserGroup extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.user_group';
        $this->_sequence = 'public.user_group_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('name', self::TYPE_VARCHAR, 50);
        $this->addField('status', self::TYPE_INTEGER);
    }

}
