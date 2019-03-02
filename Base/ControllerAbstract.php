<?php
namespace Base;
class ControllerAbstract
{
    public $tpl;

    /** @var View */
    public $view;

    public $_noRender = false;

    function __construct()
    {
        $request = Context::getInstance()->getRequest();
        $this->tpl = strtolower($request->getRequestAction()) . '.phtml';

        session_start();
        if (isset($_SESSION['user_id'])) {
            $userModel = User_Model_DB::getModelById($_SESSION['user_id']);
            $this->view->user = $userModel;
        }
    }

    public function noRender()
    {
        $this->_noRender = true;
    }

    public function needRender(): bool
    {
        return !$this->_noRender;
    }
}