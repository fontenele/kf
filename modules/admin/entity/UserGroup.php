<?php

namespace Admin\Entity;

class UserGroup extends \KF\Lib\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.user_group');
        $this->setSequence('public.user_group_cod_seq');
        $this->setPrimaryKey('cod');

        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(new \KF\Lib\View\Html\Input('cod', '', 'hidden')));

        $this->addField(self::createField('name')
                        ->setDbName('name')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(50)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setDatagridHeader(new \KF\Lib\View\Html\Datagrid\Header(1, 'Grupo', '85%'))
                        ->setViewComponent(new \KF\Lib\View\Html\InputText('name', 'Nome')));

        $this->addField(self::createField('status')
                        ->setDbName('status')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(2)
                        ->setDbOrderBySortType('ASC')
                        ->setDatagridHeader(new \KF\Lib\View\Html\Datagrid\Header(2, 'Status', '10%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\UserGroup::dgStatus')))
                        ->setViewComponent(new \KF\Lib\View\Html\Select('status', 'Status', ['options' => ['1' => 'Ativo', '2' => 'Inativo']])));
    }

}
