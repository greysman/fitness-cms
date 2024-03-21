<?php

namespace App\Models\Crm;

use App\Traits\HasAuthor;
use App\Traits\HasEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes, HasAuthor, HasEditor;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'birthday',
        'sex',
        'avatar',
        'email',
        'email_verified_at',
        'phone',
        'phone_virified_at',
        'password',
        'remember_token',
        'comment',
        'active',
        'last_actvity',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the user's full name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => trim(implode(' ', [
                $this->surname,
                $this->name,
                $this->patronymic
            ])),
        );
    }


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }


    public function requests()
    {
        return $this->hasMany(Request::class, 'contact_id');
    }


    public function purchases()
    {
        return $this->hasMany(Operation::class, 'contact_id');
    }

    public function signups()
    {
        return $this->hasMany(Signup::class, 'contact_id');
    }

    public function subsctiptions()
    {
        return $this->hasMany(ContactSubscription::class, 'contact_id', 'id');
    }
}
