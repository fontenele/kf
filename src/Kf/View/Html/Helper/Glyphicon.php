<?php

namespace KF\View\Html\Helper;

class Glyphicon extends Helper {

    public function __invoke($name, $type = 'glyphicon') {
        try {
            return self::get($name, $type);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function get($name, $type = 'glyphicon') {
        try {
            return "<span class='{$type} {$type}-{$name}'></span>";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
