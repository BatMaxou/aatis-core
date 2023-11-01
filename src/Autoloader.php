<?php

class Autoloader
{
    public static function autoload()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            $file = dirname(__DIR__) . '/' . preg_replace("/^App/", 'src', $file);

            if (file_exists($file)) {
                require $file;
            }
        });
    }
}

Autoloader::autoload();
