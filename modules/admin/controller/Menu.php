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
                $form->addField('codename', \KF\Lib\View\Html\Form::TYPE_INPUT_TEXT, 'Sigla', ['required' => true, 'placeholder' => 'Sigla']);
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
            $items = [];
            
            $this->addViewComponent('tree');
            $this->addExtraJsFile('bootstrap/jquery.jstree.js');

            if ($this->request->get->cod) {
                $row = $service->findOneBy(['cod' => $this->request->get->cod]);
                $form->setValues($row);
                
                $serviceMenuItem = new \Admin\Service\MenuItem();
                $items = $service->arrayFromDb($serviceMenuItem->findBy(['menu' => $row['cod']]));
            }
            
            $this->view->form = $form;
            $this->view->menuItems = $items;
            
            if ($this->request->isPost()) {
                $row = $this->request->post->getArrayCopy();
                $success = $service->saveFromJsTree($row);
                $this->view = new \KF\Lib\View\Json();
                $this->view->success = $success;
                
                if ($success) {
                    $this->view->message = "Menu {$row['name']} salvo com sucesso.";
                    $this->view->redirect = 'admin/menu/list-items';
                    \KF\Lib\System\Messenger::success($this->view->message);
                } else {
                    $this->view->message = "Erro ao tentar salvar menu {$row['name']}.";
                    $this->view->redirect = 'admin/menu/new-item';
                }
            }
            
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function listItems() {
        try {
            $dg = new \KF\Lib\View\Html\Datagrid('#fm-menu', $this->request->post->getArrayCopy());
            $dg->addHeader('codename', 'Sigla', '10%', 'text-center');
            $dg->addHeader('name', 'Nome', '85%');
            $dg->addHeader('', '', '5%', 'text-center', '\Admin\Controller\Menu::dgEdit');
            $dg->addCriteria('name', \KF\Lib\View\Html\Datagrid::CRITERIA_CONDITION_LIKE);
            $dg->addCriteria('codename', \KF\Lib\View\Html\Datagrid::CRITERIA_CONDITION_LIKE);

            $service = new \Admin\Service\Menu();
            $dg->setRows($service->fetchAll($dg->criteria, $dg->rowPerPage, $dg->active, null, $dg->criteriaConditions));

            $form = $this->form();
            $form->action = \KF\Kernel::$router->basePath . 'admin/menu/list-items';
            $form->submit->label = $form->submit->content = \KF\Lib\View\Html\Helper\Glyphicon::get('search') . ' Pesquisar';
            $form->name->offsetUnset('required');
            $form->codename->offsetUnset('required');
            $form->submit->addClass('btn-search');
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
            return '<a href=' . \KF\Kernel::$router->basePath . "admin/menu/new-item/cod/{$row['cod']}>" . \KF\Lib\View\Html\Helper\Glyphicon::get('pencil') . '</a>';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
