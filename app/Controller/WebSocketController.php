<?php
declare(strict_types=1);

namespace App\Controller;

use App\Constants\Atomic;
use App\Constants\MemoryTable;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Di\Container;
use Hyperf\Memory\AtomicManager;
use Hyperf\Memory\TableManager;
use Hyperf\Utils\ApplicationContext;
use Hyperf\WebSocketServer\Sender;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage($server, Frame $frame): void
    {
        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        $atomic = AtomicManager::get(Atomic::NAME);
        $atomic->sub(1);
        TableManager::get(MemoryTable::FD_TO_USER)->del((string)$fd);
        $users = TableManager::get(MemoryTable::FD_TO_USER);
        $sender = ApplicationContext::getContainer()->get(Sender::class);
        foreach ($users as $value) {
            $sender->push($value['userId'], '用户' . $fd . '离开了');
        }
    }

    public function onOpen($server, Request $request): void
    {
        $fd = $request->fd;
        $checkOnline = TableManager::get(MemoryTable::FD_TO_USER)->get((string)$fd);
        if (!$checkOnline) {
            TableManager::get(MemoryTable::FD_TO_USER)->set((string)$fd, ['userId' => $fd]);
        }
        $swooleServer = ApplicationContext::getContainer()->get(\Swoole\Server::class);
        $atomic = AtomicManager::get(Atomic::NAME);
        $atomic->add(1);
        $number = $atomic->get();
        $userToFdTable = TableManager::get(MemoryTable::FD_TO_USER);
        $fds = [];
        foreach ($userToFdTable as $item) {
            array_push($fds, $item['userId']);
        }
        foreach ($fds as $item) {
            if ($item != $fd) {
                if ($swooleServer->isEstablished($item)) {
                    $swooleServer->push($item, '欢迎' . $fd . '上线' . "共有{$number}位成员");
                }
            }
        }
    }
}