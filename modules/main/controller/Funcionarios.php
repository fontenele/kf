<?php

namespace Main\Controller;

class Funcionarios extends \KF\Lib\Module\Controller {

    public static $form;

    public function form() {
        try {
            if (!self::$form) {
                $form = new \KF\Lib\View\Html\Form([
                    'action' => \KF\Kernel::$router->basePath . 'main/funcionarios/new-item',
                    'id' => 'fm-funcionarios'
                ]);
                $service = new \Main\Service\Cargos();
                $form->addField('cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN, 'Cod');
                $form->addField('name', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Nome', ['required' => true, 'placeholder' => 'Nome do Funcionário']);
                $form->addField('cargo', \KF\Lib\View\Html\Form::TYPE_SELECT, 'Cargo', ['options' => \KF\Lib\View\Html\Select::rows2options($service->fetchAll()), 'required' => true]);
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
            $service = new \Main\Service\Funcionarios();
            $form = $this->form();

            if ($this->request->get->cod) {
                $row = $service->findOneBy(['cod' => $this->request->get->cod]);
                $form->setValues($row);
            }

            if ($this->request->isPost()) {
                $row = $this->request->post->getArrayCopy();
                $success = $service->save($row);

                if ($success) {
                    \KF\Lib\System\Messenger::success("Funcionário {$row['name']} salvo com sucesso.");
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar salvar funcionário {$row['name']}.");
                    $this->redirect('main/funcionarios/new-item');
                }
                $this->redirect('main/funcionarios/list-items');
            }

            $this->view->form = $form;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function deleteItem() {
        try {
            if ($this->request->get->cod) {
                $service = new \Main\Service\Funcionarios();
                $servicePonto = new \Main\Service\Ponto();
                $success = $servicePonto->delete(['funcionario' => $this->request->get->cod]);
                
                if($success) {
                    $success = $service->delete($this->request->get->getArrayCopy());
                }
                
                if ($success) {
                    \KF\Lib\System\Messenger::success("Funcionário excluído com sucesso.");
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar excluir funcionário.");
                }
                $this->redirect('main/funcionarios/list-items');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {
            $dg = new \KF\Lib\View\Html\Datagrid('#fm-funcionarios', $this->request->post->getArrayCopy());
            $dg->addHeader('name', 'Nome', '45%');
            $dg->addHeader('cargo_name', 'Cargo', '25%');
            $dg->addHeader('cargo_salario', 'Salário', '15%', '', '\Main\Controller\Funcionarios::dgSalario');
            $dg->addHeader('', '', '5%', 'text-center', '\Main\Controller\Funcionarios::dgEdit');
            $dg->addCriteria('name', \KF\Lib\View\Html\Datagrid::CRITERIA_CONDITION_LIKE);

            $service = new \Main\Service\Funcionarios();
            $dg->setRows($service->fetchAll($dg->criteria, $dg->rowPerPage, $dg->active, null, $dg->criteriaConditions));

            $form = $this->form();
            $form->action = \KF\Kernel::$router->basePath . 'main/funcionarios/list-items';
            $form->submit->label = $form->submit->content = \KF\Lib\View\Html\Helper\Glyphicon::get('search') . ' Pesquisar';
            $form->name->offsetUnset('required');
            $form->setValues($this->request->post->getArrayCopy());

            $this->view->form = $form;
            $this->view->dg = $dg;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgEdit($value = null, $row) {
        try {
            return '<a title="Editar Funcionário" href="' . \KF\Kernel::$router->basePath . 'main/funcionarios/new-item/cod/' . $row['cod'] . '">' . \KF\Lib\View\Html\Helper\Glyphicon::get('pencil') . '</a>&nbsp;&nbsp;&nbsp;' .
                    '<a title="Excluir Funcionário" href="' . \KF\Kernel::$router->basePath . 'main/funcionarios/delete-item/cod/' . $row['cod'] . '">' . \KF\Lib\View\Html\Helper\Glyphicon::get('remove') . '</a> ';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public static function dgSalario($value = null, $row) {
        try {
            return 'R$ ' . str_replace(['R$'], '', $value);
            return $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
