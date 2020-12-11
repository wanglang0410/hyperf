<?php


namespace App\Annotation;


use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Class User
 * @package App\Annotation
 * @Annotation
 * @Target({"CLASS", "METHOD", "PROPERTY"})
 */
class User extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $name;
}