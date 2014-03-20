<?php

namespace KF;

Class Kernel {

    /**
     * @var array
     */
    public static $config = [];

    /**
     * @var Lib\View\Html
     */
    public static $layout;

    /**
     * @var Lib\Http\Request
     */
    public static $request;

    /**
     * @var Lib\System\Router
     */
    public static $router;

    /**
     * @var Lib\Database\Pdo
     */
    public static $db;

    /**
     * @var boolean
     */
    public static $logged = false;

    public static function app() {
        try {
            self::bootLoader();
            self::setConstants();
            self::loadLibs();
            self::loadConfigs();
            self::loadRequest();
            self::loadRouter();
            self::loadDatabase();
            $valid = self::acl();
            if ($valid) {
                self::run();
            }
        } catch (\Exception $ex) {
            if (isset(self::$config['system']['router']['error'][$ex->getCode()])) {
                $session = new Lib\System\Session('errorInfo');
                $session->info = [
                    'message' => $ex->getMessage(),
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'trace' => $ex->getTrace(),
                    'previous' => $ex->getPrevious()
                ];
                return self::callAction(self::$config['system']['router']['error'][$ex->getCode()]['controller'], self::$config['system']['router']['error'][$ex->getCode()]['action'], self::$request);
            }
            xd($ex);
        }
    }

    public static function bootLoader() {
        try {
            session_start();
            chdir(dirname(__DIR__));
            set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__));
            spl_autoload_register(function($className) {
                $router = Kernel::$router;
                if ($router) {
                    $realPath = $router::getRealPath($className);
                    if ($realPath) {
                        require_once($realPath);
                    }
                }
            });
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function setConstants() {
        define('APP_PATH', dirname(__DIR__) . '/');
    }

    public static function loadLibs() {
        require_once('lib/autoload.php');
    }

    public static function loadConfigs() {
        $dir = new Lib\System\Dir(APP_PATH . 'config');
        foreach ($dir->getFiles() as $_item) {
            $config = include_once("{$dir->dirName}/{$_item}");
            self::$config = array_merge_recursive(self::$config, $config);
        }
    }

    public static function loadRequest() {
        self::$request = new Lib\Http\Request();
    }

    public static function loadRouter() {
        self::$router = new Lib\System\Router(self::$config['system']['router']);
    }

    public static function loadDatabase() {
        if (isset(self::$config['db'])) {
            self::$db = new Lib\Database\Pdo(self::$config['db']);
        }
    }

    public static function acl() {
        $session = new Lib\System\Session('system');
        if (!$session->offsetExists('identity')) {
            self::$logged = false;
            self::run(self::$router->defaultAuth);
            return false;
        }

        self::$logged = true;
        return true;
    }

    public static function run($route = null) {
        try {
            $controller = null;
            $action = null;

            if ($route) {
                $route = self::$router->parseRoute($route);
                $controller = $route['controller'];
                $action = $route['action'];
            } else {
                $controller = self::$router->getController();
                $action = self::$router->getAction();
                if (!$controller || !$action) {
                    $route = self::$router->parseRoute(self::$router->default);
                    $controller = $route['controller'];
                    $action = $route['action'];
                }
            }

            self::callAction($controller, $action, self::$request);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function callAction($controller, $action, $request) {
        try {
            self::$layout = new Lib\View\Html('public/themes/' . self::$config['system']['view']['theme'] . '/view/' . self::$config['system']['view']['layout']);
            self::$layout->success = Lib\System\Messenger::getSuccess();
            self::$layout->error = Lib\System\Messenger::getError();
            self::$layout->userLogged = self::$logged;

            $arrController = explode('\\', $controller);
            $_module = Lib\System\String::camelToDash($arrController[0]);
            $_controller = Lib\System\String::camelToDash($arrController[2]);
            $_action = Lib\System\String::camelToDash($action);
            $pathCssJs = "%s/modules/{$_module}/{$_controller}/{$_action}.%s";

            $js = array();
            $css = array();

            if (file_exists(sprintf(APP_PATH . 'public/' . $pathCssJs, 'css', 'css'))) {
                $css[] = sprintf(self::$router->basePath . $pathCssJs, 'css', 'css');
            }

            if (file_exists(sprintf(APP_PATH . 'public/' . $pathCssJs, 'js', 'js'))) {
                $js[] = sprintf(self::$router->basePath . $pathCssJs, 'js', 'js');
            }

            self::$layout->css = $css;
            self::$layout->js = $js;

            $controller = '\\' . $controller;

            if (!class_exists($controller)) {
                throw new \Exception("Controller {$controller} not found.", 404);
            }

            // Instance controller
            $obj = new $controller($action, $request);

            if (!method_exists($obj, $action)) {
                throw new \Exception("Action {$controller}::{$action} not found.", 404);
            }

            // Call action
            $view = $obj->$action($request);

            if ($view instanceof Lib\View\Json) {
                // Render Json output
                echo $view->render();
            } else {
                header('Content-type: text/html; charset=UTF-8');

                // Set content var
                self::$layout->content = $view->render();

                // Render layout
                echo self::$layout->render();
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

\KF\Kernel::app();
