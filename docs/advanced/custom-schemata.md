---
title: Custom Schemata
weight: 6
---

## Add Custom Schema for fields

Bolt allow  you to add custom form components to the form, section and fields.

### First: create the class:

create a class where ever you wnt in your app for example in `App\Zeus\CustomSchema` with the content:

```php
<?php

namespace App\Zeus\CustomSchema;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use LaraZeus\Accordion\Forms\Accordion;
use LaraZeus\Bolt\Contracts\CustomSchema;
use LaraZeus\Bolt\Fields\FieldsContract;

class Field implements CustomSchema
{
    public function make(?FieldsContract $field = null): Accordion
    {
        return Accordion::make('more-field-options')
            ->schema([
                TextInput::make('options.field.nickname')
                    ->label('field nickname'),
            ]);
    }

    public function hidden(?FieldsContract $field = null): array
    {
        return [
            Hidden::make('options.field.nickname'),
        ];
    }
}
```

- make sure to return the hidden fields the same as the fields you have defined in the `make` method
- make sure to set the `state` correctly, if you want to store these info in the `options` then use `options.more-data`, or in separate column then make sure to create the migration for it

### Second: add it to your panel config:

```php
BoltPlugin::make()
    ->customSchema([
        'form' => null,
        'section' => null,
        'field' => \App\Zeus\CustomSchema\Field::class,
    ])
```

## Replace the Schemata with Custom one

the trait `Schemata` is the heart of the form builder, and now you can customize it to your liking.

> **Note**\
> This is an advanced feature; please use it only when necessary since you have to mainline it manually with every update for Bolt.

### First, copy the trait to your app:

copy the trait from `\LaraZeus\Bolt\Concerns` to your app, let say: `\App\Zeus\Bolt\Concerns`

### call the trait in a service provider

in your register method of your `AppServiceProvider` add the following:

```php
\LaraZeus\Bolt\Livewire\FillForms::getBoltFormDesignerUsing(\App\Zeus\Bolt\Concerns\Designer::class);
```

You're done. Customize the form builder to fit your needs. Remember to keep an eye on any changes in future updates so that you will avoid breaking changes.
