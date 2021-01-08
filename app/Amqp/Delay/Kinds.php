<?php


namespace App\Amqp\Delay;


use Hyperf\Amqp\Message\Type;

class Kinds extends Type
{
    const X_DELAYED_MESSAGE = "x-delayed-message";

    public static function all()
    {
        return [
            self::DIRECT,
            self::FANOUT,
            self::TOPIC,
            self::X_DELAYED_MESSAGE,
        ];
    }
}