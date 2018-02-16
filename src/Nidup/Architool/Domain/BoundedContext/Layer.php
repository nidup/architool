<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\BoundedContext;

class Layer
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
}
