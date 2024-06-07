<?php

namespace LaraZeus\Bolt\Contracts;

use LaraZeus\Accordion\Forms\Accordion;

interface CustomSectionSchema
{
    public function make(): Accordion;
}
