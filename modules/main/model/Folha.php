<?php

namespace Main\Model;

class Folha extends \KF\Lib\Module\Model {
    
    const STATUS_ABERTA = 1;
    const STATUS_FECHADA = 2;

    public function __construct() {
        $this->_table = 'public.folha';
        $this->_sequence = 'public.folha_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('mes', self::TYPE_INTEGER);
        $this->addField('ano', self::TYPE_INTEGER);
        $this->addField('status', self::TYPE_INTEGER);
    }

}
