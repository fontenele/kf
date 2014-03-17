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

        $templace = \strpos($action, '-') === false ? \KF\Lib\View\Helper\String::camelToDash($action) : $action;
        $_module = \KF\Lib\View\Helper\String::camelToDash($arrClass[0]);
        $this->view = new \KF\Lib\View\Html("modules/{$_module}/view/{$templace}.phtml", array('request' => $request));

        $this->request = $request;

        $this->init();
    }

    public function init() {
        
    }

    public function redirect($path = null) {
        \KF\Kernel::$router->redirect($path);
    }

}
