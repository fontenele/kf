<?php

namespace KF\Lib\View\Html\Datagrid;

class Datagrid {

    /**
     * @var string
     */
    protected $id;

    /**
     * @var \KF\Lib\Module\Entity
     */
    protected $entity;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var \KF\Lib\View\Html\Paginator
     */
    protected $paginator;

    /**
     * @var array|\KF\Lib\System\Collection
     */
    protected $data;

    /**
     * @var \KF\Lib\View\Html\Renderer
     */
    protected $renderer;

    public function __construct($id = null) {
        $this->setRenderer(new \KF\Lib\View\Html\Renderer(null, new \KF\Lib\System\File(APP_PATH . 'lib/View/Html/Datagrid/datagrid.phtml')));
        $this->setPaginator(new \KF\Lib\View\Html\Paginator);
        if($id) {
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
     * @return \KF\Lib\Module\Entity
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
     * @return \KF\Lib\View\Html\Paginator
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
     * @return \KF\Lib\View\Html\Renderer
     */
    public function getRenderer() {
        return $this->renderer;
    }
    
    /**
     * @param string $id
     * @return \KF\Lib\View\Html\Datagrid\Datagrid
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @param \KF\Lib\Module\Entity $entity
     * @return \KF\Lib\View\Html\Datagrid\Datagrid
     */
    public function setEntity(\KF\Lib\Module\Entity $entity) {
        $this->entity = $entity;
        $this->parseEntity();
        return $this;
    }

    /**
     * @param array $headers
     * @return \KF\Lib\View\Html\Datagrid\Datagrid
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param \KF\Lib\View\Html\Paginator $paginator
     * @return \KF\Lib\View\Html\Datagrid\Datagrid
     */
    public function setPaginator(\KF\Lib\View\Html\Paginator $paginator) {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * @param array|\KF\Lib\System\Collection $data
     * @return \KF\Lib\View\Html\Datagrid\Datagrid
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function setRenderer(\KF\Lib\View\Html\Renderer $renderer) {
        $this->renderer = $renderer;
    }

    /**
     * @param \KF\Lib\View\Html\Datagrid\Header $header
     * @return \KF\Lib\View\Html\Datagrid\Datagrid
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

    public function __toString() {
        return $this->render();
    }

}
