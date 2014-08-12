<?php

namespace KF\Lib\Http;

use KF\Lib\System\ArrayObject;

class Request {

    /**
     * @var \ArrayObject
     */
    public $get;

    /**
     * @var \ArrayObject
     */
    public $post;

    /**
     * @var \ArrayObject
     */
    public $files;

    /**
     * @var string
     */
    public $query;

    /**
     * @var boolean
     */
    public $isAjax = false;

    public function __construct() {
        $this->get = new ArrayObject($_GET);
        $this->post = new ArrayObject($_POST);
        $this->files = new ArrayObject($_FILES);
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND \strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->isAjax = true;
        }
    }

    public function setQuery($queryString) {
        $this->query = $queryString;
        $query = \explode('&', $queryString);
        foreach ($query as $item) {
            $get = \explode('=', $item);
            $this->get->offsetSet($get[0], $get[1]);
        }
    }

    public function isPost() {
        return count($this->post) > 0;
    }

}
