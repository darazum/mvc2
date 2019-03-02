<?php
namespace Base;

class Request
{
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
        if (!$parts || sizeof($parts) < 1) {
            $this->_requestModule = false;
            $this->_requestController = false;
            $this->_requestAction = false;
        } else {
            foreach ($parts as $k => $part) {
                if ($part && !$this->validate($part)) {
                    throw new DispatchException('Url part #' . $k . ' not valid: ' . $part);
                }
            }

            $this->_requestModule = !empty($parts[0]) ? $parts[0] : false;
            $this->_requestController = !empty($parts[1]) ? $parts[1] : false;
            $this->_requestAction = !empty($parts[2]) ? $parts[2] : false;
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