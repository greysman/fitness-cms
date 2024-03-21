<?php

namespace App\Models\Crm;

use App\Models\Store\Product;
use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, HasAuthor, HasEditor, SoftDeletes;

    const DISCOUNT_TYPE_PERCENT = 0;
    const DISCOUNT_TYPE_VALUE = 1;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_APPROVED = 2;
    const STATUS_CANCELED = 3;

    protected $guarded = [];

    public static function discountTypes()
    {
        return [
            static::DISCOUNT_TYPE_PERCENT => __('requests.relations.offers.discount.options.percent'),
            static::DISCOUNT_TYPE_VALUE => __('requests.relations.offers.discount.options.value')
        ];
    }

    public static function statuses()
    {
        return [
            static::STATUS_DRAFT => __('requests.relations.offers.status.options.draft'),
            static::STATUS_ACTIVE => __('requests.relations.offers.status.options.active'),
            static::STATUS_APPROVED => __('requests.relations.offers.status.options.approved'),
            static::STATUS_CANCELED => __('requests.relations.offers.status.options.canceled'),
        ];
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn() => static::statuses()[$this->status_id]
        );
    }

    public function amount(): Attribute
    {
        return Attribute::make(
            get: function() {
                return $this->discount_type == static::DISCOUNT_TYPE_PERCENT
                    ? $this->product->price - $this->product->price * ($this->discount_value / 100)
                    : $this->product->price - $this->discount_value;
            },
        );
    }
    
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
