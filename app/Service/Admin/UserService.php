<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/3
 * Time: 17:39
 */

namespace App\Service\Admin;

use App\Model\Member;

class UserService
{
    public function getById($id)
    {
        return Member::query()->where('id', $id)->first();
    }
}