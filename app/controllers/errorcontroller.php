<?php

class ErrorController extends Controller
{

    public function not_found()
    {
        header("HTTP/1.0 404 Not Found");

        $this->_template->title = "Страница не найдена";
        $this->_template->render();
    }

}