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
                switch (self::getExtension($filename)) {
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

    /**
     * Get Modules and Path from system
     * @return array
     * @throws \Kf\System\Exception
     */
    public static function getModules() {
        try {
            $modules = [];
            foreach (\Kf\Kernel::$config['system']['modules'] as $module) {
                $path = null;
                if (is_array($module)) {
                    $path = current($module);
                    $module = key($module);
                }
                switch (true) {
                    case $path && is_dir(APP_PATH . "modules/{$path}/{$module}"):
                        $modules[$module] = APP_PATH . "modules/{$path}/{$module}/";
                        break;
                    case $path && is_dir(VENDOR_PATH . "{$path}/src/{$module}"):
                        $modules[$module] = VENDOR_PATH . "{$path}/src/{$module}/";
                        break;
                    case $path && is_dir(VENDOR_PATH . "{$path}/{$module}/src/{$module}/"):
                        $modules[$module] = VENDOR_PATH . "{$path}/{$module}/src/{$module}/";
                        break;

                    case is_dir(APP_PATH . "modules/{$module}"):
                        $modules[$module] = APP_PATH . "modules/{$module}/";
                        break;
                    case is_dir(VENDOR_PATH . "{$module}/src/{$module}"):
                        $modules[$module] = VENDOR_PATH . "{$module}/src/{$module}/";
                        break;
                    case is_dir(VENDOR_PATH . "{$module}/{$module}/src/{$module}"):
                        $modules[$module] = VENDOR_PATH . "{$module}/{$module}/src/{$module}/";
                        break;
                }
            }
            return $modules;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function getControllers($modules = []) {
        try {
            $controllers = [];
            foreach (static::getModules() as $module => $path) {
                if (count($modules) && !in_array($module, $modules)) {
                    continue;
                }
                $path = "{$path}controller/";
                foreach (Dir::create($path)->getFiles()->getArrayCopy() as $file) {
                    $name = "{$module}\Controller\\" . substr($file, 0, -4);
                    $controllers[$module][substr($file, 0, -4)] = $name;
                }
            }
            return $controllers;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function getMethods($controller) {
        try {
            $methods = [];
            $ignores = ['__construct', 'init'];
            $controller = new \ReflectionClass("\\{$controller}");
            foreach ($controller->getMethods() as $method) {
                if ($method->class != $controller->name || $method->isStatic() || in_array($method->name, $ignores)) {
                    continue;
                }
                $methods[$controller->name][$method->name] = $method->name;
            }
            return $methods;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
