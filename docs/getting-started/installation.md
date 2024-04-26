---
title: Installation
weight: 3
---

## Prerequisites

Bolt is built on top of [laravel](https://laravel.com/docs/master) and uses [filament](https://filamentphp.com/docs/3.x/panels/installation) as an admin panel to manage everything.

And for the frontend, it uses [Tall stack](https://tallstack.dev/).

So, ensure you are familiar with these tools before diving into @zeus Bolt.

> **Note**\
> You can get up and running with our [starter kit Zeus](https://github.com/lara-zeus/zeus).

## Installation

> **Important**\
> Before starting, make sure you have the following PHP extensions enabled:
`sqlite`

Install @zeus Bolt by running the following commands in your Laravel project directory.

```bash
composer require lara-zeus/bolt
php artisan bolt:install
```

The install command will publish the migrations and the necessary assets for the frontend.

## Register Bolt with Filament:

To set up the plugin with filament, you need to add it to your panel provider; The default one is `adminPanelProvider`

```php
->plugins([
    SpatieLaravelTranslatablePlugin::make()->defaultLocales([config('app.locale')]),
    BoltPlugin::make()
])
```

## Add Bolt Trait to User Model

add this to your user model:

`use \LaraZeus\Bolt\Models\Concerns\BelongToBolt;`

This will allow you to get the user name by another attribute like `full_name`

## Usage

To access the forms, visit the URL `/admin` , and `/bolt`.
