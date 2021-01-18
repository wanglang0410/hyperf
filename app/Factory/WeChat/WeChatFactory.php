<?php


namespace App\Factory\WeChat;


use EasyWeChat\Factory;
use EasyWeChat\OfficialAccount\Application;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Overtrue\Socialite\InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

class WeChatFactory
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Application
     */
    private $app;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    public function initApp()
    {
        $weChatConfig = $this->container->get(ConfigInterface::class)->get('wechat');
        $config = [
            'app_id' => $weChatConfig['AppID'],
            'secret' => $weChatConfig['AppSecret'],
            'response_type' => 'array',
        ];
        $app = Factory::officialAccount($config);
        $handler = new CoroutineHandler();
        $config = $app['config']->get('http', []);
        $config['handler'] = $stack = HandlerStack::create($handler);
        $app->rebind('http_client', new Client($config));
        $app['guzzle_handler'] = $handler;
        $app->oauth->setGuzzleOptions([
            'http_errors' => false,
            'handler' => $stack,
        ]);
        $get = $this->request->getQueryParams();
        $post = $this->request->getParsedBody();
        $cookie = $this->request->getCookieParams();
        $uploadFiles = $this->request->getUploadedFiles() ?? [];
        $server = $this->request->getServerParams();
        $xml = $this->request->getBody()->getContents();
        $files = [];
        /** @var \Hyperf\HttpMessage\Upload\UploadedFile $v */
        foreach ($uploadFiles as $k => $v) {
            $files[$k] = $v->toArray();
        }
        $request = new Request($get, $post, [], $cookie, $files, $server, $xml);
        $request->headers = new HeaderBag($this->request->getHeaders());
        $app->rebind('request', $request);
        $this->app = $app;
    }

    /**
     * 服务端
     * @return false|string
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function server()
    {
        $this->initApp();
        $this->app->server->push(function ($message) {
            $openid = $message['FromUserName'];
            switch ($message['MsgType']) {
                case 'event':
                    switch ($message['Event']) {
                        case 'subscribe':
                        case 'unsubscribe':
                        case 'CLICK':
                        case "SCAN" ://已关注公众号的人再次关注触发该事件
                            return $openid;
                            break;
                        default :
                            break;
                    }
                    return $openid;
                    break;
                case 'text':
                    return $openid;
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return $message['openid'];
                    break;
            }
        });
        $response = $this->app->server->serve();
        return $response->getContent();
    }

    /**
     * @param $data
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function customerService($data)
    {
        return $this->app->customer_service->message($data)->send();
    }
}