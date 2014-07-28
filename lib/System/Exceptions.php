<?php

namespace KF\Lib\System\Exception;

class BaseException extends \Exception {
    
}

class DatabaseException extends BaseException {
    
}

class SQLException extends BaseException {
    
}

class RouterException extends BaseException {
    
}

class ACLException extends BaseException {
    
}

class FileNotFoundException extends BaseException {

    public function __construct($filename, $code = null, $previous = null) {
        parent::__construct("File {$filename} not found.", 304, $previous);
    }

}
