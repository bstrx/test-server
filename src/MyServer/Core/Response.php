<?php
namespace MyServer\Core;

/**
 * Wraps response data
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Response
{
    const ERROR = 0;
    const OK = 1;

    /**
     * @var array
     */
    public $data;

    /**
     * @var bool
     */
    public $status;

    /**
     * @var Session
     */
    public $session;

    /**
     * @param mixed $data
     * @param int $status
     */
    public function __construct($data, $status = self::OK)
    {
        $this->data = $data;
        $this->status = $status;
        $this->session = ServiceContainer::get('session');
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        return json_encode($this->getArray());
    }

    /**
     * @return array
     */
    private function getArray()
    {
        return [
            'status' => $this->getStatus(),
            'auth' => ['sessionId' => $this->session->getId()],
            'data' => $this->getData()
        ];
    }
}
