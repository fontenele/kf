<?php

namespace Admin\Controller;

class UserGroup extends \KF\Lib\Module\Controller {

    public function form() {
        try {
            $form = new \KF\Lib\View\Html\Form();
            $form->addField('cod', 'Cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN);
            $form->addField('name', 'Nome', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT);
            $form->addField('status', 'Status', \KF\Lib\View\Html\Form::TYPE_SELECT, [1 => 'Ativo', 2 => 'Inativo']);
            return $form;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function newItem() {
        try {
            $this->view->form = $this->form();
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
