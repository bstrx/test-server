<?php
namespace MyServer\Core;

/**
 * Contains request data for both get and post request methods
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Request
{
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';

    /**
     * Common data for controllers
     *
     * @var array
     */
    public $data;

    /**
     * Data for authentication
     *
     * @var array
     */
    public $authParams;

    /**
     * @var string
     */
    public $method;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $requestContent = json_decode(file_get_contents("php://input"), true);
        $this->authParams = isset($requestContent['auth']) ? $requestContent['auth'] : null;
        $this->data = isset($requestContent['data']) ? $requestContent['data'] : null;
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
     * @return array|null
     */
    public function getAuthParams()
    {
        return $this->authParams;
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
