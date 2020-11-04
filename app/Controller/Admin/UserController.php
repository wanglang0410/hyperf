<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/24
 * Time: 15:00
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Middleware\Admin\AuthMiddleware;
use App\Model\Member;
use App\Service\Admin\UserService;
use App\Service\Admin\UserServiceInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Hyperf\Config\Annotation\Value;
use Hyperf\View\RenderInterface;
use Hyperf\WebSocketClient\ClientFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\Variable;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Controller(prefix="admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Inject
     * @var UserService
     */
    protected $userService;

    /**
     * @Inject
     * @var SessionInterface
     */
    protected $session;

    /**
     * @Value("databases.default.driver")
     */
    private $configValue;

    /**
     * @Inject()
     * @var ConfigInterface
     */
    private $config;

    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @RequestMapping(path="index", methods={"get"})
     * @Middleware(AuthMiddleware::class)
     */
    public function index()
    {
        $uid = $this->request->getAttribute('uid');
        $user = $this->userService->getById($uid);
        return $this->response->json(['data' => $user]);
    }

    /**
     * @RequestMapping(path="info", methods={"get"})
     */
    public function info(RenderInterface $render)
    {
        $container = ApplicationContext::getContainer();
        $redis = $container->get(\Hyperf\Redis\Redis::class);
        return $render->render('admin.user.info', ['name' => 'WL']);
    }

    /**
     * @RequestMapping(path="join",methods={"get"})
     */
    public function join()
    {
        $host = '127.0.0.1:9555';
        $client = $this->clientFactory->create($host);
        $client->push('HttpServer 中使用 WebSocket Client 发送数据。');
        $msg = $client->recv();
        // 获取文本数据：$res_msg->data
        return $msg->data;
    }
}