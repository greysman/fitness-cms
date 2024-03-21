<?php

namespace App\Models\Crm;

use App\Models\Cms\Gym;
use App\Models\Store\Product;
use App\Models\User;
use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Signup extends Model
{
    use HasFactory, HasAuthor, HasEditor, SoftDeletes;


    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_FINISHED = 2;
    const STATUS_CANCELED = 3;


    protected $fillable = [
        'contact_id',
        'gym_id',
        'trainer_id',
        'responsible_id',
        'product_id',
        'duration',
        'date',
        'start_time',
        'finish_time',
        'status_id',
        'comment',
        'rating',
        'review',
        'additional_data',
    ];


    protected $casts = [
        'rating' => 'integer',
        'additional_data' => 'json',
        'date' => 'datetime',
    ];


    public static function statuses(): array
    {
        return [
            static::STATUS_NEW => __('signups.statuses.new'),
            static::STATUS_PROCESSING => __('signups.statuses.processing'),
            static::STATUS_FINISHED => __('signups.statuses.finished'),
            static::STATUS_CANCELED => __('signups.statuses.canceled'),
        ];
    }

    public static function statusesColors(): array
    {
        return [
            'danger' => static::STATUS_NEW,
            'success' => static::STATUS_PROCESSING,
            'primary' => static::STATUS_FINISHED,
            'secondary' => static::STATUS_CANCELED,
        ];
    }

    // TODO: is it needed?
    public function statusesList(): array
    {
        return match($this->status_id) {
            static::STATUS_NEW => Arr::where(static::statuses(), function ($value, $key) {
                return in_array($key, [
                    static::STATUS_PROCESSING,
                    static::STATUS_CANCELED,
                ]);
            }),
            static::STATUS_PROCESSING => Arr::where(static::statuses(), function ($value, $key) {
                return in_array($key, [
                    static::STATUS_FINISHED,
                ]);
            }),
            static::STATUS_CANCELED, static::STATUS_FINISHED => []
        };
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->statuses()[$this->status_id],
        );
    }


    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status_id', static::STATUS_NEW);
    }


    public function scopeNewCount(Builder $query): int
    {
        return $query->new()->count();
    }


    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }


    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id', 'id');
    }


    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id', 'id');
    }
}
