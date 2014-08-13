<?php

namespace KF\View\Html\Datagrid;

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
     * @var \KF\View\Html\Renderer
     */
    protected $renderer;

    /**
     * @param integer $order
     * @param string $label
     * @param string $width
     * @param string $cssClass
     * @param \KF\View\Html\Renderer $renderer
     * @param string $dataName
     */
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
     * @param integer $order
     * @param string $label
     * @param string $width
     * @param string $cssClass
     * @param \KF\View\Html\Renderer $renderer
     * @param string $dataName
     * @return Header
     */
    public static function create($order = null, $label = null, $width = null, $cssClass = null, $renderer = null, $dataName = null) {
        $obj = new self($order, $label, $width, $cssClass, $renderer, $dataName);
        return $obj;
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
     * @return \KF\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * @param integer $order
     * @return \KF\View\Html\Datagrid\Header
     */
    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    /**
     * @param string $dataName
     * @return \KF\View\Html\Datagrid\Header
     */
    public function setDataName($dataName) {
        $this->dataName = $dataName;
        return $this;
    }

    /**
     * @param string $label
     * @return \KF\View\Html\Datagrid\Header
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $width
     * @return \KF\View\Html\Datagrid\Header
     */
    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

    /**
     * @param string $cssClass
     * @return \KF\View\Html\Datagrid\Header
     */
    public function setCssClass($cssClass) {
        $this->cssClass = $cssClass;
        return $this;
    }

    /**
     * @param \KF\View\Html\Renderer $renderer
     * @return \KF\View\Html\Datagrid\Header
     */
    public function setRenderer(\KF\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

}
