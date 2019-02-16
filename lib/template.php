<?php

class Template
{
    //Шаблоны по умолчанию
    const DEFAULT_HEADER = VIEWS_DIR . 'header.phtml';
    const DEFAULT_FOOTER = VIEWS_DIR . 'footer.phtml';

    protected $variables = [];
    protected $_controller;
    protected $_action;

    /**
     * Template constructor.
     * @param string $controller
     * @param string $action
     */
    public function __construct(String $controller, String $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function __get($name)
    {
        return $this->variables[$name];
    }

    /**
     * Отображаем шаблон
     */
    public function render()
    {
        //переменные шаблона
        extract($this->variables);

        $viewsPath = VIEWS_DIR . strtolower($this->_controller) . DIRECTORY_SEPARATOR;

        //если в папке views/controller/ нет header.phtml или footer.phtml, 
        //то загружаем header.phtml и footer.phtml по умолчанию        
        $header = $viewsPath . 'header.phtml';
        $footer = $viewsPath . 'footer.phtml';
        $action = $viewsPath . $this->_action . '.phtml';

        header("Content-type:text/html; charset=utf-8");

        file_exists($header) ? include $header : include self::DEFAULT_HEADER;
        if (file_exists($action)) include $action;
        file_exists($footer) ? include $footer : include self::DEFAULT_FOOTER;
    }
}