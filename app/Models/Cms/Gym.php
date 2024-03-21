<?php

namespace App\Models\Cms;

use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\App\Models\Cms\Traits\CrudTrait;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;

class Gym extends Model
{
    use HasAuthor, HasEditor;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'gyms';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'color',
        'title',
        'phone',
        'address',
        'position',
        'messangers'
    ];
    // protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'messangers' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            Cache::forget('gyms');
        });
    }

    public static function types(): array
    {
        return [
            'telegram' => 'Telegram',
            'whatsapp' => 'WhatsApp',
            'viber' => 'Viber',
        ];
    }

    
    public static function list()
    {
        return static::orderBy('title')->get()->pluck('title', 'id');
    }
}
