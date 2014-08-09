<?php

namespace Admin\Controller;

class UserGroup extends \KF\Lib\Module\Controller {

    public function newItem() {
        try {
            $service = new \Admin\Service\UserGroup; // Service
            $entity = new \Admin\Entity\UserGroup; // Entity
            $pk = $entity->getPrimaryKey(); // Primary Key
            // Set values to edit
            $edit = false;
            if ($this->request->get->$pk) {
                $edit = $this->request->get->$pk;
                $row = $service->findOneBy([$pk => $this->request->get->$pk]);
                $entity->setValues($row);
            }
            // Render HTML
            if (!$this->request->isPost()) {
                $this->view->entity = $entity;
                $this->view->edit = $edit;
                return $this->view;
            }
            // Save
            $row = $this->request->post->getArrayCopy();
            $success = $service->save($row);
            // Set alert message and redirect
            if ($success) {
                \KF\Lib\System\Messenger::success("Grupo {$row['name']} salvo com sucesso.");
                $this->redirect('admin/user-group/list-items');
            } else {
                \KF\Lib\System\Messenger::error("Erro ao tentar salvar grupo {$row['name']}.");
                $this->redirect('admin/user-group/new-item');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {
            // Service
            $userGroup = new \Admin\Service\UserGroup;
            // Datagrid
            $dg = new \KF\Lib\View\Html\Datagrid\Datagrid('dg-user-group');
            $dg->setEntity(new \Admin\Entity\UserGroup);
            $dg->addHeader(\KF\Lib\View\Html\Datagrid\Header::create(3, '', '2%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\UserGroup::dgEdit')));
            $dg->addHeader(\KF\Lib\View\Html\Datagrid\Header::create(4, '', '2%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\UserGroup::dgDelete')));
            $dg->setData($userGroup->fetchAll($this->request->post->getArrayCopy(), $dg->getPaginator()->getRowsPerPage(), $dg->getPaginator()->getActive()));
            $this->view->dg = $dg;
            // Render HTML
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function deleteItem() {
        try {
            $service = new \Admin\Service\UserGroup; // Service
            $entity = new \Admin\Entity\UserGroup; // Entity
            $pk = $entity->getPrimaryKey(); // Primary Key
            // Return if primary key wasnt setted
            if (!$this->request->get->$pk) {
                \KF\Lib\System\Messenger::error("Erro ao tentar excluir grupo pois nenhum grupo foi informado.");
                $this->redirect('admin/user-group/list-items');
            }
            // Delete item
            $success = $service->delete($this->request->get->getArrayCopy());
            // Set alert message
            if ($success) {
                \KF\Lib\System\Messenger::success("Grupo excluído com sucesso.");
            } else {
                \KF\Lib\System\Messenger::error("Erro ao tentar excluir grupo.");
            }
            // Redirect
            $this->redirect('admin/user-group/list-items');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Render Status Column
     * @param array $row
     * @return string
     * @throws \Exception
     */
    public static function dgStatus($row) {
        try {
            return $row['status'] == 1 ? \KF\Lib\View\Html\Helper\Glyphicon::get('ok-circle text-success') : \KF\Lib\View\Html\Helper\Glyphicon::get('ban-circle text-danger');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Render Edit Column
     * @param array $row
     * @return string
     * @throws \Exception
     */
    public static function dgEdit($row) {
        try {
            return '<a href=' . \KF\Kernel::$router->basePath . "admin/user-group/new-item/cod/{$row['cod']}>" . \KF\Lib\View\Html\Helper\Glyphicon::get('folder-open') . '</a>';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Render Edit Column
     * @param array $row
     * @return string
     * @throws \Exception
     */
    public static function dgDelete($row) {
        try {
            return '<a class="text-danger" href=' . \KF\Kernel::$router->basePath . "admin/user-group/delete-item/cod/{$row['cod']}>" . \KF\Lib\View\Html\Helper\Glyphicon::get('remove-sign') . '</a>';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
