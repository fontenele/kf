<?php

namespace Kf\View;

class Html extends \Kf\System\ArrayObject {

    /**
     * @var string
     */
    public $template;

    public function __construct($template = null, $vars = array()) {
        try {
            parent::__construct($vars);
            $this->basePath = \Kf\Kernel::$router->basePath;
            $this->theme = \Kf\Kernel::$config['system']['view']['theme'];
            $this->themePath = \Kf\Kernel::$router->basePath . 'themes/' . \Kf\Kernel::$config['system']['view']['theme'] . '/';
            $this->template = $template;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function render() {
        try {
            if ($this->template instanceof \Kf\System\File) {
                $this->template = str_replace(APP_PATH, '', $this->template->getName());
            }
            if (!$this->template || !file_exists(APP_PATH . $this->template)) {
                throw new \Exception;
            }

            ob_start();
            require APP_PATH . $this->template;
            $html = ob_get_clean();
            return $html;
        } catch (\Exception $ex) {
            throw new \Exception("Template {$this->template} not found.", 400);
        }
    }

    public function renderWithHeader() {
        try {
            header('Content-type: text/html; charset=UTF-8');
            return $this->render();
        } catch (\Exception $ex) {
            throw new \Exception("Template {$this->template} not found.", 400);
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
            $name = 'Kf\View\Html\Helper\\' . ucfirst($name);
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
