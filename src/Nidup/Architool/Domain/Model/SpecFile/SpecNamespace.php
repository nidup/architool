<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model\SpecFile;

class SpecNamespace
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
