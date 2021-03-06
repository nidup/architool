<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

class MoveLegacyClassFile
{
    private $legacyNamespace;
    private $destinationNamespace;
    private $description;
    private $className;

    public function __construct(
        string $legacyNamespace,
        string $destinationNamespace,
        string $className,
        string $description
    ) {
        $this->legacyNamespace = $legacyNamespace;
        $this->destinationNamespace = $destinationNamespace;
        $this->description = $description;
        $this->className = $className;
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

    public function getClassName(): string
    {
        return $this->className;
    }
}
