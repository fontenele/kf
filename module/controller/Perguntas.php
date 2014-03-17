<?php

namespace Kuestions\Controller;

use Kuestions\Module\Controller;

Class Perguntas extends Controller {

    /**
     * @var \Kuestions\Service\Perguntas
     */
    public $service;

    public function init() {
        $this->service = new \Kuestions\Service\Perguntas();
    }

    public function todasPerguntas() {
        try {
            \Kuestions\System::$layout->topItemAtual = 'relatorios';
            $paginator = new \Kuestions\Lib\View\Helper\Paginator('#fm-perguntas', $this->request->get->getArrayCopy());
            $paginator->setRows($this->service->fetchAll($paginator));
            $this->view->paginator = $paginator;
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function novaPergunta() {
        try {
            \Kuestions\System::$layout->topItemAtual = 'painel';
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function salvarPergunta() {
        try {
            $row = $this->request->post->getArrayCopy();
            if (!isset($row['cod'])) {
                $row['cod'] = null;
            }

            if ($this->request->isAjax) {
                $this->view = new \Kuestions\Lib\View\Json($this->request->post->getArrayCopy());
                $salvou = $this->service->save($row);
                if ($salvou) {
                    $this->view->message = 'Pergunta salva com sucesso.';
                    $this->view->error = false;
                } else {
                    $this->view->message = 'Erro ao tentar salvar Pergunta.';
                    $this->view->error = true;
                }
                return $this->view;
            } else {
                $serviceAlternativa = new \Kuestions\Service\Alternativa();

                $rowAlternativa1 = ['descricao' => $row['alternativa1']];
                $serviceAlternativa->save($rowAlternativa1);
                $row['alternativa1'] = $rowAlternativa1['cod'];
                $rowAlternativa2 = ['descricao' => $row['alternativa2']];
                $serviceAlternativa->save($rowAlternativa2);
                $row['alternativa2'] = $rowAlternativa2['cod'];
                $rowAlternativa3 = ['descricao' => $row['alternativa3']];
                $serviceAlternativa->save($rowAlternativa3);
                $row['alternativa3'] = $rowAlternativa3['cod'];
                $rowAlternativa4 = ['descricao' => $row['alternativa4']];
                $serviceAlternativa->save($rowAlternativa4);
                $row['alternativa4'] = $rowAlternativa4['cod'];
                $rowAlternativa5 = ['descricao' => $row['alternativa5']];
                $serviceAlternativa->save($rowAlternativa5);
                $row['alternativa5'] = $rowAlternativa5['cod'];

                $row['correta'] = $row['alternativa' . $row['correta']];
                $salvou = $this->service->save($row);

                if ($salvou) {
                    \Kuestions\Lib\View\Helper\Messenger::success('Pergunta salva com sucesso.');
                } else {
                    \Kuestions\Lib\View\Helper\Messenger::success('Erro ao tentar salvar Pergunta.');
                }

                $this->redirect('perguntas/nova-pergunta');
            }
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function salvarAlternativa() {
        try {
            $this->view = new \Kuestions\Lib\View\Json($this->request->post->getArrayCopy());

            $row = $this->request->post->getArrayCopy();
            if (!isset($row['cod'])) {
                $row['cod'] = null;
            }

            $service = new \Kuestions\Service\Alternativa();
            $salvou = $service->save($row);

            if ($salvou) {
                $this->view->message = 'Alternativa salva com sucesso.';
                $this->view->error = false;
            } else {
                $this->view->message = 'Erro ao tentar salvar Alternativa.';
                $this->view->error = true;
            }

            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
