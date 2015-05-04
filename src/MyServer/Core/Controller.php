<?php

namespace MyServer\Core;

abstract class Controller
{
    /**
     * @return Db
     * @throws \Exception
     */
    public function getDb()
    {
        return ServiceContainer::get('db');
    }

    /**
     * @return Session
     * @throws \Exception
     */
    public function getSession()
    {
        return ServiceContainer::get('session');
    }
}