<?php

namespace KF\View\Html\Datagrid;

class Field {

    /**
     * @var string
     */
    protected $template;

    /**
     * @var \KF\View\Html\Renderer
     */
    protected $renderer;

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @return \KF\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * @param string $template
     * @return \KF\View\Html\Datagrid\Field
     */
    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    /**
     * @param \KF\View\Html\Renderer $renderer
     * @return \KF\View\Html\Datagrid\Field
     */
    public function setRenderer(\KF\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

}
