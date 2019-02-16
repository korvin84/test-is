<?php

class AjaxController extends Controller
{

    public function generate()
    {
        $url = $_POST["url"];

        $obShorturl = new Shorturl();
        $obShorturl->setUrl($url);

        $resAdd = $obShorturl->add();

        die(json_encode($resAdd));
    }
}