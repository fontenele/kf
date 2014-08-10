<?php

namespace Admin\Entity;

class UserGroup extends \KF\Lib\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.user_group'); // Table
        $this->setSequence('public.user_group_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\UserGroup'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\KF\Lib\View\Html\InputHidden::create('cod')));
        // Name
        $this->addField(self::createField('name')
                        ->setDbName('name')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(50)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\KF\Lib\Database\Criteria::create(\KF\Lib\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(1, 'Grupo', '85%'))
                        ->setViewComponent(\KF\Lib\View\Html\InputText::create('name', 'Nome')
                                ->setRequired(true)
                                ->setPlaceholder('Nome do Grupo')));
        // Status
        $this->addField(self::createField('status')
                        ->setDbName('status')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(2)
                        ->setDbOrderBySortType('ASC')
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(2, 'Status', '10%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\UserGroup::dgStatus')))
                        ->setViewComponent(\KF\Lib\View\Html\Select::create('status', 'Status', ['options' => ['1' => 'Ativo', '2' => 'Inativo']])
                                ->setRequired(true)));
    }

}
