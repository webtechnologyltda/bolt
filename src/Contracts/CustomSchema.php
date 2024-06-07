<?php

namespace LaraZeus\Bolt\Contracts;

use LaraZeus\Accordion\Forms\Accordion;

interface CustomSchema
{
    public function make(): Accordion;

    public function hidden(): array;
}
