<?php

namespace Common\App\Indexes;

use TuoiTre\Elasticsearch\Indexes\IndexAbstract;
use Common\App\Indexes\Interfaces\CoreIndex as CoreIndexInterface;

abstract class CoreIndex extends IndexAbstract implements CoreIndexInterface
{
    public function settings(): array
    {
        return [
            'number_of_shards' => 5,
            'number_of_replicas' => 3,
            'analysis' => [
                'analyzer' => [
                    'vietnamese_standard' => [
                        'tokenizer' => 'icu_tokenizer',
                        'filter' => [
                            'icu_folding',
                            'icu_normalizer',
                            'icu_collation'
                        ]
                    ]
                ]
            ]
        ];
    }
}
