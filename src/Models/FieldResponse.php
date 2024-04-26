<?php

namespace LaraZeus\Bolt\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\Bolt\Database\Factories\FieldResponseFactory;

/**
 * @property string $response
 * @property string $updated_at
 * @property int $field_id
 * @property int $form_id
 */
class FieldResponse extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $with = ['field'];

    protected $guarded = [];

    protected static function newFactory(): FieldResponseFactory
    {
        return FieldResponseFactory::new();
    }

    public function getTable()
    {
        return config('zeus-bolt.table-prefix').'field_responses';
    }

    /** @return BelongsTo<Field, FieldResponse> */
    public function field(): BelongsTo
    {
        return $this->belongsTo(config('zeus-bolt.models.Field'));
    }

    /** @return BelongsTo<Response, FieldResponse> */
    public function parentResponse()
    {
        return $this->belongsTo(config('zeus-bolt.models.Response'), 'response_id', 'id');
    }

    /** @return BelongsTo<Form, FieldResponse> */
    public function form()
    {
        return $this->belongsTo(config('zeus-bolt.models.Form'));
    }
}
