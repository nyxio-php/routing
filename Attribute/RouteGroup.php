<?php

declare(strict_types=1);

namespace Nyx\Routing\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class RouteGroup
{
    public function __construct(public readonly string $name)
    {
    }
}
