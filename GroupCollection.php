<?php

declare(strict_types=1);

namespace Nyx\Routing;

use Nyx\Contract\Routing\GroupCollectionInterface;

class GroupCollection implements GroupCollectionInterface
{
    private array $groups = [];

    public function register(Group $group): static
    {
        $this->groups[$group->name] = $group;

        return $this;
    }

    public function get(string $name): ?Group
    {
        return $this->groups[$name] ?? null;
    }
}
