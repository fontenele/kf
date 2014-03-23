<?php

namespace KF\Lib\View\Html\Helper;

class Glyphicon extends Helper {

    public function __invoke($name) {
        try {
            return self::get($name);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function get($name) {
        try {
            return "<span class='glyphicon glyphicon-{$name}'></span>";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
