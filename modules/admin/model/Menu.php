<?php

namespace Admin\Model;

class Menu extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.menu';
        $this->_sequence = 'public.menu_cod_seq';
        $this->_pk = 'cod';
        
        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('name', self::TYPE_VARCHAR, 100);
        $this->addField('codename', self::TYPE_VARCHAR, 10);
    }

}
