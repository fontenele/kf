<?php

namespace Admin\Controller;

class Menu extends \KF\Lib\Module\Controller {

    public static $form;

    public function form() {
        try {
            if (!self::$form) {
                $form = new \KF\Lib\View\Html\Form([
                    'action' => \KF\Kernel::$router->basePath . 'admin/menu/new-item',
                    'id' => 'fm-menu'
                ]);
                $form->addField('cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN, 'Cod');
                $form->addField('name', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Nome', ['required' => true, 'placeholder' => 'Nome']);
                $form->addField('submit', \KF\Lib\View\Html\Form::TYPE_BUTTON, 'Salvar Menu', ['class' => 'btn-primary', 'required' => true]);
                self::$form = $form;
            }
            return self::$form;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function newItem() {
        try {
            $service = new \Admin\Service\Menu();
            $form = $this->form();
            
            $this->addViewComponent('tree');
            $this->addExtraJsFile('bootstrap/jquery.jstree.js');

            if ($this->request->get->cod) {
                $row = $service->findOneBy(['cod' => $this->request->get->cod]);
                $form->setValues($row);
                // carregar menu items
            }

            if ($this->request->isPost()) {
                $row = $this->request->post->getArrayCopy();
                xd($row);
                $success = $service->save($row);
                if ($success) {
                    \KF\Lib\System\Messenger::success("Usuário {$row['name']} salvo com sucesso.");
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar salvar usuário {$row['name']}.");
                    $this->redirect('admin/user/new-item');
                }
                $this->redirect('admin/user/list-items');
            }
            
            $this->view->form = $form;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {

            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
