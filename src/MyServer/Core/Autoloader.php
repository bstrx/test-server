<?php
namespace MyServer\Core;

/**
 * Simple autoloader
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Autoloader
{
    /**
     * Registers autoloader
     */
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
