<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model;

use Nidup\Architool\Domain\Model\BoundedContext\Layer;

class BoundedContext
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
