<?php

namespace Nidup\Architool\Application\Refactoring;

final class ConfigureSpecNamespace
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
