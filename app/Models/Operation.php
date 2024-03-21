<?php

namespace App\Models;

use App\Models\Crm\Contact;
use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory, SoftDeletes, HasAuthor, HasEditor;

    const DISCOUNT_TYPE_PERCENT = 0;
    const DISCOUNT_TYPE_VALUE = 1;

    protected $fillable = [
        'uid',
        'hash',
        'contact_id',
        'expenditure_id',
        'discount',
        'discount_type',
        'total_amount',
        'comment',
        'payload',
    ];

    public static function discountTypes(): array
    {
        return [
            self::DISCOUNT_TYPE_PERCENT => __('operations.discount_types.percent'),
            self::DISCOUNT_TYPE_VALUE => __('operations.discount_types.value'),
        ];
    }

    public function discountType(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discountTypes()[$this->discount_type_id]
        );
    }

    public function expenditure()
    {
        return $this->belongsTo(Expenditure::class, 'expenditure_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OperationItem::class, 'operation_id', 'id');
    }
}
