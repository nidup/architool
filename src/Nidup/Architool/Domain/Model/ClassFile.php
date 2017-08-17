<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model;

use Nidup\Architool\Domain\Model\ClassFile\ClassName;
use Nidup\Architool\Domain\Model\ClassFile\ClassNamespace;

class ClassFile
{
    private $originalNamespace;
    private $name;
    private $newNamespace;

    /**
     * @param ClassNamespace $namespace
     * @param ClassName      $name
     */
    public function __construct(ClassNamespace $namespace, ClassName $name)
    {
        $this->originalNamespace = $namespace;
        $this->name = $name;
        $this->newNamespace = null;
    }

    /**
     * @param ClassNamespace $newNamespace
     */
    public function move(ClassNamespace $newNamespace)
    {
        $this->newNamespace = $newNamespace;
    }

    /**
     * @return bool
     */
    public function hasMoved(): bool
    {
        return $this->newNamespace !== null;
    }

    /**
     * @return ClassNamespace
     */
    public function getOriginalNamespace(): ClassNamespace
    {
        return $this->originalNamespace;
    }

    /**
     * @return ClassName
     */
    public function getName(): ClassName
    {
        return $this->name;
    }

    /**
     * @return ClassName|null
     */
    public function getNewNamespace()
    {
        return $this->newNamespace;
    }
}
