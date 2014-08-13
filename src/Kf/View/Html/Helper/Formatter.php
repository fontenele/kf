<?php

namespace Kf\View\Html\Helper;

class Formatter extends Helper {

    public static function db2time($dbValue) {
        try {
            if(strpos($dbValue, ' ')) {
                $dbValue = explode(' ', $dbValue)[1];
            }
            return substr($dbValue, 0, 5);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
