<?php

namespace KF\Lib\View\Html;

class Renderer {

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \KF\Lib\System\File
     */
    protected $file;

    public function __construct($method = null, \KF\Lib\System\File $file = null) {
        if ($method) {
            $this->setMethod($method);
        }
        if ($file) {
            $this->setFile($file);
        }
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @return \KF\Lib\System\File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param string $method
     * @return \KF\Lib\View\Html\Renderer
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @param \KF\Lib\System\File $file
     * @return \KF\Lib\View\Html\Renderer
     */
    public function setFile(\KF\Lib\System\File $file) {
        $this->file = $file;
        return $this;
    }

    public function render($params = null) {
        $view = new \KF\Lib\View\Html($this->getFile(), $params);
        return $view->render();
    }

    public function __toString() {
        return $this->render();
    }

}
