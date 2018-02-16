<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\FileStorage\ClassFile;

class ClassNamespace
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
