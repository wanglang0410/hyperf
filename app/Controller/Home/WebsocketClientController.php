<?php


namespace App\Controller\Home;


use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\WebSocketClient\ClientFactory;

/**
 * Class WebsocketClientController
 * @package App\Controller\Home
 * @Controller(prefix="home/websocket")
 */
class WebsocketClientController extends AbstractController
{
    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $webSocketClientFactory;

    /**
     * @RequestMapping(path="join", methods={"get"})
     */
    public function join()
    {
        $host = "127.0.0.1:9555";
        $client = $this->webSocketClientFactory->create($host);
        $message = $this->request->input('message', '');
        $client->push($message);
        $msg = $client->recv();
        return $this->response->json(['data' => $msg->data]);
    }
}