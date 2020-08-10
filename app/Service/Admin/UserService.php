<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/3
 * Time: 17:39
 */

namespace App\Service\Admin;

interface UserServiceInterface
{
    public function getById(int $id);
}

class UserService implements UserServiceInterface
{
    public function getById($id = 0)
    {
        return '222';
    }
}