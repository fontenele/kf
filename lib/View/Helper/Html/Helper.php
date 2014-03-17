<?php

namespace KF\Lib\View\Helper\Html;

abstract class Helper {

    /**
     * @var \KF\Lib\View\Html
     */
    public $view;
    
    public function __construct() {
        $this->view = new \KF\Lib\View\Html();
    }

}
