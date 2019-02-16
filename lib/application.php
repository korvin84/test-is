<?php

class Application
{
    //Контроллер и действие по умолчанию
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION     = 'index';

    private static $instance;

    private function __construct()
    {
        require_once CFG_DIR . 'config.php';
        APP_STATUS === "dev" ? error_reporting(-1) : error_reporting(0);
    }

    private function __clone()
    {

    }

    private static function getInstance()
    {
        if (!is_object(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Разбирает URL
     * @return array Возвращает array(controller, action, queryString)
     */
    private function parseURL()
    {
        $urlArray = array_filter(
            explode(
                '/',
                filter_input(INPUT_GET, 'url', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
            )
        );

        switch (count($urlArray)) {
            case 0:
                return [self::DEFAULT_CONTROLLER, self::DEFAULT_ACTION, []];
            case 1:
                return [$urlArray[0], self::DEFAULT_ACTION, []];
            case 2:
                return [$urlArray[0], $urlArray[1], []];
            default:
                return [$urlArray[0], $urlArray[1], array_slice($urlArray, 2)];
        }
    }

    /**
     * Собственно загрузка
     */
    private function dispatch()
    {
        list($controller, $action, $queryString) = $this->parseURL();

        $controllerClass = ucwords($controller) . 'Controller';

        if (class_exists($controllerClass)) {
            $controllerName = ucwords($controller);
        } elseif (!empty($controller) && $action == self::DEFAULT_ACTION) {
            $controllerClass = ucwords(self::DEFAULT_CONTROLLER) . 'Controller';
            $controllerName = ucwords(self::DEFAULT_CONTROLLER);
            $action = "redirect";
            $queryString = $controller;
        } else {
            Common::processNotFound();
        }

        $dispatch = new $controllerClass($controllerName, $action);

        if (method_exists($controllerClass, $action)) {
            $dispatch->$action($queryString);
        } else {
            Common::processNotFound();
        }
    }

    public static function run()
    {
        Application::getInstance()->dispatch();
    }
}