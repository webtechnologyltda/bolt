---
title: Configuration
weight: 5
---

## Configuration

There is two different set of configuration, for filament, and for the frontend pages

## Filament Configuration

to configure the plugin Bolt, you can pass the configuration to the plugin in `adminPanelProvider` 

these all the available configuration, and their defaults values

> **Note**\
> All these configurations are optional

```php
BoltPlugin::make()
    // the default models, by default Bolt will read from the config file 'zeus-bolt'.
    // but if you want to customize the models per panel, you can do it here 
    ->boltModels([
        // ...
        'Category' => \App\Models\Bolt\Category::class,
        'Collection' => \App\Models\Bolt\Collection::class,
        'Field' => \App\Models\Bolt\Field::class,
        'FieldResponse' => \App\Models\Bolt\FieldResponse::class,
        'Form' => \App\Models\Bolt\Form::class,
        'FormsStatus' => \App\Models\Bolt\FormsStatus::class,
        'Response' => \App\Models\Bolt\Response::class,
        'Section' => \App\Models\Bolt\Section::class,
    ])
    
    // make the actions floating in create and edit forms
    ->formActionsAreSticky(true)
    
    ->hideResources([
        FormResource::class
    ])

    ->globallySearchableAttributes([
        // you can return empty array to disable it
        FormResource::class => ['name']
    ])
    
    ->navigationGroupLabel('Bolt')
    
    ->hideNavigationBadges(resource: LaraZeus\Bolt\Resources::CollectionResource)
    ->showNavigationBadges(resource: LaraZeus\Bolt\Resources::CollectionResource)
    
    // if you have custom extension or using thunder
    ->extensions([
        \LaraZeus\Thunder\Extensions\Thunder::class,
    ])
,
```

## Customize Filament Resources

you can customize all Bolt resources icons and sorting by adding the following code to your `AppServiceProvider` boot method

```php
FormResource::navigationSort(100);
FormResource::navigationIcon('heroicon-o-home');
FormResource::navigationGroup('New Name');
```

### Show or Hide Badges

To show all navigation badges (default)
```
    ->showNavigationBadges()
```

To hide all navigation badges
```
    ->hideNavigationBadges()
```

This will hide only the CollectionResource navigation badge
```
    ->hideNavigationBadges(resource: LaraZeus\Bolt\Resources::CollectionResource)
```

This will show only the FormResource navigation badge
```
    ->hideNavigationBadges()
    ->showNavigationBadges(resource: LaraZeus\Bolt\Resources::CollectionResource)
```

available resources:

- CategoryResource,
- CollectionResource,
- FormResource,

## Frontend Configuration

use the file `zeu-bolt.php`, to customize the frontend, like the prefix,domain, and middleware for each content type.

to publish the configuration:

```bash
php artisan vendor:publish --tag=zeus-bolt-config
```
