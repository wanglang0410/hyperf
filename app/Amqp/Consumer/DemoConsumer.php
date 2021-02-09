<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;

/**
 * @Consumer(exchange="hyperf", routingKey="hyperf", queue="hyperf", name ="DemoConsumer", nums=1)
 */
class DemoConsumer extends DelayConsumer
{

    public function consume($data): string
    {
        var_dump($data);
        return Result::ACK;
    }
}
