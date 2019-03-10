<?php

namespace App\User\Controller;

use Base\ControllerAbstract as ControllerAbstract;
use App\User\Model\Base as UserModel;
use Base\Exception;

class Login extends ControllerAbstract
{
    public function registerAction()
    {
    }

    /**
     * @throws \Base\RedirectException
     */
    public function createUserAction()
    {
        $name = $this->p('name');
        $password = $this->p('password');

        $user = new UserModel();
        $user->initByData([
            'name' => $name,
            'password' => $password,
        ]);

        $user->saveToDb();
        $this->redirect('/');
    }

    public function loginAction()
    {

        $name = $this->p('name');
        $password = $this->p('password');

        $user = new UserModel();
        $user->initByData([
            'name' => $name,
            'password' => $password,
        ]);

        try {
            $success = $user->authorize();
            if (!$success) {
                $error = 'Wrong login or password';
            }
        } catch (Exception $e) {
            $error = 'Sever error';
            trigger_error($e->getMessage());
            $success = false;
        }

        if ($success) {
            $this->redirect('/');
        } else {
            $this->view->error = $error;
            $this->tpl = 'register.phtml';
        }
    }

}