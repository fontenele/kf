<?php

namespace Kf\Module;

/**
 * @package Module
 * @abstract
 */
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

    /**
     * @param array $where
     * @param integer $rowsPerPage
     * @param integer $numPage
     * @param array $selectNames
     * @param array $whereConditions
     * @param array $orderBy
     * @return array
     * @throws \Kf\Module\Exception
     */
    public function fetchAll($where = [], $rowsPerPage = null, $numPage = 0, $selectNames = [], $whereConditions = [], $orderBy = []) {
        try {
            //return $this->parseAndFormatDbData2View($this->model()->fetchAll($where, $rowsPerPage, $numPage, $selectNames, $whereConditions, $orderBy));
            return $this->model()->fetchAll($where, $rowsPerPage, $numPage, $selectNames, $whereConditions, $orderBy);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $where
     * @param array $selectNames
     * @return array
     * @throws \Kf\Module\Exception
     */
    public function findBy($where, $selectNames = []) {
        try {
            return $this->parseAndFormatDbData2View($this->model()->fetchAll($where, $selectNames));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     *
     * @param array $where
     * @param array $selectNames
     * @return array
     * @throws \Kf\Module\Exception
     */
    public function findOneBy($where, $selectNames = []) {
        try {
//            return $this->parseAndFormatDbData2View($this->model()->fetch($where, $selectNames));
            return $this->model()->fetch($where, $selectNames);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function save(&$row) {
        try {
            return $this->model()->save($row);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function delete($where = []) {
        try {
            return $this->model()->delete($where);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseAndFormatDbData2View($data) {
        try {
            foreach ($data as $i => &$value) {
                if (is_array($value)) {
                    $value = $this->parseAndFormatDbData2View($value);
                    continue;
                }
                if (array_key_exists($i, $this->model->_fields)) {
                    $field = $this->model->_fields[$i];

                    switch ($field['type']) {
                        case Model::TYPE_INTEGER:
                            $value = (int) $value;
                            break;
                        case Model::TYPE_MONEY:
                            $value = str_replace(['R$'], '', $value);
                            break;
                        case Model::TYPE_BOOLEAN:
                            $value = (bool) $value;
                            break;
                    }
                }
            }
            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
