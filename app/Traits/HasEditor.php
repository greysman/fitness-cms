<?php 

namespace App\Traits;

use App\Models\User;
use Filament\Facades\Filament;

trait HasEditor {

    protected static function bootHasEditor()
    {
        static::updating(function($model) {
            $model->editor_id = Filament::auth()->user() 
                ? Filament::auth()->user()->id 
                : null;
        });
    }

    /**
     * Get the editor of Record
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id', 'id');
    }

}