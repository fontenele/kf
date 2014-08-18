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
            $this->action = $action;
            $this->request = $request;
            $this->createView();
            $this->init();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function init() {
        
    }

    public function createView() {
        // Template name
        $template = \strpos($this->action, '-') === false ? \Kf\System\String::camelToDash($this->action) : $this->action;

        $arrClass = explode('\\', get_class($this));
        $_controller = \Kf\System\String::camelToDash($arrClass[2]);

        if (!isset(\Kf\Kernel::$config['modulesPath'][$arrClass[0]])) {
            throw new \Kf\System\Exception\FileNotFoundException("Path from module {$arrClass[0]} not found.");
        }

        $modulePath = \Kf\Kernel::$config['modulesPath'][$arrClass[0]];
        if (is_dir("{$modulePath}/view/{$_controller}")) {
            $appPath = str_replace('/', '\/', APP_PATH);
            $moduleBasePath = preg_replace("/{$appPath}/", '', $modulePath, 1);
            $this->view = new \Kf\View\Html("{$moduleBasePath}view/{$_controller}/{$template}.phtml", ['request' => $this->request]);
        }
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

            if (!isset(\Kf\Kernel::$config['modulesPath'][$arrClass[0]])) {
                throw new \Kf\System\Exception\FileNotFoundException("Path from module {$arrClass[0]} not found.");
            }

            $modulePath = \Kf\Kernel::$config['modulesPath'][$arrClass[0]];
            if (is_dir("{$modulePath}/view/{$_controller}")) {
                $appPath = str_replace('/', '\/', APP_PATH);
                $moduleBasePath = preg_replace("/{$appPath}/", '', $modulePath, 1);
                $this->view = new \Kf\View\Html("{$moduleBasePath}view/{$_controller}/{$template}.phtml", ['request' => $this->request]);
            }

            \Kf\Kernel::$layout->css = $cssAndJs['css'];
            \Kf\Kernel::$layout->js = $cssAndJs['js'];
            return $this->{$this->action}();
        } catch (\Exception $ex) {
            throw $ex;
        }
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

}
