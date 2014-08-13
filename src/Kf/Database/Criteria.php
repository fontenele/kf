<?php

namespace Kf\Database;

class Criteria {

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $upper;

    const CONDITION_LIKE = 'LIKE';
    const CONDITION_EQUAL = 'EQUAL';
    const CONDITION_BETWEEN = 'BETWEEN';

    public function __construct($type) {
        $this->setUpper(true);
        $this->type = $type;
    }

    /**
     * @param string $type
     * @return \Kf\Database\Criteria
     */
    public static function create($type) {
        $criteria = new Criteria($type);
        return $criteria;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     * @return \Kf\Database\Criteria
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUpper() {
        return $this->upper;
    }

    /**
     * @param bool $upper
     * @return \Kf\Database\Criteria
     */
    public function setUpper($upper) {
        $this->upper = $upper;
        return $this;
    }

}
