<?php
namespace Base;
use App\User\Model\Base as UserModelBase;

class ControllerUser extends ControllerAbstract
{
    /**
     * @throws RedirectException
     */
    public function preAction()
    {
        parent::preAction();

        if (!$this->USER) {
            $this->redirect('/user/login/register/');
        }
    }

}