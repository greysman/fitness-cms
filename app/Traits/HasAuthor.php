<?php 

namespace App\Traits;

use App\Models\User;
use Filament\Facades\Filament;

trait HasAuthor {

    protected static function bootHasAuthor()
    {
        static::creating(function($model) {
            $model->author_id = $model->author_id ?: 
                (Filament::auth()->user() ? Filament::auth()->user()->id : null);
        });
    }

    /**
     * Get the author of Record
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

}