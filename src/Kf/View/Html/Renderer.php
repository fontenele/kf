<?php

namespace Kf\View\Html;

class Renderer {

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \Kf\System\File
     */
    protected $file;

    /**
     * @param string $method
     * @param \Kf\System\File $file
     */
    public function __construct($method = null, \Kf\System\File $file = null) {
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
     * @return \Kf\System\File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param string $method
     * @return \Kf\View\Html\Renderer
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @param \Kf\System\File $file
     * @return \Kf\View\Html\Renderer
     */
    public function setFile(\Kf\System\File $file) {
        $this->file = $file;
        return $this;
    }

    public function render($params = null) {
        if($this->getMethod()) {
            return call_user_func_array($this->getMethod(), [$params]);
        }
        $view = new \Kf\View\Html($this->getFile(), $params);
        return $view->render();
    }

    public function __toString() {
        return $this->render();
    }

}
