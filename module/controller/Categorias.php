<?php

namespace Kuestions\Controller;

use Kuestions\Module\Controller;

Class Categorias extends Controller {

    /**
     * @var \Kuestions\Service\Categorias
     */
    public $service;

    public function init() {
        $this->service = new \Kuestions\Service\Categorias();
    }

    public function todasCategorias() {
        try {
            \Kuestions\System::$layout->topItemAtual = 'relatorios';
            $paginator = new \Kuestions\Lib\View\Helper\Paginator('#fm-categorias', $this->request->get->getArrayCopy());
            $paginator->setRows($this->service->fetchAll($paginator, 1));
            $this->view->paginator = $paginator;
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function novaCategoria() {
        try {
            \Kuestions\System::$layout->topItemAtual = 'painel';
            $this->view->categorias = $this->service->fetchAll(); // remover
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function salvarCategoria() {
        try {
            $row = $this->request->post->getArrayCopy();
            if (!isset($row['cod'])) {
                $row['cod'] = null;
            }

            if ($this->request->isAjax) {
                $this->view = new \Kuestions\Lib\View\Json($this->request->post->getArrayCopy());
                $salvou = $this->service->save($row);
                if ($salvou) {
                    $this->view->message = 'Categoria salva com sucesso.';
                    $this->view->error = false;
                } else {
                    $this->view->message = 'Erro ao tentar salvar Categoria.';
                    $this->view->error = true;
                }
                return $this->view;
            } else {
                $salvou = $this->service->save($row);
                if ($salvou) {
                    \Kuestions\Lib\View\Helper\Messenger::success('Categoria salva com sucesso.');
                } else {
                    \Kuestions\Lib\View\Helper\Messenger::error('Erro ao tentar salvar Categoria.');
                }
                return $this->redirect('categorias/nova-categoria');
            }
        } catch (\Exception $ex) {
            xd($ex);
        }
    }
    
    public function removerCategoria() {
        try {
            $row = $this->request->post->getArrayCopy();
            if (!isset($row['cod'])) {
                $row['cod'] = null;
            }

            if ($this->request->isAjax) {
                $this->view = new \Kuestions\Lib\View\Json($this->request->post->getArrayCopy());
                $deletou = $this->service->delete($row['cod']);
                if ($deletou) {
                    $this->view->message = 'Categoria excluída com sucesso.';
                    $this->view->error = false;
                } else {
                    $this->view->message = 'Erro ao tentar excluír Categoria.';
                    $this->view->error = true;
                }
                return $this->view;
            }
            
            return false;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
