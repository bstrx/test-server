<?php
namespace MyServer\Core;

class Request
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';

    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $method;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        if ($requestMethod === self::POST_METHOD) {
            $this->method = self::POST_METHOD;
        } elseif ($requestMethod === self::GET_METHOD) {
            $this->method = self::GET_METHOD;
        } else {
            throw new \Exception('Only GET/POST methods are supported');
        }

        $this->data = array_merge($_GET, $_POST);
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return string
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            $default = $this->data[$key];
        }

        return $default;
    }

    /**
     * @param array $requestArray
     */
    public function set($requestArray)
    {
        foreach ($requestArray as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }
}