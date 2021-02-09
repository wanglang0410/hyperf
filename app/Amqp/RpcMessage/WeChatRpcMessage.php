<?php


namespace App\Amqp\RpcMessage;


use Hyperf\Amqp\Message\RpcMessage;

class WeChatRpcMessage extends RpcMessage
{
    protected $exchange = 'wechat';

    protected $routingKey = 'wechat';

    public function __construct($data)
    {
        // 要传递数据
        $this->payload = $data;
    }
}