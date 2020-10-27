<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/24
 * Time: 15:00
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Model\Member;
use App\Service\Admin\UserService;
use App\Service\Admin\UserServiceInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\Context;
use Hyperf\Config\Annotation\Value;
use Hyperf\View\RenderInterface;
use Hyperf\WebSocketClient\ClientFactory;

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
     * @return array
     */
    public function index()
    {
//        Context::set('name', '111');
//        Context::override('name', function (){
//           return '2222';
//        });
        echo env('APP_NAME', '111') . PHP_EOL;
        echo $this->config->has('databases.default.driver');
        echo $this->configValue;
        echo Context::get('name');
        $user = $this->request->input('user', '11111');
        $method = $this->request->getMethod();
        $user = $this->userService->getById(1);
//        if ($this->session->has('test')) {
//            $this->session->set('uid', 1);
//        }
//        $users =  Db::select('SELECT * FROM `member` WHERE id = ?', [1]);
//        $users = Db::table('member')->where('id', '>=', 1)->paginate(10)->toArray();
//        $users = Member::query()->where('id', 1)->offset(1)->limit(10)->get()->toArray();
//        var_dump($users);
//        foreach($users as $user){
//            echo $user->nick_name;
//        }
        $user = $this->session->getName();
//        $user = $this->session->get('test');
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    /**
     * @RequestMapping(path="info", methods={"get"})
     * @param RenderInterface $render
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function info(RenderInterface $render)
    {
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