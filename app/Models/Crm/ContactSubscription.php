<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'contact_id',
        'product_id',
        'available',
        'used',
        'canceled_at',
        'expiring_at',
        'payload',
    ];

    protected $castas = [
        'expiring_at' => 'datetime',
        'canceled_at' => 'datetime',
        'payload' => 'array',
    ];

    public function contact ()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(ContactSubscriptionLog::class, 'contact_subscription_id', 'id');
    }
}
