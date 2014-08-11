<?php

namespace Admin\Entity;

class User extends \KF\Lib\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.user'); // Table
        $this->setSequence('public.user_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\User'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\KF\Lib\View\Html\InputHidden::create('cod')));
        // Name
        $this->addField(self::createField('name')
                        ->setDbName('name')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(200)
                        ->setDbOrderBySequence(2)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\KF\Lib\Database\Criteria::create(\KF\Lib\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(1, 'Nome', '30%'))
                        ->setViewComponent(\KF\Lib\View\Html\InputText::create('name', 'Nome')
                                ->setRequired(true)
                                ->setPlaceholder('Nome do Usuário')));
        // E-mail
        $this->addField(self::createField('email')
                        ->setDbName('email')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(150)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\KF\Lib\Database\Criteria::create(\KF\Lib\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(2, 'E-mail', '20%'))
                        ->setViewComponent(\KF\Lib\View\Html\InputText::create('email', 'E-mail')
                                ->setRequired(true)
                                ->setPlaceholder('E-mail do Usuário')));
        // Password
        $this->addField(self::createField('password')
                        ->setDbName('password')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(32)
                        ->setViewComponent(\KF\Lib\View\Html\InputPassword::create('password', 'Senha')
                                ->setRequired(true)
                                ->setPlaceholder('Senha')));
        // UserGroup
        $this->addField(self::createField('user_group')
                        ->setDbName('user_group')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setFkEntity(new \Admin\Entity\UserGroup(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\KF\Lib\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(3, 'Grupo', '20%'))
                        ->setViewComponent(\KF\Lib\View\Html\Select::create('user_group', 'Grupo')
                                ->setRequired(true)));
        // Status
        $this->addField(self::createField('status')
                        ->setDbName('status')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(4, 'Status', '10%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\User::dgStatus')))
                        ->setViewComponent(\KF\Lib\View\Html\Select::create('status', 'Status', ['options' => ['1' => 'Ativo', '2' => 'Inativo']])
                                ->setRequired(true)));
    }

}
