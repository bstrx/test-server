<?php

namespace MyServer\Core;

use \Memcached;
use \Exception;

/**
 * Basic controller class with quick access to common services
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
abstract class Controller
{
    /**
     * @return Db
     * @throws Exception
     */
    public function getDb()
    {
        return ServiceContainer::get('db');
    }

    /**
     * @return Session
     * @throws Exception
     */
    public function getSession()
    {
        return ServiceContainer::get('session');
    }

    /**
     * @return Memcached
     * @throws Exception
     */
    public function getMemcached()
    {
        return ServiceContainer::get('memcached');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getUser()
    {
        return ServiceContainer::get('session')->get('user');
    }

    /**
     * Quick shortcut to get user id
     * @return int
     */
    public function getUserId()
    {
        return $this->getUser()['id'];
    }
}
