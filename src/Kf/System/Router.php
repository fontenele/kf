<?php

namespace KF\System;

class Router {

    /**
     * @var array
     */
    public static $sslPorts = ['443'];

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
    public $appName;

    /**
     * @var string
     */
    public $requestUri;

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
     * @throws \KF\System\Exception
     */
    public function __construct($config) {
        try {
            $this->config($config);
            $this->defineControllerAndAction();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param array $config
     * @throws \KF\System\Exception
     */
    public function config($config) {
        try {
            switch (true) {
                case isset($_SERVER['HTTPS']) && in_array($_SERVER['HTTPS'], ['on', '1']):
                case isset($_SERVER['SERVER_PORT']) && in_array($_SERVER['SERVER_PORT'], self::$sslPorts):
                    $this->isSSL = true;
                    break;
                default:
                    $this->isSSL = false;
                    break;
            }

            $httpScheme = $this->isSSL ? 'https' : 'http';
            $this->serverName = "{$httpScheme}://{$_SERVER['SERVER_NAME']}";

            if ($_SERVER['SCRIPT_NAME'] == '/index.php') {
                $this->basePath = "{$httpScheme}://{$_SERVER['SERVER_NAME']}/";
            } else {
                $host = \str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
                $this->basePath = "{$httpScheme}://{$_SERVER['HTTP_HOST']}{$host}/";
            }
            
            $appName = str_replace(array('/public','/index.php'), '', $_SERVER['SCRIPT_NAME']);
            if(!$appName) {
                $appName = $_SERVER['SERVER_NAME'];
            }
            $this->appName = $appName;
            $this->requestUri = str_replace($appName, '', $_SERVER['REQUEST_URI']);
            
            //xd($this->basePath, $this->serverName, str_replace($appName, '', str_replace($this->serverName, '', $this->basePath)), $appName);
            $this->default = $config['default'];
            $this->defaultAuth = $config['defaultAuth'];
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @throws \KF\System\Exception
     */
    public function defineControllerAndAction() {
        try {
            if ($this->requestUri && $this->requestUri != '/') {
                $uri = $this->parseRoute($this->requestUri, 1);
                $this->controller = $uri['controller'];
                if ($uri['params']) {
                    \KF\Kernel::$request->setQuery(urldecode($uri['params']));
                }
                $this->action = $uri['action'];
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @throws \KF\System\Exception
     */
    public function getController() {
        try {
            return $this->controller;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @throws \KF\System\Exception
     */
    public function getAction() {
        try {
            return $this->action;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $className
     * @return string
     * @throws \KF\System\Exception
     */
    public static function getRealPath($className) {
        try {
            $arrClassName = \explode('\\', $className);
            $_module = String::camelToDash($arrClassName[0]);
            $_class = $arrClassName[2];

            $path = "modules/{$_module}/" . strtolower($arrClassName[1]) . "/{$_class}.php";
            $realPath = file_exists(APP_PATH . $path) ? APP_PATH . $path : null;
            return $realPath;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $route
     * @return array
     * @throws \KF\System\Exception
     */
    public function parseRoute($route) {
        try {
            if(substr($route, 0, 1) === '/') {
                $route = substr($route, 1);
            }
            $arrRoute = \explode('/', $route);
            $_module = ucfirst(String::dashToCamel(array_shift($arrRoute)));
            $_controller = ucfirst(String::dashToCamel(array_shift($arrRoute)));
            $_arrAction = explode('?', String::dashToCamel(array_shift($arrRoute)));
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
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $path
     * @throws \KF\System\Exception
     */
    public function redirect($path = null) {
        try {
            $path = $this->basePath . $path;
            header("Location: {$path}");
            exit;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
