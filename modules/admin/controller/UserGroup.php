<?php

namespace Admin\Controller;

class UserGroup extends \KF\Lib\Module\Controller {

    public static $form;

    public function form() {
        try {
            if (!self::$form) {
                $form = new \KF\Lib\View\Html\Form([
                    'action' => \KF\Kernel::$router->basePath . 'admin/user-group/new-item',
                    'method' => \KF\Lib\View\Html\Form::METHOD_POST
                ]);
                $form->addField('cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN, 'Cod');
                $form->addField('name', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Nome', ['required' => true, 'placeholder' => 'Nome do Perfil']);
                $form->addField('status', \KF\Lib\View\Html\Form::TYPE_SELECT, 'Status', ['options' => [1 => 'Ativo', 2 => 'Inativo'], 'required' => true]);
                $form->addField('submit', \KF\Lib\View\Html\Form::TYPE_BUTTON, 'Salvar', ['class' => 'btn-primary', 'required' => true]);
                self::$form = $form;
            }
            return self::$form;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function newItem() {
        try {
            $this->view->form = $this->form();
            if ($this->request->isPost()) {
                $service = new \Admin\Service\UserGroup();
                $row = $this->request->post->getArrayCopy();
                $success = $service->save($row);
                
                if($success) {
                    \KF\Lib\System\Messenger::success("Perfil {$row['name']} salvo com sucesso.");
                    $this->redirect('/admin/user-group/new-item');
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar salvar Perfil {$row['name']}.");
                    $this->redirect('/admin/user-group/new-item');
                }
            }
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
