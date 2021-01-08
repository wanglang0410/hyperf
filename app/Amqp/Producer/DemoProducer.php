<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use App\Model\Member;
use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * @Producer(exchange="hyperf", routingKey="hyperf")
 */
class DemoProducer extends DelayProducer
{
    public function __construct($data, int $delay = 0)
    {
        parent::__construct($data, $delay);
    }
}
