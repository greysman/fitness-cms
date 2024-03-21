<?php

namespace App\Models\Cms;

use App\Models\User;
use Spatie\Tags\Tag as SpatieTag;

/**
 * @property string $slug
 * @property string $type
 * @property string $name
 */
class Tag extends SpatieTag
{
}