<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/24
 * Time: 15:00
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Service\Admin\UserService;
use App\Service\Admin\UserServiceInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Controller(prefix="admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Inject
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @Inject
     * @var SessionInterface
     */
    protected $session;

    /**
     * @RequestMapping(path="index", methods={"get"})
     * @return array
     */
    public function index()
    {
        $user = $this->request->input('user', '11111');
        $method = $this->request->getMethod();
        $user = $this->userService->getById(1);
        if ($this->session->has('test')) {
            $this->session->set('uid', 1);
        }
        $users =  Db::select('SELECT * FROM `member` WHERE id = ?', [1]);
        foreach($users as $user){
            echo $user->nick_name;
        }
        $user = $this->session->getName();
//        $user = $this->session->get('test');
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}