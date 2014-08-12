<?php

namespace KF\Lib\View\Html\Datagrid;

class Field {

    /**
     * @var string
     */
    protected $template;

    /**
     * @var \KF\Lib\View\Html\Renderer
     */
    protected $renderer;

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @return \KF\Lib\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * @param string $template
     * @return \KF\Lib\View\Html\Datagrid\Field
     */
    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    /**
     * @param \KF\Lib\View\Html\Renderer $renderer
     * @return \KF\Lib\View\Html\Datagrid\Field
     */
    public function setRenderer(\KF\Lib\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

}
