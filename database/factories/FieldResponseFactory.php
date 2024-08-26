<?php

namespace LaraZeus\Bolt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaraZeus\Bolt\Models\FieldResponse;

class FieldResponseFactory extends Factory
{
    protected $model = FieldResponse::class;

    public function definition(): array
    {
        return [
            'form_id' => config('zeus-bolt.models.Forms')::factory(),
            'field_id' => config('zeus-bolt.models.Field')::factory(),
            'response_id' => config('zeus-bolt.models.Response')::factory(),
            'response' => $this->faker->words(3, true),
        ];
    }
}
