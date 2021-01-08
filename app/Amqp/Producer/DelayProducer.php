<?php


namespace App\Amqp\Producer;


use App\Amqp\Delay\Kinds;
use Hyperf\Amqp\Builder\ExchangeBuilder;
use Hyperf\Amqp\Message\Message;
use Hyperf\Amqp\Message\ProducerMessage;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

abstract class DelayProducer extends ProducerMessage
{

    protected $type = 'x-delayed-message';

    protected $delayType = "topic";

    protected $arguments = [];

    /**
     * DelayProducer constructor.
     * @param $data
     * @param int $delay 延迟发送时间 毫秒
     */
    public function __construct($data, int $delay = 0)
    {
        $this->payload = $data;
        $this->properties['application_headers'] = new AMQPTable(['x-delay' => $delay]);
        $this->properties['delivery_mode'] = AMQPMessage::DELIVERY_MODE_PERSISTENT;
    }

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