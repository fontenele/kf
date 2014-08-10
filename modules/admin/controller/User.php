<?php

namespace Admin\Controller;

class User extends \KF\Lib\Module\Controller {

    public function newItem() {
        try {
            $service = new \Admin\Service\User();
            $entity = new \Admin\Entity\User; // Entity
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
            unset($row['re-password']);
            $row['password'] = md5($row['password']);
            $success = $service->save($row);
            // Set alert message and redirect
            if ($success) {
                \KF\Lib\System\Messenger::success("Usuário {$row['name']} salvo com sucesso.");
                $this->redirect('admin/user/list-items');
            } else {
                \KF\Lib\System\Messenger::error("Erro ao tentar salvar usuário {$row['name']}.");
                $this->redirect('admin/user/new-item');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {
            $service = new \Admin\Service\User(); // Service
            // Datagrid
            $dg = new \KF\Lib\View\Html\Datagrid\Datagrid('dg-user');
            $dg->setEntity(new \Admin\Entity\User);
            $dg->getHeader(3)->setRenderer(new \KF\Lib\View\Html\Renderer('\Admin\Controller\User::dgGrupo')); //@TODO REMOVER ISSO, FAZER AUTOMATICO
            $dg->addHeader(\KF\Lib\View\Html\Datagrid\Header::create(3, '', '2%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\User::dgEdit')));
            $dg->addHeader(\KF\Lib\View\Html\Datagrid\Header::create(4, '', '2%', 'text-center', new \KF\Lib\View\Html\Renderer('\Admin\Controller\User::dgDelete')));
            $dg->setData($service->fetchAll($this->request->post->getArrayCopy(), $dg->getPaginator()->getRowsPerPage(), $dg->getPaginator()->getActive()));
            $this->view->dg = $dg;
            // Render HTML
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Render UserGroup Column
     * @param array $row
     * @return string
     */
    public static function dgGrupo($row) {
        $basePath = \KF\Kernel::$router->basePath;
        return "<a href='{$basePath}admin/user-group/new-item/cod/{$row['user_group']}'>{$row['user_group_name']}</a>";
    }

    /**
     * Render Status Column
     * @param array $row
     * @return string
     */
    public static function dgStatus($row) {
        return $row['status'] == 1 ? '<span title="Ativo">' . \KF\Lib\View\Html\Helper\Glyphicon::get('ok-circle text-success') . '</span>' : '<span title="Inativo">' . \KF\Lib\View\Html\Helper\Glyphicon::get('ban-circle text-danger') . '</span>';
    }

    /**
     * Render Edit Column
     * @param array $row
     * @return string
     */
    public static function dgEdit($row) {
        return '<a title="Editar usuário" href=' . \KF\Kernel::$router->basePath . "admin/user/new-item/cod/{$row['cod']}>" . \KF\Lib\View\Html\Helper\Glyphicon::get('folder-open') . '</a>';
    }

    /**
     * Render Delete Column
     * @param array $row
     * @return string
     */
    public static function dgDelete($row) {
        return '<a class="text-danger" title="Excluir usuário" data-confirmation data-placement="left" href="' . \KF\Kernel::$router->basePath . "admin/user/delete-item/cod/{$row['cod']}\">" . \KF\Lib\View\Html\Helper\Glyphicon::get('remove-sign') . '</a>';
    }

    public function deleteItem() {
        try {
            $service = new \Admin\Service\User; // Service
            $entity = new \Admin\Entity\User; // Entity
            $pk = $entity->getPrimaryKey(); // Primary Key
            // Return if primary key wasnt setted
            if (!$this->request->get->$pk) {
                \KF\Lib\System\Messenger::error("Erro ao tentar excluir usuário pois nenhum usuário foi informado.");
                $this->redirect('admin/user/list-items');
            }
            // Delete item
            $success = $service->delete($this->request->get->getArrayCopy());
            // Set alert message
            if ($success) {
                \KF\Lib\System\Messenger::success("Usuário excluído com sucesso.");
            } else {
                \KF\Lib\System\Messenger::error("Erro ao tentar excluir usuário.");
            }
            // Redirect
            $this->redirect('admin/user/list-items');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function profile() {
        try {
            $this->view->usuario = \Admin\Service\User::identity();
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
