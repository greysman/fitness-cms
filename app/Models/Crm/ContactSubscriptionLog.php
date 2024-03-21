<?php

namespace App\Models\Crm;

use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubscriptionLog extends Model
{
    use HasFactory, HasAuthor, HasEditor;

    protected $fillable = [
        'contact_subscription_id',
        'data_before',
        'data_after',
    ];

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
    ];

    public function contactSubscription ()
    {
        return $this->belongsTo(ContactSubscription::class, 'contact_subscription_id', 'id');
    }
}
