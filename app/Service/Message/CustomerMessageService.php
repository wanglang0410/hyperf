<?php


namespace App\Service\Message;


use App\Amqp\RpcMessage\WeChatRpcMessage;
use Hyperf\Amqp\RpcClient;
use Hyperf\Utils\ApplicationContext;

class CustomerMessageService
{
    public function push($data)
    {
        if (isset($data['delay']) && $data['delay'] > 0) {
            swoole_timer_after($data['delay'] * 1000, function () use ($data) {
                $this->rpcMessage($data['data']);
            });
        } else {
            $this->rpcMessage($data['data']);
        }
    }

    public function rpcMessage($data)
    {
        $rpcClient = ApplicationContext::getContainer()->get(RpcClient::class);
        return $rpcClient->call(new WeChatRpcMessage($data));
    }
}