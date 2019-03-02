<?php
namespace Base;

class Application
{
    private $_config;
    /** @var Context */
    private $_context;
    /** @var Request */
    private $_request;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    private function _init()
    {
        // это глобальный контекст приложения доступный везде
        $this->_context = Context::getInstance();
    }

    public function run()
    {
        try {
            // инициализируем приложение
            $this->_init();

            // это объект запроса, содержит все данные которые пришли от пользователя
            $this->_request = new Request();

            // помещаем его в контекст, он нам еще пригодится
            $this->_context->setRequest($this->_request);

            // обрабатываем пользовательский запрос
            $this->_request->handle();

            // это диспетчер, он занимается обработкой запроса и получением нужного контроллера
            $dispatcher = new Dispatcher();
            $dispatcher->dispatch();

            // просим диспетчер создать нам объект контроллера
            $controller = $dispatcher->getController();

            // получаем от диспетчера имя вызванного экшена
            $action = $dispatcher->getActionName();

            // проверяем существование метода
            if (!method_exists($controller, $action)) {
                throw new \Exception(
                    'Action ' . $action . ' not found in controller '
                    . $this->_request->getRequestController()
                );
            }


            // создаем view
            $view = new View($this->_getDefaultTemplatePath());

            // передаем созданный объект view в контроллер (теперь мы из контроллера можем им управлять)
            $controller->view = $view;

            // вызываем экшен
            $controller->$action();

            // рендерим контент
            if ($controller->needRender()) {
                $content = $view->render($controller->tpl);
                echo $content;
            }

        } catch (\Exception $e) {
            echo 'Произошло исключение: ' . $e->getMessage();
            // обработка исключений самого базового уровня - редирект на 404.html
        }
    }

    private function _getDefaultTemplatePath()
    {
        return ucfirst($this->_request->getRequestModule())
            . DIRECTORY_SEPARATOR
            . 'Templates'
            . DIRECTORY_SEPARATOR
            . ucfirst($this->_request->getRequestController());
    }

}