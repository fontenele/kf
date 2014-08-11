<?php

namespace Admin\Entity;

class Access extends \KF\Lib\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access'); // Table
        $this->setSequence('public.access_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\Access'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\KF\Lib\View\Html\InputHidden::create('cod')));
        // Key
        $this->addField(self::createField('key')
                        ->setDbName('key')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(200)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\KF\Lib\Database\Criteria::create(\KF\Lib\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(1, 'Chave', '45%'))
                        ->setViewComponent(\KF\Lib\View\Html\InputText::create('key', 'Chave')
                                ->setRequired(true)
                                ->setPlaceholder('Nome da Chave de acesso')));
        // Description
        $this->addField(self::createField('description')
                        ->setDbName('description')
                        ->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(300)
                        ->setSearchCriteria(\KF\Lib\Database\Criteria::create(\KF\Lib\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header::create(1, 'Descrição', '45%'))
                        ->setViewComponent(\KF\Lib\View\Html\InputText::create('description', 'Descrição')
                                ->setRequired(true)
                                ->setPlaceholder('Descrição da Chave de acesso')));
    }

}
