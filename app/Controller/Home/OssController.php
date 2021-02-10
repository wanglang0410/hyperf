<?php


namespace App\Controller\Home;


use App\Controller\AbstractController;
use App\Service\File\OssService;
use App\Service\WeChat\WeChatMaterialService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * Class OssController
 * @package App\Controller\Home
 * @Controller(prefix="home/oss")
 */
class OssController extends AbstractController
{
    /**
     * @Inject()
     * @var OssService
     */
    private $ossService;

    /**
     * @Inject()
     * @var WeChatMaterialService
     */
    private $weChatMaterialService;

    /**
     * @RequestMapping(path="upload", methods={"post"})
     */
    public function upload()
    {
        $file = $this->request->file('file');
        $ext = $file->getExtension();
        $key = time() . '.' . $ext;
        try {
            $res = $this->ossService->upload($key, file_get_contents($file->getRealPath()));
            return $this->response->json(['status' => 200, 'message' => '上传成功', 'data' => $res]);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @RequestMapping(path="token", methods={"get"})
     */
    public function uploadToken()
    {
        $dir = $this->request->input('dir', '/');
        try {
            $res = $this->ossService->uploadToken($dir);
            return $this->response->json(['status' => 200, 'message' => '获取成功', 'data' => $res]);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}