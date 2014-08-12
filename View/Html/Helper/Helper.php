<?php

namespace KF\Lib\View\Html\Helper;

abstract class Helper {

    /**
     * @var \KF\Lib\View\Html
     */
    public $view;
    
    public function __construct() {
        $this->view = new \KF\Lib\View\Html();
    }

}
