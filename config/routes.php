<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
//Router::get('/test', 'App\Controller\TestController::index');

//Router::addGroup('/admin/', function () {
//    Router::get('user/index', 'App\Controller\Admin\UserController@index');
//}, ['middleware' => [\App\Middleware\Admin\CheckMiddleware::class]]);

Router::addRoute(['GET', 'POST', 'HEAD'], '/wechat/event', 'App\Controller\WeChat\WeChatController@event');

Router::addServer('ws', function () {
    Router::get('/ws', 'App\Controller\WebSocketController');
});