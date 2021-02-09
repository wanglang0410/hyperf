<?php


namespace App\Service\WeChat;


use App\Factory\WeChat\WeChatFactory;
use EasyWeChat\Kernel\Messages\Text;
use Hyperf\Di\Annotation\Inject;

class CustomerService
{
    /**
     * @Inject()
     * @var WeChatFactory
     */
    private $weChatFactory;

    public function sendMessage($data =[])
    {
        $text = new Text('这是一个延迟的消息');
        return $this->weChatFactory->customerService($text, $data['openid']);
    }
}