<?php


namespace App\Controller\Home;


use App\Controller\AbstractController;
use App\Service\File\QiNiuService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * Class QiNiuController
 * @package App\Controller\Home
 * @Controller(prefix="home/qiniu")
 */
class QiNiuController extends AbstractController
{
    /**
     * @Inject()
     * @var QiNiuService
     */
    private $qiNiuService;

    /**
     * @RequestMapping(path="upload", methods={"post"})
     */
    public function upload()
    {
        $file = $this->request->file('file');
        if (empty($file)) {
            return $this->response->json(['status' => 500, 'message' => '请选择图片上传']);
        }
        $extension = strtolower($file->getExtension()) ?: 'png';
        $filename = time() . '.' . $extension;
        $res = $this->qiNiuService->upload($filename, file_get_contents($file->getRealPath()));
        return $this->response->json(['status' => 200, 'message' => '上传成功', 'data' => $res]);
    }

    /**
     * @RequestMapping(path="token", methods={"get"})
     */
    public function getUploadToken()
    {
        $key = $this->request->input('key');
        try {
            $res = $this->qiNiuService->uploadToken($key);
            return $this->response->json(['status' => 200, 'message' => '上传成功', 'data' => $res]);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}