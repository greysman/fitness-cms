<?php

namespace App\Models\Crm;

use App\Models\User;
use Buildix\Timex\Traits\TimexTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\PermissionRegistrar;

class Event extends Model
{
    use HasUuids;
    use TimexTrait;

    protected $guarded = [];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
        'isAllDay' => 'boolean',
        'participants' => 'array',
        'attachments' => 'array',
    ];

    public function getTable()
    {
        return config('timex.tables.event.name', "timex_events");
    }

    public function __construct(array $attributes = [])
    {
        $attributes['organizer'] = \Auth::id();

        parent::__construct($attributes);

    }

    public function category()
    {
        return $this->hasOne(self::getCategoryModel());
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
}
