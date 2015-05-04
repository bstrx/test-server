<?php
namespace MyServer\Core;

/**
 * Just a global registry for important core services
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class ServiceContainer
{
    /**
     * @var array
     */
    private static $services = array();

    /**
     * @param string $key
     * @param mixed $value
     */
    static function set($key, $value)
    {
        self::$services[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    static function get($key)
    {
        if (isset(self::$services[$key])) {
            return self::$services[$key];
        }

        throw new \Exception(sprintf('Service %s does not exist'));
    }
}