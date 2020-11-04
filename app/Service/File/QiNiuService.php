<?php


namespace App\Service\File;


use Hyperf\Di\Annotation\Inject;
use Hyperf\Filesystem\FilesystemFactory;
use Qiniu\Auth;

class QiNiuService
{
    /**
     * @Inject()
     * @var FilesystemFactory
     */
    private $filesystemFactory;

    public function upload($fileName, $content)
    {
        $qiNiu = $this->filesystemFactory->get('qiniu');
        return $qiNiu->put($fileName, $content);
    }

    public function uploadToken($key, $expires = 3600)
    {
        $qiNiuConfig = config('file.storage.qiniu');
        $auth = new Auth($qiNiuConfig['accessKey'], $qiNiuConfig['secretKey']);
        return $auth->uploadToken($qiNiuConfig['bucket'], $key, $expires);
    }

}