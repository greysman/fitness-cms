<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Expenditure extends Model
{
    use HasFactory, HasAuthor, HasEditor;

    const TYPE_INCOME = 0;
    const TYPE_EXPENDITURE = 1;

    protected $table = 'expenditures';
    protected $fillable = [
        'title',
        'type_id',
        'comment',
        'has_contact',
        'has_items',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_contact' => 'boolean',
        'has_items' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            Cache::forget('expinditures');
        });
    }

    public static function types(): array
    {
        return [
            self::TYPE_INCOME => __('expenditure.types.income.text'),
            self::TYPE_EXPENDITURE => __('expenditure.types.expenditure.text'),
        ];
    }

    public function type(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->types()[$this->type_id],
        );
    }

    public function operation()
    {
        return $this->hasMany(Operation::class, 'expenditure_id');
    }
}
