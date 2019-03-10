<?php
namespace App\User\Model;

use Base\Context;
use Base\Model\ModelAbstract;
use Base\Session;

class Base extends ModelAbstract
{
    protected $_name;
    protected $_id;
    protected $_passwordHash;
    protected $_password;
    protected $_createdAt;

    public function __construct()
    {
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->_passwordHash;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    public function getTable()
    {
       return 'users';
    }

    public function initByDbData(array $data)
    {
        $this->_id = $data['id'];
        $this->_name = $data['name'];
        $this->_passwordHash = $data['password_hash'];
        $this->_createdAt = $data['created_at'];
    }

    public function initByData($data)
    {
        $this->_name = $data['name'];
        $this->_password = $data['password'];
    }

    public function saveToDb()
    {
        $db = Context::getInstance()->getDbConnection();
        $table = $this->getTable();
        $passwordHash = $this->generatePasswordHash();
        $date = date('Y-m-d H:i:s', time());
        $insert = "INSERT INTO $table (`name`, password_hash, created_at)
          VALUES(:name, '$passwordHash', '$date')";

        return $db->exec($insert, __METHOD__, [':name' => $this->_name]);
    }

    public function generatePasswordHash()
    {
        $salt = 'fdsif/,3';
        $hash = sha1($salt . '_' . $this->_password);
        return $hash;
    }

    /**
     * @throws \Base\Exception
     */
    public function authorize()
    {
        $db = Context::getInstance()->getDbConnection();
        $select = "SELECT * FROM users WHERE `name` = :name AND password_hash = :password_hash";
        $data = $db->fetchOne($select, __METHOD__, [
            ':name' => $this->_name,
            ':password_hash' => $this->generatePasswordHash()
        ]);
        if ($data) {
            $session = Session::instance();
            $session->save((int)$data['id']);
            return true;
        }

        return false;
    }


}