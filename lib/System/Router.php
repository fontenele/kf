<?php

namespace KF\Lib\System;

class Router {

    /**
     * @var array
     */
    public static $sslPorts = array('443');

    /**
     * @var boolean
     */
    public $isSSL = false;

    /**
     * @var string
     */
    public $basePath;

    /**
     * @var string
     */
    public $serverName;

    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $default;

    /**
     * @var string
     */
    public $defaultAuth;

    /**
     * @param array $config
     */
    public function __construct($config) {
        $this->config($config);
        $this->defineControllerAndAction();
    }

    /**
     * @param array $config
     */
    public function config($config) {
        switch (true) {
            case isset($_SERVER['HTTPS']) && in_array($_SERVER['HTTPS'], array('on', '1')):
            case isset($_SERVER['SERVER_PORT']) && in_array($_SERVER['SERVER_PORT'], self::$sslPorts):
                $this->isSSL = true;
            default:
                $this->isSSL = false;
        }

        $httpScheme = $this->isSSL ? 'https' : 'http';
        $this->serverName = "{$httpScheme}://{$_SERVER['SERVER_NAME']}";

        if ($_SERVER['SCRIPT_NAME'] == '/index.php') {
            $this->basePath = "{$httpScheme}://{$_SERVER['SERVER_NAME']}/";
        } else {
            $host = \str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
            $this->basePath = "{$httpScheme}://{$_SERVER['HTTP_HOST']}{$host}/";
        }

        $this->default = $config['default'];
        $this->defaultAuth = $config['defaultAuth'];
    }

    public function defineControllerAndAction() {
        if ($_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_URI'] != '/') {
            //$uri = explode('/', substr($_SERVER['REQUEST_URI'], 1));
            $uri = $this->parseRoute($_SERVER['REQUEST_URI'], 1);
            $this->controller = $uri['controller'];
            if ($uri['params']) {
                \KF\Kernel::$request->setQuery(urldecode($uri['params']));
            }
            /* if (strpos($uri[1], '?')) {
              $urlQuery = explode('?', $uri[1]);
              $uri[1] = $urlQuery[0];
              $request->setQuery(urldecode($urlQuery[1]));
              } */
            /* xd($uri, \KF\Kernel::$request);
              for ($i = 2; $i < count($uri); $i = $i + 2) {
              if (isset($uri[$i + 1])) {
              $request->get->offsetSet($uri[$i], $uri[$i + 1]);
              }
              } */

            $this->action = $uri['action'];
        }
    }

    public function getController() {
        return $this->controller ? $this->controller : $this->defaults['controller'];
    }

    public function getAction() {
        return $this->action ? $this->action : $this->defaults['action'];
    }

    public static function getRealPath($className) {
        $arrClassName = \explode('\\', $className);
        $_module = \KF\Lib\View\Helper\String::camelToDash($arrClassName[0]);
        $_class = $arrClassName[2];

        $path = "modules/{$_module}/" . strtolower($arrClassName[1]) . "/{$_class}.php";
        $realPath = file_exists(APP_PATH . $path) ? APP_PATH . $path : null;
        return $realPath;
    }

    public function parseRoute($route) {
        $route = str_replace($this->basePath, '', $this->serverName . $route);
        $arrRoute = \explode('/', $route);
        $_module = ucfirst(\KF\Lib\View\Helper\String::dashToCamel(array_shift($arrRoute)));
        $_controller = ucfirst(\KF\Lib\View\Helper\String::dashToCamel(array_shift($arrRoute)));
        $_arrAction = explode('?', \KF\Lib\View\Helper\String::dashToCamel(array_shift($arrRoute)));
        $_action = $_arrAction[0];

        $_params = null;
        if (isset($arrRoute[count($arrRoute) - 1])) {
            $_params = explode('?', $arrRoute[count($arrRoute) - 1]);
            $arrRoute[count($arrRoute) - 1] = array_shift($_params);
        }

        $_params = count($_params) ? array_shift($_params) : null;

        for ($i = 0; $i < count($arrRoute); $i = $i + 2) {
            if (isset($arrRoute[$i + 1])) {
                if ($_params) {
                    $_params.= '&';
                }
                $_params.= "{$arrRoute[$i]}={$arrRoute[$i + 1]}";
            }
        }

        return array(
            'controller' => "{$_module}\Controller\\{$_controller}",
            'action' => $_action,
            'params' => $_params
        );
    }

    public function redirect($path = null) {
        $path = $this->basePath . $path;
        header("Location: {$path}");
        exit;
    }

}
