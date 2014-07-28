<?php

namespace KF\Lib\View\Html\Datagrid;

class Header {

    /**
     * @var integer
     */
    protected $order;

    /**
     * @var string
     */
    protected $dataName;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $cssClass;

    /**
     * @var \KF\Lib\View\Html\Renderer
     */
    protected $renderer;

    public function __construct($order = null, $label = null, $width = null, $cssClass = null, $renderer = null, $dataName = null) {
        if ($order) {
            $this->order = $order;
        }
        if ($label) {
            $this->label = $label;
        }
        if ($width) {
            $this->width = $width;
        }
        if ($cssClass) {
            $this->cssClass = $cssClass;
        }
        if ($renderer) {
            $this->renderer = $renderer;
        }
        if ($dataName) {
            $this->dataName = $dataName;
        }
    }

    /**
     * @return integer
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getDataName() {
        return $this->dataName;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return string
     */
    public function getCssClass() {
        return $this->cssClass;
    }

    /**
     * @return \KF\Lib\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * @param integer $order
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    /**
     * @param string $dataName
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function setDataName($dataName) {
        $this->dataName = $dataName;
        return $this;
    }

    /**
     * @param string $label
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $width
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    /**
     * @param string $cssClass
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function setCssClass($cssClass) {
        $this->cssClass = $cssClass;
        return $this;
    }

    /**
     * @param \KF\Lib\View\Html\Renderer $renderer
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function setRenderer(\KF\Lib\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

}
