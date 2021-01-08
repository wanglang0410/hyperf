<?php

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Amqp\Producer\DemoProducer;
use Hyperf\Amqp\Producer;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\ApplicationContext;

/**
 * @Controller();
 */
class TestController extends AbstractController
{
    /**
     * @RequestMapping(path="index", methods="get,post")
     */
    public function index()
    {
//        co(function () {
//            $channel = new \Swoole\Coroutine\Channel();
//            co(function () use ($channel) {
//                $channel->push('data');
//            });
//            $data = $channel->pop();
//            var_dump($data);
//        });
//        var_dump(env('APP_NAME'));
//        $user = $this->request->input('user', 'Hyperf');
//        $method = $this->request->getMethod();
        $message = new DemoProducer(['retry' => 3, 'id' => 1], 5000);
        $producer = ApplicationContext::getContainer()->get(Producer::class);
        $producer->produce($message);
        return [
//            'method' => $method,
            'message' => "Hello.",
        ];
    }

    public function user()
    {
        $name = $this->request->input('name', 'wl');
        return ['method' => $this->request->getMethod(), 'name' => $name];
    }
}
