<?php

namespace KF\Lib\Module;

abstract class Controller {

    /**
     * @var \KF\Lib\View\Html
     */
    public $view;

    /**
     * @var string
     */
    public $action;

    /**
     * @var \KF\Lib\Http\Request
     */
    public $request;

    public function __construct($action, $request) {
        $arrClass = explode('\\', get_class($this));
        $this->action = $action;

        $templace = \strpos($action, '-') === false ? \KF\Lib\System\String::camelToDash($action) : $action;
        $_module = \KF\Lib\System\String::camelToDash($arrClass[0]);
        $_controller = \KF\Lib\System\String::camelToDash($arrClass[2]);
        $this->view = new \KF\Lib\View\Html("modules/{$_module}/view/{$_controller}/{$templace}.phtml", array('request' => $request));

        $this->request = $request;

        $this->init();
    }

    public function init() {
        
    }

    public function redirect($path = null) {
        if ($path && substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }
        \KF\Kernel::$router->redirect($path);
    }

}
