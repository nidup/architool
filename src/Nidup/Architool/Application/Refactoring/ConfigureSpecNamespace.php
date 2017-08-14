<?php

namespace Nidup\Architool\Application\Refactoring;

final class ConfigureSpecNamespace
{
    private $legacyNamespace;
    private $destinationNamespace;
    private $description;

    public function __construct(string $legacyNamespace, string $destinationNamespace, string $description)
    {
        $this->legacyNamespace = $legacyNamespace;
        $this->destinationNamespace = $destinationNamespace;
        $this->description = $description;
    }

    public function getLegacyNamespace(): string
    {
        return $this->legacyNamespace;
    }

    public function getDestinationNamespace(): string
    {
        return $this->destinationNamespace;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
