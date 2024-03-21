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

class Schedule extends Model
{
    use HasFactory, HasAuthor, HasEditor, SoftDeletes;


    protected $fillable = [
        'date',
        'date_end',
        'title',
        'description',
        'gym_id',
        'trainer_id',
        'active',
    ];


    protected $casts = [
        'date' => 'datetime',
        'date_end' => 'datetime',
    ];


    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id');
    }


    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id', 'id');
    }


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Product::class, 'schedule_subscriptions', 'schedule_id', 'product_id');
    }
}
