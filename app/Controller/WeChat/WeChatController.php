<?php


namespace App\Controller\WeChat;


use App\Factory\WeChat\WeChatFactory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * Class WeChatController
 * @package App\Controller\WeChat
 */
class WeChatController
{
    /**
     * @Inject()
     * @var WeChatFactory
     */
    private $weChatFactory;


    public function event()
    {
        return $this->weChatFactory->server();
    }
}