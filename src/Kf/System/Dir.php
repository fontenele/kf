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

    /**
     * @param string $dirName
     * @return \Kf\System\Dir
     */
    public static function create($dirName) {
        $obj = new Dir($dirName);
        return $obj;
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
