<?php

namespace Kf\DataType;

class Salary extends Money {

    public static function numeric2money($value) {
        try {
            return str_replace('.', ',', $value);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function money2numeric($value) {
        try {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            return $value;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function calcularValorHora($salario, $formatted = true) {
        try {
            if ($formatted) {
                return number_format($salario / 220, 2);
            } else {
                return $salario / 220;
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function calcularValorHoraExtra($valorHora, $formatted = true) {
        if ($formatted) {
            return number_format($valorHora + ($valorHora / 2), 2);
        } else {
            return $valorHora + ($valorHora / 2);
        }
    }

    public static function calcularValorHoraExtraFeriados($valorHora, $formatted = true) {
        if ($formatted) {
            return number_format($valorHora * 2, 2);
        } else {
            return $valorHora * 2;
        }
    }

    public static function calcularDuracaoFaixa($hora, $formatted = false) {
        if ($formatted) {
            return number_format($this->time2decimal($hora) / 0.996, 2);
        } else {
            return $this->time2decimal($hora) / 0.996;
        }
    }

    public static function calcularValorTotalHoraExtra($valorHoraExtra, $valorHorasTrabalhadas, $formatted = false) {
        if ($formatted) {
            return round($this->calcularValorHoraExtra($valorHoraExtra, false) * $this->calcularDuracaoFaixa($valorHorasTrabalhadas, false), 2);
        } else {
            return $valorHoraExtra * $valorHorasTrabalhadas;
        }
    }

    public static function calcularValorTotalHoraExtraFeriado($valorHoraExtraFeriado, $valorHorasTrabalhadas, $formatted = false) {
        if ($formatted) {
            return round($this->calcularValorHoraExtraFeriados($valorHoraExtraFeriado, false) * $this->calcularDuracaoFaixa($valorHorasTrabalhadas, false), 2);
        } else {
            return $this->calcularValorHoraExtraFeriados($valorHoraExtraFeriado, false) * $this->calcularDuracaoFaixa($valorHorasTrabalhadas, false);
        }
    }

    public static function calcularValorTotalHoraExtraAPagar($valorTotalHoraExtra, $valorTotalHoraExtraFeriado, $formatted = false) {
        if ($formatted) {
            return round($valorTotalHoraExtra + $valorTotalHoraExtraFeriado, 2);
        } else {
            return $valorTotalHoraExtra + $valorTotalHoraExtraFeriado;
        }
    }

}
