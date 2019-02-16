<?php
//корневой каталог
define('ROOT_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

//каталоги приложения
define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
define('LIB_DIR', ROOT_DIR . 'lib' . DIRECTORY_SEPARATOR);
define('CFG_DIR', ROOT_DIR . 'cfg' . DIRECTORY_SEPARATOR);

define('CONTROLLERS_DIR', APP_DIR . 'controllers' . DIRECTORY_SEPARATOR);
define('MODELS_DIR', APP_DIR . 'models' . DIRECTORY_SEPARATOR);
define('VIEWS_DIR', APP_DIR . 'views' . DIRECTORY_SEPARATOR);

require_once LIB_DIR . 'init.php';

/**
 * Автозагрузка классов
 * @param string $name Имя класса
 */
function __autoload($name)
{
    $filename = strtolower($name) . '.php';

    $arAutloadPaths = [
        LIB_DIR,
        CONTROLLERS_DIR,
        MODELS_DIR,
    ];

    foreach ($arAutloadPaths as $path) {
        if (file_exists($path . $filename)) {
            require_once $path . $filename;
            return;
        }
    }
}
