<?php

namespace App\Models\Crm;

use App\Models\Cms\Gym;
use App\Models\User;
use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes, HasAuthor, HasEditor;


    const STAGE_NEW = 0;
    const STAGE_EXPLORE = 1;
    const STAGE_OFFER = 2;
    const STAGE_WIN = 3;
    const STAGE_LOST = 4;


    const STATUS_NEW = 0;
    const STATUS_PROGRESS = 1;
    const STATUS_OVERDUE = 2;
    const STATUS_FINISHED = 3;
    const STATUS_ARCHIVED = 4;


    const SOURCE_WEBSITE = 0;
    const SOURCE_CALL = 1;
    const SOURCE_EMAIL = 2;
    const SOURCE_OTHER = 3;


    protected $fillable = [
        'title',
        'comment',
        'expected_profit',
        'status_id',
        'lost_reason',
        'closed_at',
        'gym_id',
        'source_id',
        'contact_id',
        'stage_id',
        'expected_close_date',
        'responsible_id'
    ];


    protected $casts = [
        'closed_at' => 'datetime',
        'expected_close_date' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->stage_id == null) $model->stage_id = static::STAGE_NEW;
        });

        static::updating(function ($model) {
            if (in_array($model->stage_id, [static::STAGE_EXPLORE, static::STAGE_OFFER])) {
                $model->status_id = static::STATUS_PROGRESS;
                
                if ($model->responsible_id === null) {
                    $model->responsible_id = Filament::auth()->user()->id;
                }
            }

            if (in_array($model->stage_id, [static::STAGE_WIN, static::STAGE_LOST])) {
                $model->status_id = static::STATUS_FINISHED;

                if ($model->responsible_id === null) {
                    $model->responsible_id = Filament::auth()->user()->id;
                }
            }
        });
    }


    public static function sources(): array
    {
        return [
            static::SOURCE_WEBSITE => __('requests.sources.website'),
            static::SOURCE_CALL => __('requests.sources.call'),
            static::SOURCE_EMAIL => __('requests.sources.email'),
            static::SOURCE_OTHER => __('requests.sources.other'),
        ];
    }


    public static function statuses(): array
    {
        return [
            static::STATUS_NEW => __('requests.statuses.new'),
            static::STATUS_PROGRESS => __('requests.statuses.progress'),
            static::STATUS_OVERDUE => __('requests.statuses.overdue'),
            static::STATUS_FINISHED => __('requests.statuses.finished'),
            static::STATUS_ARCHIVED => __('requests.statuses.archived'),
        ];
    }


    public static function stages(): array
    {
        return [
            static::STAGE_NEW => __('requests.stages.new'),
            static::STAGE_EXPLORE => __('requests.stages.explore'),
            static::STAGE_OFFER => __('requests.stages.offer'),
            static::STAGE_WIN => __('requests.stages.win'),
            static::STAGE_LOST => __('requests.stages.lost'),
        ];
    }


    public function source(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->sources()[$this->source_id],
        );
    }


    public function status(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->statuses()[$this->status_id],
        );
    }

    public function stage(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->stages()[$this->stage_id],
        );
    }


    /**
     * Get the GYM
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id');
    }


    /**
     * Get contact 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }


    public function events()
    {
        return $this->hasMany(Event::class, 'request_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'request_id');
    }


    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */


    public function scopeProgress($query)
    {
        return $query->where('status_id', '=', static::STATUS_PROGRESS);
    }


    public function scopeOverdue($query)
    {
        return $query->where([
            ['expected_close_date', '>', 'NOW()'],
        ])
        ->whereNotIn('stage_id', [
            static::STAGE_LOST,
            static::STAGE_WIN,
        ]);
    }

    public function scopeOverdueCount($query)
    {
        return $query->overdue()->count();
    }


    public function scopeNew($query)
    {
        return $query->where('stage_id', static::STAGE_NEW);
    }

    public function scopeNewCount($query)
    {
        return $query->new()->count();
    }
}
