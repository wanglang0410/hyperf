<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use App\Amqp\Producer\DemoProducer;
use Hyperf\Amqp\Producer;
use Hyperf\Amqp\Result;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Utils\ApplicationContext;
use PhpAmqpLib\Message\AMQPMessage;

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
