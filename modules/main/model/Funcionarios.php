<?php

namespace Main\Model;

class Funcionarios extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.funcionarios';
        $this->_sequence = 'public.funcionarios_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('name', self::TYPE_VARCHAR, 200);
        $this->addField('cargo', self::TYPE_INTEGER, null, self::JOIN_INNER, new \Main\Model\Cargos, 'cod');
    }

}
