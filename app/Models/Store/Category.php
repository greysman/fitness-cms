<?php

namespace App\Models\Store;

use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasAuthor, HasEditor;

    protected $fillable = [
        'parent_id',
        'title', 
        'slug', 
        'description'
    ];

    public function title(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                return $this->parent 
                    ? $this->parent->title . ' - ' . $value
                    : $value;
            }
        );
    }

    public static function list(int | array | null $except = null): array
    {
        return $except === null
            ? static::get()->pluck('title', 'id')->toArray()
            : static::whereNot('id', $except)->get()->pluck('title', 'id')->array();
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, 'product_categories', 'product_id', 'category_id');
    }
}
