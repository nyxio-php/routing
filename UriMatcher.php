<?php

declare(strict_types=1);

namespace Nyx\Routing;

use Nyx\Contract\Routing\UriMatcherInterface;
use Nyx\Contract\Validation\Handler\RulesCheckerInterface;
use Nyx\Routing\Attribute\Route;
use Nyx\Validation\Handler\Validator;
use Psr\Http\Message\ServerRequestInterface;

use function Nyx\Helper\Text\parseFromString;

class UriMatcher implements UriMatcherInterface
{
    private array $params = [];

    public function __construct(private readonly RulesCheckerInterface $rulesChecker)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param Route $route
     * @return bool
     */
    public function compare(ServerRequestInterface $request, Route $route): bool
    {
        if ($route->getUri() === $request->getUri()->getPath()) {
            return true;
        }

        $partsRequest = \explode('/', $request->getUri()->getPath());
        $partsRoute = \explode('/', $route->getUri());

        if (\count($partsRequest) !== \count($partsRoute)) {
            return false;
        }

        $difference = \array_diff_assoc($partsRoute, $partsRequest);

        $filtered = \array_filter($difference, static function ($value) {
            return ($value[0] ?? null) === '@';
        });

        if (\count($difference) !== \count($filtered)) {
            return false;
        }

        foreach ($difference as $key => $param) {
            $param = \substr($param, 1);

            $parsedValue = parseFromString($partsRequest[$key]);

            $validator = $route->getValidator($param);

            $this->params[$param] = $parsedValue;

            if ($validator instanceof Validator
                && !empty($this->rulesChecker->check($this->params, $validator))) {
                return false;
            }
        }

        return true;
    }

    public function getQueryParams(): array
    {
        return $this->params;
    }
}
