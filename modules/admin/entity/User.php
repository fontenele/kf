<?php

namespace Admin\Entity;

class User extends \KF\Lib\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.user');
        $this->setSequence('public.user_cod_seq');
        $this->setPrimaryKey('cod');
        
        $this->addField(self::createField('cod')->setDbName('cod')->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER));
        $this->addField(self::createField('name')->setDbName('name')->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)->setDbMaxLength(200));
        $this->addField(self::createField('email')->setDbName('email')->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)->setDbMaxLength(150));
        $this->addField(self::createField('password')->setDbName('password')->setDbType(\KF\Lib\Database\Field::DB_TYPE_VARCHAR)->setDbMaxLength(32));
        $this->addField(self::createField('userGroup')
                ->setDbName('user_group')
                ->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER)
                ->setFkEntity(new \Admin\Entity\UserGroup(false))
                ->setFkEntityField('cod')
                ->setFkEntityJoinType(\KF\Lib\Database\Field::DB_JOIN_INNER));
        $this->addField(self::createField('status')->setDbName('status')->setDbType(\KF\Lib\Database\Field::DB_TYPE_INTEGER));
    }

}
