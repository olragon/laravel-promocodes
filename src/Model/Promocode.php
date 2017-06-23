<?php

namespace Gabievi\Promocodes\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'reward',
        'is_used',
        'data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_used' => 'boolean',
        'data'    => 'array',
    ];

    /**
     * Promocode constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('promocodes.table', 'promocodes');
    }
    
    /**
     * Simulate json column by getter
     * @param $data
     */    
    public function getDataAttribute($data)
    {
        return collect(json_decode($data,true));
    }

    /**
     * Simulate json column by setter
     *
     * @param $data
     *
     * @return mixed
     */
    public function setDataAttribute($data)
    {
        $data = (is_array($data) || is_object($data)) ? json_encode($data) : $data;
        return $this->attributes['data'] = $data;
    }

    /**
     * Get the user who owns the promocode.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Query builder to find promocode using code.
     *
     * @param $query
     * @param $code
     *
     * @return mixed
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Query builder to find all not used promocodes.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeFresh($query)
    {
        return $query->where('is_used', false);
    }
}
