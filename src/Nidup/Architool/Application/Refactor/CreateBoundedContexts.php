<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

class CreateBoundedContexts
{
    private $names;

    public function __construct(array $names)
    {
        $this->names = $names;
    }

    public function getNames(): array
    {
        return $this->names;
    }
}
