<?php
namespace MyServer\Core;

class Autoloader
{
    public function register()
    {
        spl_autoload_register(function ($class) {
            $baseDir = __DIR__ . '/../../';
            $file = $baseDir . str_replace('\\', '/', $class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
    }
}