<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\Model;

use Nidup\Architool\Domain\Model\File\Name;
use Nidup\Architool\Domain\Model\File\Path;
use Nidup\Architool\Domain\Model\SpecFile\SpecNamespace;

class SpecFile implements File
{
    private $originalNamespace;
    private $name;
    private $newNamespace;

    /**
     * @param SpecNamespace $namespace
     * @param Name          $name
     */
    public function __construct(SpecNamespace $namespace, Name $name)
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
    public function getNamespace(): SpecNamespace
    {
        return $this->originalNamespace;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return SpecNamespace|null
     */
    public function getNewNamespace()
    {
        return $this->newNamespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): Path
    {
        $fileExtension = '.php';
        $fromFile = $this->getNamespace()->getName().DIRECTORY_SEPARATOR.$this->getName()->getValue().$fileExtension;

        return new Path($fromFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinationDirectoryPath(): Path
    {
        $toDir = $this->getNewNamespace()->getName();

        return new Path($toDir);
    }

    /**
     * {@inheritdoc}
     */
    public function getDestinationPath(): Path
    {
        $fileExtension = '.php';
        $toFile = $this->getDestinationDirectoryPath()->getContent().DIRECTORY_SEPARATOR.$this->getName()->getValue().$fileExtension;

        return new Path($toFile);
    }
}
