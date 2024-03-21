<?php

namespace App\Models;

use App\Models\Crm\Trainer;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, SoftDeletes, HasTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'birthday',
        'phone',
        'telegram_user_id',
        'avatar',
        'email',
        'password',
        'active'
    ];

    protected $with = [
        'trainer',
        'tags',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->password)) {
                // TODO: Send an email with password to user
                $model->password = Str::random(20);
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'active' => 'boolean',
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

    /**
     * Get the trainer associated with the user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'user_id', 'id');
    }

    public function canAccessFilament(): bool
    {
        return $this->isActive();
    }

    /**
     * Determine if the user is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return !!$this->active;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
