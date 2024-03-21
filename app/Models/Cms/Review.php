<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reviews';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'name',
        'text',
        'status_id'
    ];
    // protected $hidden = [];

    const STATUS_NEW        = 0;
    const STATUS_MODERATED  = 1;
    const STATUS_PUBLISHED  = 2;
    const STATUS_SPAM       = 99;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            Cache::forget('reviews');
        });
    }

    public static function statuses()
    {
        return [
            self::STATUS_NEW        => __('reviews.new'),
            self::STATUS_MODERATED  => __('reviews.moderated'),
            self::STATUS_PUBLISHED  => __('reviews.published'),
            self::STATUS_SPAM       => __('reviews.spam')
        ];
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->statuses()[$this->status_id]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        $query->where('status_id', self::STATUS_PUBLISHED);
    }

    public function scopeNew($query)
    {
        $query->where('status_id', self::STATUS_NEW);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
