<?php

namespace Admin\Controller;

class UserGroup extends \KF\Lib\Module\Controller {

    public static $form;

    public function form() {
        try {
            if (!self::$form) {
                $form = new \KF\Lib\View\Html\Form([
                    'action' => \KF\Kernel::$router->basePath . 'admin/user-group/new-item',
                    'id' => 'fm-user-group'
                ]);
                $form->addField('cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN, 'Cod');
                $form->addField('name', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Nome', ['required' => true, 'placeholder' => 'Nome do Grupo']);
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
            $service = new \Admin\Service\UserGroup();
            $form = $this->form();

            if ($this->request->get->cod) {
                $row = $service->findOneBy(['cod' => $this->request->get->cod]);
                $form->setValues($row);
            }

            if ($this->request->isPost()) {
                $row = $this->request->post->getArrayCopy();
                $success = $service->save($row);

                if ($success) {
                    \KF\Lib\System\Messenger::success("Grupo {$row['name']} salvo com sucesso.");
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar salvar grupo {$row['name']}.");
                    $this->redirect('admin/user-group/new-item');
                }
                $this->redirect('admin/user-group/list-items');
            }

            $this->view->form = $form;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {
            $dg = new \KF\Lib\View\Html\Datagrid\Datagrid(new \Admin\Entity\UserGroup);
            $dg->addHeader(new \KF\Lib\View\Html\Datagrid\Header(3, '', '5%', 'text-center', '\Admin\Controller\UserGroup::dgEdit'));
            $this->view->dg = $dg;
            //xd($dg);
            
            
            
            
            
            
//            $dg = new \KF\Lib\View\Html\Datagrid('#fm-user-group', $this->request->post->getArrayCopy());
//            $dg->addHeader('name', 'Grupo', '85%');
//            $dg->addHeader('status', 'Status', '10%', 'text-center', '\Admin\Controller\UserGroup::dgStatus');
//            $dg->addHeader('', '', '5%', 'text-center', '\Admin\Controller\UserGroup::dgEdit');
//            $dg->addCriteria('name', \KF\Lib\View\Html\Datagrid::CRITERIA_CONDITION_LIKE);
//
//            $service = new \Admin\Service\UserGroup();
//            $dg->setRows($service->fetchAll($dg->criteria, $dg->rowPerPage, $dg->active, null, $dg->criteriaConditions));
//
//            $form = $this->form();
//            $form->action = \KF\Kernel::$router->basePath . 'admin/user-group/list-items';
//            $form->submit->label = $form->submit->content = \KF\Lib\View\Html\Helper\Glyphicon::get('search') . ' Pesquisar';
//            $form->name->offsetUnset('required');
//            $form->status->offsetUnset('required');
//            $form->setValues($this->request->post->getArrayCopy());
//
//            $this->view->form = $form;
//            $this->view->dg = $dg;
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
            return '<a href=' . \KF\Kernel::$router->basePath . "admin/user-group/new-item/cod/{$row['cod']}>" . \KF\Lib\View\Html\Helper\Glyphicon::get('pencil') . '</a>';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
