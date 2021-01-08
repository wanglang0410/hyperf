<?php


namespace App\Amqp\Consumer;


use App\Amqp\Delay\Kinds;
use Hyperf\Amqp\Builder\ExchangeBuilder;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Message\Message;
use PhpAmqpLib\Wire\AMQPTable;

abstract class DelayConsumer extends ConsumerMessage
{

    protected $type = 'x-delayed-message';

    protected $delayType = "topic";

    protected $arguments = [];

    public function setType(string $type): Message
    {
        if (!in_array($type, Kinds::all())) {
            throw new \InvalidArgumentException(sprintf('Invalid type %s, available values [%s]', $type,
                implode(',', Kinds::all())));
        }
        $this->type = $type;
        return $this;
    }

    public function getExchangeBuilder(): ExchangeBuilder
    {
        $this->arguments = array_merge($this->arguments, ['x-delayed-type' => $this->delayType]);
        return (new ExchangeBuilder())->setExchange($this->getExchange())->setType($this->getType())->setArguments
        (new AMQPTable($this->arguments));
    }
}