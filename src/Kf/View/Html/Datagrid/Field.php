<?php

namespace Kf\View\Html\Datagrid;

class Field {

    /**
     * @var string
     */
    protected $template;

    /**
     * @var \Kf\View\Html\Renderer
     */
    protected $renderer;

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @return \Kf\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * @param string $template
     * @return \Kf\View\Html\Datagrid\Field
     */
    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    /**
     * @param \Kf\View\Html\Renderer $renderer
     * @return \Kf\View\Html\Datagrid\Field
     */
    public function setRenderer(\Kf\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

}
