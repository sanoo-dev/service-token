<?php

namespace Common\App\Repositories\Elasticsearch;

use Common\App\Indexes\Interfaces\CoreIndex;
use Common\App\Repositories\Elasticsearch\Interfaces\CoreRepository as CoreRepositoryInterface;
use TuoiTre\Elasticsearch\Repositories\ElasticsearchRepositoryAbstract;

class CoreRepository extends ElasticsearchRepositoryAbstract implements CoreRepositoryInterface
{
    public function __construct(CoreIndex $index)
    {
        parent::__construct($index);
    }
}
