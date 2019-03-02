<?php
namespace Base;

class Context
{
    private $_request;

    private $_user;

    private static $_instance;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @return \Base\Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }
}