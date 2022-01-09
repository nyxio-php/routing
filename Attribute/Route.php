<?php

declare(strict_types=1);

namespace Nyx\Routing\Attribute;

use Nyx\Contract\Http\Method;
use Nyx\Routing\ValidatorTrait;

use function Nyx\Helper\Url\normalizeUri;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Route
{
    use ValidatorTrait;

    /**
     * @param Method $method
     * @param string $uri
     * @param array $rules
     */
    public function __construct(
        public readonly Method $method,
        protected string $uri,
        private readonly array $rules = [],
    ) {
        $this->uri = normalizeUri($uri);
        $this->createValidators($rules);
    }

    public function addPrefix(string $prefix): static
    {
        $this->uri = normalizeUri(rtrim($prefix, '/') . '/' . ltrim($this->uri, '/'));

        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
