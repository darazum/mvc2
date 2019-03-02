<?php
namespace Base;

class Dispatcher
{
    /** @var Base_Request */
    private $_request;

    private $_moduleName;
    private $_controllerName;
    private $_actionName;

    public function __construct()
    {
        $this->_request = Context::getInstance()->getRequest();
    }

    public function dispatch()
    {
        $module = ucfirst(strtolower($this->_request->getRequestModule()));
        $controller = ucfirst(strtolower($this->_request->getRequestController()));
        $action = ucfirst(strtolower($this->_request->getRequestAction()));

        $this->_moduleName = $module;
        $this->_controllerName = $controller;
        $this->_actionName = $action . 'Action';
    }

    /**
     * @return \Base\ControllerAbstract
     */
    public function getController()
    {
        $controllerClassName =  'App\\' . $this->_moduleName . '\Controller\\' . $this->_controllerName;
        /*if (!class_exists($controllerClassName)) {
            throw new \Exception('Controller ' . $controllerClassName . ' not found');
        }*/

        $controller = new $controllerClassName();
        if (! ($controller instanceof ControllerAbstract) ) {
            throw new \Exception('Controller ' . $controllerClassName . ' not implement abstract controller');
        }

        return $controller;
    }

    /**
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->_controllerName;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->_actionName;
    }
}