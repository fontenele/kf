<?php

namespace KF\Lib\View;

class Html extends \KF\Lib\System\ArrayObject {

    /**
     * @var string
     */
    public $template;

    public function __construct($template = null, $vars = array()) {
        try {
            parent::__construct($vars);
            $this->basePath = \KF\Kernel::$router->basePath;
            $this->template = $template;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function render() {
        try {
            if (!$this->template || !file_exists(APP_PATH . $this->template)) {
                throw new \Exception("Template {$this->template} not found.");
            }

            ob_start();
            require APP_PATH . $this->template;
            $html = ob_get_clean();
            return $html;
        } catch (\Exception $ex) {
            throw new \Exception("Template {$this->template} not found.", 404);
        }
    }

    public function __get($name) {
        try {
            return $this->offsetExists($name) ? $this->offsetGet($name) : '';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __set($name, $value = null) {
        try {
            $this->offsetSet($name, $value);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __call($name, $arguments) {
        try {
            $name = 'KF\Lib\View\Html\Helper\\' . ucfirst($name);
            if (class_exists($name)) {
                $class = new $name();
                return call_user_func_array(array($class, "__invoke"), $arguments);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function partial($template, $vars = array()) {
        try {
            $partial = new Html($template, $vars);
            return $partial->render();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
