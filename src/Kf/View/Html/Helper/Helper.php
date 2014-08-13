<?php

namespace KF\View\Html\Helper;

abstract class Helper {

    /**
     * @var \KF\View\Html
     */
    public $view;
    
    public function __construct() {
        $this->view = new \KF\View\Html();
    }

}
