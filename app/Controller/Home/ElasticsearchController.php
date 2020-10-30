<?php


namespace App\Controller\Home;


use App\Controller\AbstractController;
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
     * @RequestMapping(path="index", methods={"get"})
     */
    public function index()
    {
        $builder = $this->container->get(ClientBuilderFactory::class)->create();
        $client = $builder->setHosts(['http://127.0.0.1:9200'])->build();
        $body = [
            'query' => [
                'bool' => [
//                    'must' => [
//                        ['match_phrase' => ['name' => '张']],
//                    ],
                    'filter' => [
                        ['range' => ['age' => [[
                            'gt' => 18,
//                            'lt' => 25
                        ]]]],
                    ]
                ]
            ]
        ];
        $data = $client->search([
            'index' => 'my_index',
            'type' => 'my_type',
            'body' => $body,
        ]);
        var_dump($data);
    }

    /**
     * @RequestMapping(path="put", methods={"get"})
     */
    public function put()
    {
        $client = $this->container->get(ClientBuilderFactory::class)->create();
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 30,
            'body' => [
                'name' => '张小红'
            ]
        ];
        $client->build()->index($params);
    }

    /**
     * @RequestMapping(path="batch_put", methods={"get"})
     */
    public function batchPut()
    {
        $builder = $this->container->get(ClientBuilderFactory::class)->create();
        $client = $builder->setHosts(['http://127.0.0.1:9200'])->build();
        $params = ['body' => []];
        $array = [
            [
                'id' => 10,
                'name' => '赵东',
                'age' => 18
            ],
            [
                'id' => 11,
                'name' => '赵曦',
                'age' => 19
            ],
            [
                'id' => 12,
                'name' => '赵蓓',
                'age' => 20
            ],
            [
                'id' => 13,
                'name' => '赵楠',
                'age' => 21
            ], [
                'id' => 14,
                'name' => '赵中',
                'age' => 22
            ], [
                'id' => 15,
                'name' => '赵霞',
                'age' => 23
            ]
        ];
        $indexName = 'my_index';
        $typeName = 'my_type';
        foreach ($array as $value) {
            $params['body'][] = [
                'index' => [
                    '_index' => $indexName,
                    '_type' => $typeName,
                    '_id' => $value['id']
                ]
            ];
            $params['body'][] = [
                'name' => $value['name'],
                'age' => $value['age']
            ];
        }
        $client->bulk($params);
        echo 'OK';
    }

    /**
     * @RequestMapping(path="update", methods={"get"})
     */
    public function update()
    {
        $client = $this->container->get(ClientBuilderFactory::class)->create();
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 1,
            'body' => [
                'doc' => [
                    'name' => '张小红'
                ]
            ],
        ];
        $client->build()->update($params);
    }
}