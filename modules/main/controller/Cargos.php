<?php

namespace Main\Controller;

class Cargos extends \KF\Lib\Module\Controller {

    public static $form;

    public function form() {
        try {
            if (!self::$form) {
                $form = new \KF\Lib\View\Html\Form([
                    'action' => \KF\Kernel::$router->basePath . 'main/cargos/new-item',
                    'id' => 'fm-cargos'
                ]);
                $form->addField('cod', \KF\Lib\View\Html\Form::TYPE_INPUT_HIDDEN, 'Cod');
                $form->addField('name', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Nome', ['required' => true, 'placeholder' => 'Nome do Cargo']);
                $form->addField('salario', \KF\Lib\View\Html\Form::TYPE_INPUT_MONEY, 'Salário');
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
            $service = new \Main\Service\Cargos();
            $form = $this->form();

            if ($this->request->get->cod) {
                $row = $service->findOneBy(['cod' => $this->request->get->cod]);
                $form->setValues($row);
            }

            if ($this->request->isPost()) {
                $row = $this->request->post->getArrayCopy();
                $success = $service->save($row);

                if ($success) {
                    \KF\Lib\System\Messenger::success("Cargo {$row['name']} salvo com sucesso.");
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar salvar cargo {$row['name']}.");
                    $this->redirect('main/cargos/new-item');
                }
                $this->redirect('main/cargos/list-items');
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
                $service = new \Main\Service\Cargos();
                $success = $service->delete($this->request->get->getArrayCopy());
                if ($success) {
                    \KF\Lib\System\Messenger::success("Cargo excluído com sucesso.");
                } else {
                    \KF\Lib\System\Messenger::error("Erro ao tentar excluir cargo.");
                }
                $this->redirect('main/cargos/list-items');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {
            $dg = new \KF\Lib\View\Html\Datagrid('#fm-cargos', $this->request->post->getArrayCopy());
            $dg->addHeader('name', 'Nome', '30%');
            $dg->addHeader('salario', 'Salário', '25%', '', '\Main\Controller\Cargos::dgSalario');
            $dg->addHeader('_valor_hora', 'Valor/Hora', '10%', '', '\Main\Controller\Cargos::dgValorHora');
            $dg->addHeader('_he_50', 'HE + 50%', '10%', '', '\Main\Controller\Cargos::dgHoraExtra');
            $dg->addHeader('_he_100', 'HE + 100%', '10%', '', '\Main\Controller\Cargos::dgHoraExtraFeriado');
            $dg->addHeader('', '', '5%', 'text-center', '\Main\Controller\Cargos::dgEdit');
            $dg->addCriteria('name', \KF\Lib\View\Html\Datagrid::CRITERIA_CONDITION_LIKE);

            $service = new \Main\Service\Cargos();
            $dg->setRows($service->fetchAll($dg->criteria, $dg->rowPerPage, $dg->active, null, $dg->criteriaConditions));

            $form = $this->form();
            $form->action = \KF\Kernel::$router->basePath . 'main/cargos/list-items';
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

    public static function dgValorHora($value = null, $row) {
        try {
            return 'R$ ' . \KF\DataType\Salary::numeric2money(\KF\DataType\Salary::calcularValorHora(\KF\DataType\Salary::money2numeric($row['salario'])));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgHoraExtra($value = null, $row) {
        try {
            return 'R$ ' . \KF\DataType\Salary::numeric2money(\KF\DataType\Salary::calcularValorHoraExtra(\KF\DataType\Salary::calcularValorHora(\KF\DataType\Salary::money2numeric($row['salario']))));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgHoraExtraFeriado($value = null, $row) {
        try {
            return 'R$ ' . \KF\DataType\Salary::numeric2money(\KF\DataType\Salary::calcularValorHoraExtraFeriados(\KF\DataType\Salary::calcularValorHora(\KF\DataType\Salary::money2numeric($row['salario']))));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgEdit($value = null, $row) {
        try {
            return '<a title="Editar Cargo" href="' . \KF\Kernel::$router->basePath . 'main/cargos/new-item/cod/' . $row['cod'] . '">' . \KF\Lib\View\Html\Helper\Glyphicon::get('pencil') . '</a>&nbsp;&nbsp;&nbsp;' .
                    '<a title="Excluir Cargo" href="' . \KF\Kernel::$router->basePath . 'main/cargos/delete-item/cod/' . $row['cod'] . '">' . \KF\Lib\View\Html\Helper\Glyphicon::get('remove') . '</a> ';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function dgSalario($value = null, $row) {
        try {
            return "R$ {$value}";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
