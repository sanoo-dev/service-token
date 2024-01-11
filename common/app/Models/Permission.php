<?php

namespace Common\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    public $table = 'permissions';

    protected $fillable = [
        'action',
        'route',
        'status'
    ];

    public function account(): BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}
