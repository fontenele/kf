<?php

namespace Admin\Model;

class MenuItem extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.menu_item';
        $this->_sequence = 'public.menu_item_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('menu', self::TYPE_INTEGER, null, self::JOIN_INNER, new \Admin\Model\Menu, 'cod');
        //$this->addField('parent', self::TYPE_INTEGER, null, self::JOIN_INNER, clone $this, 'cod');
        $this->addField('parent', self::TYPE_INTEGER);
        $this->addField('name', self::TYPE_VARCHAR, 100);
        $this->addField('link', self::TYPE_VARCHAR, 500);
    }

}
