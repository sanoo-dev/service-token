<?php
namespace App\Modules\Auth\Repositories\Elasticsearch;


use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\RoleRepository as  ServiceRepositoryInterface;
use Common\App\Repositories\Elasticsearch\CoreRepository;

class RoleRepository extends CoreRepository implements ServiceRepositoryInterface
{

}
