<?php

namespace KF\Lib\System;

class Logger {
    
    public static $errorFile;
    public static $routerFile;
    public static $databaseFile;
    
    public static function setDefaults() {
        try {
            self::$errorFile = LOG_PATH . 'error.log';
            self::$routerFile = LOG_PATH . 'router.log';
            self::$databaseFile = LOG_PATH . 'database.log';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function database($sql, $message, $code = 1) {
        try {
            \error_log("[#{$code}] {$message}\n\tSQL: {$sql}\n", 3, self::$databaseFile);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    public static function router($text, $code = 1) {
        try {
            \error_log("[#{$code}] {$text}\n", 3, self::$routerFile);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    public static function error($text, $code = 1) {
        try {
            \error_log("[#{$code}] {$text}\n", 3, self::$errorFile);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
