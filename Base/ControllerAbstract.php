<?php
namespace Base;
use App\User\Model\Base as UserModelBase;

class ControllerAbstract
{
    public $tpl;

    /** @var View */
    public $view;

    /** @var UserModelBase */
    protected $USER;

    public $_noRender = false;

    function __construct()
    {
        $request = Context::getInstance()->getRequest();
        $this->tpl = strtolower($request->getRequestAction()) . '.phtml';
    }

    public function preAction()
    {
    }

    public function noRender()
    {
        $this->_noRender = true;
    }

    public function needRender(): bool
    {
        return !$this->_noRender;
    }

    public function setUser(UserModelBase $user)
    {
        $this->USER = $user;
    }

    /**
     * @throws RedirectException
     */
    protected function redirect(string $location)
    {
        throw new RedirectException($location);
    }

    public function p(string $key)
    {
        return htmlspecialchars($_REQUEST[$key] ?? '');
    }
}