<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/24
 * Time: 16:16
 */

namespace App\Middleware\Admin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckMiddleware implements MiddlewareInterface
{
    protected $response;

    public function __construct(\Hyperf\HttpServer\Contract\ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $is_valid = false;
        // TODO: Implement process() method.
        if ($is_valid == true) {
            return $handler->handle($request);
        } else {
            return $this->response->json(
                [
                    'code' => -1,
                    'data' => [
                        'error' => '中间里验证token无效，阻止继续向下执行',
                    ],
                ]
            );
        }
    }
}