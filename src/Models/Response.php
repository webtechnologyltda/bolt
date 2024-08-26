<?php

namespace LaraZeus\Bolt\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\Bolt\Concerns\HasUpdates;
use LaraZeus\Bolt\Database\Factories\ResponseFactory;
use LaraZeus\Bolt\Facades\Extensions;

/**
 * @property string $updated_at
 * @property int $form_id
 * @property int $user_id
 * @property string $status
 * @property string $notes
 * @property string $response
 * @property Form $form
 * @property FieldResponse $fieldsResponses
 */
class Response extends Model
{
    use HasFactory;
    use HasUlids;
    use HasUpdates;
    use SoftDeletes;

    protected $with = ['form', 'user'];

    protected $guarded = [];

    protected static function booted(): void
    {
        static::deleting(function (Response $response) {
            $canDelete = Extensions::init($response->form, 'canDeleteResponse', ['response' => $response]);

            if ($canDelete === null) {
                $canDelete = true;
            }

            if (! $canDelete) {
                Notification::make()
                    ->title(__('Can\'t delete a form linked to an Extensions'))
                    ->danger()
                    ->send();

                return false;
            }

            if ($response->isForceDeleting()) {
                $response->fieldsResponses()->withTrashed()->get()->each(fn ($item) => $item->forceDelete());
            } else {
                $response->fieldsResponses->each(fn ($item) => $item->delete());
            }

            return true;
        });
    }

    /** @phpstan-return HasMany<FieldResponse> */
    public function fieldsResponses(): HasMany
    {
        return $this->hasMany(config('zeus-bolt.models.FieldResponse'));
    }

    protected static function newFactory(): Factory
    {
        return ResponseFactory::new();
    }

    public function getTable()
    {
        return config('zeus-bolt.table-prefix').'responses';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /** @return BelongsTo<Form, Response> */
    public function form(): BelongsTo
    {
        return $this->belongsTo(config('zeus-bolt.models.Form'));
    }

    /**
     * get status detail.
     */
    public function statusDetails(): array
    {
        $getStatues = config('zeus-bolt.models.FormsStatus')::where('key', $this->status)->first();

        return [
            'class' => $getStatues->class ?? '',
            'icon' => $getStatues->icon ?? 'heroicon-s-document',
            'label' => $getStatues->label ?? $this->status,
            'key' => $getStatues->key ?? '',
            'color' => $getStatues->color ?? '',
        ];
    }
}
