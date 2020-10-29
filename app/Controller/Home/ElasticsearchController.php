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
        $params = ['body' => []];
        $array = [
            [
                'id' => 1,
                'name' => 'tom_1',
                'age' => 18
            ],
            [
                'id' => 2,
                'name' => 'tom_2',
                'age' => 19
            ],
            [
                'id' => 3,
                'name' => 'tom_3',
                'age' => 20
            ],
            [
                'id' => 4,
                'name' => 'tom_4',
                'age' => 21
            ], [
                'id' => 5,
                'name' => 'tom_5',
                'age' => 22
            ], [
                'id' => 6,
                'name' => 'tom_6',
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
                    '_id' => $value['id'],
                ]
            ];
            $params['body'][] = [
                'name' => $value['name'],
                'age' => $value['age'],
            ];
        }
//        $client->bulk($params);

//        $params = [];
//        for ($i = 0; $i < 100; $i++) {
//            $params['body'][] = [
//                'index' => [
//                    '_index' => 'my_index',
//                    '_type' => 'my_type',
//                    '_id' => $i,
//                ]
//            ];
//
//            $params['body'][] = [
//                'my_field' => 'my_value',
//                'second_field' => 'some more values'
//            ];
//        }
        $data = $client->search([
            'index' => 'my_index',
            'type' => 'my_type',
            'body' => [
                'query' => [
                    'match_phrase' => [
                        'name' => 'o'
                    ]
                ]
            ]
        ]);
        var_dump($data);
    }
}