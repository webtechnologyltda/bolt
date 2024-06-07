<?php

namespace LaraZeus\Bolt\Contracts;

interface CustomSchema
{
    public function make(): array;

    public function hidden(): array;
}
