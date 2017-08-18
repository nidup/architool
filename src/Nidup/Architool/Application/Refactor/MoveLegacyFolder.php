<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

class MoveLegacyFolder
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
