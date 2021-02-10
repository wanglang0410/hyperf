<?php


namespace App\Service\WeChat;


use App\Factory\WeChat\WeChatFactory;
use EasyWeChat\Kernel\Messages\Image;
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
        $openid = $data['openid'];
        $text = new Text('欢迎来得WL的个人空间');
        $this->weChatFactory->customerService($text, $openid);
        $image = new Image('hzo1WpT55SfTc4VezTLAleanvdHelqvOncdO7gYbVFE');
        $this->weChatFactory->customerService($image, $openid);
        $this->weChatFactory->templateMessage($openid, '78txlEoBY6DBbQZwY9c63yc01D-2VnNHuPDGSi2V6P8');
    }
}