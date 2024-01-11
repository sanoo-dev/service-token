<?php

namespace Common\App\Models;

use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    public $table = 'endpoints';

    protected $fillable = [
        'name',
        'server_ip',
        'domain',
        'public_key',
        'private_key',
        'tracker',
        'status',
        'expire',
        'service_id',
    ];
}
