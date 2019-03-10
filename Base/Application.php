<?php
namespace Base;

use Base\Model\Factory;

class Application
{
    private $_config;
    /** @var Context */
    private $_context;
    /** @var Request */
    private $_request;
    /** @var Dispatcher */
    private $_dispatcher;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    public function init()
    {
        // это глобальный контекст приложения доступный везде
        $this->_context = Context::getInstance();

        // подгружаем конфиг
        $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        define('PRODUCTION', getenv('PRODUCTION'));

        // создаем объект подключения к БД
        $this->_context->setDbConnection(
            DbConnection::instance()
        );

        // это объект запроса, содержит все данные которые пришли от пользователя
        $this->_request = new Request();

        // помещаем его в контекст, он нам еще пригодится
        $this->_context->setRequest($this->_request);

        $this->_initUser();
    }

    /**
     * @throws Exception
     */
    private function _initUser()
    {
        $session = Session::instance();
        $userId = $session->getUserId();
        if ($userId) { // проверили что в сессии есть user_id
            if ($session->check()) { // проверили что ip и user-agent не изменился
                $user = Factory::getById(Factory::MODEL_USER, __METHOD__, $userId);
                if ($user) {
                    $this->_context->setUser($user);
                }
            }
        }
    }

    public function run()
    {
        try {
            // инициализируем приложение
            $this->init();

            // обрабатываем пользовательский запрос
            $this->_request->handle();

            // это диспетчер, он занимается обработкой запроса и получением нужного контроллера
            $this->_dispatcher = new Dispatcher();
            $this->_context->setDispatcher($this->_dispatcher);
            $this->_dispatcher->dispatch();

            // просим диспетчер создать нам объект контроллера
            $controller = $this->_dispatcher->getController();

            // получаем от диспетчера имя вызванного экшена
            $action = $this->_dispatcher->getActionName() . 'Action';

            // проверяем существование метода
            if (!method_exists($controller, $action)) {
                throw new DispatchException(
                    'Action ' . $action . ' not found in controller '
                    . $this->_request->getRequestController()
                );
            }

            // создаем view
            $view = new View($this->_getDefaultTemplatePath());

            // передаем созданный объект view в контроллер (теперь мы из контроллера можем им управлять)
            $controller->view = $view;

            // добвляем пользователя
            $user = Context::getInstance()->getUser();
            if ($user) {
                $controller->setUser($user);
            }

            // вызываем экшен
            $controller->preAction();
            $controller->$action();

            // рендерим контент
            if ($controller->needRender()) {
                $content = $view->render($controller->tpl);
                echo $content;

                if (!PRODUCTION) {
                    echo $this->_context->getDbConnection()->getLog();
                }
            }
        } catch (RedirectException $e) {
            header('Location: ' . $e->getLocation());
        } catch (DispatchException $e) {
            $e->process();
            // обработка исключений в диспетчере
        } catch (\Exception $e) {
            echo 'Произошло исключение: ' . $e->getMessage();
            // обработка исключений самого базового уровня - редирект на 404.html
        }
    }

    private function _getDefaultTemplatePath()
    {
        return ucfirst($this->_dispatcher->getModuleName())
            . DIRECTORY_SEPARATOR
            . 'Templates'
            . DIRECTORY_SEPARATOR
            . ucfirst($this->_dispatcher->getControllerName());
    }

}