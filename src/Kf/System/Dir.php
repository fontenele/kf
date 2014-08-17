<?php

namespace Kf\System;

class Dir extends ArrayObject {

    public $dirName;
    public $resource;
    public $ignores = array(
        '.', '..', '.svn', '.git'
    );

    public function __construct($dirName) {
        $this->dirName = $dirName;
        $this->resource = \dir($dirName);
    }

    public function getFiles($withFullPath = false) {
        $dir = clone $this;
        while (($item = $this->resource->read()) !== false) {
            if (!\in_array($item, $this->ignores)) {
                $dir->append($withFullPath ? "{$this->resource->path}/{$item}" : $item);
            }
        }
        return $dir;
    }

}
