<?php

namespace App\Models;

use AlAminFirdows\LaravelEditorJs\Facades\LaravelEditorJs;
use Beier\FilamentPages\Models\FilamentPage as BeierFilamentPage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\Builder as QueryBuilder;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FilamentPage extends BeierFilamentPage implements HasMedia
{
    use HasSEO, InteractsWithMedia;

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
        );
    }


    /**
     * Find a model by its primary slug.
     *
     * @param string $slug
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public static function findBySlug(string $slug, array $columns = ['*'])
    {
        return static::whereSlug($slug)->first($columns);
    }


    public function body(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->data['content']) ? LaravelEditorJs::render(json_encode($this->data['content'])) : null,
        );
    }


    public function isPublished(): bool
    {
        return $this->published 
            && $this->published_at->isPast()
            && ($this->published_until?->isFuture() ?? true);
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width('545')
            ->height('340');
    }


    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true)
            ->whereDate('published_at', '<', Carbon::now())
            ->where(function($query) {
                $query->whereNull('published_until')
                    ->orWhereDate('published_until', '>', Carbon::now());
            });
    }

}