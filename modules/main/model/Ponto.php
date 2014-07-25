<?php

namespace Main\Model;

class Ponto extends \KF\Lib\Module\Model {

    public function __construct() {
        $this->_table = 'public.ponto';
        $this->_sequence = 'public.ponto_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('folha', self::TYPE_INTEGER, null, self::JOIN_INNER, new \Main\Model\Folha, 'cod');
        $this->addField('funcionario', self::TYPE_INTEGER, null, self::JOIN_INNER, new \Main\Model\Funcionarios, 'cod');
        $this->addField('dia', self::TYPE_INTEGER);
        $this->addField('mes', self::TYPE_INTEGER);
        $this->addField('feriado', self::TYPE_BOOLEAN);
        $this->addField('horario1', self::TYPE_TIME);
        $this->addField('horario2', self::TYPE_TIME);
        $this->addField('horario3', self::TYPE_TIME);
        $this->addField('horario4', self::TYPE_TIME);
        $this->addField('horario5', self::TYPE_TIME);
        $this->addField('horario6', self::TYPE_TIME);
        $this->addField('total', self::TYPE_VARCHAR, 10);
        $this->addField('total_extra', self::TYPE_VARCHAR, 10);
    }

}
