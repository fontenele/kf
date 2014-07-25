<?php

namespace Main\Service;

class Ponto extends \KF\Lib\Module\Service {

    public function __construct() {
        $this->_model = '\Main\Model\Ponto';
    }
    
    /**
     * Resetar horas dos domingos e feriados
     * @param integer $folha ID da Folha
     * @param integer $funcionario ID do Funcionário
     * @throws \Main\Service\Exception
     */
    public function resetarDomingosFeriados($folha, $funcionario = null) {
        try {
            $serviceFolha = new Folha();
            $folha = $serviceFolha->findOneBy(['cod' => $folha]);
            $row = [];
            if(!$funcionario) {
                $serviceFuncionarios = new Funcionarios();
                $funcionario = $serviceFuncionarios->fetchAll();
                $funcionario = \array_shift($funcionario)['cod'];
            }
            $pontoDeUnicoFuncionario = $this->model()->fetchAll(['funcionario' => $funcionario]);
            $dias = [];
            $sabados = [];
            $domingos = [];
            $feriados = [];
            foreach($pontoDeUnicoFuncionario as $ponto) {
                if(\KF\DataType\Time::isSaturday($ponto['dia'], $folha['mes'], $folha['ano'])) {
                    $sabados[] = $ponto['dia'];
                }
                if(\KF\DataType\Time::isSunday($ponto['dia'], $folha['mes'], $folha['ano'])) {
                    $domingos[] = $ponto['dia'];
                }
                $dias[] = $ponto['dia'];
            }
            xd($dias, $sabados, $domingos, $feriados);
            //$this->model()
            xd($folha, $this->model()->update($row,['folha' => $folha]));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
