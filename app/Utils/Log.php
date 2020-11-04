<?php
declare(strict_types=1);

namespace App\Utils;


use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

class Log
{
    public static function getInstance(string $name = 'app')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name);
    }

    public static function __callStatic($name, $arguments)
    {
        self::getInstance()->$name(...$arguments);
    }
}