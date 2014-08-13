<?php

namespace KF\View\Html\Datagrid;

class Datagrid {

    /**
     * @var string
     */
    protected $id;

    /**
     * @var \KF\Module\Entity
     */
    protected $entity;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var \KF\View\Html\Paginator
     */
    protected $paginator;

    /**
     * @var array|\KF\System\Collection
     */
    protected $data;

    /**
     * @var \KF\View\Html\Renderer
     */
    protected $renderer;

    public function __construct($id = null) {
        $this->setRenderer(new \KF\View\Html\Renderer(null, new \KF\System\File(APP_PATH . 'lib/View/Html/Datagrid/datagrid.phtml')));
        $this->setPaginator(new \KF\View\Html\Paginator);
        if ($id) {
            $this->setId($id);
        }
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return \KF\Module\Entity
     */
    public function getEntity() {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @return Header
     */
    public function getHeader($index) {
        return $this->headers[$index];
    }

    /**
     * @return \KF\View\Html\Paginator
     */
    public function getPaginator() {
        return $this->paginator;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return \KF\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }

    public function getFormItems() {
        try {
            $html = <<<HTML
                <input type="hidden" name="kf-dg-p" value="{$this->getPaginator()->getActive()}" />
HTML;
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $html;
    }

    /**
     * @param string $id
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param \KF\Module\Entity $entity
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function setEntity(\KF\Module\Entity $entity) {
        $this->entity = $entity;
        $this->parseEntity();
        return $this;
    }

    /**
     * @param array $headers
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param \KF\View\Html\Paginator $paginator
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function setPaginator(\KF\View\Html\Paginator $paginator) {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * @param array|\KF\System\Collection $data
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @param \KF\View\Html\Renderer $renderer
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function setRenderer(\KF\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @param \KF\View\Html\Datagrid\Header $header
     * @return \KF\View\Html\Datagrid\Datagrid
     */
    public function addHeader(Header $header) {
        $this->headers[] = $header;
        return $this;
    }

    public function parseEntity() {
        $headers = [];
        foreach ($this->getEntity()->getFields() as $field => $dataField) {
            if ($dataField->getDatagridHeader()) {
                $headers[$dataField->getDatagridHeader()->getOrder()] = $dataField->getDatagridHeader();
            }
        }
        ksort($headers);
        $this->setHeaders($headers);
    }

    public function render() {
        return $this->getRenderer()->render(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->render();
    }

}
