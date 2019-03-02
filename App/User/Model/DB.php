<?php
namespace App\User\Model;

use App\User\Model\Base as UserModelBase;

class DB
{
    private static $_users = [
        1 => [
            'id' => 1,
            'name' => 'Dima',
            'city' => 'SPb',
        ],
        2 => [
            'id' => 2,
            'name' => 'Dima',
            'city' => 'SPb',
        ]
    ];

    /**
     * @param int $id
     * @return null|UserModelBase
     */
    public static function getModelById(int $id)
    {
        $userData = self::$_users[$id] ?? false;
        if (!$userData) {
            return null;
        }

        return new UserModelBase($userData);
    }

    public static function saveUser(UserModelBase $user)
    {
        $data = ['id' => $user->getId(), 'name' => $user->getName()];
        // соединяемся с БД и сохраняем массив в базу
    }
}