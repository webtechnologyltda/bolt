<?php

namespace LaraZeus\Bolt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaraZeus\Bolt\Models\Field;

class FieldFactory extends Factory
{
    protected $model = Field::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'type' => '\LaraZeus\Bolt\Fields\Classes\TextInput',
            'section_id' => config('zeus-bolt.models.Section')::factory(),
            'ordering' => $this->faker->numberBetween(1, 20),
        ];
    }
}
