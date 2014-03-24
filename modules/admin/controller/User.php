<?php

namespace Admin\Controller;

class User extends \KF\Lib\Module\Controller {

    public static $form;

    public function form() {
        try {
            if (!self::$form) {
                $form = new \KF\Lib\View\Html\Form([
                    'action' => \KF\Kernel::$router->basePath . 'admin/user/new-item',
                    'id' => 'fm-user'
                ]);
                $service = new \Admin\Service\UserGroup();
                $form->addField('cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN, 'Cod');
                $form->addField('name', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Nome', ['required' => true, 'placeholder' => 'Nome']);
                $form->addField('email', \KF\Lib\View\Html\Form::TYPE_INPUT_EMAIL, 'E-mail', ['required' => true, 'placeholder' => 'E-mail']);
                $form->addField('password', \KF\Lib\View\Html\Form::TYPE_INPUT_PASSWORD, 'Senha', ['required' => true, 'placeholder' => 'Senha']);
                $form->addField('user_group', \KF\Lib\View\Html\Form::TYPE_SELECT, 'Grupo', ['options' => \KF\Lib\View\Html\Select::rows2options($service->fetchAll()), 'required' => true]);
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
            $service = new \Admin\Service\User();
            $form = $this->form();

            if ($this->request->get->cod) {
                $row = $service->findOneBy(['cod' => $this->request->get->cod]);
                $row['password'] = null;
                $form->setValues($row);
            }

            if ($this->request->isPost()) {
                $row = $this->request->post->getArrayCopy();
                $row['password'] = md5($row['password']);
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
            $dg = new \KF\Lib\View\Html\Datagrid('#fm-user', $this->request->post->getArrayCopy());
            $dg->addHeader('name', 'Nome', '45%');
            $dg->addHeader('email', 'E-mail', '20%');
            $dg->addHeader('user_group_name', 'Grupo', '20%');
            $dg->addHeader('status', 'Status', '10%', 'text-center', '\Admin\Controller\User::dgStatus');
            $dg->addHeader('', '', '5%', 'text-center', '\Admin\Controller\User::dgEdit');

            $service = new \Admin\Service\User();
            $dg->setRows($service->fetchAll($dg->criteria, $dg->rowPerPage, $dg->active));

            $form = $this->form();
            $form->action = \KF\Kernel::$router->basePath . 'admin/user/list-items';
            $form->submit->label = $form->submit->content = \KF\Lib\View\Html\Helper\Glyphicon::get('search') . ' Pesquisar';
            $form->name->offsetUnset('required');
            $form->email->offsetUnset('required');
            $form->user_group->offsetUnset('required');
            $form->status->offsetUnset('required');
            $form->setValues($this->request->post->getArrayCopy());

            $this->view->form = $form;
            $this->view->dg = $dg;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgStatus($value = null, $row) {
        try {
            return $value == 1 ? \KF\Lib\View\Html\Helper\Glyphicon::get('ok-circle text-success') : \KF\Lib\View\Html\Helper\Glyphicon::get('ban-circle text-danger');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgEdit($value = null, $row) {
        try {
            return '<a href=' . \KF\Kernel::$router->basePath . "admin/user/new-item/cod/{$row['cod']}>" . \KF\Lib\View\Html\Helper\Glyphicon::get('pencil') . '</a>';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
