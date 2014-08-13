<?php

namespace Kf\Module;

abstract class Controller {

    /**
     * @var \Kf\View\Html
     */
    public $view;

    /**
     * @var string
     */
    public $action;

    /**
     * @var \Kf\Http\Request
     */
    public $request;

    public function __construct($action, $request) {
        try {
            $arrClass = explode('\\', get_class($this));
            $this->action = $action;

            $template = \strpos($action, '-') === false ? \Kf\System\String::camelToDash($action) : $action;
            $_module = \Kf\System\String::camelToDash($arrClass[0]);
            $_controller = \Kf\System\String::camelToDash($arrClass[2]);
            $this->view = new \Kf\View\Html("modules/{$_module}/view/{$_controller}/{$template}.phtml", array('request' => $request));

            $this->request = $request;

            $this->init();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function init() {
        
    }

    public function addJsFile($file) {
        try {
            \Kf\Kernel::$layout->addJsFile($file);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addCssFile($file) {
        try {
            \Kf\Kernel::$layout->addCssFile($file);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addExtraJsFile($file) {
        try {
            \Kf\Kernel::$layout->addExtraJsFile($file);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addExtraCssFile($file) {
        try {
            \Kf\Kernel::$layout->addExtraCssFile($file);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function addViewComponent($name) {
        try {
            if (!isset(\Kf\Kernel::$config['view']['components'][$name])) {
                return;
            }
            foreach (\Kf\Kernel::$config['view']['components'][$name]['css'] as $file) {
                $this->addExtraCssFile($file);
            }
            foreach (\Kf\Kernel::$config['view']['components'][$name]['js'] as $file) {
                $this->addExtraJsFile($file);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function redirect($path = null) {
        if ($path && substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }
        \Kf\Kernel::$router->redirect($path);
    }

    public function callAction($action) {
        try {
            $arrClass = explode('\\', get_class($this));
            $this->action = $action;

            $template = \strpos($action, '-') === false ? \Kf\System\String::camelToDash($action) : $action;
            $_module = \Kf\System\String::camelToDash($arrClass[0]);
            $_controller = \Kf\System\String::camelToDash($arrClass[2]);
            $_action = \Kf\System\String::camelToDash($action);
            
            $pathCssJs = "%s/modules/{$_module}/{$_controller}/{$_action}.%s";

            $js = array();
            $css = array();

            if (file_exists(sprintf(APP_PATH . 'public/' . $pathCssJs, 'css', 'css'))) {
                $css[] = sprintf(\Kf\Kernel::$router->basePath . $pathCssJs, 'css', 'css');
            }

            if (file_exists(sprintf(APP_PATH . 'public/' . $pathCssJs, 'js', 'js'))) {
                $js[] = sprintf(\Kf\Kernel::$router->basePath . $pathCssJs, 'js', 'js');
            }
            
            $this->view = new \Kf\View\Html("modules/{$_module}/view/{$_controller}/{$template}.phtml", array('request' => $this->request));
            \Kf\Kernel::$layout->css = $css;
            \Kf\Kernel::$layout->js = $js;
            
            return $this->$action();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
