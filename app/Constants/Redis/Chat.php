<?php


namespace App\Constants\Redis;


class Chat
{
    use Common;

    const FDS = 'chat:fds';

    const FD_TO_USER = 'chat:fdToUser:';

    const USER_TO_FD = 'chat:userToFd:';


    public static function getFdsName()
    {
        return self::getSetName(self::FDS);
    }

    public static function getMemberName($uid)
    {
        return self::getHashName(self::FD_TO_USER . $uid);
    }
}