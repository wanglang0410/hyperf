<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/6
 * Time: 17:59
 */

namespace App\Job;


use Hyperf\AsyncQueue\Job;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\ApplicationContext;

class UserJob extends Job
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        $logger = ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
        $logger->info($this->params);
    }
}