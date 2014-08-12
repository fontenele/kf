<?php

namespace KF;

class Kernel {

    /**
     * @var array
     */
    public static $config = [];

    /**
     * @var Lib\View\Layout
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
            self::loadLogger();
            self::loadRequest();
            self::loadRouter();
            self::loadDatabase();
            $valid = self::acl();
            if ($valid) {
                self::run();
            }
        } catch (Lib\System\Exception\ACLException $ex) {

        } catch (Lib\System\Exception\DatabaseException $ex) {

        } catch (Lib\System\Exception\RouterException $ex) {

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
            date_default_timezone_set('America/Sao_Paulo');
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
        define('PUBLIC_PATH', dirname(__DIR__) . '/public/');
        define('TMP_PATH', dirname(__DIR__) . '/public/tmp/');
        define('LOG_PATH', dirname(__DIR__) . '/logs/');
        defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'prod'));
    }

    public static function loadLibs() {
        try {
            require_once('lib/autoload.php');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadConfigs() {
        try {
            $dir = new Lib\System\Dir(APP_PATH . 'config');
            $envs = ['dev', 'hom', 'prod'];
            $filesIgnore = [];

            $files = $dir->getFiles()->getArrayCopy();
            sort($files);
            foreach ($files as $file) {
                $filename = "{$dir->dirName}/{$file}";
                if (in_array($filename, $filesIgnore)) {
                    continue;
                }

                if (Lib\System\File::fileExists($filename, APPLICATION_ENV)) {
                    $filesIgnore[] = $filename;
                    $filename = Lib\System\File::getFileName($filename, APPLICATION_ENV);
                    $filesIgnore[] = $filename;
                } else {
                    foreach ($envs as $env) {
                        if ($env == APPLICATION_ENV) {
                            continue;
                        }
                        if (Lib\System\File::fileExists($filename, $env)) {
                            $filesIgnore[] = $filename;
                            $filesIgnore[] = Lib\System\File::getFileName($filename, $env);
                        }
                    }
                }

                $config = Lib\System\File::loadFile($filename);
                self::$config = array_merge_recursive(self::$config, $config);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadLogger() {
        try {
            Lib\System\Logger::setDefaults();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadRequest() {
        try {
            self::$request = new Lib\Http\Request();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadRouter() {
        try {
            self::$router = new Lib\System\Router(self::$config['system']['router']);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function loadDatabase() {
        try {
            if (isset(self::$config['db']['type'])) {
                switch (self::$config['db']['type']) {
                    case 'pgsql':
                        self::$db = new Lib\Database\Postgres(self::$config['db']);
                        break;
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function acl() {
        try {
            if (self::$config['system']['acl']['enabled']) {
                $session = new Lib\System\Session('system');
                if (!$session->offsetExists('identity')) {
                    self::$logged = false;
                    self::run(self::$router->defaultAuth);
                    return false;
                }
            }

            self::$logged = true;
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
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

    public static function createLayout() {
        try {
            self::$layout = new Lib\View\Layout('public/themes/' . self::$config['system']['view']['theme'] . '/view/' . self::$config['system']['view']['layout']);
            self::$layout->success = Lib\System\Messenger::getSuccess();
            self::$layout->error = Lib\System\Messenger::getError();
            self::$layout->userLogged = self::$logged;
            self::$layout->config = self::$config;
            self::$layout->theme = self::$config['system']['view']['theme'];
            self::$layout->themePath = self::$router->basePath . 'themes/' . self::$config['system']['view']['theme'] . '/';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function callAction($controller, $action, $request) {
        try {
            self::createLayout();

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
            self::$layout->css = $css;
            self::$layout->js = $js;
            $view = $obj->$action($request);
//            self::$layout->css = array_merge(self::$layout->css, $css);
//            self::$layout->js = array_merge(self::$layout->js, $js);

            if ($view instanceof Lib\View\Json) {
                // Render Json output
                echo $view->render();
            } else {
                // Set content var
                self::$layout->content = $view ? $view->render() : $obj->view->render();

                // Render layout with html headers
                echo self::$layout->renderWithHeader();
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

\KF\Kernel::app();
