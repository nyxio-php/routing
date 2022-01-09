<?php

declare(strict_types=1);

namespace Nyx\Routing\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class Middleware
{
    public function __construct(public string $name)
    {
    }
}
