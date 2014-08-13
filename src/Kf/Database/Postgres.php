<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kf\Database;

/**
 * Description of Postgres
 *
 * @author fontenele
 */
class Postgres extends Pdo {

    //public function setDefaults() {
    public function setDefaults() {
        try {
            $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            //$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            //$dml = "set lc_monetary to 'en_US'";
            //$stmt = $this->prepare($dml);
            //$stmt->execute();
            //return $stmt->fetch();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
