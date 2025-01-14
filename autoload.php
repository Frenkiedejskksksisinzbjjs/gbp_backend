<?php

spl_autoload_register(function ($class) {
    // Define the base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // Convert the class name to the file path
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    // Check if the file exists, then require it
    if (file_exists($file)) {
        require $file;
    }
});
