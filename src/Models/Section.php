<?php

namespace LaraZeus\Bolt\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\Bolt\Database\Factories\SectionFactory;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $updated_at
 * @property string $name
 * @property string $columns
 * @property string $description
 * @property bool $aside
 * @property bool $compact
 * @property mixed $fields
 */
class Section extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUlids;
    use SoftDeletes;

    public array $translatable = ['name'];

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Section $section) {
            if ($section->isForceDeleting()) {
                $section->fields()->withTrashed()->get()->each(function ($item) {
                    $item->fieldResponses()->withTrashed()->get()->each(function ($item) {
                        $item->forceDelete();
                    });
                    $item->forceDelete();
                });
            } else {
                $section->fields->each(function ($item) {
                    $item->fieldResponses->each(function ($item) {
                        $item->delete();
                    });
                    $item->delete();
                });
            }
        });
    }

    /** @phpstan-return hasMany<Field> */
    public function fields(): HasMany
    {
        return $this->hasMany(config('zeus-bolt.models.Field'), 'section_id', 'id');
    }

    protected static function newFactory(): SectionFactory
    {
        return SectionFactory::new();
    }

    public function getTable()
    {
        return config('zeus-bolt.table-prefix').'sections';
    }

    /** @return BelongsTo<Form, Section> */
    public function form(): BelongsTo
    {
        return $this->belongsTo(config('zeus-bolt.models.Form'));
    }
}
