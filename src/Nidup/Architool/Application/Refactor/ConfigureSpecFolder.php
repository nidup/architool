<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

final class ConfigureSpecFolder
{
    private $namespace;
    private $description;

    public function __construct(string $namespace, string $description)
    {
        $this->namespace = $namespace;
        $this->description = $description;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }


    public function getDescription(): string
    {
        return $this->description;
    }
}
