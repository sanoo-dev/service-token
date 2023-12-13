<?php
namespace App\Modules\Auth\Repositories\Elasticsearch;

use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\PermissionRepository as ServiceRepositoryInterface;
use Common\App\Repositories\Elasticsearch\CoreRepository;

class PermissionRepository extends CoreRepository implements ServiceRepositoryInterface
{

}
