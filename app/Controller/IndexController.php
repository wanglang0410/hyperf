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

use App\Annotation\User;
use App\Service\QueueService;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Inject;

/**
 * Class IndexController
 * @package App\Controller
 * @User(name="123")
 */
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
        var_dump(AnnotationCollector::getClassByAnnotation(User::class));
//        $method = $this->request->getMethod();
//        $this->service->push('11111111111', 2);
//        $this->service->testPush('2222222222');
//        return [
//            'method' => $method,
//            'message' => "Hello {$user}.",
//        ];
//        return $user;
    }
}
