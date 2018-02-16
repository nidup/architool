<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\FileStorage;

use Nidup\Architool\Domain\FileStorage\Folder\FolderNamespace;

class Folder
{
    private $originalNamespace;
    private $newNamespace;

    /**
     * @param FolderNamespace $namespace
     */
    public function __construct(FolderNamespace $namespace)
    {
        $this->originalNamespace = $namespace;
        $this->newNamespace = null;
    }

    /**
     * @param FolderNamespace $newNamespace
     */
    public function move(FolderNamespace $newNamespace)
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
     * @return FolderNamespace
     */
    public function getOriginalNamespace(): FolderNamespace
    {
        return $this->originalNamespace;
    }

    /**
     * @return FolderNamespace|null
     */
    public function getNewNamespace()
    {
        return $this->newNamespace;
    }
}
