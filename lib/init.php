<?php

class Common
{
    public static function dmp($var)
    {
        $result = htmlspecialchars(print_r($var, true));
        die($result);
    }

    public static function processNotFound()
    {
        self::processRedirect('/error/not_found');
    }

    public static function processRedirect($url, $status = 301)
    {
        header('Location: ' . $url, TRUE, $status);
        exit;
    }

    public static function isHttps()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               $_SERVER['SERVER_PORT'] == 443;
    }

    public static function getServerUri()
    {
        $uri = self::isHttps() ? 'https://' : 'http://';
        $uri .= $_SERVER["HTTP_HOST"] . "/";

        return $uri;
    }
}
