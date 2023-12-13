<?php

/**
 * Created by Reliese Model.
 */

    namespace Api\Modules\Services\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MemMember
 *
 * @property int $id
 * @property string $appId
 * @property string|null $appName
 * @property string|null $serveIp
 * @property string|null $domain
 * @property string|null $meta
 * @property string|null $content
 * @property int|null $status
 * @property string|null $styleToken
 * @property string|null $partnerCode
 * @property string|null $secretKey
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Service extends Model
{
	public $table = 'service';

	protected $casts = [
		'status' => 'int',

	];

	protected $fillable = [
		'appId',
		'appName',
		'status',
		'serveIp',
		'domain',
		'content',
		'meta',
		'styleToken',
		'partnerCode',
		'secretKey',

	];

//    protected static function boot()
//    {
//        parent::boot(); // TODO: Change the autogenerated stub
//        self::saving(function ($model) {
//
//        });
//    }


    public function getTable()
    {
        return parent::getTable(); // TODO: Change the autogenerated stub
    }

}