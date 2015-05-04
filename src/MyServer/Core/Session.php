<?php
namespace MyServer\Core;

/**
 * @author Vladimir Prudilin
 * Sessions keys handling
 */
class Session
{
    /**
     * Returns data from session or default instead
     *
     * @param string $key
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
    public function init()
    {
        $session_id = session_id();
        if (empty($session_id)) {
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