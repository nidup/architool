<?php

namespace Nidup\Architool\Application\Refactoring;

final class ReplaceCodeInClass
{
    private $namespace;
    private $description;
    private $className;
    private $legacyCode;
    private $replacementCode;

    public function __construct(
        string $namespace,
        string $className,
        string $legacyCode,
        string $replacementCode,
        string $description)
    {
        $this->namespace = $namespace;
        $this->description = $description;
        $this->className = $className;
        $this->legacyCode = $legacyCode;
        $this->replacementCode = $replacementCode;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getLegacyCode(): string
    {
        return $this->legacyCode;
    }

    public function getReplacementCode(): string
    {
        return $this->replacementCode;
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
