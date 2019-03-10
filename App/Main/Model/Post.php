<?php
namespace App\Main\Model;

use Base\Model\ModelAbstract;
use Base\Context as Context;

class Post extends ModelAbstract
{
    protected $_id;
    protected $_text;
    protected $_userId;
    protected $_createdAt;

    public function __construct()
    {
    }

    public function getText()
    {
        return $this->_text;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getUserId()
    {
        return $this->_userId;
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
        return 'posts';
    }

    public function initByDbData(array $data)
    {
        $this->_id = $data['id'];
        $this->_text = $data['text'];
        $this->_userId = $data['user_id'];
        $this->_createdAt = $data['created_at'];
    }

    public function initByData(array $data)
    {
        $this->_userId = $data['user_id'];
        $this->_text = $data['text'];
    }

    public function saveToDb()
    {
        $db = Context::getInstance()->getDbConnection();
        $table = $this->getTable();
        $date = date('Y-m-d H:i:s', time());
        $insert = "INSERT INTO $table (user_id, `text`, created_at)
          VALUES(:user_id, :text, '$date')";

        return $db->exec($insert, __METHOD__, [':text' => $this->_text, 'user_id' => $this->_userId]);
    }

}