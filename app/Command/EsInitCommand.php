<?php

declare(strict_types=1);

namespace App\Command;

use Elasticsearch\ClientBuilder;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class EsInitCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $name = 'es:init';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('es:init');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('生成es的索引！');
    }

    public function handle()
    {
        $index = config('scout.engine.elasticsearch.index');
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1, // 分片为
                    'number_of_replicas' => 0, // 副本数
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'ik_max_word', // 最细粒度拆分
                            'search_analyzer' => 'ik_smart'
                        ],
                        'category' => [
                            'type' => 'text',
                            'analyzer' => 'ik_max_word', // 最细粒度拆分
                            'search_analyzer' => 'ik_smart'
                        ],
                        'content' => [
                            'type' => 'text',
                            'analyzer' => 'ik_max_word', // 最粗粒度拆分
                            'search_analyzer' => 'ik_smart'
                        ],
                    ],
                ],
            ],
        ];

        $client = ClientBuilder::create()->setHosts(config('scout.engine.elasticsearch.hosts'))->build();

        try {
            $client->indices()->delete(compact('index'));
        } catch (\Exception $e) {
        }

        $client->indices()->create($params);
        $this->line("=========创建索引成功=========");
    }
}
