<?php

namespace LaraZeus\Bolt\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\Bolt\Database\Factories\FieldFactory;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $name
 * @property string $description
 * @property string $updated_at
 * @property string $type
 * @property int $id
 * @property array $options
 */
class Field extends Model
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
        static::deleting(function (Field $field) {
            if ($field->isForceDeleting()) {
                $field->fieldResponses()->withTrashed()->get()->each(function ($item) {
                    $item->forceDelete();
                });
            } else {
                $field->fieldResponses->each(function ($item) {
                    $item->delete();
                });
            }
        });
    }

    /** @return HasMany<FieldResponse> */
    public function fieldResponses(): HasMany
    {
        return $this->hasMany(config('zeus-bolt.models.FieldResponse'));
    }

    protected static function newFactory(): FieldFactory
    {
        return FieldFactory::new();
    }

    public function getTable()
    {
        return config('zeus-bolt.table-prefix').'fields';
    }

    /** @return BelongsTo<Form, Field> */
    public function form(): BelongsTo
    {
        return $this->belongsTo(config('zeus-bolt.models.Form'));
    }

    /** @return BelongsTo<Section, Field> */
    public function section(): BelongsTo
    {
        return $this->belongsTo(config('zeus-bolt.models.Section'));
    }
}
