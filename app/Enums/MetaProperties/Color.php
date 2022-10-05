<?php

namespace App\Enums\MetaProperties;

use ArchTech\Enums\Meta\MetaProperty;
use Attribute;

#[Attribute]
class Color extends MetaProperty
{
    protected function transform(mixed $value): mixed
    {
        return "text-{$value}-600";
    }
}
