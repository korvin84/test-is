<?php

/**
 * Базовый класс контроллера
 */
abstract class Controller
{
    protected $_template;

    public function __construct(String $controller, String $action)
    {
        $this->_template = new Template($controller, $action);
    }
}
