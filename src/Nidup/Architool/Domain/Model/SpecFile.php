<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model;

use Nidup\Architool\Domain\Model\SpecFile\SpecName;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;

class SpecFile
{
    private $originalNamespace;
    private $name;
    private $newNamespace;

    /**
     * @param SpecNamespace $namespace
     * @param SpecName      $name
     */
    public function __construct(SpecNamespace $namespace, SpecName $name)
    {
        $this->originalNamespace = $namespace;
        $this->name = $name;
        $this->newNamespace = null;
    }

    /**
     * @param SpecNamespace $newNamespace
     */
    public function move(SpecNamespace $newNamespace)
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
     * @return SpecNamespace
     */
    public function getOriginalNamespace(): SpecNamespace
    {
        return $this->originalNamespace;
    }

    /**
     * @return SpecName
     */
    public function getName(): SpecName
    {
        return $this->name;
    }

    /**
     * @return SpecName|null
     */
    public function getNewNamespace()
    {
        return $this->newNamespace;
    }
}
