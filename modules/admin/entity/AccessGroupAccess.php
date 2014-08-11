<?php

namespace Admin\Entity;

class AccessGroupAccess extends \KF\Lib\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access_group_access'); // Table
        $this->setSequence('public.access_group_access_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\AccessGroupAccess'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\KF\Lib\View\Html\InputHidden::create('cod')));
        // AccessGroup
        $this->addField(self::createField('access_group')
                        ->setDbName('access_group')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setFkEntity(new \Admin\Entity\AccessGroup(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\KF\Lib\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(1, 'Grupo', '45%'))
                        ->setViewComponent(\KF\Lib\View\Html\Select::create('access_group', 'Grupo')
                                ->setRequired(true)));
        // Access
        $this->addField(self::createField('access')
                        ->setDbName('access')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setFkEntity(new \Admin\Entity\Access(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\KF\Lib\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(2, 'Acesso', '45%'))
                        ->setViewComponent(\KF\Lib\View\Html\Select::create('access', 'Acesso')
                                ->setRequired(true)));
    }

}
