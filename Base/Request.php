<?php
namespace Base;

class Request
{
    const DEFAULT_MODULE = 'Main';
    const DEFAULT_CONTROLLER = 'Index';
    const DEFAULT_ACTION = 'Index';

    private $_requestModule;
    private $_requestController;
    private $_requestAction;
    private $_requestParams;
    private $_requestUri;

    public function __construct()
    {
        $this->_requestParams = $_REQUEST;
        $this->_requestUri = trim($_SERVER['REQUEST_URI'], '/');
    }

    /**
     * @throws \Exception
     *
     * Метод обрабатывает пользовательский запрос
     * Валидирует переданный модуль, контроллер и экшен
     * Заполняет соответствующие переменные для будущего создания объекта контроллера
     */
    public function handle()
    {
        $parts = explode('/', $this->_requestUri);

        if (!$parts || sizeof($parts) < 2) {
            $this->_requestModule = self::DEFAULT_MODULE;
            $this->_requestController = self::DEFAULT_CONTROLLER;
            $this->_requestAction = self::DEFAULT_ACTION;
        } else {
            foreach ($parts as $k => $part) {
                if (!$this->validate($part)) {
                    throw new \Exception('Url part #' . $k . ' not valid: ' . $part);
                }
            }

            $this->_requestModule = $parts[0] ?? self::DEFAULT_MODULE;
            $this->_requestController = $parts[1] ?? self::DEFAULT_CONTROLLER;
            $this->_requestAction = $parts[2] ?? self::DEFAULT_ACTION;
        }
    }

    private function validate($urlPart)
    {
        $ret = preg_match('/^[a-zA-Z0-9]+$/', $urlPart);
        return $ret;
    }

    /**
     * @return mixed
     */
    public function getRequestModule()
    {
        return $this->_requestModule;
    }

    /**
     * @return mixed
     */
    public function getRequestController()
    {
        return $this->_requestController;
    }

    /**
     * @return mixed
     */
    public function getRequestAction()
    {
        return $this->_requestAction;
    }

    /**
     * @return mixed
     */
    public function getRequestParams()
    {
        return $this->_requestParams;
    }

    /**
     * @return mixed
     */
    public function getRequestUri()
    {
        return $this->_requestUri;
    }
}