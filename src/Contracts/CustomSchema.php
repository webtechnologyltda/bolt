<?php

namespace LaraZeus\Bolt\Contracts;

use Filament\Forms\Components\Tabs\Tab;

interface CustomSchema
{
    public function make(): Tab;
}
