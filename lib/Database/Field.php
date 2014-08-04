<?php

namespace KF\Lib\Database;

class Field {

    protected $name;
    protected $dbName;
    protected $dbType;

    /**
     * @var integer
     */
    protected $dbMaxLength;

    /**
     * @var integer
     */
    protected $dbOrderBySequence;
    protected $dbOrderBySortType;

    /**
     * @var \KF\Lib\Module\Entity
     */
    protected $fkEntity;

    /**
     * @var string
     */
    protected $fkEntityField;

    /**
     * @var integer
     */
    protected $fkEntityJoinType;

    /**
     * @var bool
     */
    protected $required;
    protected $viewComponent;

    /**
     * @var \KF\Lib\View\Html\Datagrid\Header
     */
    protected $datagridHeader;

    /**
     * @var Criteria
     */
    protected $searchCriteria;

    /**
     * Fields types
     */
    const DB_TYPE_INTEGER = 1;
    const DB_TYPE_VARCHAR = 2;
    const DB_TYPE_DATE = 3;
    const DB_TYPE_TIME = 4;
    const DB_TYPE_DATETIME = 5;
    const DB_TYPE_MONEY = 6;
    const DB_TYPE_BOOLEAN = 7;

    /**
     * Joins types
     */
    const DB_JOIN_INNER = 1;
    const DB_JOIN_LEFT = 2;
    const DB_JOIN_RIGHT = 3;
    const DB_JOIN_FULL = 4;

    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @param string $dbName
     * @return \KF\Lib\Database\Field
     */
    public function setDbName($dbName) {
        $this->dbName = $dbName;
        return $this;
    }

    /**
     * @param integer $dbType
     * @return \KF\Lib\Database\Field
     */
    public function setDbType($dbType) {
        $this->dbType = $dbType;
        return $this;
    }

    /**
     * @param integer $dbMaxLength
     * @return \KF\Lib\Database\Field
     */
    public function setDbMaxLength($dbMaxLength) {
        $this->dbMaxLength = $dbMaxLength;
        return $this;
    }

    /**
     * @param integer $dbMaxLength
     * @return \KF\Lib\Database\Field
     */
    public function setDbOrderBySequence($dbOrderBySequence) {
        $this->dbOrderBySequence = $dbOrderBySequence;
        return $this;
    }

    public function setDbOrderBySortType($dbOrderBySortType) {
        $this->dbOrderBySortType = $dbOrderBySortType;
        return $this;
    }

    /**
     * @param \KF\Lib\Module\Entity $fkEntity
     * @return \KF\Lib\Database\Field
     */
    public function setFkEntity(\KF\Lib\Module\Entity $fkEntity) {
        $this->fkEntity = $fkEntity;
        return $this;
    }

    /**
     * @param string $fkEntityField
     * @return \KF\Lib\Database\Field
     */
    public function setFkEntityField($fkEntityField) {
        $this->fkEntityField = $fkEntityField;
        return $this;
    }

    /**
     * @param integer $fkEntityField
     * @return \KF\Lib\Database\Field
     */
    public function setFkEntityJoinType($fkEntityJoinType) {
        $this->fkEntityJoinType = $fkEntityJoinType;
        return $this;
    }

    /**
     * @param bool $required
     * @return \KF\Lib\Database\Field
     */
    public function setRequired($required) {
        $this->required = $required;
        return $this;
    }

    public function setViewComponent($viewComponent) {
        $this->viewComponent = $viewComponent;
        return $this;
    }

    /**
     * @param \KF\Lib\View\Html\Datagrid\Header $datagridHeader
     * @return \KF\Lib\Database\Field
     */
    public function setDatagridHeader(\KF\Lib\View\Html\Datagrid\Header $datagridHeader) {
        $datagridHeader->setDataName($this->getDbName());
        $this->datagridHeader = $datagridHeader;
        return $this;
    }

    /**
     * @param \KF\Lib\Database\Criteria $searchCriteria
     * @return \KF\Lib\Database\Field
     */
    public function setSearchCriteria(Criteria $searchCriteria) {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDbName() {
        return $this->dbName;
    }

    /**
     * @return integer
     */
    public function getDbType() {
        return $this->dbType;
    }

    /**
     * @return integer
     */
    public function getDbMaxLength() {
        return $this->dbMaxLength;
    }

    /**
     * @return integer
     */
    public function getDbOrderBySequence() {
        return $this->dbOrderBySequence;
    }

    public function getDbOrderBySortType() {
        return $this->dbOrderBySortType;
    }

    /**
     * @return \KF\Lib\Module\Entity
     */
    public function getFkEntity() {
        return $this->fkEntity;
    }

    /**
     * @return string
     */
    public function getFkEntityField() {
        return $this->fkEntityField;
    }

    public function getFkEntityJoinType() {
        return $this->fkEntityJoinType;
    }

    /**
     * @return bool
     */
    public function getRequired() {
        return $this->required;
    }

    public function getViewComponent() {
        return $this->viewComponent;
    }

    /**
     * @return \KF\Lib\View\Html\Datagrid\Header
     */
    public function getDatagridHeader() {
        return $this->datagridHeader;
    }

    /**
     * @return Criteria
     */
    public function getSearchCriteria() {
        return $this->searchCriteria;
    }

}
