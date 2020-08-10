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

namespace App\Controller;

use App\Service\QueueService;
use Hyperf\Di\Annotation\Inject;

class IndexController extends AbstractController
{
    /**
     * @Inject()
     * @var QueueService
     */
    protected $service;

    public function index()
    {
        $user = $this->request->input('user', '222');
        $method = $this->request->getMethod();
        $this->service->push('11111111111', 2);
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
