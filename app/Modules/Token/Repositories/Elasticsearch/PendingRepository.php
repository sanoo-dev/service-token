<?php
namespace App\Modules\Token\Repositories\Elasticsearch;

use App\Modules\Token\Repositories\Elasticsearch\Interfaces\PendingRepository as ServiceRepositoryInterface;
use Common\App\Repositories\Elasticsearch\CoreRepository;

class PendingRepository extends CoreRepository implements ServiceRepositoryInterface
{

}
