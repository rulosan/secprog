<?php
spl_autoload_register(function($class){
    $prefix = basename(__DIR__);
    $len = strlen($prefix);
    if(strncmp($prefix, $class, $len) == 0)
    {
        $relative_class = substr($class, $len);
        $file = __DIR__ . str_replace('\\', '/', $relative_class) . '.php';
        if(file_exists($file)){
            require_once($file);
            return;
        }
    }
});