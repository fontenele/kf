<?php

namespace Kf\System;

class Collection extends \Kf\System\ArrayObject {

    public function sortBy($field = null, $method = null) {
        $newData = [];
        foreach ($this->getArrayCopy() as $row) {
            $index = null;
            switch (true) {
                case is_array($row) && isset($row[$field]):
                    $index = $row[$field];
                    break;
                case $method && method_exists($row, $method):
                    $index = $row->$method();
                    break;
                case $field && property_exists($row, $field):
                    $index = $row->$field;
                    break;
            }

            $newData[$index] = $row;
        }
        ksort($newData);
        $this->exchangeArray($newData);
    }

}
