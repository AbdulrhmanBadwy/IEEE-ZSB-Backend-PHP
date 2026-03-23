<?php

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'core/functions.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $path = base_path($class . '.php');

    if (file_exists($path)) {
        require $path;
    }
});

require base_path('core/router.php');