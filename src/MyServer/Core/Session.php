<?php
namespace MyServer\Core;

/**
 * Sessions keys handling
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Session
{
    /**
     * Sets session id
     *
     * @param $id
     */
    public function setId($id)
    {
        session_id($id);
    }

    /**
     * Returns session id
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Returns data from session or default instead
     *
     * @param string $key
     * @param mixed $default
     * @return mixed value of $_SESSION[$key]
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        
        return $default;
    }

    /**
     * Inputs data to session
     *
     * @param string $key
     * @param mixed $value 
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
  
    /**
     * Starts session if it was not already started
     */
    public function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Removes key/value from session
     *
     * @param $key
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }
}
