<?php

namespace Corals\Utility\Tag\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tag extends BaseModel implements HasMedia
{
    use PresentableTrait;
    use LogsActivity;
    use Sluggable;
    use ModelPropertiesTrait;
    use InteractsWithMedia;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility-tag.models.tag';

    protected $table = 'utility_tags';

    protected $casts = [
        'properties' => 'json',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public $guarded = ['id'];

    public $mediaCollectionName = 'utility-tag-thumbnail';

    public function scopeWithModule(Builder $query, string $module = null): Builder
    {
        if (is_null($module)) {
            return $query;
        }

        return $query->where('module', $module);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * @param array|\ArrayAccess $values
     * @param string|null $module
     *
     * @return Tag|static
     */
    public static function findOrCreate($values, string $module = null)
    {
        $tags = collect($values)->map(function ($value) use ($module) {
            if ($value instanceof Tag) {
                return $value;
            }

            return static::findOrCreateFromString($value, $module);
        });

        return is_string($values) ? $tags->first() : $tags;
    }

    public static function getWithModule(string $module): DbCollection
    {
        return static::withModule($module)->get();
    }

    public static function findFromString(string $name, string $module = null)
    {
        return static::query()
            ->where("name", $name)
            ->where('module', $module)
            ->first();
    }

    protected static function findOrCreateFromString(string $name, string $module = null): self
    {
        $tag = static::findFromString($name, $module);

        if (! $tag) {
            $tag = static::create([
                'name' => $name,
                'module' => $module,
            ]);
        }

        return $tag;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function source()
    {
        return $this->morphTo();
    }
}
