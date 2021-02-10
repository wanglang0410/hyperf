<?php


namespace App\Service\WeChat;


use App\Factory\WeChat\WeChatFactory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\Filesystem;

class WeChatMaterialService
{
    /**
     * @Inject()
     * @var WeChatFactory
     */
    private $weChatFactory;

    /**
     * @Inject()
     * @var Filesystem
     */
    private $filesystem;

    public function uploadImage(UploadedFile $file)
    {
        $stream = fopen($file->getRealPath(), 'r+');
        $uploadPath = 'upload/' . $file->getClientFilename();
        $this->filesystem->writeStream(
            $uploadPath,
            $stream
        );
        fclose($stream);
        $localPath = BASE_PATH . '/runtime/' . $uploadPath;
        $result = $this->weChatFactory->uploadImage($localPath, false);
        unlink($localPath);
        return $result;
    }
}