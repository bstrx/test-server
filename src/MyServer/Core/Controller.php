<?php

namespace MyServer\Core;

use \Memcached;
use \Exception;

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
}