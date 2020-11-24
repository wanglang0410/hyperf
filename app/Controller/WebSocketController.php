<?php
declare(strict_types=1);

namespace App\Controller;

use App\Constants\Atomic;
use App\Constants\MemoryTable;
use App\Constants\Redis\Chat;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Memory\AtomicManager;
use Hyperf\Memory\TableManager;
use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage($server, Frame $frame): void
    {
        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        $fdName = Chat::getFdsName();
        $memberName = Chat::getMemberName($fd);
        $atomic = AtomicManager::get(Atomic::NAME);
        $atomic->sub(1);
        $number = $atomic->get();
        $redisClient = ApplicationContext::getContainer()->get(Redis::class);
        $redisClient->sRem($fdName, $fd);
        $redisClient->del($memberName);
        $fds = $redisClient->sMembers($fdName);
        foreach ($fds as $item) {
            if ($server->isEstablished((int)$item)) {
                $server->push((int)$item, '用户' . $fd . '离开了' . "共有{$number}位成员");
            }
        }
//        TableManager::get(MemoryTable::FD_TO_USER)->del((string)$fd);
//        $users = TableManager::get(MemoryTable::FD_TO_USER);
//        $sender = ApplicationContext::getContainer()->get(Sender::class);
//        foreach ($users as $value) {
//            $sender->push($value['userId'], '用户' . $fd . '离开了');
//        }
    }

    public function onOpen($server, Request $request): void
    {
        $fd = $request->fd;
        $redisClient = ApplicationContext::getContainer()->get(Redis::class);
        $fdName = Chat::getFdsName();
        $memberName = Chat::getMemberName($fd);
        if (!$redisClient->sIsMember($fdName, $fd)) {
            $redisClient->sAdd($fdName, $fd);
            $redisClient->hMSet($memberName, ['fd' => $fd, 'name' => '客户端' . $fd]);
        }
        $atomic = AtomicManager::get(Atomic::NAME);
        $atomic->add(1);
        $number = $atomic->get();
        $fds = $redisClient->sMembers($fdName);
        foreach ($fds as $item) {
            if ($item != $fd) {
                if ($server->isEstablished((int)$item)) {
                    $server->push((int)$item, '欢迎' . $fd . '上线' . "共有{$number}位成员");
                }
            }
        }
//        $checkOnline = TableManager::get(MemoryTable::FD_TO_USER)->get((string)$fd);
//        if (!$checkOnline) {
//            TableManager::get(MemoryTable::FD_TO_USER)->set((string)$fd, ['userId' => $fd]);
//        }
//        $swooleServer = ApplicationContext::getContainer()->get(\Swoole\Server::class);
//        $atomic = AtomicManager::get(Atomic::NAME);
//        $atomic->add(1);
//        $number = $atomic->get();
//        $userToFdTable = TableManager::get(MemoryTable::FD_TO_USER);
//        $fds = [];
//        foreach ($userToFdTable as $item) {
//            array_push($fds, $item['userId']);
//        }
//        foreach ($fds as $item) {
//            if ($item != $fd) {
//                if ($swooleServer->isEstablished($item)) {
//                    $swooleServer->push($item, '欢迎' . $fd . '上线' . "共有{$number}位成员");
//                }
//            }
//        }
    }
}