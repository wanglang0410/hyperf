<?php


namespace App\Aspect;


use App\Annotation\User;
use App\Controller\IndexController;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * Class IndexAspect
 * @package App\Aspect
 * @Aspect()
 */
class IndexAspect extends AbstractAspect
{
    public $classes = [
        IndexController::class . '::' . 'index',
    ];

    public $annotations = [
        User::class,
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        // TODO: Implement process() method.
        $result = $proceedingJoinPoint->process();
        /**
         * @var User $user
         */
        $user = $proceedingJoinPoint->getAnnotationMetadata()->class[User::class];
        return '222' . $result . $user->name;
    }
}