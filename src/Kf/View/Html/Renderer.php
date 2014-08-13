<?php

namespace KF\View\Html;

class Renderer {

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \KF\System\File
     */
    protected $file;

    /**
     * @param string $method
     * @param \KF\System\File $file
     */
    public function __construct($method = null, \KF\System\File $file = null) {
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
     * @return \KF\System\File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param string $method
     * @return \KF\View\Html\Renderer
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @param \KF\System\File $file
     * @return \KF\View\Html\Renderer
     */
    public function setFile(\KF\System\File $file) {
        $this->file = $file;
        return $this;
    }

    public function render($params = null) {
        if($this->getMethod()) {
            return call_user_func_array($this->getMethod(), [$params]);
        }
        $view = new \KF\View\Html($this->getFile(), $params);
        return $view->render();
    }

    public function __toString() {
        return $this->render();
    }

}
