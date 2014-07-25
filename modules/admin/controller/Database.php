<?php

namespace Admin\Controller;

class Database extends \KF\Lib\Module\Controller {

    public function init() {
        
    }

    public function createDefaultEntities() {
        try {
            $entities = [
                'Admin\Model\Menu',
                'Admin\Model\MenuItem',
                'Admin\Model\UserGroup',
                'Admin\Model\User',
                //'Main\Model\Funcionarios',
                //'Main\Model\Folha',
                //'Main\Model\Ponto',
            ];
            
            if ($this->request->isPost()) {
                $entitiesToCreate = [];
                
                foreach($this->request->post->tables as $entitie => $checked) {
                    if($checked == 'on' || $checked == 'true' || $checked == 'checked') {
                        $entitiesToCreate[$entitie] = $entitie;
                        
                    }
                }
                foreach($entities as $entitie) {
                    if(in_array($entitie, $entitiesToCreate)) {
                        $model = new $entitie;
                        $model->createTable();
                    }
                }
                
                \KF\Lib\System\Messenger::success("Tabelas criadas com sucesso.");
                $this->redirect('admin/index/index');
            }
            
            $db = [];
            foreach ($entities as $entitie) {
                $model = new $entitie;
                $table = $model->_table;
                $pk = $model->_pk;
                $seq = $model->_sequence;
                $fields = $model->_fields;
                $joins = $model->_joins;
                $db[$table] = ['pk' => $pk, 'seq' => $seq, 'fields' => $fields, 'joins' => $joins, 'entitie' => $entitie];
            }

            $this->view->db = $db;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
