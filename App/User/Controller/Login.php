<?php

namespace App\User\Controller;

use Base\ControllerAbstract as ControllerAbstract;
use App\User\Model as UserModel;

class Login extends ControllerAbstract
{
    function mainAction()
    {
        echo 'We are here';
    }

    function testAction()
    {
        $userModel = UserModel\DB::getModelById(1);
        UserModel\DB::saveUser($userModel);
        $this->view->user = $userModel;
    }
}