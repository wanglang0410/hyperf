<?php


namespace App\Amqp\Consumer;

use App\Service\Message\CustomerMessageService;
use App\Service\WeChat\CustomerService;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Result;
use Hyperf\Di\Annotation\Inject;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @Consumer(exchange="wechat", routingKey="wechat", queue="wechat-message", name ="WeChatMessageConsumer", nums=1)
 */
class WeChatMessageConsumer extends DelayConsumer
{
    /**
     * @Inject()
     * @var CustomerService
     */
    private $customerService;

    public function consumeMessage($data, AMQPMessage $message): string
    {
        $result = $this->customerService->sendMessage($data);
        var_dump($result);
        $this->reply($data, $message);
        return Result::ACK;
    }
}