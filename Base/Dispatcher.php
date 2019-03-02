<?php
namespace Base;

class Dispatcher
{
    const DEFAULT_MODULE = 'Main';
    const DEFAULT_CONTROLLER = 'Index';
    const DEFAULT_ACTION = 'Index';

    /** @var Request */
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
        $requestedModule = $this->_request->getRequestModule();
        $requestedController = $this->_request->getRequestController();
        $requestedAction = $this->_request->getRequestAction();

        $module = $requestedModule ? ucfirst(strtolower($requestedModule)) : false;
        $controller = $requestedController ? ucfirst(strtolower($requestedController)) : false;
        $action = $requestedAction ? ucfirst(strtolower($requestedAction)) : false;

        $this->_moduleName = $module;
        $this->_controllerName = $controller;
        $this->_actionName = $action;
    }

    private function _getRoutes()
    {
        return [
            'main' => 'Main.Index.index',
            'fs' => 'Main.Index.fs',
            'carbon' => 'Main.Index.carbon',
            'parser' => 'Main.Index.parser',
            'user' => [
                'login' => 'User.Login.main'
            ],
            'ololo' => [
                'atata' => 'Main.Index.index'
            ]
        ];
    }

    private function processRoutes()
    {
        $routes = $this->_getRoutes();
        $module = strtolower($this->_moduleName);
        $foundRoute = false;

        if (isset($routes[$module]) && is_string($routes[$module]) && empty($this->_controllerName)) {
            $foundRoute = $routes[$module];
        } elseif(isset($routes[$module])
            && is_array($routes[$module])
            && isset($routes[$module][strtolower($this->_controllerName)])
            && empty($this->_actionName)
        ) {
            $foundRoute = $routes[$module][strtolower($this->_controllerName)];
        }

        if ($foundRoute) {
            list($newModule, $newController, $newAction) = explode('.', $foundRoute);
            $this->_moduleName = $newModule;
            $this->_controllerName = $newController;
            $this->_actionName = $newAction;
        }
    }

    /**
     * @return \Base\ControllerAbstract
     *
     * @throws DispatchException
     */
    public function getController()
    {
        $this->processRoutes();

        $this->_moduleName = $this->_moduleName ?: self::DEFAULT_MODULE;
        $this->_controllerName = $this->_controllerName ?: self::DEFAULT_CONTROLLER;
        $this->_actionName = $this->_actionName ?: self::DEFAULT_ACTION;

        $controllerClassName =  'App\\' . $this->_moduleName . '\Controller\\' . $this->_controllerName;
        if (!class_exists($controllerClassName)) {
            throw new DispatchException('Controller ' . $controllerClassName . ' not found');
        }

        $controller = new $controllerClassName();
        if (! ($controller instanceof ControllerAbstract) ) {
            throw new DispatchException('Controller ' . $controllerClassName . ' not implement abstract controller');
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