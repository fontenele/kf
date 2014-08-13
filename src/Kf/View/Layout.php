<?php

namespace Kf\View;

class Layout extends Html {

    public function __construct($template = null, $vars = array()) {
        try {
            parent::__construct($template, $vars);
            $this->extraJs = [];
            $this->extraCss = [];
            $this->js = [];
            $this->css = [];
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addJsFile($file) {
        try {
            if (!file_exists(APP_PATH . 'public/js/' . $file)) {
                throw new \Exception('Javascript File not found. ' . $file);
            }
            $this->js = array_merge($this->js, [\Kf\Kernel::$router->basePath . 'js/' . $file]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addCssFile($file) {
        try {
            if (!file_exists(APP_PATH . 'public/css/' . $file)) {
                throw new \Exception('Style File not found. ' . $file);
            }
            $this->css = array_merge($this->css, [\Kf\Kernel::$router->basePath . 'css/' . $file]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addExtraJsFile($file) {
        try {
            if (!file_exists(APP_PATH . 'public/js/' . $file)) {
                throw new \Exception('Javascript File not found. ' . $file);
            }
            $this->extraJs = array_merge($this->extraJs, [\Kf\Kernel::$router->basePath . 'js/' . $file]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addExtraCssFile($file) {
        try {
            if (!file_exists(APP_PATH . 'public/css/' . $file)) {
                throw new \Exception('Style File not found. ' . $file);
            }
            $this->extraCss = array_merge($this->extraCss, [\Kf\Kernel::$router->basePath . 'css/' . $file]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
