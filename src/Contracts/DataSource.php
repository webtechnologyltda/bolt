<?php

namespace LaraZeus\Bolt\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface DataSource
{
    public function title(): string;

    public function getSort(): int;

    public function getValuesUsing(): string;

    public function getKeysUsing(): string;

    public function getModel(): string;

    public function getQuery(): Builder;
}
