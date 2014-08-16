<?php

namespace Kf\View\Html\Helper;

class Icon extends Helper {

    public function __invoke($name, $type = 'fa') {
        try {
            return self::get($name, $type);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function get($name, $type = 'fa') {
        try {
            return "<span class='{$type} {$type}-{$name}'></span>";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
