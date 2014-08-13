<?php

namespace Kf\View\Html\Helper;

abstract class Helper {

    /**
     * @var \Kf\View\Html
     */
    public $view;
    
    public function __construct() {
        $this->view = new \Kf\View\Html();
    }

}
