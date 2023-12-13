<?php
namespace App\Modules\Auth\Repositories\Elasticsearch;

use Common\App\Repositories\Elasticsearch\CoreRepository;
use App\Modules\Auth\Repositories\Elasticsearch\Interfaces\AccountRepository as ServiceRepositoryInterface;

class AccountRepository extends CoreRepository implements ServiceRepositoryInterface
{

}
