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
            $this->request = $request;

            $template = \strpos($action, '-') === false ? \Kf\System\String::camelToDash($action) : $action;
            $_module = \Kf\System\String::camelToDash($arrClass[0]);
            $_controller = \Kf\System\String::camelToDash($arrClass[2]);

            if (is_dir("modules/{$arrClass[0]}/view/{$_controller}")) {
                $this->view = new \Kf\View\Html("modules/{$arrClass[0]}/view/{$_controller}/{$template}.phtml", array('request' => $request));
            } elseif (is_dir("vendor/{$arrClass[0]}/$arrClass[0]/src/{$arrClass[0]}/view/{$_controller}")) {
                $this->view = new \Kf\View\Html("vendor/{$arrClass[0]}/$arrClass[0]/src/{$arrClass[0]}/view/{$_controller}/{$template}.phtml", array('request' => $request));
            }

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

    public function callAction($action = null) {
        try {
            $arrClass = explode('\\', get_class($this));
            $this->action = $action ? $action : $this->action;

            $template = \strpos($this->action, '-') === false ? \Kf\System\String::camelToDash($this->action) : $this->action;
            $_module = \Kf\System\String::camelToDash($arrClass[0]);
            $_controller = \Kf\System\String::camelToDash($arrClass[2]);
            $_action = \Kf\System\String::camelToDash($this->action);
            $cssAndJs = \Kf\Kernel::loadJsAndCssFiles(get_class($this), $this->action);

            if (is_dir("modules/{$_module}/view/{$_controller}")) {
                $this->view = new \Kf\View\Html("modules/{$_module}/view/{$_controller}/{$template}.phtml", array('request' => $this->request));
            } elseif (is_dir("vendor/{$_module}/$_module/src/{$_module}/view/{$_controller}")) {
                $this->view = new \Kf\View\Html("vendor/{$_module}/$_module/src/{$_module}/view/{$_controller}/{$template}.phtml", array('request' => $this->request));
            }
            
            $cssAndJs['css'] = array_merge(\Kf\Kernel::$layout->css, $cssAndJs['css']);
            $cssAndJs['js'] = array_merge(\Kf\Kernel::$layout->js, $cssAndJs['js']);
            \Kf\Kernel::$layout->css = $cssAndJs['css'];
            \Kf\Kernel::$layout->js = $cssAndJs['js'];

            return $this->{$this->action}();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
