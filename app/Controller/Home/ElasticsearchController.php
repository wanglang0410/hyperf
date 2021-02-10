<?php


namespace App\Controller\Home;


use App\Controller\AbstractController;
use App\Service\ElasticSearchService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Utils\ApplicationContext;

/**
 * Class ElasticsearchController
 * @package App\Controller\Home
 * @Controller(prefix="home/es")
 */
class ElasticsearchController extends AbstractController
{
    /**
     * @Inject()
     * @var ElasticSearchService
     */
    private $elasticSearchService;

    /**
     * @RequestMapping(path="index", methods={"get"})
     */
    public function index()
    {
        $body = [
            //搜索 match 全匹配  match_phrase模糊匹配
            'query' => [
                'bool' => [
                    //must 相当于and
                    'must' => [
//                      ['match_phrase' => ['name' => '张']],
//                        ['terms' => ['age' => [23,22]]],
                    ],
                    //should 相当于or
                    'should' => [
                        ['match_phrase' => ['name' => '张']],
                        ['match_phrase' => ['name' => '李']],
                        ['match_phrase' => ['name' => '滚']],
                        ['match_phrase' => ['name' => '不']],
                    ],
                    "minimum_should_match" => 1,
                    'filter' => [
                        [
                            'range' => [
                                'age' => [
                                    ['gt' => 0]
                                ]
                            ]
                        ],
                    ]
                ]
            ],
            //排序
            'sort' => [
                'age' => [
                    "order" => "desc",
                ]
            ]
        ];
        $data = $this->elasticSearchService->search($body);
        return $this->response->json($data);
    }

    /**
     * @RequestMapping(path="put", methods={"get"})
     */
    public function put()
    {
        $result = $this->elasticSearchService->put(['id' => 200, 'name' => '不学无术', 'age' => 200]);
        return $this->response->json($result);
    }

    /**
     * @RequestMapping(path="batch_put", methods={"get"})
     */
    public function batchPut()
    {
        $data = [
            [
                'id' => 300,
                'name' => '不二家的小子啊小子啊',
                'age' => 20
            ],
            [
                'id' => 301,
                'name' => '不三家',
                'age' => 19
            ]
        ];
        $result = $this->elasticSearchService->bulk($data);
        return $this->response->json($result);
    }

    /**
     * @RequestMapping(path="update", methods={"get"})
     */
    public function update()
    {
        $result = $this->elasticSearchService->update(300, [
            'id' => 300,
            'name' => '不要啊客官客官客官',
            'age' => 40,
        ]);
        return $this->response->json($result);
    }

    /**
     * @RequestMapping(path="delete", methods={"get","post"})
     */
    public function delete()
    {
        $result = $this->elasticSearchService->delete(200);
        return $this->response->json($result);
    }

    /**
     * @RequestMapping(path="delete_index", methods={"get", "post"})
     */
    public function deleteIndex()
    {
        $result = $this->elasticSearchService->deleteIndex();
        return $this->response->json($result);
    }
}