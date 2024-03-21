<?php

namespace App\Models\Store;

use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasAuthor, HasEditor;


    const TYPE_PHYSICAL = 0;
    const TYPE_SUBSCRIPTION = 1;
    const TYPE_VIRTUAL = 2;
    const TYPE_SERVICE = 3;


    protected $fillable = [
        'title',
        'slug',
        'description',
        'image_url',
        'category_id',
        'sku',
        'type_id',
        'subtract',
        'additional_data',
        'active',
        'published',
        'price',
        'order'
    ];


    protected $casts = [
        'additional_data' => 'json',
        'active' => 'boolean',
        'published' => 'boolean',
    ];


    public static function types()
    {
        return [
            static::TYPE_PHYSICAL => __('products.types.physical'),
            static::TYPE_SUBSCRIPTION => __('products.types.subscription'),
            static::TYPE_VIRTUAL => __('products.types.virtual'),
            static::TYPE_SERVICE => __('products.types.service'),
        ];
    }


    public function scopeSubscription(Builder $query): Builder
    {
        return $query->where('type_id', static::TYPE_SUBSCRIPTION);
    }


    public function scopePublished(Builder $query): Builder
    {
        return $query->active()->where('published', true);
    }


    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereDate('additional_data->available_from', '<', Carbon::today())
            ->whereDate('additional_data->available_to', '>', Carbon::today())
            ->orWhereNull('additional_data->available_to');
    }
 

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }


    public function type(): Attribute
    {
        return Attribute::make(
            get: fn (): string => static::types()[$this->type_id], 
        );
    }


    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

}
