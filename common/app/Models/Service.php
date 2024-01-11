<?php

namespace Common\App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $table = 'services';

    protected $fillable = [
        'app_id',
        'name',
        'status',
        'server_ip',
        'domain',
        'endpoint_server_ip',
        'endpoint_domain',
        'token_type',
        'partner_code',
        'secret_key',
        'meta',
        'content',
    ];
}
