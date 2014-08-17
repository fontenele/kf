<?php

namespace Kf\System;

class File {

    protected $name;

    public function __construct($filename) {
        try {
            if (!self::fileExists($filename)) {
                throw new Exception\FileNotFoundException($filename);
            }
            $this->setName($filename);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public static function getExtension($filename = null) {
        $filename = $filename ? $filename : $this->getName();
        $filename = explode('.', $filename);
        return array_pop($filename);
    }

    public static function loadFile($filename = null) {
        try {
            $filename = $filename ? $filename : $this->getName();
            if (self::fileExists($filename)) {
                switch(self::getExtension($filename)) {
                    case 'xml':
                        $xml = simplexml_load_string(file_get_contents($filename));
                        $json = json_encode($xml);
                        return json_decode($json, true);
                    case 'php':
                    default:
                        return include_once($filename);
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function getFileName($filename, $env = null) {
        try {
            if ($env) {
                $file = substr($filename, 0, -3);
                $ext = substr($filename, -4);
                $filename = $file . $env . $ext;
            }
            return $filename;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function fileExists($filename, $env = null) {
        try {
            if ($env) {
                $file = substr($filename, 0, -3);
                $ext = substr($filename, -4);
                $filename = $file . $env . $ext;
            }
            return \file_exists($filename);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
