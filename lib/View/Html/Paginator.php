<?php

namespace KF\Lib\View\Html;

class Paginator {

    /**
     * @var string
     */
    protected $templateFirst = '&laquo;';

    /**
     * @var string
     */
    protected $templateLeft = '<';

    /**
     * @var string
     */
    protected $templateRight = '>';

    /**
     * @var string
     */
    protected $templateLast = '&raquo;';

    /**
     * @var integer
     */
    protected $rowsPerPage = 10;

    /**
     * @var integer
     */
    protected $active = 0;

    /**
     * @var integer
     */
    protected $totalItems = 0;

    /**
     * @var integer
     */
    protected $totalPages = 0;

    /**
     * @var integer
     */
    protected $maxItems = 3;

    public function __construct() {
        $this->setTemplateFirst(Helper\Glyphicon::get('step-backward'));
        $this->setTemplateLeft(Helper\Glyphicon::get('backward'));
        $this->setTemplateLast(Helper\Glyphicon::get('step-forward'));
        $this->setTemplateRight(Helper\Glyphicon::get('forward'));
        $this->setRowsPerPage(\KF\Kernel::$config['system']['view']['datagrid']['rowsPerPage']);
    }

    /**
     * @return string
     */
    public function getTemplateFirst() {
        return $this->templateFirst;
    }

    /**
     * @return string
     */
    public function getTemplateLeft() {
        return $this->templateLeft;
    }

    /**
     * @return string
     */
    public function getTemplateRight() {
        return $this->templateRight;
    }

    /**
     * @return string
     */
    public function getTemplateLast() {
        return $this->templateLast;
    }

    /**
     * @return integer
     */
    public function getRowsPerPage() {
        return $this->rowsPerPage;
    }

    /**
     * @return integer
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * @return integer
     */
    public function getTotalItems() {
        return $this->totalItems;
    }

    /**
     * @return integer
     */
    public function getTotalPages() {
        return $this->totalPages;
    }

    /**
     * @return integer
     */
    public function getTotalPagesInPaginator() {
        return $this->totalPagesInPaginator;
    }

    /**
     * @param string $templateFirst
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTemplateFirst($templateFirst) {
        $this->templateFirst = $templateFirst;
        return $this;
    }

    /**
     * @param string $templateLeft
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTemplateLeft($templateLeft) {
        $this->templateLeft = $templateLeft;
        return $this;
    }

    /**
     * @param string $templateRight
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTemplateRight($templateRight) {
        $this->templateRight = $templateRight;
        return $this;
    }

    /**
     * @param string $templateLast
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTemplateLast($templateLast) {
        $this->templateLast = $templateLast;
        return $this;
    }

    /**
     * @param integer $rowsPerPage
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setRowsPerPage($rowsPerPage) {
        $this->rowsPerPage = $rowsPerPage;
        return $this;
    }

    /**
     * @param integer $active
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }

    /**
     * @param integer $totalItems
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTotalItems($totalItems) {
        $this->totalItems = $totalItems;
        return $this;
    }

    /**
     * @param integer $totalPages
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTotalPages($totalPages) {
        $this->totalPages = $totalPages;
        return $this;
    }

    /**
     * @param integer $totalPagesInPaginator
     * @return \KF\Lib\View\Html\Paginator
     */
    public function setTotalPagesInPaginator($totalPagesInPaginator) {
        $this->totalPagesInPaginator = $totalPagesInPaginator;
        return $this;
    }

}
