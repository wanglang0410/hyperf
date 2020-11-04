<?php


namespace App\Controller\Admin;


use App\Controller\AbstractController;
use App\Model\Member;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Phper666\JWTAuth\JWT;

/**
 * Class LoginController
 * @package App\Middleware\Admin
 * @Controller(prefix="admin/login")
 */
class LoginController extends AbstractController
{
    /**
     * @Inject()
     * @var JWT
     */
    private $jwt;

    /**
     * @RequestMapping(path="login", methods={"post"})
     */
    public function login()
    {
        $userName = $this->request->input('user_name');
        $password = $this->request->input('password');
        $user = Member::query()->where('user_name', $userName)->first();
        if (!empty($user->password) && password_verify($password, $user->password)) {
            $token = $this->jwt->getToken([
                'uid' => $user->id,
            ]);
            $data = [
                'token' => (string)$token,
                'exp' => $this->jwt->getTTL(),
            ];
            return $this->response->json($data);
        } else {
            return $this->response->json(['status' => 500, 'message' => '登陆失败']);
        }
    }
}