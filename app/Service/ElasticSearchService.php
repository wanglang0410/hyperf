<?php


namespace App\Service;


use Hyperf\Di\Annotation\Inject;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Psr\Container\ContainerInterface;

class ElasticSearchService
{
    const INDEX_NAME = 'my_index';
    const TYPE_NAME = 'my_type';

    /**
     * @Inject()
     * @var ContainerInterface
     */
    protected $container;

    public function init()
    {
        $client = $this->container->get(ClientBuilderFactory::class);
        return $client->create()->build();
    }

    public function put($data, $index = self::INDEX_NAME, $type = self::TYPE_NAME)
    {
        $client = $this->init();
        return $client->index([
            'index' => $index,
            'type' => $type,
            'id' => $data['id'],
            'body' => $data
        ]);
    }

    public function bulk($data, $index = self::INDEX_NAME, $type = self::TYPE_NAME)
    {
        $client = $this->init();
        $params = [
            'body' => []
        ];
        foreach ($data as $value) {
            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => $type,
                    '_id' => $value['id'],
                ]
            ];
            $params['body'][] = $value;
        }
        return $client->bulk($params);
    }

    public function get($id, $index = self::INDEX_NAME, $type = self::TYPE_NAME)
    {
        $client = $this->init();
        return $client->get([
            'index' => $index,
            'type' => $type,
            'id' => $id
        ]);
    }

    public function search($body, $from = 0, $size = 10, $index = self::INDEX_NAME, $type = self::TYPE_NAME)
    {
        $client = $this->init();
        $result = $client->search([
            'index' => $index,
            'type' => $type,
            'body' => $body,
            'from' => $from,
            'size' => $size,
        ]);
        return [
            'list' => self::getReturnData($result),
            'total' => self::getReturnTotal($result)
        ];
    }

    public function delete($id, $index = self::INDEX_NAME, $type = self::TYPE_NAME)
    {
        $client = $this->init();
        return $client->delete([
            'index' => $index,
            'type' => $type,
            'id' => $id
        ]);
    }

    public function update($id, $data, $index = self::INDEX_NAME, $type = self::TYPE_NAME)
    {
        $client = $this->init();
        return $client->update([
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => [
                'doc' => $data
            ]
        ]);
    }

    public function deleteIndex($index = self::INDEX_NAME)
    {
        return $this->init()->indices()->delete([
            'index' => $index
        ]);
    }

    public static function getReturnData($result)
    {
        $returnData = [];
        if (!empty($result['hits']['hits'])) {
            foreach ($result['hits']['hits'] as $value) {
                array_push($returnData, $value['_source']);
            }
        }
        return $returnData;
    }

    public static function getReturnTotal($result)
    {
        return $result['hits']['total']['value'] ?? 0;
    }
}