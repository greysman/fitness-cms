<?php

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Tags\HasTags;

class Trainer extends Model
{
    use HasFactory, HasTags, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'education',
        'specialization',
        'order',
        'published',
    ];
    
    public static function boot()
    {
        parent::boot();

        static::updated(function ($mode) {
            Cache::forget('trainers');
        });
    }

    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopePublished($query)
    {
        $query->where('published', 1);
    }
}
