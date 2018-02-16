<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\FileStorage;

use Nidup\Architool\Domain\FileStorage\ClassFile\ClassNamespace;
use Nidup\Architool\Domain\FileStorage\File\Name;
use Nidup\Architool\Domain\FileStorage\File\Path;

class ClassFile implements File
{
    private $namespace;
    private $name;
    private $newNamespace;

    /**
     * @param ClassNamespace $namespace
     * @param Name           $name
     */
    public function __construct(ClassNamespace $namespace, Name $name)
    {
        $this->namespace = $namespace;
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
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return ClassNamespace
     */
    public function getNamespace(): ClassNamespace
    {
        return $this->namespace;
    }

    /**
     * @return ClassNamespace|null
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
