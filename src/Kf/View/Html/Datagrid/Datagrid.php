<?php

namespace Kf\View\Html\Datagrid;

class Datagrid {

    /**
     * @var string
     */
    protected $id;

    /**
     * @var \Kf\Module\Entity
     */
    protected $entity;

    /**
     * @var \Kf\System\Collection
     */
    protected $headers;

    /**
     * @var \Kf\View\Html\Paginator
     */
    protected $paginator;

    /**
     * @var array|\Kf\System\Collection
     */
    protected $data;

    /**
     * @var \Kf\View\Html\Renderer
     */
    protected $renderer;

    public function __construct($id = null) {
        $this->headers = new \Kf\System\Collection;
        $this->setRenderer(new \Kf\View\Html\Renderer(null, new \Kf\System\File(__DIR__ . '/datagrid.phtml')));
        $this->setPaginator(new \Kf\View\Html\Paginator);
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
     * @return \Kf\Module\Entity
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
     * @return \Kf\View\Html\Paginator
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
     * @return \Kf\View\Html\Renderer
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
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param \Kf\Module\Entity $entity
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function setEntity(\Kf\Module\Entity $entity) {
        $this->entity = $entity;
        $this->parseEntity();
        return $this;
    }

    /**
     * @param array $headers
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        $this->getHeaders()->sortBy(null, 'getOrder');
        return $this;
    }

    /**
     * @param \Kf\View\Html\Paginator $paginator
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function setPaginator(\Kf\View\Html\Paginator $paginator) {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * @param array|\Kf\System\Collection $data
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @param \Kf\View\Html\Renderer $renderer
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function setRenderer(\Kf\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @param \Kf\View\Html\Datagrid\Header $header
     * @return \Kf\View\Html\Datagrid\Datagrid
     */
    public function addHeader(Header $header) {
        $this->headers[] = $header;
        $this->getHeaders()->sortBy(null, 'getOrder');
        return $this;
    }

    public function parseEntity() {
        $headers = new \Kf\System\Collection;
        foreach ($this->getEntity()->getFields() as $field => $dataField) {
            if ($dataField->getDatagridHeader()) {
                $headers[$dataField->getDatagridHeader()->getOrder()] = $dataField->getDatagridHeader();
            }
        }
        //ksort($headers);
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
