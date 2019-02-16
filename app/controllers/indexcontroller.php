<?php

class IndexController extends Controller
{

    public function index()
    {
        $this->_template->title = "Главная страница";
        $this->_template->render();
    }

    public function redirect($hash)
    {
        $obShorturl = new Shorturl();
        $obShorturl->setHash($hash);

        $url = $obShorturl->find();

        if (empty($url)) {
            Common::processNotFound();
        } else {
            Common::processRedirect($url);
        }
    }
}
