<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/24
 * Time: 16:16
 */

namespace App\Middleware\Admin;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Phper666\JWTAuth\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @Inject()
     * @var \Hyperf\HttpServer\Contract\ResponseInterface
     */
    protected $response;

    /**
     * @Inject()
     * @var JWT
     */
    protected $jwt;

    protected $prefix = 'Bearer';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isValid = false;
        try {
            if ($this->jwt->checkToken()) {
                $isValid = true;
            }
        } catch (\Exception $e) {
            $isValid = false;
        }
        if ($isValid) {
            $jwtData = $this->jwt->getParserData();
            $request = Context::get(ServerRequestInterface::class);
            $request = $request->withAttribute('uid', $jwtData['uid']);
            Context::set(ServerRequestInterface::class, $request);
            return $handler->handle($request);
        }
        $data = [
            'code' => 500,
            'msg' => 'token验证失败',
        ];
        return $this->response->json($data);
    }
}