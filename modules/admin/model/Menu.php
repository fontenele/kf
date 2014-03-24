<?php

namespace Admin\Model;

class Menu extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.menu';
        $this->_sequence = 'menu_cod_seq';
        $this->_pk = 'cod';
    }

}
