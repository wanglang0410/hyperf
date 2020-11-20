<?php


namespace App\Task;

use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

///**
// * Class UserTask
// * @package App\Task
// * @Crontab(name="user", rule="* * * * *", callback="execute", memo="用户定时测试")
// */
class UserTask
{
    /**
     * @Inject()
     * @var LoggerFactory
     */
    protected $loggerFactory;

    public function execute()
    {
        $this->loggerFactory->get('log', 'default')->info(date('Y-m-d H:i:s') . '这是测试');
    }
}