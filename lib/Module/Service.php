<?php

namespace KF\Lib\Module;

abstract class Service {

    public $_model;

    /**
     * @var Model
     */
    private $model;

    public function model() {
        try {
            $this->model = new $this->_model;
            return $this->model;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __call($name, $arguments) {
        try {
            return call_user_func_array(array($this->model(), $name), $arguments);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
