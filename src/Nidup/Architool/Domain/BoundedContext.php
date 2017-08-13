<?php

namespace Nidup\Architool\Domain;

final class BoundedContext
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLayers(): array
    {
        return [
            new Layer('Domain'),
            new Layer('Application'),
            new Layer('Infrastructure')
        ];
    }
}
