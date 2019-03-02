<?php
namespace App\User\Model;

class Base
{
    protected $_name;
    protected $_id;

    public function __construct($userData)
    {
        $this->_name = $userData['name'];
        $this->_id = $userData['id'];
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function saveUserToDb()
    {
        // соединяемся с БД и сораняем модель в БД
    }
}