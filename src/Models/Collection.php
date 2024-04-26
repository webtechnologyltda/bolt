<?php

namespace LaraZeus\Bolt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\Bolt\Concerns\HasUpdates;
use LaraZeus\Bolt\Database\Factories\CollectionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

/**
 * @property string $updated_at
 * @property array $values
 */
class Collection extends Model
{
    use HasFactory;
    use HasUpdates;
    use SoftDeletes;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'values' => 'collection',
    ];

    public function getTable()
    {
        return config('zeus-bolt.table-prefix') . 'collections';
    }

    public function getValuesListAttribute(): ?string
    {
        $allValues = collect($this->values);

        if ($allValues->isNotEmpty()) {
            return $allValues
                ->take(5)
                ->map(function ($item) {
                    return $item['itemValue'] ?? null;
                })
                ->join(',');
        }

        return null;
    }

    protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
