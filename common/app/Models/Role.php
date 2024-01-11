<?php

namespace Common\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public $table = 'roles';

    protected $fillable = [
        'name',
        'permission_id',
        'permission_name',
        'status'
    ];

    public function account(): BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
